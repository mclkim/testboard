<?php
/************************************************************************\
 * 프로그램명 : pop3 연결
 * 특기사항   : 1.pop3(post office protocol)기능
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2004/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('common.php');
require_once ('mime.php');
class db_pop3 extends db_common {
	var $db_host = null; // 호스트
	var $db_user = null; // 사용자
	var $db_pass = null; // 암호
	var $db_name = null; // 데이타베이스
	var $socket = 0;
	var $result = false;
	var $last_cmd, $last_msg;
	var $errno, $errstr;
	
	// 기본설정
	function db_set($defconf) {
		if (empty ( $defconf ))
			return;
		$this->timestamps ( "db_set" );
		$this->db_host = $defconf ["pop3_host"];
		$this->db_user = $defconf ["pop3_user"];
		$this->db_pass = $defconf ["pop3_pass"];
		$this->db_name = $defconf ["pop3_name"];
	}
	
	// 시스템에 접속하기
	function _connect() {
		$this->last_msg = "open socket";
		
		if (! $this->_connected ()) {
			$this->timestamps ( "_connect" );
			
			$this->socket = fsockopen ( $this->db_host, port_pop3, &$this->errno, &$this->errstr, 15 ) or die ( "fsockopen file open" );
			
			$this->_command ( "" );
			
			if ($this->_login ( $this->db_user, $this->db_pass ))
				unset ( $this->socket );
		} // end if
		
		return $this->socket;
	}
	
	// 연결확인
	function _connected() {
		if ($this->socket <= 0)
			return false;
		
		$sock_status = socket_get_status ( $this->socket );
		
		return empty ( $sock_status ["eof"] );
	}
	
	// 쿼리 실행하기
	function _command($cmd) {
		$this->last_msg = "";
		$this->last_cmd = $cmd;
		
		if (! $this->_connected ())
			return false;
			
			// 명령어를 전달
		if ($cmd)
			fwrite ( $this->socket, $cmd . "\r\n" );
		
		$this->last_msg = $this->_fetch_row ();
		
		$this->timestamps ( "$cmd" );
		return $this->result = ereg ( "^(\+OK)", $this->last_msg );
	}
	function _login($user, $pass) {
		if (empty ( $user ) || empty ( $pass ))
			return false;
		
		$command = "user " . $user;
		if (! $this->_command ( $command ))
			return false;
		
		$command = "pass " . $pass;
		if (! $this->_command ( $command ))
			return false;
	}
	function _fetch_row() {
		$buffer = fgets ( $this->socket, 8192 );
		return preg_replace ( "/\r?\n/", "\r\n", $buffer );
	}
	function _fetch_assoc() {
		if (chop ( $line = $this->_fetch_row () ) == ".")
			return 0;
		return $line;
	}
	
	// 에러메시지 출력하기
	function _error_db() {
		if ($this->errno) {
			$cmd = wordwrap ( $this->last_cmd, 80, "\n", 1 );
			
			echo ("<div>\n");
			echo ("<table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'>\n");
			echo ("<tr>\n");
			echo ("<td height=40 bgcolor='red' align='center' valign='middle'>\n");
			echo ("<font color='#ffffff'><b>error</b></font>\n");
			echo ("</td></tr>\n");
			echo ("<tr>\n");
			echo ("<td bgcolor='white'>\n");
			echo ("sql query : <pre>$cmd</pre><br>\n");
			echo ("<p align=>$this->errstr</p>\n");
			echo ("</td></tr>\n");
			echo ("<tr>\n");
			echo ("<td height=40 bgcolor='red' align='center' valign='middle'>\n");
			echo ("</td></tr>\n");
			echo ("</table>\n");
			echo ("</div>\n");
			exit ();
		} // endif
	}
	
	// 연결종료
	function _disconnect() {
		return true;
		
		// send pop3 quit command
		$this->_command ( "quit" );
		
		// close socket
		fclose ( $this->socket );
		unset ( $this->socket );
	}
	function _version_db() {
	}
	function create_db($db) {
	}
	function drop_db($db) {
	}
	/**
	 * **********************************************************************\
	 * [].인덱스 읽기
	 * \***********************************************************************
	 */
	// 전체 데이타수 읽기
	function loadtotal($db, $ff = '', $fw = '') {
		// sql설정
		$command = "stat";
		
		// 연결
		$this->_connect ();
		
		// sql실행
		$this->_command ( $command );
		$msgs = split ( " ", $this->last_msg );
		$total = $msgs [1];
		
		// 연결종료
		$this->_disconnect ();
		
		return $total;
	}
	
	// 전체 인덱스 읽기
	function loadindexfile($db) {
		// sql설정
		$command = "list";
		
		// 연결
		$this->_connect ();
		
		// sql실행
		$this->_command ( $command );
		
		while ( $line = $this->_fetch_assoc () ) {
			$msgs = split ( " ", $line );
			$index [] = $msgs [0];
		} // end while
		  
		// 연결종료
		$this->_disconnect ();
		
		if (count ( $index ) > 1)
			rsort ( $index );
		
		return $index;
	}
	
	// 검색 인덱스 읽기
	function findindexfile($db, $ff, $fw) {
		global $sysconf;
		
		// 임시
		if (empty ( $ff ) || empty ( $fw ))
			return null;
			
			// 파일찾기 파라미터 수정(2001.12.14)
		switch ($ff) {
			case 'content' :
			case 'all' :
				break;
			
			default :
				$common_query = "$sysconf[findall] $ff=.*$fw " . path_fix ( "$sysconf[path_host]/db/$db/data/*.cgi" );
				break;
		} // end switch
		
		$rows = runshell ( $common_query );
		
		// 임시(파일이 없으면)
		if (empty ( $rows ))
			return null;
			
			// 파일이름 추출하기
		if (is_array ( $rows ))
			$index = array_map ( "extractfilename", $rows );
		
		return empty ( $index ) ? null : $index;
	}
	
	// 정렬 인덱스 읽기
	function sortindexfile($db, $ff) {
	}
	
	// 답변글 인덱스 파일읽기(2002.03.12)
	function findreplyfile($db, $ppid) {
	}
	/**
	 * **********************************************************************\
	 * [].글 읽기,쓰기,삭제
	 * \***********************************************************************
	 */
	// 목록 파일 읽기
	function loaddatalist($db, $index) {
		global $sysconf;
		
		$this->_connect ();
		$data = array ();
		if (count ( $index ) > 0)
			foreach ( $index as $no => $row ) {
				$command = "top $row 0";
				
				$this->_command ( $command );
				
				$messages = '';
				while ( $line = $this->_fetch_assoc () )
					$messages .= $line;
				
				$mime = new mime ();
				$temp = $mime->get_mail_info ( $messages );
				
				$temp ["id"] = $row; // 수정예정
				$temp ["rowid"] = md5 ( trim ( $temp ["date"] . $temp ["message-id"] ) );
				$temp ["name"] = $temp ["from"] [0] ["name"];
				$temp ["email"] = $temp ["from"] [0] ["mail"];
				$temp ["in_date"] = date ( shortdateformat, $temp ["date"] );
				$temp ["in_time"] = $temp ["mo_time"] = $temp ["date"];
				$temp ["localname"] = path_fix ( "$sysconf[path_host]/db/$db/data/$temp[rowid].eml" );
				
				$data [$row] = $temp;
			} // end foreach
		
		$this->_disconnect ();
		
		return $data;
	}
	
	// 글 파일 읽기
	function loaddatafile($db, $id) {
		// 임시
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			return null;
		
		$temp = $this->loaddatalist ( $db, array (
				$id 
		) );
		
		return empty ( $temp ) ? null : $temp [$id];
	}
	
	// 글 파일 쓰기
	function savedatafile($data, $db, $cmd = "") {
	}
	// 글 파일 삭제
	function movedatafile($db, $id) {
	}
	/**
	 * **********************************************************************\
	 * [].본문 읽기,쓰기
	 * \***********************************************************************
	 */
	// 본문내용 파일 읽기
	function loadcontentfile(&$msg, $db, $id) {
		global $sysconf;
		
		// 임시
		if (empty ( $id ) || ($id = to_index ( $id )) < 0)
			return null;
		
		if (file_exists ( $msg ["localname"] )) {
			$msgcontent = file_get_contents ( $msg ["localname"] );
		} else {
			$command = "retr $id";
			
			$this->_connect ();
			$this->_command ( $command );
			$msgcontent = '';
			while ( $line = $this->_fetch_assoc () )
				$msgcontent .= $line;
			$this->_disconnect ();
			
			file_put_contents ( $msg ["localname"], $msgcontent );
		} // end if else
		
		$mime = new mime ();
		$mime->user_folder = path_fix ( "$sysconf[path_host]/db/$db/upload" );
		$temp = $mime->decode ( $msgcontent );
		
		$msg ["htmltype"] = $mime->use_html;
		$msg ["attachfile"] = $temp ["attachments"];
		
		return $temp ["body"];
	}
	
	// 본문내용 파일 쓰기
	function savecontentfile($text, $db, $id) {
	}
	/**
	 * **********************************************************************\
	 * [].의견 읽기,쓰기
	 * \***********************************************************************
	 */
	// 의견 파일 읽기
	function loadopinionfile($db, $id) {
	}
	
	// 의견 파일 쓰기
	function saveopinionfile($text, $db, $id) {
	}
}
?>
