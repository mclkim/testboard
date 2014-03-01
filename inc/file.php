<?php
/************************************************************************\
 * 프로그램명 : 파일연결
 * 특기사항   : 1.인덱스파일 형식(포멧) 수정(2004.03.10)-[test4conv.php-실행]
                2.정렬기능(2001.04.23)
                3.답변글보기(2002.03.12)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('inc/common.php');
class db_file extends db_common {
	var $db_host = null; // 호스트
	var $db_user = null; // 사용자
	var $db_pass = null; // 암호
	var $db_name = null; // 데이타베이스
	var $link = 0;
	var $result = 0;
	var $last_query = null;
	
	// 데이타베이스 기본설정
	function db_set($defconf) {
		if (empty ( $defconf ))
			return;
		$this->timestamps ( "db_set" );
		$this->db_host = $defconf ["file_host"];
		$this->db_user = $defconf ["file_user"];
		$this->db_pass = $defconf ["file_pass"];
		$this->db_name = $defconf ["file_name"];
	}
	function create_db($db) {
		return 1;
	}
	function drop_db($db) {
		return 1;
	}
	/**
	 * **********************************************************************\
	 * [].인덱스 읽기
	 * \***********************************************************************
	 */
	// 전체 인덱스 읽기
	function loadtotal($db, $ff = '', $fw = '') {
		// 인덱스가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/${db}.idx" );
		if (! file_exists ( $filename ) || ! is_file ( $filename ))
			return null;
		return count ( file2 ( $filename ) );
	}
	// 전체 인덱스 읽기
	function loadindexfile($db) {
		$this->timestamps ( "loadindexfile" );
		// 인덱스가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/${db}.idx" );
		
		if (! file_exists ( $filename ) || ! is_file ( $filename ))
			return null;
		$rows = file2 ( $filename );
		$this->timestamps ( "loadindexfile_1" );
		// 인덱스분리 추출하기
		foreach ( $rows as $item ) {
			list ( $id, $pid, $ppid ) = explode ( ";", $item );
			$index [] = compact ( "id", "pid", "ppid" );
		} // end foreach
		$this->timestamps ( "loadindexfile_2" );
		// 업데이트 없는 리플방법(2004.03.10)
		return index_chain ( 'id', 'pid', 'ppid', $index );
	}
	
	// 신규추가,답변시 새로운 인덱스 추가하기
	function addindexfile($db, $data) {
		$this->timestamps ( "addindexfile" );
		// 인덱스가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/${db}.idx" );
		$record = array (
				$data ["id"],
				$data ["pid"],
				$data ["ppid"] 
		);
		$final = implode ( ";", $record );
		
		$f = fopen ( $filename, "ab" );
		flock ( $f, LOCK_EX );
		$ret = fwrite ( $f, "\n" . $final );
		flush ( $f );
		flock ( $f, LOCK_UN );
		fclose ( $f );
	}
	
	// 검색 인덱스 파일읽기
	function findindexfile($db, $ff, $fw) {
		global $sysconf;
		$this->timestamps ( "findindexfile" );
		// 임시
		if (empty ( $ff ) || empty ( $fw ))
			return null;
			
			// 파일찾기 파라미터 수정(2001.12.14)
		switch ($ff) {
			case 'id' :
			case 'pid' :
			case 'ppid' :
			case 'anniversary' :
				$common_query = "$sysconf[findcmd] $ff=$fw " . path_fix ( "$this->db_host/$db/data/*c.cgi" );
				break;
			
			case 'content' :
				$common_query = "$sysconf[findall] $fw " . path_fix ( "$this->db_host/$db/data/*d.cgi" );
				break;
			
			case 'all' :
				$common_query = "$sysconf[findall] $fw " . path_fix ( "$this->db_host/$db/data/*.cgi" );
				break;
			
			default :
				$common_query = "$sysconf[findall] $ff=.*$fw " . path_fix ( "$this->db_host/$db/data/*c.cgi" );
				break;
		} // end switch
		
		$rows = runshell ( $common_query );
		
		// 임시(파일이 없으면)
		if (empty ( $rows ))
			return null;
			
			// 파일이름 추출하기
		if (is_array ( $rows ))
			$index = array_map ( "extractfilename", $rows );
			
			// 임시
		arsort ( $index );
		$this->timestamps ( "findindexfile" );
		return empty ( $index ) ? null : $index;
	}
	
	// 정렬 인덱스 파일읽기(2001.04.23)
	function sortindexfile($db, $ff) {
		global $sysconf;
		$this->timestamps ( "sortindexfile" );
		// 임시
		if (empty ( $ff ))
			return null;
			
			// 전체파일 정렬 시작(마침표'.'가 없으면...)
		$common_query = "$sysconf[sortcmd] $ff=. " . path_fix ( "$this->db_host/$db/data/*c.cgi" );
		$rows = runshell ( $common_query );
		
		// 임시(파일이 없으면)
		if (empty ( $rows ))
			return null;
			
			// 인덱스분리 추출하기(파일명=필드값)
		$rows = index2array ( $rows );
		
		switch ($ff) {
			case 'in_date' :
			case 'readcount' :
				arsort ( $rows );
				break;
			
			default :
				asort ( $rows );
				break;
		} // end switch
		  
		// 키(key)부분에 파일명이 있슴.
		$rows = array_keys ( $rows );
		
		// 파일이름 추출하기
		if (is_array ( $rows ))
			$index = array_map ( "extractfilename", $rows );
		$this->timestamps ( "sortindexfile" );
		return empty ( $index ) ? null : $index;
	}
	
	// 답변글 인덱스 파일읽기(2002.03.12)
	function findreplyfile($db, $ppid) {
		global $sysconf;
		$this->timestamps ( "findreplyfile" );
		// 임시
		if (($ppid) < 1)
			return null;
			
			// 파일 찾기 시작
		$common_query = "$sysconf[findcmd] ppid=$ppid " . path_fix ( "$this->db_host/$db/data/*c.cgi" );
		$rows = runshell ( $common_query );
		
		// 임시(파일이 없으면)
		if (empty ( $rows ))
			return null;
			
			// 인덱스분리 추출하기
		foreach ( $rows as $file ) {
			$row = file2array ( path_fix ( "$file" ) );
			$index [] = array (
					"id" => "$row[id]",
					"pid" => "$row[pid]",
					"ppid" => "$row[ppid]" 
			);
		} // end foreach
		$this->timestamps ( "findreplyfile" );
		// 업데이트 없는 리플방법(2004.03.10)
		return index_chain ( 'id', 'pid', 'ppid', $index );
	}
	
	// 인덱스 파일읽기(2002.03.12)
	function findfile($db, $ff, $fw) {
		global $sysconf;
		$this->timestamps ( "findfile" );
		// 임시
		if (empty ( $ff ) || empty ( $fw ))
			return null;
			
			// 파일 찾기 시작
		$common_query = "$sysconf[findfile] $ff=$fw " . path_fix ( "$sysconf[path_data]/*c.cgi" );
		$rows = runshell ( $common_query );
		
		// 인덱스분리 추출하기
		$rows = index2array ( $rows );
		
		// 파일이름 추출하기(필드값=array(파일명,파일명))
		foreach ( $rows as $key => $val ) {
			$index [$val] [] = extractfilename ( $key );
		} // end foreach
		$this->timestamps ( "findfile" );
		return empty ( $index ) ? null : $index;
	}
	/**
	 * **********************************************************************\
	 * [].글 읽기,쓰기,삭제
	 * \***********************************************************************
	 */
	// 목록 파일 읽기
	function loaddatalist($db, $index) {
		$this->timestamps ( "loaddatalist" );
		$list = array ();
		if (count ( $index ) > 0)
			foreach ( $index as $no => $row ) {
				if ($data = $this->loaddatafile ( $db, $row ))
					$list [$data ["id"]] = $data;
			} // end foreach
		
		return $list;
	}
	
	// 글 파일 읽기
	function loaddatafile($db, $id) {
		$this->timestamps ( "loaddatafile($id)" );
		// 임시
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			return null;
			
			// 데이타가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/data/${id}c.cgi" );
		$temp = file2array ( $filename );
		return empty ( $temp ) ? null : $this->decodedheader ( $temp );
	}
	
	// 글 파일 쓰기
	function savedatafile($data, $db, $cmd = "") {
		$data = $this->encodedheader ( $data );
		
		// 글 인덱스
		$id = $data ["id"];
		
		// long type skip
		unset ( $data ["content"] );
		unset ( $data ["opinion"] );
		
		// 신규추가,답변시 새로운 인덱스 추가하기
		switch ($cmd) {
			case 'reply' :
			case 'write' :
				$this->addindexfile ( $db, $data );
				break;
		} // end switch
		  
		// 데이타가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/data/${id}c.cgi" );
		$this->timestamps ( "savedatafile($id)" );
		return array2file ( $filename, $data );
	}
	
	// 글 파일 삭제
	function movedatafile($db, $id) {
		$data = $this->loaddatafile ( $db, $id );
		
		// 데이타가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/data/${id}c.cgi" );
		if (is_writeable ( $filename ))
			unlink ( $filename );
			
			// 인덱스가 있는 경로
		$filename = path_fix ( "$this->db_host/$db/${db}.idx" );
		if (! file_exists ( $filename ) || ! is_file ( $filename ))
			return 0;
		$index = file2 ( $filename );
		
		$data = array (
				$data ["id"],
				$data ["pid"],
				$data ["ppid"] 
		);
		$record = implode ( ";", $data );
		
		if (($idx = array_search ( $record, $index, true )) == false)
			return 0;
		unset ( $index [$idx] );
		
		return file_put_contents ( $filename, implode ( "\n", $index ) );
	}
	/**
	 * **********************************************************************\
	 * [].본문 읽기,쓰기
	 * \***********************************************************************
	 */
	// 본문내용 파일 읽기
	function loadcontentfile($data, $db, $id) {
		$this->timestamps ( "loadcontentfile($id)" );
		// 임시
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			return null;
			
			// 글 인덱스
		$filename = path_fix ( "$this->db_host/$db/data/${id}d.cgi" );
		if (! file_exists ( $filename ) || ! is_file ( $filename ))
			return null;
		
		return file_get_contents ( $filename );
	}
	
	// 본문내용 파일 쓰기
	function savecontentfile($text, $db, $id) {
		// 본문내용 없을경우?
		if (empty ( $text ))
			return 0; // false
				          
		// 글 인덱스
		$filename = path_fix ( "$this->db_host/$db/data/${id}d.cgi" );
		$this->timestamps ( "savecontentfile($id)" );
		return file_put_contents ( $filename, $text );
	}
	/**
	 * **********************************************************************\
	 * [].의견 읽기,쓰기
	 * \***********************************************************************
	 */
	// 의견 파일 읽기
	function loadopinionfile($db, $id) {
		$this->timestamps ( "loadopinionfile($id)" );
		// 임시
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			return null;
			
			// 글 인덱스
		$filename = path_fix ( "$this->db_host/$db/data/${id}e.cgi" );
		if (! file_exists ( $filename ) || ! is_file ( $filename ))
			return null;
		
		return file_get_contents ( $filename );
	}
	
	// 의견 파일 쓰기
	function saveopinionfile($text, $db, $id) {
		// 본문내용 없을경우?
		if (empty ( $text ))
			return 0; // false
				          
		// 글 인덱스
		$filename = path_fix ( "$this->db_host/$db/data/${id}e.cgi" );
		$this->timestamps ( "saveopinionfile($id)" );
		return file_put_contents ( $filename, $text );
	}
}
?>
