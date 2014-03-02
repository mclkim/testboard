<?php
/************************************************************************\
 * 프로그램명 : mysql 데이타베이스 연결
 * 특기사항   : 1.mysql -u root -p < create_mysql.sql
           2.업데이트 없는 리플방법(2004.03.10)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/08
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('common.php');
class db_mysql extends db_common {
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
		$this->db_host = $defconf ["mysql_host"];
		$this->db_user = $defconf ["mysql_user"];
		$this->db_pass = $defconf ["mysql_pass"];
		$this->db_name = $defconf ["mysql_name"];
	}
	
	// 데이타베이스 시스템에 접속하기
	function _connect_db() {
		$this->timestamps ( "_connect_db" );
		if (! $this->_connected ()) {
			$this->link = mysql_connect ( $this->db_host, $this->db_user, $this->db_pass );
			if (empty ( $this->link ))
				$this->_error_db ();
				
				// 데이터베이스를 선택하기
			$this->_select_db ( $this->db_name );
		} // end if
		
		return $this->link;
	}
	
	// 연결확인
	function _connected() {
		if ($this->link <= 0)
			return false;
	}
	
	// 데이터베이스를 선택하기
	function _select_db($dbname) {
		$this->timestamps ( "_select_db" );
		
		$this->result = mysql_select_db ( $dbname, $this->link );
		if (! $this->result)
			$this->_error_db ();
		return $this->result;
	}
	
	// 쿼리 실행하기
	function _execute_query($query) {
		$this->timestamps ( "$query" );
		
		$this->last_query = $query;
		
		// if(!$this->_connected()) return false;
		
		// sql실행
		$this->result = mysql_query ( $query, $this->link );
		
		if (! $this->result)
			$this->_error_db ();
		
		return $this->result;
	}
	
	// 에러메시지 출력하기
	function _error_db() {
		if ($errno = mysql_errno ()) {
			$error = mysql_error ();
			$query = wordwrap ( $this->last_query, 80, "\n", 1 );
			
			echo ("<div>\n");
			echo ("<table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'>\n");
			echo ("<tr>\n");
			echo ("<td height=40 bgcolor='red' align='center' valign='middle'>\n");
			echo ("<font color='#ffffff'><b>error</b></font>\n");
			echo ("</td></tr>\n");
			echo ("<tr>\n");
			echo ("<td bgcolor='white'>\n");
			echo ("sql query : <pre>" . $query . "</pre><br>\n");
			echo ("<p align=left>" . $error . "</p>\n");
			echo ("</td></tr>\n");
			echo ("<tr>\n");
			echo ("<td height=40 bgcolor='red' align='center' valign='middle'>\n");
			echo ("</td></tr>\n");
			echo ("</table>\n");
			echo ("</div>\n");
			exit ();
		} // endif
	}
	
	// 데이타베이스 연결종료
	function _disconnect_db() {
		$this->timestamps ( "_disconnect_db" );
		mysql_close ( $this->link );
		unset ( $this->link );
	}
	function _version_db() {
		$temp = mysql_query ( "select version() as version" );
		$version = mysql_fetch_array ( $temp );
		return $version ["version"];
	}
	function create_db($db) {
		global $sysconf;
		
		$filename = path_fix ( "$sysconf[path_inc]/create_mysql.sql" );
		$common_query = file_get_contents ( $filename );
		
		$common_query = ereg_replace ( "<database>", $this->db_name, chop ( $common_query ) );
		$common_query = ereg_replace ( "<table_name>", $db, chop ( $common_query ) );
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$this->_disconnect_db ();
	}
	function drop_db($db) {
		return $this->_execute_query ( "drop table ${db}" );
	}
	/**
	 * **********************************************************************\
	 * [].인덱스 읽기
	 * \***********************************************************************
	 */
	// 전체 데이타수 읽기
	function loadtotal($db, $ff = '', $fw = '') {
		switch ($ff) {
			case 'content' :
			case 'all' :
				$common_query = "select count(id) as cnt from $db where content like '%$fw%'";
				break;
			
			default :
				$common_query = "select count(id) as cnt from $db where $ff like '%$fw%'";
				break;
		} // end switch
		
		if (empty ( $ff ) || empty ( $fw ))
			$common_query = "select count(id) as cnt from $db";
			
			// 데이터베이스 연결
		$this->_connect_db ();
		
		// sql실행
		$this->_execute_query ( $common_query );
		$temp = mysql_fetch_assoc ( $this->result );
		$total = $temp ["cnt"];
		
		// 데이터베이스 연결종료
		$this->_disconnect_db ();
		
		return $total;
	}
	// 전체 인덱스 읽기
	function loadindexfile($db, $total = 0, $page = 1, $limit = 15) {
		return $this->findindexfile ( $db, '', '', $total, $page, $limit );
	}
	// 검색 인덱스 읽기
	function findindexfile($db, $ff = '', $fw = '', $total = 0, $page = 1, $limit = 15) {
		switch ($ff) {
			case 'id' :
			case 'pid' :
			case 'ppid' :
				$f_query = "and $ff = $fw";
				break;
			
			case 'content' :
			case 'all' :
				$f_query = "and content like '%$fw%'";
				break;
			
			default :
				$f_query = "and $ff like '%$fw%'";
				break;
		} // end switch
		
		if (empty ( $ff ) || empty ( $fw ))
			$f_query = "";
			
			// 데이터베이스 연결
		$this->_connect_db ();
		
		// 페이지 오프셋(첫번째=0,맨끝=전체건수-1)
		$start_offset = max ( ($page - 1) * $limit, 0 );
		$end_offset = min ( $page * $limit, $total - 1 );
		$offset_query = "select ppid from {$db} where 1=1 $f_query order by ppid desc limit $start_offset,$limit";
		
		// 전체 갯수에서 반을 나눠 앞뒤정렬검색
		if ($start_offset > ($total / 2))
			$flag = 1;
		else
			$flag = 0;
			
			// 절반이상
		if ($flag) {
			$end_offset = max ( $total - $end_offset, 0 );
			$offset_query = "select ppid from {$db} where 1=1 $f_query order by ppid limit $end_offset,$limit";
		} // end if
		
		_debug ( $offset_query );
		
		// sql실행
		$this->_execute_query ( $offset_query );
		
		$offset [] = '0';
		while ( $row = mysql_fetch_assoc ( $this->result ) )
			$offset [] = $row ["ppid"];
		
		_debug ( $offset );
		
		// 시작점,끝점
		$start_ppid = min ( $offset );
		$end_ppid = max ( $offset );
		
		_debug ( $start_ppid );
		_debug ( $end_ppid );
		
		// 시작점 이전 데이타수
		$count_query = "select count(ppid) as cnt from {$db} where ppid > $end_ppid $f_query";
		if ($flag)
			$count_query = "select count(ppid) as cnt from {$db} where ppid <= $end_ppid $f_query";
		
		$this->_execute_query ( $count_query );
		$row = mysql_fetch_assoc ( $this->result );
		$count = $row ["cnt"];
		
		if ($flag)
			$count = max ( $total - $count, 0 );
			
			// 시작점과 끝점사이의 데이타(해당페이지 게시물)
		$common_query = "select id,pid,ppid from $db\n" . "where ppid >= $start_ppid and ppid <= $end_ppid $f_query";
		
		// sql실행
		$this->_execute_query ( $common_query );
		
		$index = array ();
		while ( $row = mysql_fetch_assoc ( $this->result ) )
			$index [] = $row;
			
			// 데이터베이스 연결종료
		$this->_disconnect_db ();
		
		// 업데이트 없는 리플방법(2004.03.10)
		$index = index_chain ( 'id', 'pid', 'ppid', $index );
		
		// 시작점 이전 데이타 제거하기
		if ($start_offset > 1)
			array_splice ( $index, 0, $start_offset - $count );
		
		_debug ( count ( $index ) );
		
		return $index;
	}
	
	// 정렬 인덱스 읽기
	function sortindexfile($db, $ff) {
	}
	
	// 답변글 인덱스 파일읽기(2002.03.12)
	function findreplyfile($db, $ppid) {
		// 임시
		if (($ppid) < 1)
			return null;
			
			// sql설정
		$common_query = "select id,pid,ppid from $db where ppid=$ppid";
		
		// 데이터베이스 연결
		$this->_connect_db ();
		
		// sql실행
		$this->_execute_query ( $common_query );
		
		$index = array ();
		while ( $row = mysql_fetch_assoc ( $this->result ) )
			$index [] = $row;
			
			// 데이터베이스 연결종료
		$this->_disconnect_db ();
		
		// 업데이트 없는 리플방법(2004.03.10)
		return index_chain ( 'id', 'pid', 'ppid', $index );
	}
	
	// 인덱스 파일읽기(2002.03.12)
	function findfile($db, $ff, $fw) {
		// 임시
		if (empty ( $ff ) || empty ( $fw ))
			return null;
			
			// sql설정
		$common_query = "select id,$ff from $db where $ff like '%$fw%'";
		
		// 데이터베이스 연결
		$this->_connect_db ();
		
		// sql실행
		$this->_execute_query ( $common_query );
		
		$index = array ();
		while ( $row = mysql_fetch_assoc ( $this->result ) )
			$index [$row [$ff]] [] = $row ["id"];
		$this->_disconnect_db ();
		
		return empty ( $index ) ? null : $index;
	}
	/**
	 * **********************************************************************\
	 * [].글 읽기,쓰기,삭제
	 * \***********************************************************************
	 */
	// 목록 파일 읽기
	function loaddatalist($db, $index) {
		$this->_connect_db ();
		
		$data = array ();
		if (count ( $index ) > 0)
			foreach ( $index as $no => $row ) {
				$common_query = "select * from $db where id=$row";
				
				$this->_execute_query ( $common_query );
				$temp = mysql_fetch_assoc ( $this->result );
				$temp = $this->decodedheader ( $temp );
				
				$data [$row] = $temp;
			} // end foreach
		
		$this->_disconnect_db ();
		
		return empty ( $data ) ? null : $data;
	}
	
	// 글 파일 읽기
	function loaddatafile($db, $id) {
		// 임시
		if (empty ( $id ) || ($id) < 0)
			return null;
		
		$temp = $this->loaddatalist ( $db, array (
				$id 
		) );
		
		return empty ( $temp ) ? null : $temp [$id];
	}
	
	// 글 파일 쓰기
	function savedatafile($data, $db, $cmd = "") {
		$data = $this->encodedheader ( $data );
		
		// long type skip
		unset ( $data ["content"] );
		unset ( $data ["opinion"] );
		
		$update = array ();
		foreach ( $data as $key => $val )
			$update [] = "$key=" . quote ( $val );
		
		$update = implode ( ",", $update );
		
		// 신규추가,답변시 새로운 인덱스 추가하기
		switch ($cmd) {
			case 'reply' :
			case 'write' :
				$common_query = "insert into $db set $update";
				break;
			
			default :
				$common_query = "update $db set $update where id=" . ($data ["id"]);
				break;
		} // end switch
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$this->_disconnect_db ();
	}
	
	// 글 파일 삭제
	function movedatafile($db, $id) {
		$common_query = "delete from $db where id=$id";
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$this->_disconnect_db ();
	}
	/**
	 * **********************************************************************\
	 * [].본문 읽기,쓰기
	 * \***********************************************************************
	 */
	// 본문내용 파일 읽기
	function loadcontentfile($data, $db, $id) {
		// 임시
		if (empty ( $id ) || ($id) < 0)
			return null;
		
		$common_query = "select content from $db where id=$id";
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$row = mysql_fetch_assoc ( $this->result );
		$this->_disconnect_db ();
		
		return $row ["content"];
	}
	
	// 본문내용 파일 쓰기
	function savecontentfile($text, $db, $id) {
		$text = quote ( $text );
		
		$common_query = "update $db set content=$text where id=$id";
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$this->_disconnect_db ();
	}
	/**
	 * **********************************************************************\
	 * [].의견 읽기,쓰기
	 * \***********************************************************************
	 */
	// 의견 파일 읽기
	function loadopinionfile($db, $id) {
		// 임시
		if (empty ( $id ) || ($id) < 0)
			return null;
		
		$common_query = "select opinion from $db where id=$id";
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$row = mysql_fetch_assoc ( $this->result );
		$this->_disconnect_db ();
		
		return $row ["opinion"];
	}
	
	// 의견 파일 쓰기
	function saveopinionfile($text, $db, $id) {
		$text = quote ( $text );
		
		$common_query = "update $db set opinion=$text where id=$id";
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		$this->_disconnect_db ();
	}
}
?>
