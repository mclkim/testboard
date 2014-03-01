<?php
/************************************************************************\
 * 프로그램명 : 기본화면
 * 특기사항   : 1.데이타베이스를 구분,연결을 목적으로 한다.
                2.머릿글(head),꼬리글(tail)html 기능(2001.04.12)
                3.기본 본문 메시지 출력(예정)
                4.접근허용검사(2001.12.27)
                5.로딩 메세지 기능(2001.09.26)
                6.쪽지 기능(예정)
                7.글 정리를 위해서 바구니 기능(예정)
                8.메시지화면에서 일정시간이 흐르면 자동으로 새로고침(예정)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('inc/common.php');
/**
 * **********************************************************************\
 * [].데이타베이스 환경파일에서 설정가능(2002.07.13)
 * \***********************************************************************
 */
class db {
	/**
	 * create a new db connection object for the specified database
	 * type
	 */
	static function &factory($type = 'file') {
		include_once ("inc/${type}.php");
		
		$classname = "db_${type}";
		
		if (! class_exists ( $classname ))
			showerror ( "db_error" );
		
		$obj = & new $classname ();
		
		return $obj;
	}
	/**
	 * load a php database extension if it is not loaded already.
	 *
	 * access public
	 *
	 * param string $name the base name of the extension
	 * (without the .so or .dll suffix)
	 *
	 * return boolean true if the extension was already or successfully
	 * loaded, false if it could not be loaded
	 */
	static function extension_module($name) {
		if (! extension_loaded ( $name )) {
			$dlphp = is_windows () ? 'php_' : '';
			$dlext = is_windows () ? '.dll' : '.so';
			@dl ( $dlphp . $name . $dlext );
		} // endif
		
		return extension_loaded ( $name );
	}
}
/**
 * **********************************************************************\
 * [].기본화면구성
 * \***********************************************************************
 */
class form1new extends db_common {
	var $obj = null;
	function &factory($type) {
		global $defconf;
		
		$type = empty ( $type ) ? 'file' : $type;
		
		// load a php database extension if it is not loaded already.
		db::extension_module ( $type );
		
		// create a new db connection object for the specified database type
		$obj = db::factory ( $type );
		
		// connect to the specified database
		$obj->db_set ( $defconf );
		
		return $obj;
	}
	function form1new() {
		global $envconf;
		
		// only try to send out headers in case
		// those were not sent already
		if (! headers_sent ()) {
			// always modified
			header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
			header ( "cache-control: no-cache, must-revalidate" );
			header ( "pragma: no-cache" );
		}
		
		// 타임스텝
		$this->timestamps ( "form1new" );
		
		// 세션설정
		$this->db_common ();
		
		// 환경설정을 재초기화
		$this->resetconfig ();
		
		// 데이타베이스 적용
		$this->obj = $this->factory ( $envconf ["db_engine"] );
	}
	function free() {
		$timestamp = max ( $this->obj->timestamps ) - min ( $this->obj->timestamps );
		printf ( "\n<!-- processing db time %2.3f seconds -->\n", $timestamp );
		
		$this->timestamps ( "free" );
		$timestamp = max ( $this->timestamps ) - min ( $this->timestamps );
		printf ( "\n<!-- processing time %2.3f seconds -->\n", $timestamp );
		
		_debug ( $this );
		exit ();
	}
	
	// 0.모드설정
	function mode($_REQUEST) {
		$this->prog = basename ( php_self );
		$this->db = if_exists ( $_REQUEST, "db" );
		$this->mode = if_exists ( $_REQUEST, "mode" );
		$this->cmd = if_exists ( $_REQUEST, "cmd", $this->mode );
		$this->id = if_exists ( $_REQUEST, "id" );
		$this->page = if_exists ( $_REQUEST, "page" );
		$this->ff = if_exists ( $_REQUEST, "ff" );
		$this->fw = if_exists ( $_REQUEST, "fw" );
		$this->fn = if_exists ( $_REQUEST, "fn" );
		$this->sid = if_exists ( $_REQUEST, "sid" );
		$this->selected = if_exists ( $_REQUEST, "selected" );
		$this->db_engine = if_exists ( $_REQUEST, "db_engine" );
	}
	
	// 모드변경
	// 관리자권한,회원권한,비공객문서권한,작성자권한별 모드 변경(2003.04.12)
	function mode_swap(&$mode, &$cmd) {
		global $envconf;
		
		_debug ( "before=$mode/$cmd" );
		
		if (empty ( $mode ) || $cmd == 'logon')
			return;
			
			// 글 파일 읽기(수정예정)
		$temp = $this->session->getsess ( "data" );
		$data = if_exists ( $temp, $this->id, $this->obj->loaddatafile ( $this->db, $this->id ) );
		
		$temp = $cmd;
		switch ($cmd = $mode) {
			// 글 읽기권한
			case 'remote' :
			case 'read' :
			case 'down' :
				// 관리자 권한
				if (intval ( $envconf ["access_read"] ))
					$mode = 'admin_login';
					// 비공개문서 기능(2001.04.23)
				else if (intval ( $data ["privatetype"] ))
					$mode = 'admin_login';
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ) && empty ( $this->sess_user ))
					$mode = 'user_login';
				break;
			
			// 답변글 쓰기권한
			case 'reply' :
				// 관리자 권한
				if (intval ( $envconf ["access_reply"] ))
					$mode = 'admin_login';
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ) && empty ( $this->sess_user ))
					$mode = 'user_login';
				break;
			
			// 글 쓰기권한
			case 'write' :
				// 관리자 권한
				if (intval ( $envconf ["access_write"] ))
					$mode = 'admin_login';
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ) && empty ( $this->sess_user ))
					$mode = 'user_login';
				break;
			
			// 글 수정권한
			case 'modify' :
				// 관리자 권한
				if (intval ( $envconf ["access_write"] ))
					$mode = 'admin_login';
					// 작성자 권한
				else if (empty ( $envconf ["useuser"] ) && intval ( $envconf ["show_passwd"] ))
					$mode = 'user_login';
					// 회원 권한
				else if (intval ( $envconf ["useuser"] ) && empty ( $this->sess_user ))
					$mode = 'user_login';
				break;
			
			// 글 삭제권한
			case 'delete' :
				// 관리자 권한
				$mode = 'admin_login';
				break;
			
			case 'webmail' :
				if (intval ( $envconf ["useuser"] ) && empty ( $this->sess_user ))
					$mode = 'user_login';
				break;
			
			case 'find_pass' :
			case 'login' :
			case 'member' : // 회원정보변경
				unset ( $this->sess_user );
				$mode = 'user_login';
				break;
			
			case 'admin_adjust' :
			case 'admin_conv' :
			case 'admin_permit' :
			case 'admin_modify' :
			case 'admin_delete' :
			case 'admin_sql' :
			case 'newpass' :
			case 'config' :
			case 'new_folder' :
				unset ( $this->sess_user );
				$mode = 'admin_login';
				break;
			
			default :
				$cmd = $temp;
				break;
		} // end switch
		
		_debug ( "after=$mode/$cmd" );
	}
	
	// 1.테스트보드 프로파일
	function htmlprofile() {
		global $sysconf;
		setlocale(LC_TIME, "ko");		
		echo ("\n");
		echo ("<!------------------------------------------------------------------\n");
		echo ("\t".program_name." ".program_ver." (update : ".program_date.")\n");
		echo ("\n");
		echo ("\tdistribution site : $sysconf[board_home]\n");
		echo ("\ttechnical contact : $sysconf[board_maker]\n");
		echo ("\tprogrammer : $sysconf[author] ($sysconf[contact])\n");
		echo ("\tskin maker : $sysconf[skin_maker]\n");
		echo ("\n");
		echo ("\tlast updated: " . strftime ( "%c", getlastmod () ) . "\n");
		echo ("\n");
		echo ("\tcopyright(?)2001-" . yy () . " $sysconf[author]. all rights reserved.\n");
		echo ("-------------------------------------------------------------------->\n");
		echo ("\n");
	}
	
	// 2.게시판 헤더(타이틀)
	function htmlheader($showt = true) {
		global $sysconf, $envconf;
		
		// 3.테스트보드 프로파일
		$this->htmlprofile ();
		
		// 로딩 메세지 기능(2001.09.26)
		$this->loadingbegin ();
		
		echo ("<html>\n");
		echo ("<head>\n");

		echo ("\n<title>$envconf[browsertitle]</title>\n");
		echo ("<meta http-equiv='Content-Type' content='text/html;charset=$envconf[charset]'>\n");
		echo ("<meta name='Description' content='$sysconf[description]'>\n");
		echo ("<meta name='Content' content='$sysconf[content]'>\n");
		echo ("<meta name='Author' content='$sysconf[author]'>\n");
		echo ("<meta name='Keywords' content='$sysconf[keywords]'>\n");
		
		// 링크타입
		$linktype = empty ( $envconf ["linktype"] ) ? "none" : "underline";
		
		echo ("\n<!-- define link shape none/under/over -->\n");
		
		// TODO::게시판 설정변수 사용(예정)
		// $envconf["stylesheet"]='css.php';
		// echo("<link rel=stylesheet href='$sysconf[path_inc]/$envconf[stylesheet]' type='text/css' title=style>\n");
		
		echo ("<style type='text/css'>\n");
		echo ("<!--\n");
		
		// 바디스타일 추가(2002.02.04)
		echo ("body,input,textarea\n");
		echo ("{\n");
		echo ("scrollbar-3dlight-color:#595959;\n");
		echo ("scrollbar-arrow-color:#ffffff;\n");
		echo ("scrollbar-base-color:#cfcfcf;\n");
		echo ("scrollbar-darkshadow-color:#ffffff;\n");
		echo ("scrollbar-face-color:#cfcfcf;\n");
		echo ("scrollbar-highlight-color:#fffff;\n");
		echo ("scrollbar-track-color:#fffff;\n");
		echo ("scrollbar-shadow-color:#595959}\n\n");
		
		// 링크스타일 정의
		echo ("a:link    { color:$envconf[linkcolor];text-decoration:none;}\n");
		echo ("a:visited { color:$envconf[vlinkcolor];text-decoration:none;}\n");
		// echo("a:active { text-decoration:none;}\n");
		echo ("a:hover   { color:$envconf[hlinkcolor];text-decoration:none;background:$linktype;}\n\n");
		
		// 폰트스타일 정의
		echo (".hint     { font-family:돋움;font-size:8pt;color:#929292 }\n");
		echo (".small    { font-family:돋움;font-size:8pt }\n");
		echo (".normal   { font-family:$envconf[deffont];font-size:10pt }\n");
		echo (".subject  { font-family:$envconf[deffont];font-size:12pt;font-weight:bold }\n");
		echo (".big      { font-family:$envconf[deffont];font-size:14pt;font-weight:bold }\n");
		echo (".button   { height:20px;background-color:#dfdfdf;border-width:1px;border-style:ridge;border-color:#d0d0d0;}\n");
		echo (".readbox  { border:1 solid black;background-color:#d0d0d0;}\n");
		echo (".memobox  { background-color:#f8fff1;}\n");
		echo (".editbox  { background-color:#f8fff1;height:21px;}\n");
		echo (".babel    { border-width:1px;border-top-color:#fff6e9;border-left-color:#fff6e9;border-right-color:#887f6d;border-bottom-color:#887f6d;border-style:solid;}\n");
		echo (".pageskip { page-break-before:always}\n"); // 페이지 나누어 프린트하기..(사용예정)
		
		echo ("body,p,br,table,td,select,input,form,textarea,option\n");
		echo ("          { font-family:$envconf[deffont];font-size:$envconf[deffontsize]pt;}\n");
		
		echo ("#b{ font-weight:bold}\n");
		echo ("#o{ color:#FF7635}\n");
		echo ("#w{ color:#ffffff;text-decoration: none}\n");
		echo ("#wb{ color:#ffffff;text-decoration: none}\n");
		
		echo ("//-->\n");
		echo ("</style>\n");
		
		echo ("</head>\n");
		
		// 재설정함수들
		$this->resetstyle ();
		$this->resetjavascript ();
		
		// 기본색상
		$defcolor = empty ( $envconf ["defcolor"] ) ? "" : "text='$envconf[defcolor]'";
		$backcolor = empty ( $envconf ["backcolor"] ) ? "" : "bgcolor='$envconf[backcolor]'";
		$backimage = empty ( $envconf ["backimage"] ) ? "" : "background='$envconf[backimage]'";
		$boardalign = empty ( $envconf ["boardalign"] ) ? "" : "align='$envconf[boardalign]'";
		
		echo ("\n<!-- body start -->\n");
		echo ("<body $defcolor $backcolor $backimage\n");
		echo ("leftmargin=0 marginwidth=0 topmargin=0 marginheight=0\n");
		// TODO::내용 입력할때 마우스 사용할 수 없게
		// echo("oncontextmenu='return false' ondragstart='return false' onselectstart='return false'\n");
		echo (">\n");
		
		// 게시판 정렬
		if ($showt) {
			echo ("<table width='$envconf[tablewidth]' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]' $boardalign>\n");
			echo ("<tr>\n<td>\n");
		} // end if
	}
	
	// 3.사용자 게시판 헤더 정의
	function userheader() {
		global $envconf;
		
		echo ("\n<!-- userheader start -->\n");
		echo ($envconf ["htmlhead"]);
		echo ("\n<!-- userheader end -->\n");
		
		// 제목과 그림이 있는경우 출력방식
		switch (intval ( $envconf ["autotitle"] )) {
			case 0 :
				echo "<br>\n";
				break;
			
			case 1 :
				if ($envconf ["boardimage"] && is_readable ( $envconf ["boardimage"] ))
					echo "<img src='$envconf[boardimage]' border=0><br>\n";
				if ($envconf ["boardtitle"])
					echo "<center><font class=norm><b>$envconf[boardtitle]</b></font></center>\n";
				echo "<br>\n";
				break;
			
			case 2 :
				if ($envconf ["boardimage"] && is_readable ( $envconf ["boardimage"] ))
					echo "<img src='$envconf[boardimage]' border=0><br>\n";
				else if ($envconf ["boardtitle"])
					echo "<center><font class=big><b>$envconf[boardtitle]</b></font></center>\n";
				echo "<br>\n";
				break;
		} // end switch
	}
	
	// 4.컬럼 헤더
	function columnheader($align = "center") {
		global $envconf;
		
		$titlebkcol = empty ( $envconf ["titlebkcol"] ) ? "" : "bgcolor='$envconf[titlebkcol]'";
		$boardtitle = empty ( $envconf ["boardtitle"] ) ? "&nbsp;" : $envconf ["boardtitle"];
		
		echo ("\n<!-- columnheader design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// echo("<tr align=$align><td $titlebkcol>\n");
		echo ("<tr align=$align><td style=\"filter:progid:DXImageTransform.Microsoft.Gradient(startColorStr='#FFFFFF', endColorStr='$envconf[titlebkcol]', gradientType='1')\">\n");
		echo ("<font class=subject color='$envconf[titletxtcol]'><b>$boardtitle</b></font>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
	}
	
	// 5.컬럼 바텀
	function columnbottom($align = "right") {
		global $sysconf, $envconf;
		
		$titlebkcol = empty ( $envconf ["titlebkcol"] ) ? "" : "bgcolor='$envconf[titlebkcol]'";
		$copyright = empty ( $sysconf ["board_home"] ) ? "&nbsp;" : "distribution by " . hyperlink ( $sysconf ["board_home"] );
		$skin_maker = empty ( $sysconf ["skin_maker"] ) ? "&nbsp;" : "skin by " . hyperlink ( $sysconf ["skin_maker"] );
		
		echo ("\n<!-- columnbottom design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		// echo("<tr align=$align><td $titlebkcol>\n");
		echo ("<tr align=$align><td style=\"filter:progid:DXImageTransform.Microsoft.Gradient(startColorStr='#FFFFFF', endColorStr='$envconf[titlebkcol]', gradientType='1')\">\n");
		echo ("<font class=small color=silver>$copyright / $skin_maker</font>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
	}
	
	// 6.사용자 게시판 바텀 정의
	function userbottom() {
		global $envconf;
		
		echo ("\n<!-- userbottom start -->\n");
		echo ($envconf ["htmltail"]);
		echo ("\n<!-- userbottom end -->\n");
	}
	
	// 7.게시판 바텀
	function htmlbottom($showt = true) {
		echo ("\n<!-- htmlbottom design -->\n");
		if ($showt)
			echo ("</td>\n</tr>\n</table>\n");
		echo ("</body>\n");
		echo ("</html>\n");
		
		// 로딩 메세지 기능(2001.09.26)
		$this->loadingend ();
	}
	
	// 오류메세지 출력
	function htmlerror($info, $go = 'history.back()') {
		global $sysconf, $envconf, $msg, $btn;
		
		$errmsg = sprintf ( $msg ["err_def"], hyperlink ( $envconf ["admin_mail"] ) );
		
		$this->htmlheader ( false );
		echo ("\n<!-- htmlerror design -->\n");
		echo ("<center>\n");
		echo ("<table width='100%' height='100%' border='0' cellspacing='0' cellpadding='0'>\n");
		echo ("<tr bgcolor='white' align='center'>\n");
		
		echo ("<td>\n");
		echo ("<table width='400' height='200' border='0' cellspacing='0' cellpadding='0' bordercolor='red'>\n");
		
		echo ("<tr bgcolor='white' align='center'>\n");
		echo ("<td><img border=0 src='$sysconf[img_image]/errmsg.gif'></td>\n");
		echo ("</tr>\n");
		
		echo ("<tr bgcolor='white' align='center'>\n");
		echo ("<td><font color=red>$info</font><p>$errmsg\n");
		echo ("[<a href='javascript:$go'>$btn[back]</a>]\n");
		echo ("[<a href='javascript:self.close()'>$btn[close]</a>]\n");
		echo ("</td></tr>\n");
		
		echo ("</table>\n");
		echo ("</td>\n");
		
		echo ("</tr>\n");
		echo ("</table>\n");
		echo ("</center>\n");
		$this->htmlbottom ( false );
		$this->free ();
	}
	
	// 확인메세지 출력
	function htmlinfo($info, $go = 'history.back()') {
		global $sysconf, $envconf, $msg, $btn;
		
		$defmsg = sprintf ( $msg ["info_def"], hyperlink ( $envconf ["admin_mail"] ) );
		
		$this->htmlheader ( false );
		echo ("\n<!-- htmlinfo design -->\n");
		echo ("<center>\n");
		echo ("<table width='100%' height='100%' border='0' cellspacing='0' cellpadding='0'>\n");
		echo ("<tr bgcolor='white' align='center'>\n");
		
		echo ("<td>\n");
		echo ("<table width='400' height='200' border='2' cellspacing='0' cellpadding='0' bordercolor='#1d5b99'>\n");
		/**
		 * **********************************************************************\
		 * echo("<tr bgcolor='white' align='center'>\n");
		 * echo("<td><img border=0 src='$sysconf[icon_image]/infomsg.gif'></td>\n");
		 * echo("</tr>\n");
		 * \***********************************************************************
		 */
		echo ("<tr bgcolor='white' align='center'>\n");
		echo ("<td><font color=blue>$info</font><p>$defmsg\n");
		echo ("[<a href='javascript:$go'>$btn[back]</a>]\n");
		echo ("[<a href='javascript:self.close()'>$btn[close]</a>]\n");
		echo ("</td></tr>\n");
		
		echo ("</table>\n");
		echo ("</td>\n");
		
		echo ("</tr>\n");
		echo ("</table>\n");
		echo ("</center>\n");
		$this->htmlbottom ( false );
		$this->free ();
	}
	
	// 수평 분리선
	function htmlhrline($width = 100, $colspan = 0) {
		global $envconf;
		
		if (intval ( $width ) > 0 && intval ( $width ) < 101)
			$width .= '%';
		$backcolor = empty ( $envconf ["backcolor"] ) ? "" : "bgcolor='$envconf[backcolor]'";
		
		echo ("\n<!-- htmlhrline design -->\n");
		echo ("<table width='$width' border=0 cellspacing=0 cellpadding=0>\n");
		echo ("<tr>\n");
		echo ("<td width='$width' $backcolor colspan=$colspan><p>\n");
		echo ("<hr color='$envconf[defcolor]' size=1 noshade></td>\n");
		echo ("</tr>\n");
		echo ("</table>\n");
	}
	
	// 새로고침
	function refresh($mode = "list") {
		
		// if (defined('_debug_')){
		// phpinfo();
		// exit;
		// }
		echo ("<form method=post name='f'        action='$this->prog'>\n");
		echo ("<input type='hidden' name='mode'    value='$mode'>\n");
		echo ("<input type='hidden' name='db'      value='$this->db'>\n");
		echo ("<input type='hidden' name='id'      value='$this->id'>\n");
		echo ("<input type='hidden' name='page'    value='$this->page'>\n");
		echo ("<input type='hidden' name='ff'      value='$this->ff'>\n");
		echo ("<input type='hidden' name='fw'      value='$this->fw'>\n");
		echo ("<input type='hidden' name='fn'      value='$this->fn'>\n");
		echo ("<input type='hidden' name='sid'     value='$this->sid'>\n");
		echo ("<input type='hidden' name='selected'     value='$this->selected'>\n");
		echo ("</form>\n");
		echo ("<script language='javascript'>document.f.submit();</script>\n");
		exit ();
	}
	
	// 새로고침
	function commit($mode = "list") {
		global $envconf;
		
		if (defined ( '_debug_' )) {
			phpinfo ();
			exit ();
		}
		// only try to send out headers in case those were not sent already
		if (! headers_sent ()) {
			header ( "cache-control: no-cache" );
			header ( "cache-control: must-revalidate" );
			header ( "pragma: no-cache" );
		}
		
		echo ("<form method=post name='f'        action='$envconf[testboard]'>\n");
		echo ("<input type='hidden' name='mode'    value='$mode'>\n");
		echo ("<input type='hidden' name='db'      value='$this->db'>\n");
		echo ("<input type='hidden' name='id'      value='$this->id'>\n");
		echo ("<input type='hidden' name='page'    value='$this->page'>\n");
		echo ("<input type='hidden' name='ff'      value='$this->ff'>\n");
		echo ("<input type='hidden' name='fw'      value='$this->fw'>\n");
		echo ("<input type='hidden' name='fn'      value='$this->fn'>\n");
		echo ("<input type='hidden' name='sid'     value='$this->sid'>\n");
		echo ("</form>\n");
		echo ("<script language='javascript'>document.f.submit();</script>\n");
		exit ();
	}
	
	// 재설정함수들
	function resetconfig() {
	}
	function resetstyle() {
	}
	
	// 자바스크립트 재정의
	function resetjavascript() {
		global $sysconf;
		
		echo ("\n<!-- javascript design -->\n");
		echo ("<script language='javascript' src='$sysconf[home_inc]/js/default.js'></script>\n");
		echo ("<script language='javascript' src='$sysconf[home_inc]/js/cart.js'></script>\n");
		echo ("<script language='javascript' src='$sysconf[home_inc]/js/calendar.js'></script>\n");
	}
	
	// 로딩 메세지 기능(2001.09.26)
	function loadingbegin() {
		echo ("\n<!-- loading begin design -->\n");
		echo ("<table id='waiting' height='50' style='position:absolute;visibility:hidden'>\n");
		echo ("<tr>\n");
		echo ("<td align=center width='200' style='font-size:10pt;background:#d6d3ce;'>\n");
		echo ("<b>Loading... please wait</b>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		echo ("  window.status='Loading...'\n");
		echo ("  waiting.style.top=document.body.offsetHeight/2 -waiting.offsetHeight/2;\n");
		echo ("  waiting.style.left=document.body.offsetWidth/2 -waiting.offsetWidth/2;\n");
		echo ("  waiting.style.visibility='visible'\n");
		echo ("//-->\n");
		echo ("</script>\n");
	}
	
	// 로딩 메세지 기능(2001.09.26)
	function loadingend() {
		echo ("\n<!-- loadingend design -->\n");
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		echo ("  window.status=''\n");
		echo ("  waiting.style.visibility='hidden';\n");
		echo ("//-->\n");
		echo ("</script>\n");
	}
	/**
	 * **********************************************************************\
	 * 기본태그출력함수
	 * \***********************************************************************
	 */
	function showtitle($field, $color = "black", $bgcolor = 'white', $colspan = '', $align = 'center') {
		global $envconf, $label;
		
		$result = if_exists ( $label, $field, $field );
		$bgcolor = empty ( $bgcolor ) ? $envconf ["dataheadbkcol"] : $bgcolor;
		echo ("<td height=36 align='$align' bgcolor='$bgcolor' colspan='$colspan'><font color='$color'><b>$result</b></font></td>\n");
	}
	function showlabel($field, $bgcolor = '#f0f0f0', $align = 'right', $width = '25', $color = '#000000') {
		global $label;
		
		$result = if_exists ( $label, $field, $field );
		if (intval ( $width ) > 0 && intval ( $width ) < 101)
			$width .= '%';
		echo ("<th align='$align' bgcolor='$bgcolor' width='$width'><font color='$color'>{$result}&nbsp;</font></th>\n");
	}
	function showmemo($field, $data, $rows = 0, $cols = 0, $max = 0) {
		global $hint;
		
		$result = if_exists ( $hint, $field );
		$maxlength = intval ( $max ) ? "maxlength='$max'" : "";
		
		echo ("<textarea name='$field' rows='$rows' cols='$cols' $maxlength class=memobox>$data</textarea>\n");
		echo ("<br><font class=hint>$result</font>\n");
	}
	function showinput($type, $field, $data, $size = 0, $max = 0) {
		global $hint;
		
		$result = if_exists ( $hint, $field );
		$maxlength = intval ( $max ) ? "maxlength='$max'" : "";
		
		echo ("<input type='$type' name='$field' value='$data' size='$size' $maxlength onblur='onexit(this)' onfocus='onenter(this)' onkeydown='next_focus()' class=editbox>\n");
		echo ("<font class=hint>&nbsp;$result</font>\n");
	}
	function showoutput($type, $field, $data, $size = 0, $max = 0) {
		echo ("<input type='$type' name='$field' value='$data' size='$size' maxlength='$max' class=readbox readonly>\n");
	}
	function showcheck($field, $data, $help = '') {
		global $hint;
		
		$checked = (intval ( $data ) > 0) ? "checked" : "";
		echo ("<input type='checkbox' name='$field' value='1' $checked>$hint[$field]&nbsp;\n");
		echo ($help);
	}
	function showradio($field, $data, $help = '') {
		global $hint;
		
		if (! is_array ( $radio = $hint [$field] ))
			return;
		
		foreach ( $radio as $key => $val ) {
			$checked = ($key == intval ( $data )) ? "checked" : "";
			echo ("<input type='radio' name='$field' value='$key' $checked>$radio[$key]&nbsp;\n");
		} // end foreach
		echo ($help);
	}
	function showselect($field, $data, $help = '', $onchange = '') {
		global $hint;
		
		if (! is_array ( $select = $hint [$field] ))
			return;
		
		echo ("<select name='$field' onchange='$onchange'>\n");
		foreach ( $select as $key => $val ) {
			$selected = ($val == $data) ? "selected" : "";
			echo ("<option value='$val' $selected>$val</option>\n");
		} // end foreach
		echo ("</select>\n");
		echo ($help);
	}
	function showbutton($btn, $onclick = '') {
		if (is_tag ( $btn ))
			echo ("<a href='javascript:$onclick'>$btn</a>\n");
		else
			echo ("<input type='button' value='$btn' class='button' onclick='$onclick'>\n");
		return;
	}
}
?>
