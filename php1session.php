<?php
/************************************************************************\
 * 프로그램명 : 세션(session) 클래스 정의
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/05
 * 수 정 자   : 김명철
 * 수정일자   : 2004/04
 * 수정내역   :
\************************************************************************/
if (defined ( '_session_' ))
	return;
define ( '_session_', true );

/**
 * **********************************************************************\
 * setting the cache limiter to nocache,
 * for example,would disallow any client-side caching
 * a value of public,however,would permit caching
 * it can also be set to private,which is slightly more restrictive than public.
 * \***********************************************************************
 */
if ($session_cache_limiter = "nocache") {
	session_cache_limiter ( $session_cache_limiter );
} else {
	session_cache_limiter ( 'no-cache,must-revalidate' );
} // end if else
  
// 2004.04.10 추가
session_set_cookie_params ( 0, "/" );

/**
 * **********************************************************************\
 * [].세션에 대한 설정도 php.ini파일에 기록되어 있다.
 * [].php4를 인스톨하면 기본적으로 세션에 관련된 설정이 되어 있다.
 * \***********************************************************************
 */
class oksess {
	var $sess_name;
	var $sess_vars = array ();
	var $timeout = expireinseconds;
	function oksess($name = "oksess") {
		$this->sess_name = $name;
		
		session_name ( $name );
		session_start ();
		
		$this->sid = session_id ();
		
		foreach ( $GLOBALS ["HTTP_SESSION_VARS"] as $key => $value )
			$this->sess_vars [substr ( $key, strlen ( $this->sess_name . "_" ) )] = $value;
		
		if ($this->timeout > 0)
			$this->expire ();
		// ini_set('session.cache_expire',expireinseconds);
	}
	function init() {
		$this->sess_key = md5 ( uniqid ( rand () ) );
		$this->setsess ( "key", $this->sess_key );
	}
	function expire() {
		if ($lastused = $this->getsess ( "lastused" ))
			if ($lastused + $this->timeout < time ())
				$this->destroy ();
		
		$this->setsess ( "lastused", time () );
	}
	function is_reg($key) {
		return $this->sid == $key;
	}
	function setsess($key, $value) {
		if (! session_is_registered ( $this->sess_name . "_" . $key ))
			session_register ( $this->sess_name . "_" . $key );
		
		$GLOBALS [$this->sess_name . "_" . $key] = $value;
		$this->sess_vars [$key] = $value;
	}
	function getsess($key) {
		return if_exists ( $this->sess_vars, $key );
	}
	function destroy() {
		session_unset ();
		session_destroy ();
		$this->sess_vars = array ();
	}
}
/**
 * **********************************************************************\
 * [].다른방법으로
 * \***********************************************************************
 */
if (! function_exists ( 'path_fix' )) {
	function path_fix($path) {
		if (eregi ( "^windows", php_uname () ))
			$path = preg_replace ( '/\//', '\\\\', $path );
		return $path;
	}
}
class session {
	var $sid;
	var $save_path;
	var $file_ext = "cgi"; // 세션 파일의 확장자
	var $timeout = expireinseconds;
	var $sess_name;
	var $sess_vars = array ();
	function session($name = "oksess") {
		global $_COOKIE;
		
		$this->sess_name = $name;
		
		if (! $this->save_path = get_cfg_var ( 'session.save_path' ))
			$this->save_path = dirname ( tempnam ( '', '' ) );
		
		$this->sid = $_COOKIE [$this->sess_name];
		if (! preg_match ( "/{[a-f0-9]+-[a-f0-9]+-[0-9]+}/i", $this->sid ))
			$this->sid = strtoupper ( "{" . uniqid ( "" ) . "-" . uniqid ( "" ) . "-" . time () . "}" );
		
		$this->sess_vars = $this->load ();
		
		if ($this->timeout > 0)
			$this->expire ();
	}
	function is_reg($key) {
		return $this->sid == $key;
	}
	function expire() {
		if ($this->getsess ( "userid" ) && $lastused = $this->getsess ( "lastused" ))
			if ($lastused + $this->timeout < time ())
				$this->destroy ();
		
		$this->setsess ( "lastused", time () );
	}
	function setsess($key, $value) {
		$this->sess_vars [$key] = $value;
		$this->save ();
	}
	function getsess($key) {
		return $this->sess_vars [$key];
	}
	function destroy() {
		setcookie ( $this->sess_name, "" );
		$sessionfile = path_fix ( "$this->save_path/$this->sid.$this->file_ext" );
		return unlink ( $sessionfile );
	}
	function load() {
		$result = array ();
		
		$sessionfile = path_fix ( "$this->save_path/$this->sid.$this->file_ext" );
		if (file_exists ( $sessionfile )) {
			clearstatcache ();
			$fp = fopen ( $sessionfile, "rb" );
			$result = fread ( $fp, filesize ( $sessionfile ) );
			fclose ( $fp );
			$result = unserialize ( base64_decode ( $result ) );
		} // end if
		
		return $result;
	}
	function save() {
		$content = base64_encode ( serialize ( $this->sess_vars ) );
		
		$sessionfile = path_fix ( "$this->save_path/$this->sid.$this->file_ext" );
		$fp = fopen ( $sessionfile, "wb" ) or die ( "could not open session file" );
		flock ( $fp, LOCK_EX );
		fwrite ( $fp, $content );
		flock ( $fp, LOCK_UN );
		fclose ( $fp );
		
		setcookie ( $this->sess_name, $this->sid, time () + $this->timeout );
		return 1;
	}
}
?>
