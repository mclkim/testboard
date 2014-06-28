<?php
/**
 * [].디버그를 위한...
 */
define ( '_debug_', true );
define ( 'ENVIRONMENT', 'development' );
/**
 * 
 */
define ( 'DS', '/' );
define ( 'ROOT', dirname ( dirname ( __FILE__ ) ) );
/**
 * [].프로그램에 대한 정보
 * [].가능한 수정하지 않았으면 합니다.
 */
define ( 'PROGRAM_NAME', "Testboard" );
define ( 'PROGRAM_VER', "Rev" );
define ( 'PROGRAM_DATE', "2014.02.17" );
/**
 * [].경고메시지를 error_reporting()을 사용함으로써 은폐시킬 수 있다.
 */
set_time_limit ( 0 );

if (defined ( '_debug_' ))
	$error_flags = E_ALL; // ^ E_NOTICE;

if (! defined ( '_debug_' ))
	$error_flags = E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE;

error_reporting ( $error_flags );
ini_set ( 'error_reporting', $error_flags );

if (defined ( 'ENVIRONMENT' )) {
	switch (ENVIRONMENT) {
		case 'development' :
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 'on' );
			break;
		case 'testing' :
			error_reporting ( E_ALL );
			ini_set ( 'display_errors', 'off' );
			break;
		case 'production' :
			error_reporting ( 0 );
			ini_set ( 'display_errors', 'off' );
			break;
		default :
			exit ( 'The application environment is not set correctly.' );
	}
}


/**
 * [].sets a user-defined error handler function.
 * $old_error_handler = set_error_handler("output_handler");
 * $old_error_handler = set_error_handler("error_handler");
 */
function err_handler($errno, $errstr, $errfile, $errline, $display_errors = 1) {
	if (($errno != E_NOTICE && $errno != E_WARNING) && $display_errors)
		echo ("
                <font face='courier new,courier,monospace' size=2>
                <hr size=1 color=black>
                <b>error [$errno]:        $errstr</b><br>
                file: " . basename ( $errfile ) . "<br>
                line: $errline<br>
                <hr size=1 color=black>
                </font>
        ");
}

if (! defined ( '_debug_' ))
	$old_error_handler = set_error_handler ( "err_handler" );
	
	// Put the magic quotes setting to $MQ
$MQ = ( boolean ) get_magic_quotes_gpc ();

/**
 * **********************************************************************\
 * backward compatible array creation.
 * after this point,the php 4.1.0+ arrays can be used to access variables coming
 * from outside php. but it should be noted that these variables
 * are not necessarily superglobals,so they need to be global-ed!
 * \***********************************************************************
 */
if (! isset ( $_SERVER )) {
	$_ENV = &$HTTP_ENV_VARS;
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_FILES = &$HTTP_POST_FILES;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
} // endif

$_REQUEST = array_merge ( $_GET, $_POST, $_FILES, $_COOKIE );
extract ( $_REQUEST );

/**
 * **********************************************************************\
 * [].퍼미션
 * \***********************************************************************
 */
define ( 'mode_symbols', 0777 );

/**
 * **********************************************************************\
 * [].회원 로긴시 타임아웃 시간(단위 초)
 * [].설정시간동안 아무 일도 하지 않는 경우 자동으로 로그아웃상태가 된다
 * \***********************************************************************
 */
define ( 'expireinseconds', 5 * 60 ); // 15 minutes

/**
 * **********************************************************************\
 * [].출력할 날짜 포맷 설정
 * \***********************************************************************
 */
define ( 'longdateformat', 'Y/m/d,H:i:s' );
define ( 'shortdateformat', 'Y/m/d' );

/**
 * **********************************************************************\
 * [].초기값(기초값)
 * \***********************************************************************
 */
define ( 'http_url', 'http://' . dirname ( $_SERVER ["HTTP_HOST"] . $_SERVER ["PHP_SELF"] ) );
define ( 'document_root', $_SERVER ["DOCUMENT_ROOT"] );
define ( 'php_self', $_SERVER ["PHP_SELF"] );
define ( 'http_host', $_SERVER ["HTTP_HOST"] );
define ( 'server_name', $_SERVER ["SERVER_NAME"] );
define ( 'request_uri', $_SERVER ["REQUEST_URI"] );
define ( 'request_method', $_SERVER ["REQUEST_METHOD"] );
define ( 'remote_addr', $_SERVER ["REMOTE_ADDR"] );
define ( 'user_agent', $_SERVER ["HTTP_USER_AGENT"] );
define ( 'time_now', strtotime ( 'now' ) );
define ( 'time_long', date ( longdateformat, time () ) );
define ( 'time_short', date ( shortdateformat, time () ) );
define ( 'rowid', microtime () );
define ( 'server_software', $_SERVER ["SERVER_SOFTWARE"] );

/**
 * **********************************************************************\
 * [].포트번호를 정한다.
 * \***********************************************************************
 */
define ( 'port_smtp', 25 ); // smtp 의 포트번호를 정한다.
define ( 'port_pop3', 110 ); // pop3 의 포트번호를 정한다.

/**
 * 한국시간(timezone)설정
 * http://kr2.php.net/manual/en/timezones.php)
 */
if (! ini_get ( 'date.timezone' ) && function_exists ( 'date_default_timezone_set' )) {
	date_default_timezone_set ( "Asia/Seoul" );
}
                             
// print_r($_SERVER);exit;
?>
