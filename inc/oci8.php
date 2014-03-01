<?php
/************************************************************************\
 * 프로그램명 : oracle 데이타베이스 연결
 * 특기사항   : 1.sqlplus scott/tiger@localhost @create_oracle.sql
  		2.업데이트 없는 리플방법(2004.03.10)
                3.무한대형 게시판(2004.06.17)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/08
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('inc/common.php');
class db_oci8 extends db_common {
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
		$this->db_host = $defconf ["oracle_host"];
		$this->db_user = $defconf ["oracle_user"];
		$this->db_pass = $defconf ["oracle_pass"];
		$this->db_name = $defconf ["oracle_name"];
	}
	
	// 연결확인
	function _connected() {
		return $this->link;
	}
	
	// 데이타베이스 시스템에 접속하기
	function _connect_db() {
		$this->timestamps ( "_connect_db" );
		if (! $this->_connected ()) {
			$this->link = ocilogon ( $this->db_user, $this->db_pass, $this->db_host );
			if (empty ( $this->link ))
				$this->_error_db ();
		} // end if
		return $this->link;
	}
	
	// 쿼리 실행하기
	function _execute_query($query, $mode = OCI_DEFAULT) {
		$this->timestamps ( "$query" );
		
		$this->last_query = $query;
		
		if (! $this->_connected ())
			return false;
			
			// sql검사
		$this->stmt = ociparse ( $this->link, $query );
		if (! $this->stmt)
			return $this->_error_db ();
			
			// sql실행
			// OCI_DEFAULT : 매번 실행시마다 자동 commit가 되지 않도록 함.
		$this->result = ociexecute ( $this->stmt, $mode );
		if (! $this->result)
			return $this->_error_db ();
		
		return $this->result;
	}
	
	// 에러메시지 출력하기
	function _error_db() {
		if ($error = ocierror ( $this->link )) {
			$query = wordwrap ( $this->last_query, 80, "\n", 1 );
			
			echo "<div>\n";
			echo "<table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<td height=40 bgcolor='red' align='center' valign='middle'>\n";
			echo "<font color='#ffffff'><b>error</b></font></td></tr><tr>\n";
			echo "<td bgcolor='white'>\n";
			echo "sql query : <pre>" . $query . "</pre><br>\n";
			echo "<p align=>" . $error ["message"] . "</p>\n";
			echo "</td>\n";
			echo "</tr><tr>\n";
			echo "<td height=40 bgcolor='red' align='center' valign='middle'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</div>\n";
			exit ();
		} // endif
	}
	
	// clob형에 대용량 텍스트를 저장하기
	function _execute_clob($query, $text) {
		$this->last_query = $query;
		
		// sql검사
		$this->stmt = ociparse ( $this->link, $query );
		if (! $this->stmt)
			return $this->_error_db ();
		
		$clob = ocinewdescriptor ( $this->link, OCI_D_LOB );
		ocibindbyname ( $this->stmt, ":text", &$clob, - 1, OCI_B_CLOB );
		
		// sql실행
		$this->result = ociexecute ( $this->stmt, OCI_DEFAULT );
		if (! $this->result)
			return $this->_error_db ();
		
		if ($clob->save ( $text ))
			ocicommit ( $this->link );
		else
			echo "problems:couldn't upload clob\n";
			
			// notice: ocifreedesc() should not be called like this.
			// use $clob->free() to free a lob in
			// ocifreedesc($clob);
		$clob->free ();
	}
	
	// blob형에 바이너리 파일을 저장하기
	function _execute_blob($query, $filename) {
		$this->last_query = $query;
		
		// sql검사
		$this->stmt = ociparse ( $this->link, $query );
		if (! $this->stmt)
			return $this->_error_db ();
		
		$blob = ocinewdescriptor ( $this->link, OCI_D_LOB );
		ocibindbyname ( $this->stmt, ":file", &$blob, - 1, OCI_B_BLOB );
		
		// sql실행
		$this->result = ociexecute ( $this->stmt, OCI_DEFAULT );
		if (! $this->result)
			return $this->_error_db ();
			
			// 업로드된 임시파일을 오픈한다.
		if (! $fp = fopen ( $filename, "rb" ))
			return;
			
			// 열려진 파일로부터 읽어들여 $buf 변수에 저장한다.
		$buf = fread ( $fp, filesize ( $filename ) );
		
		// 오픈된 파일을 닫는다.
		fclose ( $fp );
		
		// blob에 읽어들인 바이너리 파일을 저장한다.
		if ($blob->save ( $buf ))
			ocicommit ( $this->link );
		else
			echo "problems:couldn't upload blob\n";
			
			// notice: ocifreedesc() should not be called like this.
			// use $blob->free() to free a lob in
			// ocifreedesc($blob);
		$blob->free ();
	}
	
	// clob(blob)형을 데이타 읽기
	function _execute_get($query, $fieldno) {
		$this->last_query = $query;
		
		// sql검사
		$this->stmt = ociparse ( $this->link, $query );
		if (! $this->stmt)
			return $this->_error_db ();
			
			// sql실행
		$this->result = ociexecute ( $this->stmt );
		if (! $this->result)
			return $this->_error_db ();
			
			// blob형을 읽어들이기 위해 ocifetchinto를 이용한다.
		ocifetchinto ( $this->stmt, &$clob, OCI_ASSOC );
		
		return empty ( $clob ) ? null : $clob [$fieldno]->load ();
	}
	
	// 데이타베이스 연결종료
	function _disconnect_db() {
		$this->timestamps ( "_disconnect_db" );
		ocirollback ( $this->link );
		ocilogoff ( $this->link );
	}
	function _version_db() {
		return ociserverversion ( $this->link );
	}
	function create_db($db) {
		global $sysconf;
		
		$filename = path_fix ( "$sysconf[path_inc]/create_oracle.sql" );
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
				// intermedia option 설치(예정)
				// oracle long형인 자료형 검색하기
				// 처음에 http://database.sarang.net 에서 long형 검색이 가능하다고 해서
				// 아래와 같은 사이트를 참고 했는데요...
				$common_query = "select count(rowid) as cnt from $db where contains(content,'%$fw%')>0";
				break;
			
			default :
				$common_query = "select count(rowid) as cnt from $db where $ff like '%$fw%'";
				break;
		} // end switch
		
		if (empty ( $ff ) || empty ( $fw ))
			$common_query = "select count(rowid) as cnt from $db";
			
			// 데이터베이스 연결
		$this->_connect_db ();
		
		// sql실행
		$this->_execute_query ( $common_query );
		ocifetchinto ( $this->stmt, $row, OCI_ASSOC );
		$total = $row ["CNT"];
		
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
			case 'content' :
			case 'all' :
				$f_query = "and contains(content,'%$fw%')>0";
				break;
			
			default :
				$f_query = "and $ff like '%$fw%'";
				break;
		} // end switch
		
		if (empty ( $ff ) || empty ( $fw ))
			$f_query = "";
			
			// 데이터베이스 연결
		$this->_connect_db ();
		
		// 페이지 오프셋(첫번째=1,맨끝=전체건수)
		$start_offset = max ( ($page - 1) * $limit, 1 );
		$end_offset = min ( $page * $limit, $total );
		$offset_query = "select ppid from (select --+index_desc({$db},{$db}_idx_02)\n" . "ppid,rownum rnum from {$db} where ppid>0 $f_query)\n" . "where rnum>=$start_offset and rownum <= $limit";
		
		// 전체 갯수에서 반을 나눠 앞뒤정렬검색
		if ($start_offset > ($total / 2))
			$flag = 1;
		else
			$flag = 0;
			
			// 절반이상
		if ($flag) {
			$end_offset = max ( $total - $end_offset, 0 );
			$offset_query = "select ppid from (select --+index({$db},{$db}_idx_02)\n" . "ppid,rownum rnum from {$db} where ppid>0 $f_query)\n" . "where rnum>=$end_offset and rownum <= $limit";
		} // end if
		
		$this->_execute_query ( $offset_query );
		while ( ocifetchinto ( $this->stmt, $row, OCI_ASSOC ) )
			$offset [] = $row ["PPID"];
			
			// 시작점,끝점
		$start_ppid = min ( $offset );
		$end_ppid = max ( $offset );
		
		// 시작점 이전 데이타수
		$count_query = "select count(ppid) as cnt from $db where ppid > $end_ppid $f_query";
		if ($flag)
			$count_query = "select count(ppid) as cnt from {$db} where ppid <= $end_ppid $f_query";
			
			// sql실행
		$this->_execute_query ( $count_query );
		ocifetchinto ( $this->stmt, $row, OCI_ASSOC );
		$count = $row ["CNT"];
		
		if ($flag)
			$count = max ( $total - $count, 0 );
			
			// sql설정
		$common_query = "select --+use_nl({$db},{$db}_idx_02)\n" . "id,pid,ppid from $db\n" . "where ppid >= $start_ppid and ppid <= $end_ppid $f_query";
		
		// sql실행
		$index = array ();
		$this->_execute_query ( $common_query );
		while ( ocifetchinto ( $this->stmt, $row, OCI_ASSOC ) )
			$index [] = array_change_key_case ( $row );
			
			// 데이터베이스 연결종료
		$this->_disconnect_db ();
		
		// 업데이트 없는 리플방법(2004.03.10)
		$index = index_chain ( 'id', 'pid', 'ppid', $index );
		
		// 전페이지에 출력한 데이타 제거하기
		if ($start_offset > 1)
			array_splice ( $index, 0, $start_offset - $count );
		
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
		while ( ocifetchinto ( $this->stmt, $row, OCI_ASSOC ) )
			$index [] = array_change_key_case ( $row );
			
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
		
		$this->_connect_db ();
		$this->_execute_query ( $common_query );
		while ( ocifetchinto ( $this->stmt, $row, OCI_ASSOC ) )
			$index [$row [strtoupper ( $ff )]] [] = $row ["ID"];
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
		$list = array ();
		
		if (count ( $index ) > 0)
			foreach ( $index as $no => $row ) {
				$common_query = "select * from $db where id=$row";
				
				$this->_execute_query ( $common_query );
				ocifetchinto ( $this->stmt, &$temp, OCI_ASSOC + OCI_RETURN_NULLS );
				$temp = $this->decodedheader ( $temp );
				
				$list [$row] = $temp;
			} // end foreach
		
		$this->_disconnect_db ();
		
		return empty ( $list ) ? null : $list;
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
		
		$update = $fields = $values = array ();
		foreach ( $data as $key => $val ) {
			$update [] = "$key=" . quote ( $val );
			$fields [] = $key;
			$values [] = quote ( $val );
		} // end foreach
		
		$update = implode ( ",", $update );
		$fields = implode ( ",", $fields );
		$values = implode ( ",", $values );
		
		// 신규추가,답변시 새로운 인덱스 추가하기
		switch ($cmd) {
			case 'reply' :
			case 'write' :
				$common_query = "insert into $db ($fields) values ($values)";
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
			
			// blob형을 읽어들이기 위해 _value 이용한다.
		$common_query = "select CONTENT from $db where id=$id";
		
		$this->_connect_db ();
		$text = $this->_execute_get ( $common_query, "CONTENT" );
		$this->_disconnect_db ();
		
		return $text;
	}
	
	// 본문내용 파일 쓰기
	function savecontentfile($text, $db, $id) {
		$text = quote ( $text );
		
		$common_query = "update $db set content=empty_clob() where id=$id " . "returning content into :text";
		
		$this->_connect_db ();
		$this->_execute_clob ( $common_query, $text );
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
			
			// blob형을 읽어들이기 위해 _value 이용한다.
		$common_query = "select OPINION from $db where id=$id";
		
		$this->_connect_db ();
		$text = $this->_execute_get ( $common_query, "OPINION" );
		$this->_disconnect_db ();
		
		return $text;
	}
	
	// 의견 파일 쓰기
	function saveopinionfile($text, $db, $id) {
		$text = quote ( $text );
		
		$common_query = "update $db set opinion=empty_clob() where id=$id " . "returning opinion into :text";
		
		$this->_connect_db ();
		$this->_execute_clob ( $common_query, $text );
		$this->_disconnect_db ();
	}
}
?>
