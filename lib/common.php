<?php
/**
 * ---------------------------------------------------------------
 * 프로그램명 : 파일기본(공통)함수
 * 특기사항   : 1.일자별 접속수 관리기능(2001.04.16)
 *         2.일별접속현황 파일읽기(2001.10.17)
 *         3.월별접속현황(예정)
 *         4.공지기능추가(2002.11.05)
 *         5.회원데이타 db 에 연결(예정)
 *         6.사용자(user)와 그룹(group) 권한(예정)
 *         7.메일리스팅-그룹별관리(예정)
 * 관련테이블 :
 * services :
 * 작 성 자   : 김명철
 * 작성일자   : 2002/07
 * 수 정 자   : 김명철
 * 수정일자   : 2014/03
 * 수정내역   :
 * ---------------------------------------------------------------
 */
if (defined ( '_common_' ))
	return;
define ( '_common_', true );

require_once ('../inc/common.inc');
require_once ('php1lib.php');
require_once ('php1session.php');

$def_data = array (
		'id' => 0,
		'pid' => 0,
		'ppid' => 0,
		'depth' => 0,
		'name' => "", // 작성자(보낸사람)(2003.12.22)
		'email' => "", // 작성자이메일(보낸사람)(2003.12.22)
		'homeurl' => "http://",
		'jumin' => "", // 주민번호
		'category' => "", // 카테고리(2003.05.14)
		'subject' => "", // 제목
		'passwd' => "", // 비밀번호
		'htmltype' => "0", //
		'privatetype' => "0",
		'attachfile' => array (), // 첨부파일(2004.03.10)
		'small_image' => "",
		'readcount' => 0,
		// 'hit' =>0,
		// 'comment' =>0,
		'content' => null,
		'opinion' => null,
		'anniversary' => time_short, // 기록일(2003.04.14)
		'in_date' => time_short,
		'in_time' => time_now,
		'mo_time' => time_now,
		'ip' => remote_addr,
		'domain' => user_agent 
);

$def_user = array (
		'uid' => "",
		'name' => "",
		'email' => "",
		'homeurl' => "http://",
		'jumin' => "",
		'mailing' => 0,
		'sex' => 0,
		'birth' => "",
		'phone' => "",
		'post' => "",
		'address' => "",
		'os' => PHP_OS,
		'work' => "",
		'dept' => "",
		'workurl' => "http://",
		'intro' => "",
		'special' => "",
		'passwd' => "",
		'point' => "0",
		'private' => "0",
		'lastlogin' => "",
		'querycount' => "0",
		'in_date' => time_short,
		'in_time' => time_now,
		'mo_time' => time_now,
		'ip' => remote_addr,
		'domain' => user_agent 
);

$def_mail = array (
		'message-id' => "",
		'content-type' => "",
		'priority' => "",
		'flags' => "",
		'content-transfer-encoding' => "",
		'date' => "",
		'subject' => "",
		'from' => array (), // 보낸사람(작성자)
		'to' => array (), // 받는사람(작성자)
		'cc' => array (),
		'reply-to' => array (),
		'status' => "",
		'read' => "" 
);
/**
 * ---------------------------------------------------------------
 * 데이타베이스 엔진을 환경파일에서 선택,연결한다.(2002.07.13)
 * 각 데이타베이스의 기본(공통)함수를 정의한다.
 * 데이타베이스 스키마를 자동생성할 수 있도록 구조변경(예정)
 * ---------------------------------------------------------------
 */
class db_common {
	var $session = array ();
	var $timestamps = array ();
	function db_common() {
		global $userid, $passwd;
		$this->timestamps ( "db_common" );
		
		// 세션설정
		// $this->session = new oksess ();
		$this->session = new session ();
		
		if (! $this->session->getsess ( "userid" ))
			$this->session->setsess ( "userid", $userid );
		
		if (! $this->session->getsess ( "passwd" ))
			$this->session->setsess ( "passwd", $passwd );
			
			// 세션을 이용하여 회원정보 읽어오기(수정필요)
		if ($this->session->getsess ( "userid" ))
			$this->sess_user = $this->loaduserfile ( $this->session->getsess ( "userid" ) );
	}
	function timestamps($func) {
		$this->timestamps [sizeof ( $this->timestamps ) . " => $func"] = getmicrotime ();
	}
	/**
	 * ---------------------------------------------------------------
	 * [].기타함수
	 * ---------------------------------------------------------------
	 */
	function decodedheader($array) {
		$decodedheader = array ();
		
		if (is_array ( $array ))
			foreach ( $array as $key => $value ) {
				if (@unserialize ( $value ) === false)
					$decodedheader [strtolower ( $key )] = $value;
				else
					$decodedheader [strtolower ( $key )] = unserialize ( $value );
			} // end foreach
		
		return $decodedheader;
	}
	function encodedheader($array) {
		$encodedheader = array ();
		
		if (is_array ( $array ))
			foreach ( $array as $key => $value ) {
				if (is_array ( $value ))
					$encodedheader [$key] = serialize ( $value );
				else
					$encodedheader [$key] = $value;
			} // end foreach
		
		return $encodedheader;
	}
	/**
	 * ---------------------------------------------------------------
	 * [].인덱스파일
	 * ---------------------------------------------------------------
	 */
	function index2head($index, $idx) {
		return ($index [$idx]);
	}
	function head2index($index, $id) {
		if (! is_array ( $index ))
			return - 1;
		$ret = array_search ( ($id), $index, false );
		return (is_int ( $ret )) ? $ret : - 1;
	}
	/**
	 * ---------------------------------------------------------------
	 * [].로그파일
	 * ---------------------------------------------------------------
	 */
	// 일자별 접속수 관리기능(2001.04.16)
	// 일자별 접속수 증가하기
	function readhitcount($inc = 0) {
		global $sysconf;
		
		$idx = $cnt = $hit = $to = $min = $max = 0;
		$filename = $sysconf ["file_cnt"];
		
		// 파일읽기
		$text = file_get_contents ( $filename );
		list ( $today, $idx, $cnt, $hit, $to, $min, $max ) = explode ( "|", chop ( $text ) );
		
		// 일자별 접속수 관리기능(2001.04.16)
		if ($this->savelogfile ( $today )) {
			$to = 0;
		} // endif
		
		$hit += $inc;
		$to += $inc;
		$min = min ( $min, $to );
		$max = max ( $max, $to );
		
		file_put_contents ( $filename, "$today|$idx|$cnt|$hit|$to|$min|$max\n" );
		
		return compact ( "today", "hit", "to", "max" );
	}
	
	// 일별접속현황 파일읽기(2001.10.17)
	function loadlogfile($year = "") {
		global $sysconf;
		
		// 해당년에 대한 내용만 보여 주기(2002.03.27)
		$common_query = "$sysconf[grepcmd] ^$year/ $sysconf[file_log]";
		
		return runshell ( $common_query );
	}
	
	// 일별(바뀌면)접속현황 저장하기
	function savelogfile(&$today) {
		global $sysconf;
		
		if (is_equal ( $today, gettodate () ))
			return false;
		
		$today = gettodate ();
		$common_query = "$sysconf[typecmd] $sysconf[file_cnt] >> $sysconf[file_log]";
		runshell ( $common_query );
		
		return true;
	}
	/**
	 * ---------------------------------------------------------------
	 * [].회원파일
	 * ---------------------------------------------------------------
	 */
	// 회원파일 모두 찾기(2001.07.05)
	function alluserfile() {
		global $sysconf;
		
		$common_query = "$sysconf[findall] name=. " . path_fix ( "$sysconf[path_users]/*" );
		return runshell ( $common_query );
	}
	
	// 회원정보(파일)읽기
	function loaduserfile($uid) {
		global $sysconf;
		
		// 임시
		if (empty ( $uid ))
			return null;
		
		$filename = path_fix ( "$sysconf[path_users]/$uid.cgi" );
		$temp = file2array ( $filename );
		
		return empty ( $temp ) ? null : $this->decodedheader ( $temp );
	}
	
	// 회원정보(파일)쓰기
	function saveuserfile($data) {
		global $sysconf;
		
		$data = $this->encodedheader ( $data );
		
		// 회원아이디
		$uid = trim ( $data ["uid"] );
		$filename = path_fix ( "$sysconf[path_users]/$uid.cgi" );
		
		return array2file ( $filename, $data );
	}
	
	// 회원정보(파일)삭제
	function moveuserfile($uid) {
		global $sysconf;
		
		// 회원아이디
		$uid = trim ( $uid );
		$filename = path_fix ( "$sysconf[path_users]/$uid.cgi" );
		
		if (is_writeable ( $filename ))
			unlink ( $filename );
	}
}
/**
 * ---------------------------------------------------------------
 * --.this is a class i built to sort parent/child relationships of array elements.
 * ---------------------------------------------------------------
 */
function index_chain($id, $pid, $ppid, $rows, $root_id = 0, $maxlevel = 25) {
	$c = new index_chain_2 ( $id, $pid, $ppid, $rows, $root_id, $maxlevel );
	
	return $c->index;
}
function array_qsort(&$array, $column = 0, $order = "asc") {
	$oper = ($order == "asc") ? ">" : "<";
	usort ( $array, create_function ( '$a,$b', "return (\$a['$column'] $oper \$b['$column']);" ) );
	reset ( $array );
}

// 무 업데이트형 응답게시판 알고리즘(1)
class index_chain_1 {
	var $id;
	var $pid;
	var $ppid;
	var $table;
	var $chain_table;
	var $index;
	function index_chain_1($id, $pid, $ppid, $rows, $root_id, $maxlevel) {
		$this->id = $id;
		$this->pid = $pid;
		$this->ppid = $ppid;
		
		if (! is_array ( $rows ))
			return;
			
			// 중요
		foreach ( $rows as $item )
			$this->table [$item [$this->pid]] [$item [$this->id]] = $item;
			
			// 정렬(재귀적 호출법)
		$this->make_chain ( $root_id, 0, $maxlevel );
	}
	
	// 재귀적 호출법
	function make_chain($parent_id, $level, $maxlevel) {
		$rows = $this->table [$parent_id];
		
		if (! is_array ( $rows ))
			return;
			
			// 중요
		array_qsort ( $rows, $this->ppid, 'desc' );
		
		foreach ( $rows as $item ) {
			$item ["indent"] = $level;
			$this->chain_table [] = $item;
			$this->index [] = $item [$this->id];
			if ((isset ( $this->table [$item [$this->id]] )) && (($maxlevel > $level + 1) || ($maxlevel == 0)))
				$this->make_chain ( $item [$this->id], $level + 1, $maxlevel );
		} // end foreach
	}
}

// 무 업데이트형 응답게시판 알고리즘(2)
class index_chain_2 {
	var $id;
	var $pid;
	var $ppid;
	var $table = array ();
	var $chain_table = array ();
	var $index = array ();
	function index_chain_2($id, $pid, $ppid, $rows) {
		$this->id = $id;
		$this->pid = $pid;
		$this->ppid = $ppid;
		
		if (! is_array ( $rows ))
			return;
			
			// 중요
		foreach ( $rows as $item )
			$this->table [$item [$this->ppid]] [] = $item;
			
			// 최근데이타 출력
		krsort ( $this->table );
		
		// 정렬(직접선택법)
		foreach ( $this->table as $item )
			$this->chain_table = array_merge ( $this->chain_table, $this->make_chain ( $item ) );
			
			// 인덱스 추출
		foreach ( $this->chain_table as $item )
			$this->index [] = $item [$this->id];
	}
	
	// 직접선택법(정렬법)
	function make_chain($rows) {
		if (($total = count ( $rows )) < 2)
			return $rows;
		
		array_qsort ( $rows, $this->id, 'asc' );
		
		// 좋은 방법있으면...계산량=0(n^2)
		for($i = 0; $i < $total - 1; $i ++) {
			for($j = $i + 2; $j < $total; $j ++) {
				if (($rows [$i] [$this->id] == $rows [$j] [$this->pid])) {
					
					$item = $rows [$j];
					unset ( $rows [$j] );
					
					$rows1 = array_slice ( $rows, 0, $i + 1 );
					$rows2 = array_slice ( $rows, $i + 1, $total - ($i + 1) );
					$rows = array_merge ( $rows1, array (
							$item 
					), $rows2 );
				} // end if
			} // end for
		} // end for
		
		return $rows;
	}
}
?>
