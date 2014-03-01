<?php
/************************************************************************\
 * 프로그램명 : 기타함수들
 * 특기사항   : 1.파일에 해당되는 아이콘을 구한다.
                2.자동으로 링크 걸어주는 함수
                3.한글문자열자르기
                4.확장자가 php,php3,ph,ph. 등의 첨부파일 방지하기
                5.파일명,확자명 구하기
                6.중복된 파일이름을 바꾼다.
                7.시스템 명령 실행하기
                8.그라디에이션 함수들
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
if (defined ( '_lib_' ))
	return;
define ( '_lib_', true );

require_once ('inc/common.inc');

// i don't find any analog "php -m" and here is my one:
function available_extensions() {
	ob_start ();
	phpinfo ();
	$val_phpinfo .= ob_get_contents ();
	ob_end_clean ();
	preg_match_all ( '|name="module_(\w+)"|', $val_phpinfo, $mt );
	$available_extensions = $mt [1];
}
function output_handler(&$buffer) {
	if (ereg ( "(error</b>:)(.+)(<br)", $buffer, $regs )) {
		$error_text = "a fatal error has been caught.\n\n" . "date: " . date ( "f j,y,g:ia" ) . "\n" . "error text: " . $regs [2];
		
		error_log ( $error_text, 1, "mcli@nstsoft.co.kr" );
		error_log ( $error_text, 0 );
		
		return "the web site is not available at this time." . " please try again soon.";
	} else {
		return $buffer;
	} // end if else
}
function error_handler($errno, $errstr, $errfile, $errline) {
	$error_text = "a error has been caught.\n\n" . "date: " . date ( "f j,y,g:ia" ) . "\n" . "error #: $errorno\n" . "error text: $errstr\n" . "file: $errfile\n" . "line: $errline\n";
	
	error_log ( $error_text, 1, "mcli@nstsoft.co.kr" );
	error_log ( $error_text, 0 );
	
	echo "the web site is not available at this time." . " please try again soon.";
	exit ( 1 );
}
/**
 * **********************************************************************\
 * function: _debug()
 * purpose: 오류메세지 출력 후 전화면으로 이동
 * print_r(debug_backtrace());
 * \***********************************************************************
 */
function _debug($reason) {
	if (defined ( '_debug_' )) {
		print_r ( $reason );
		echo ("\n<p>\n");
	} // endif
	return;
}
function showerror($reason) {
	$reason = ereg_replace ( "\n", "\\n", chop ( $reason ) );
	echo ("<script>\nalert('$reason');\nwindow.close();\n</script>");
	exit ();
}
function showmessage($reason) {
	$reason = ereg_replace ( "\n", "\\n", chop ( $reason ) );
	echo ("<script>\nconfirm('$reason');\n</script>");
	return;
}
/**
 * **********************************************************************\
 * php함수의 버젼을 확인하면서 함수정의 및 적용하기
 * \***********************************************************************
 */
function is_version_up($version) {
	if (function_exists ( 'version_compare' ))
		return version_compare ( phpversion (), $version );
	
	$minver = explode ( ".", $version );
	$curver = explode ( ".", phpversion () );
	
	return (($minver [0] <= $curver [0]) && ($minver [1] <= $curver [1]) && ($minver [2] <= $curver [2]));
}
function is_email($email) {
	return (eregi ( "^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$", $email ));
}
function is_home($home) {
	return (eregi ( "([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", $home ));
}
function is_windows() {
	return (eregi ( "^windows", php_uname () ));
}
function is_image($image) {
	return (eregi ( "(\.gif|\.jpg|\.png|\.bmp)$", $image ));
}
function is_inline($inline) {
	return (eregi ( "(\.gif|\.jpg|\.png|\.bmp)$", $inline ));
}
function is_equal($a, $b) {
	return (isset ( $a ) && trim ( $a ) == trim ( $b ));
}
function is_alike($a, $b) {
	return (! empty ( $a ) && ! empty ( $b ) && eregi ( chop ( $a ), chop ( $b ) ));
}
function is_post() {
	return (is_equal ( request_method, "POST" ));
}
function is_host() {
	return true; // 임시
	return (is_alike ( http_host, server_name ));
}
function is_tag($tag) {
	return ereg ( "^<", $tag ) && ereg ( ">$", $tag );
}
function is_ns4($uagent) {
	return (ereg ( "mozilla/4", $uagent ) && ! ereg ( "msie", $uagent ) && ! ereg ( "gecko", $uagent ));
}
function is_ie4up($uagent) {
	return ereg ( "msie (4|5|6)", $uagent );
}
function is_ns6moz($uagent) {
	return ereg ( "gecko", $uagent );
}
function is_other($uagent) {
	return (! is_ns4 ( $uagent ) && ! is_ie4up ( $uagent ) && ! is_ns6moz ( $uagent ));
}
/**
 * **********************************************************************\
 * [].파일에 해당되는 아이콘을 구한다.
 * \***********************************************************************
 */
function getfoldericon($filename = "", $dir = false) {
	// 파일 아이콘
	$foldericon = array (
			"blank.gif",
			"brep.gif",
			"brepnew.gif",
			"close.gif",
			"closenew.gif",
			"open.gif",
			"opennew.gif",
			"lock.gif",
			"this.gif" 
	);
	
	extract ( $filename );
	
	$diff = time () - $mo_time;
	$days = floor ( $diff / 86400 );
	
	$dg = 3;
	$dg = empty ( $readcount ) ? 4 : $dg;
	$dg = ($days < 1) ? 6 : $dg; // 최근 글일때
	$dg = intval ( $depth ) ? 1 : $dg; // 답글일때
	$dg = intval ( $depth ) && ($days < 1) ? 2 : $dg; // 답글 최근 글일때
	$dg = intval ( $privatetype ) ? 7 : $dg; // 비공개글일때
	$dg = ($dir) ? 8 : $dg;
	
	return if_exists ( $foldericon, $dg, $foldericon [0] );
}
function getstaticon($filename = "", $hit = 20) {
	// 파일 아이콘
	$staticon = array (
			"blank.gif",
			"blank.gif" 
	);
	
	extract ( $filename );
	
	$dg = ($readcount > $hit) ? 1 : 0;
	
	return if_exists ( $staticon, $dg, $staticon [0] );
}
function getfileicon($filename = "") {
	// 파일타입 아이콘
	$iconfile = array (
			"" => "default.gif",
			"asf" => "asf.gif",
			"bmp" => "bmp.gif",
			"com" => "com.gif",
			"doc" => "doc.gif",
			"elm" => "elm.gif",
			"exe" => "exe.gif",
			"gif" => "gif.gif",
			"htm" => "html.gif",
			"html" => "html.gif",
			"hwp" => "hwp.gif",
			"jpg" => "jpg.gif",
			"mdb" => "mdb.gif",
			"movie" => "movie.gif",
			"png" => "png.gif",
			"ppt" => "ppt.gif",
			"ra" => "ra.gif",
			"sound" => "sound.gif",
			"txt" => "txt.gif",
			"xls" => "xls.gif",
			"tar" => "zip.gif",
			"zip" => "zip.gif",
			"arj" => "zip.gif",
			"gz" => "zip.gif",
			"?" => "unknown.gif" 
	);
	
	$ext = strtolower ( extractfileext ( $filename ) );
	
	return if_exists ( $iconfile, $ext, $iconfile [""] );
}
/**
 * **********************************************************************\
 * [].자동으로 링크 걸어주는 함수
 * \***********************************************************************
 */
function hyperlink($text, $target = "_new", $redir_path = "") {
	// turn urls into links
	$text = eregi_replace ( "(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", "<a href='mailto:\\1' target='$target'>\\1</a>", $text );
	$text = eregi_replace ( "([[:alnum:]]+)://([^[:space:]()]*)([[:alnum:]#?/&=])", "<a href='\\1://\\2\\3' target='$target'>\\1://\\2\\3</a>", $text );
	
	return $text;
}
/**
 * **********************************************************************\
 * [].문자열을 날짜로
 * \***********************************************************************
 */
function strtodate($text) {
	list ( $yy, $mm, $dd, $hh, $nn, $ss ) = preg_split ( '/\\/|,|:/', $text );
	if (checkdate ( $mm, $dd, $yy ))
		return mktime ( $hh, $nn, $ss, $mm, $dd, $yy );
	return 0; // false
}
function _strtodate($text) {
	list ( $yy, $mm, $dd ) = split ( '[/.-]', substr ( $text, 0, 10 ) );
	if (checkdate ( $mm, $dd, $yy ))
		return mktime ( 0, 0, 0, $mm, $dd, $yy );
	return 0; // false
}
function ymd($text) {
	return date ( shortdateformat, strtodate ( $text ) );
}
/**
 * **********************************************************************\
 * [].날짜함수들
 * \***********************************************************************
 */
function yy($inc = 0) {
	return date ( strtoupper ( 'y' ), strtotime ( $inc . ' day', time () ) );
}
function mm($inc = 0) {
	return date ( strtolower ( 'n' ), strtotime ( $inc . ' day', time () ) );
}
function gettodate($inc = 0) {
	return date ( shortdateformat, strtotime ( $inc . ' day', time () ) );
}
function gettodatetime($inc = 0) {
	return date ( longdateformat, strtotime ( $inc . ' day', time () ) );
}
/**
 * **********************************************************************\
 * [].파일 사이즈 포멧하기(api)
 * [].내가 생각해도 너무 잘 잘 만드것 같은...
 * \***********************************************************************
 */
function bytesize($size) {
	$sizestr = array (
			"",
			"",
			"",
			"K",
			"K",
			"K",
			"M",
			"M",
			"M",
			"G",
			"G",
			"G",
			"T",
			"T",
			"T" 
	);
	$sizepow = array (
			0,
			0,
			0,
			10,
			10,
			10,
			20,
			20,
			20,
			30,
			30,
			30,
			40,
			40,
			40 
	);
	
	$pow = max ( 0, floor ( log10 ( $size ) - log10 ( 2 ) ) );
	$size >>= $sizepow [$pow];
	
	return $size . $sizestr [$pow];
}
function kbytesize($size) {
	$sizestr = array (
			"",
			"",
			"",
			"KB",
			"KB",
			"KB",
			"KB",
			"KB",
			"KB",
			"KB",
			"KB",
			"KB" 
	);
	$sizepow = array (
			0,
			0,
			0,
			10,
			10,
			10,
			10,
			10,
			10,
			10,
			10,
			10 
	);
	
	$pow = max ( 0, floor ( log10 ( $size ) - log10 ( 2 ) ) );
	$size >>= $sizepow [$pow];
	
	return $size . $sizestr [$pow];
}
/**
 * **********************************************************************\
 * [].한글문자열자르기
 * \***********************************************************************
 */
function kstrcut($str, $len, $suffix = "...") {
	if ($len < 1 || $len >= strlen ( $str ))
		return $str;
	$klen = $len - 1;
	// warning: uninitialized string offset
	while ( $klen > 0 && ord ( $str [$klen] ) & 0x80 )
		$klen --;
	return substr ( $str, 0, $len - ((($len + $klen) & 1) ^ 1) ) . $suffix;
}
/**
 * Lael's World » [PHP] UTF-8 한글 및 다국어 글자수 자르기
 * 출처 : https://www.google.co.kr/search?q=utf8_strcut&aq=f&oq=utf8_strcut&aqs=chrome.0.57j0l3.826j0&sourceid=chrome&ie=UTF-8
 */
function utf8_length($str) {
	$len = strlen ( $str );
	for($i = $length = 0; $i < $len; $length ++) {
		$high = ord ( $str {$i} );
		if ($high < 0x80) // 0<= code <128 범위의 문자(ASCII 문자)는 인덱스 1칸이동
			$i += 1;
		else if ($high < 0xE0) // 128 <= code < 224 범위의 문자(확장 ASCII 문자)는 인덱스 2칸이동
			$i += 2;
		else if ($high < 0xF0) // 224 <= code < 240 범위의 문자(유니코드 확장문자)는 인덱스 3칸이동
			$i += 3;
		else // 그외 4칸이동 (미래에 나올문자)
			$i += 4;
	}
	return $length;
}
function utf8_strcut($str, $chars, $tail = '...') {
	if (utf8_length ( $str ) <= $chars) // 전체 길이를 불러올 수 있으면 tail을 제거한다.
		$tail = '';
	else
		$chars -= utf8_length ( $tail ); // 글자가 잘리게 생겼다면 tail 문자열의 길이만큼 본문을 빼준다.
	$len = strlen ( $str );
	for($i = $adapted = 0; $i < $len; $adapted = $i) {
		$high = ord ( $str {$i} );
		if ($high < 0x80)
			$i += 1;
		else if ($high < 0xE0)
			$i += 2;
		else if ($high < 0xF0)
			$i += 3;
		else
			$i += 4;
		if (-- $chars < 0)
			break;
	}
	return trim ( substr ( $str, 0, $adapted ) ) . $tail;
}
/**
 * **********************************************************************\
 * [].파일의 확장자가 php,php3,ph,ph.
 * 등의 파일이 업로드 방지하기
 * \***********************************************************************
 */
function checkfilename($text) {
	$text = eregi_replace ( " ", "_", $text );
	$text = eregi_replace ( "([\.]*)$", "", $text );
	$text = eregi_replace ( ".(ph|php[0-9a-z]*|inc|cgi)$", ".phps", $text );
	$text = eregi_replace ( "(.*).(pl|sh|html|htm|shtml|vbs|ztx|dot|asp|jsp)$", "\\1_\\2.phps", $text );
	
	return ($text);
}
/**
 * **********************************************************************\
 * [].utility function used for time measuring
 * \***********************************************************************
 */
function getmicrotime() {
	list ( $usec, $sec ) = explode ( " ", microtime () );
	return (( float ) $usec + ( float ) $sec);
}
/**
 * **********************************************************************\
 * [].변수초기화
 * [].배열값복사하기
 * \***********************************************************************
 */
function if_empty($text = "", $value = "") {
	return ! empty ( $text ) ? $text : $value;
}
function if_exists($array, $key, $def = "") {
	if (! is_array ( $array ))
		return $def;
	return array_key_exists ( $key, $array ) ? $array [$key] : $def;
}
function a4b($a, $b) {
	if (empty ( $b ))
		return $a;
	
	if (is_array ( $a ) && is_array ( $b ))
		foreach ( $a as $key => $val )
			$row [$key] = if_exists ( $b, $key, $val );
	
	return count ( $row ) > 0 ? $row : null;
}
/**
 * **********************************************************************\
 * [].배열값
 * \***********************************************************************
 */
function index2array($index) {
	$to = array (
			"#13" => chr ( 13 ),
			"#10" => chr ( 10 ),
			'~~' => "=",
			chr ( 29 ) => "=",
			'[ns]' => "=" 
	);
	
	if (empty ( $index ))
		return null;
	
	foreach ( $index as $v ) {
		// line mustn't start with a ';' and must contain at least one '=' symbol.
		if ((substr ( trim ( $v ), 0, 1 ) != ';') && (substr_count ( $v, '=' ) >= 1)) {
			$pos = strpos ( $v, '=' );
			$headname = strtolower ( trim ( substr ( $v, 0, $pos ) ) );
			$headvalue = trim ( substr ( $v, $pos + 1 ) );
			if (get_magic_quotes_gpc ())
				$headvalue = stripslashes ( $headvalue );
			$config [$headname] = strtr ( $headvalue, $to );
		} // end if
	} // end foreach
	unset ( $index );
	
	return count ( $config ) > 0 ? $config : null;
}
function array2file($filename, $index) {
	$to = array (
			chr ( 13 ) => "#13",
			chr ( 10 ) => "#10" 
	);
	if (! $fp = fopen ( $filename, "wb" ))
		return 0; // false
	
	flock ( $fp, LOCK_EX );
	// set the internal pointer of an array to its first element
	foreach ( $index as $field => $record ) {
		// 엔터값을 바꾸기
		$record = strtr ( $record, $to );
		// todo::php.ini(magic_quotes_gpc = Off)
		if (get_magic_quotes_gpc ())
			$record = stripslashes ( $record );
			// write option not belonging to any section
		$res = fwrite ( $fp, "$field=$record\n" );
	} // end foreach
	flock ( $fp, LOCK_UN );
	fclose ( $fp );
	
	return count ( $index );
}
function file2array($filename) {
	if (! file_exists ( $filename ) || ! is_file ( $filename ))
		return null;
	
	$rows = file2 ( $filename );
	
	return index2array ( $rows );
}
/**
 * **********************************************************************\
 * [].파일시스템(일반텍스트)처리 함수
 * \***********************************************************************
 */
function file2($filename) {
	$text = file_get_contents ( $filename );
	return preg_split ( "/\r?\n|\r/", $text, - 1, PREG_SPLIT_NO_EMPTY );
}
function url2text($url) {
	$text = '';
	if (! $fp = fopen ( chop ( $url ), "rb" ))
		return null;
	while ( ! feof ( $fp ) )
		$text .= fread ( $fp, 1024 );
	fclose ( $fp );
	$text = chop ( $text ) . "<p>";
	return $text;
}
/**
 * **********************************************************************\
 * [].파일명,확자명 구하기
 * \***********************************************************************
 */
function extractfilepath($filename) {
	return ($filename) ? dirname ( $filename ) : null;
}
function extractfilename($filename) {
	return ($filename) ? current ( explode ( '.', basename ( $filename ) ) ) : null;
}
function _extractfileext_($filename) {
	return ($filename) ? array_pop ( explode ( '.', basename ( $filename ) ) ) : null;
}
function extractfileext($file) {
	if (($pos = strrpos ( $file, '.' )) === false)
		return '';
	return substr ( $file, $pos + 1 );
}
/**
 * **********************************************************************\
 * [].중복된 파일이름을 바꾼다.
 * \***********************************************************************
 */
function getuniquefile($path, $filename) {
	$file = eregi_replace ( " ", "_", $filename );
	$ext = extractfileext ( $filename );
	$new = $old = extractfilename ( $file );
	for($i = 1; file_exists ( path_fix ( "$path/$new.$ext" ) ); $i ++)
		$new = sprintf ( "%s(%d)", $old, $i );
	return $new . '.' . $ext;
}
/**
 * **********************************************************************\
 * [].디렉토리 관련 함수들
 * \***********************************************************************
 */
function dirsize($dir) {
	$size = 0;
	$dh = opendir ( $dir ); // or die("dirsize file open");
	while ( ($filename = readdir ( $dh )) ) {
		$path = path_fix ( "$dir/$filename" );
		// todo::
		// $size += filesize($path);
		if ($filename != "." && $filename != "..") {
			if (is_dir ( $path ))
				$size += dirsize ( $path );
			elseif (is_file ( $path ))
				$size += filesize ( $path );
		} // endif
		flush ();
	} // end while
	closedir ( $dh );
	return $size;
}
/**
 * **********************************************************************\
 * [].시스템 명령 실행하기
 * \***********************************************************************
 */
function path_fix($path) {
	if (is_windows ())
		$path = preg_replace ( '/\//', '\\\\', $path );
	return $path;
}
function runshell($command) {
	// 외부명령실행하기
	// win98,winnt인 경우 버그발생(예상)
	exec ( $command, $output, $return_var );
	
	_debug ( $command );
	_debug ( $output );
	_debug ( $return_var );
	
	return empty ( $output ) ? ($return_var == 0) : $output;
}
/**
 * **********************************************************************\
 * [].그라디에이션 함수들
 * \***********************************************************************
 */
function invertcolor($hex) {
	return sprintf ( "%06x", $hex ^ 0xffffff );
}
function lightgradation($color, $step = 0, $depth = 10) {
	$tcolor = str_replace ( "#", "", $color );
	
	$r = hexdec ( substr ( $tcolor, 0, 2 ) );
	$g = hexdec ( substr ( $tcolor, 2, 2 ) );
	$b = hexdec ( substr ( $tcolor, 4, 2 ) );
	
	$r += $depth * $step;
	$g += $depth * $step;
	$b += $depth * $step;
	
	$r = min ( $r, 255 );
	$g = min ( $g, 255 );
	$b = min ( $b, 255 );
	
	return sprintf ( "#%02x%02x%02x", $r, $g, $b );
}
function darkgradation($color, $step = 0, $depth = 10) {
	$tcolor = str_replace ( "#", "", $color );
	
	$r = hexdec ( substr ( $tcolor, 0, 2 ) );
	$g = hexdec ( substr ( $tcolor, 2, 2 ) );
	$b = hexdec ( substr ( $tcolor, 4, 2 ) );
	
	$r -= $depth * $step;
	$g -= $depth * $step;
	$b -= $depth * $step;
	
	$r = max ( $r, 0 );
	$g = max ( $g, 0 );
	$b = max ( $b, 0 );
	
	return sprintf ( "#%02x%02x%02x", $r, $g, $b );
}
/**
 * **********************************************************************\
 * source of: http://www.php.net/source.php?url=/include/layout.inc
 * \***********************************************************************
 */
// resize_image()
// tag the output of make_image() and resize it manually
// (considering possible html/xhtml image tag endings)
//
function resize_image($img, $width = 1, $height = 1) {
	$str = preg_replace ( '!width=\"([0-9]+?)\"!i', '', $img );
	$str = preg_replace ( '!height=\"([0-9]+?)\"!i', '', $str );
	return preg_replace ( '!/?>$!', sprintf ( ' height="%s" width="%s" />', $height, $width ), $str );
}

// make_image()
// return an img tag for a given file (relative to the images dir)
//
function make_image($filename, $dir = false, $alt = false, $align = false, $extras = false, $border = 0, $maxwidth = 0) {
	if (! $dir)
		$dir = './images';
		
		// todo::속도가 넘 늦어짐.
		// if(is_version_up("4.0.6"))
	$size = false;
	if ($maxwidth > 0) {
		$size = getimagesize ( $dir . '/' . $filename );
		
		$new_width = min ( $maxwidth, $size [0] );
		$new_height = ceil ( $new_width / $size [0] * $size [1] );
		
		$size [3] = "width='$new_width' height='$new_height'";
	} // end if
	
	return sprintf ( '<img src="%s/%s" border="%d" %s alt="%s" %s%s />', $dir, $filename, $border, ($size ? $size [3] : ''), ($alt ? $alt : $filename), ($align ? ' align="' . $align . '"' : ''), ($extras ? ' ' . $extras : '') );
}

// print_image()
// print an img tag for a given file
//
function print_image($filename, $dir = false, $alt = false, $align = false, $extras = false, $border = 0, $maxwidth = 0) {
	print make_image ( $filename, $dir, $alt, $align, $extras, $border, $maxwidth );
}

// make_link()
// return a hyperlink to something,within the site
//
function make_link($url, $linktext = false, $target = false, $extras = false) {
	return sprintf ( "<a href=\"%s\"%s%s>%s</a>", $url, ($target ? ' target="' . $target . '"' : ''), ($extras ? ' ' . $extras : ''), 
			// ($linktext ? $linktext : $url)
			($linktext ? $linktext : '&nbsp') );
}

// print_link()
// print a hyperlink to something,within the site
//
function print_link($url, $linktext = false, $target = false, $extras = false) {
	print make_link ( $url, $linktext, $target, $extras );
}

// make_popup_link()
// return a hyperlink to something,within the site,that pops up a new window
//
function make_popup_link($url, $linktext = false, $windowprops = false, $target = false, $extras = false) {
	if (! $windowprops)
		$windowprops = 'left=0,top=0,width=500,height=400,resizable=yes,scrollbars=yes,status=no';
	
	return sprintf ( "<a href=\"%s\" target=\"%s\" onclick=\"window.open('%s','%s','%s');return false;\"%s>%s</a>", 
			// todo::
			// htmlspecialchars($url),
			('javascript:void(0)'), ($target ? $target : "_new"), 
			// todo::
			// htmlspecialchars($url),
			($url), ($target ? $target : "_new"), ($windowprops), ($extras ? ' ' . $extras : ''), ($linktext ? $linktext : $url) );
}

// print_popup_link()
// print a hyperlink to something,within the site,that pops up a new window
//
function print_popup_link($url, $linktext = false, $windowprops = false, $target = false, $extras = false) {
	print make_popup_link ( $url, $linktext, $windowprops, $target, $extras );
}
function print_email($string) {
	$finished = '';
	for($i = 0; $i < strlen ( $string ); ++ $i) {
		$n = rand ( 0, 1 );
		if ($n)
			$finished .= '&#x' . sprintf ( "%X", ord ( $string {$i} ) ) . ';';
		else
			$finished .= '&#' . ord ( $string {$i} ) . ';';
	} // end for
	return $finished;
}
/**
 * **********************************************************************\
 * [].퍼미션을 얻는다.
 * \***********************************************************************
 */
function get_perms($dir) {
	$s = fileperms ( $dir ); // 루트 디렉토리의 퍼미션을 얻고
	$s = decoct ( $s ); // 십진수를 8진수로 바꾸고
	$s = substr ( $s, - 3 ); // 뒤 세자리만 얻는다.
	return $s;
}
//
function quote($str = null) {
	switch (strtolower ( gettype ( $str ) )) {
		case 'null' :
			return 'null';
		case 'integer' :
			return $str;
		case 'string' :
		default :
			return "'" . addslashes ( $str ) . "'";
	} // end switch
}
/**
 * **********************************************************************\
 * if you have the gd library (available at http://www.boutell.com/gd/)
 * you will also be able to create and manipulate images.
 *
 * since php 4.3 there is a bundled version of the gd lib.
 * this bundled version has some additional features like alpha blending,
 * and should be used in preference to the external library
 * since its codebase is better maintained and more stable.
 *
 * by gd it's impossible to create gif images,so any gif images will be converted in jpeg type
 * \***********************************************************************
 */
function image_resize($filename, $maxwidth = 100, $maxheight = 100, $quality = 100) {
	if (! is_file ( $filename ))
		return false;
	
	$path = extractfilepath ( $filename );
	$file = extractfilename ( $filename );
	$ext = extractfileext ( $filename );
	
	$tmp_image_name = path_fix ( "{$path}/{$file}_m.{$ext}" );
	
	list ( $width, $height, $type, $attr ) = getimagesize ( $filename );
	
	// src image
	if ($type == 1)
		$srcimage = imagecreatefromgif ( $filename );
	elseif ($type == 2)
		$srcimage = imagecreatefromjpeg ( $filename );
	elseif ($type == 3)
		$srcimage = imagecreatefrompng ( $filename );
	else
		return false;
		
		// 이미지 만들기
		// 비율로 줄여야만 이미지가 찌그러지지 않음.
	if ($width > $height) {
		$new_width = min ( $width, $maxwidth );
		$new_height = ceil ( $new_width / $width * $height );
	} else {
		$new_height = min ( $height, $maxheight );
		$new_width = ceil ( $new_height / $height * $width );
	} // end if else
	  
	// true color image
	if (is_version_up ( "4.2.0" ))
		$destimage = imagecreatetruecolor ( $new_width, $new_height );
	if (! $destimage) {
		$destimage = imagecreate ( $new_width, $new_height );
	}
	
	// resampling
	imagecopyresized ( $destimage, $srcimage, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	
	// 이미지 저장
	if ($type == 1)
		imagejpeg ( $destimage, $tmp_image_name, $quality );
	elseif ($type == 2)
		imagejpeg ( $destimage, $tmp_image_name, $quality );
	elseif ($type == 3)
		imagepng ( $destimage, $tmp_image_name );
	else
		return false;
	
	imagedestroy ( $srcimage );
	imagedestroy ( $destimage );
	
	return $tmp_image_name;
}
/**
 * **********************************************************************\
 * function_exists(PHP 3>= 3.0.7,PHP 4 ,PHP 5)
 * \***********************************************************************
 */
// file_put_contents(PHP 5)
if (! function_exists ( 'file_put_contents' )) {
	function file_put_contents($filename, $content) 	// file_put_contents
	{
		if (! $fp = fopen ( $filename, "wb" ))
			return 0; // false
		flock ( $fp, LOCK_EX );
		$n = fwrite ( $fp, $content );
		flock ( $fp, LOCK_UN );
		fclose ( $fp );
		unset ( $content, $fp );
		return $n;
	}
}
// file_get_contents(PHP 5)
if (! function_exists ( 'file_get_contents' )) {
	function file_get_contents($filename) 	// file_get_contents
	{
		$text = '';
		if (! $fp = fopen ( $filename, "rb" ))
			return null;
		while ( ! feof ( $fp ) )
			$text .= fread ( $fp, 1024 );
		fclose ( $fp );
		return $text;
	}
}
// array_chunk(PHP 4 >= 4.2.0,PHP 5)
if (! function_exists ( 'array_chunk' )) {
	function array_chunk($input, $size, $preserve_keys = false) {
		for(reset ( $input ), $i = $j = 0; list ( $key, $value ) = each ( $input );) {
			if (! (isset ( $chunks [$i] ))) {
				$chunks [$i] = array ();
			} // end if
			
			if (count ( $chunks [$i] ) < $size) {
				if ($preserve_keys) {
					$chunks [$i] [$key] = $value;
					$j ++;
				} else {
					$chunks [$i] [] = $value;
				} // end if else
			} else {
				$i ++;
				
				if ($preserve_keys) {
					$chunks [$i] [$key] = $value;
					$j ++;
				} else {
					$j = 0;
					$chunks [$i] [$j] = $value;
				} // end if else
			} // end if else
		} // end for
		
		return $chunks;
	}
}
// array_key_exists(PHP 4 >= 4.1.0,PHP 5)
if (! function_exists ( 'array_key_exists' )) {
	function array_key_exists($key, $array) {
		$keys = array_keys ( $array );
		return in_array ( $key, $keys );
	}
}
function to_index($string) {
	return ereg_replace ( "[[:alpha:]]", "", $string );
}
?>
