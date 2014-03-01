<?php
/************************************************************************\
 * 프로그램명 : 우편번호검색
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2002/03
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1new.php');
class form1post extends form1new {
	// 자바스크립트 재정의
	function resetjavascript() {
		echo ("\n<!-- resetjavascript design -->\n");
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		
		// 우편번호조회
		echo ("function set_close()\n");
		echo ("{\n");
		echo ("  juso=getform.juso1.value+\" \"+getform.juso2.value;\n");
		if ($this->f_zipcode)
			echo ("  window.opener.inputform.$this->f_zipcode.value=getform.zipcode.value;\n");
		if ($this->f_address)
			echo ("  window.opener.inputform.$this->f_address.value=juso;\n");
		echo ("  window.close();\n");
		echo ("}\n\n");
		
		echo ("function check_submit(obj)\n");
		echo ("{\n");
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  obj.submit();\n");
		echo ("}\n\n");
		
		echo ("function onuse(index){\n");
		echo ("  if(showpost.addr.length > 1){\n");
		echo ("     showpost.zipcode.value = showpost.addr[index].value.substring(0,7);\n");
		echo ("     showpost.juso1.value   = showpost.addr[index].value.substring(8);\n");
		echo ("  }else{\n");
		echo ("     showpost.zipcode.value = showpost.addr.value.substring(0,7);\n");
		echo ("     showpost.juso1.value   = showpost.addr.value.substring(8);\n");
		echo ("  }\n");
		echo ("  showpost.submit();\n");
		echo ("}\n\n");
		
		echo ("//-->\n");
		echo ("</script>\n");
	}
	
	// 우편번호검색(2002.02.01)
	function postform($ff = '') {
		global $envconf, $btn;
		
		echo ("<br>\n");
		echo ("\n<!-- postform design -->\n");
		echo ("<form name='postform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='mode' value='step1'>\n");
		
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr><th class='subject' align='center' bgcolor='#dcddeb'>우편번호찾기</th></tr>\n");
		echo ("<tr><td align='center'>\n");
		echo ("<font class=hint>예) 주소가 <font color='#0000a0'>'서울시 송파구 문정1동 ...'</font>일 경우<br>\n");
		echo ("<font color='#0000a0'>'문정','문정1동','138-201'</font>을 입력하십시요.</font><br><br>\n");
		echo ("<font class=hint>조회 후 검색결과 중 해당 주소를 선택하시면 자동입력됩니다.</font><br>\n");
		echo ("</td></tr>\n");
		
		echo ("<tr><td align='center'>\n");
		echo ("<input type='text' name='ff' value='$ff' size=20 maxlength=20 class=editbox>\n");
		echo ("<input type='button' value='$btn[find]' class='button' onclick='check_submit(postform)'>\n");
		echo ("<input type='button' value='$btn[close]' class='button' onclick='self.close()'>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		
		echo ("</form>\n");
	}
	
	// 우편번호검색(2002.02.01)
	function showpost($data) {
		global $envconf;
		
		if (empty ( $data ))
			return;
		
		echo ("<form name='showpost' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='mode'    value='step2'>\n");
		echo ("<input type='hidden' name='zipcode' value=''>\n");
		echo ("<input type='hidden' name='juso1'   value=''>\n");
		
		echo ("\n<!-- showpost design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		$i = 0;
		foreach ( $data as $key => $val ) {
			$addr = explode ( "^", $val );
			
			// 임시(우편번호가 아닌경우)
			if (empty ( $addr [0] ) || intval ( $addr [0] ) == 0)
				continue;
			
			$juso = implode ( " ", $addr );
			echo ("<tr><td style='padding-left:30px;'><input type='hidden' name='addr' value='$juso'><a href='javascript:onuse($i)'>$juso</a><td></tr>\n");
			$i ++;
		} // end foreach
		
		echo ("</table>\n");
		
		echo ("</form>\n");
	}
	
	// 세부주소입력화면(2002.02.01)
	function getform() {
		global $envconf, $btn;
		global $zipcode, $juso1;
		
		echo ("<br>\n");
		echo ("\n<!-- getform design -->\n");
		echo ("<form name='getform' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='mode'    value='step3'>\n");
		echo ("<input type='hidden' name='zipcode' value='$zipcode'>\n");
		echo ("<input type='hidden' name='juso1'   value='$juso1'>\n");
		
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr><th class='subject' align='center' bgcolor='#dcddeb'>우편번호찾기</th></tr>\n");
		echo ("<tr><td>\n");
		echo ("<font class=hint>&nbsp;&nbsp;나머지 주소를 입력해주세요</font><br>\n");
		echo ("</td></tr>\n");
		echo ("<tr><td>\n");
		echo ("<font>&nbsp;&nbsp;[$zipcode] $juso1</font><br>\n");
		echo ("</td></tr>\n");
		
		echo ("<tr><td align='center'>\n");
		echo ("<input type='text' name='juso2' size=40 maxlength=40 class=editbox>\n");
		echo ("<input type='button' value='$btn[apply]' class='button' onclick='set_close()'>\n");
		echo ("<input type='button' value='$btn[close]' class='button' onclick='self.close()'>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		echo ("</form>\n");
	}
	
	// 우편번호검색(2002.02.01)
	function findpostfile($ff) {
		global $sysconf;
		
		if (empty ( $ff ))
			return null;
		
		$common_query = "$sysconf[grepcmd] $ff $sysconf[file_zip]";
		
		return runshell ( $common_query );
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1post ();

if (! empty ( $f_zipcode ))
	$inst->session->setsess ( "f_zipcode", $f_zipcode );
$inst->f_zipcode = $inst->session->getsess ( "f_zipcode" );

if (! empty ( $f_address ))
	$inst->session->setsess ( "f_address", $f_address );
$inst->f_address = $inst->session->getsess ( "f_address" );

// 모드설정
$inst->mode ( $_REQUEST );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->htmlheader ( false );
		$inst->postform ();
		$inst->htmlbottom ( false );
		break;
	
	case 'step1' :
		$inst->htmlheader ( false );
		$inst->postform ( $ff );
		$inst->showpost ( $inst->findpostfile ( $ff ) );
		$inst->htmlbottom ( false );
		break;
	
	case 'step2' :
		$inst->htmlheader ( false );
		$inst->getform ();
		$inst->htmlbottom ( false );
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>
