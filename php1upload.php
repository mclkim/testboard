<?php
/************************************************************************\
 * 프로그램명 : 첨부파일
 * 특기사항   : 1.다중파일 업로드
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2004/03
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1new.php');
class form1upload extends form1new {
	// 자바스크립트 재정의
	function resetjavascript() {
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		
		echo ("function add_file()\n");
		echo ("{\n");
		echo ("  var filenm = document.attachaddform.userfile.value;\n");
		echo ("  var ext = filenm.slice(filenm.lastIndexOf('.')+1).toLowerCase();\n");
		
		echo ("  if ( filenm == '' ) {\n");
		echo ("  alert('파일이 선택되지 않았습니다.');\n");
		echo ("  return false;\n");
		echo ("  }\n");
		
		// echo(" if ( !(ext == 'gif' || ext == 'jpg' || ext == 'png') ){\n");
		// echo(" alert('지원하지 않는 파일형식입니다.');\n");
		// echo(" return false;\n");
		// echo(" }\n");
		
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  document.attachaddform.db.value = top.opener.document.inputform.db.value;\n");
		echo ("  document.attachaddform.submit();\n");
		echo ("}\n");
		
		echo ("function del_file()\n");
		echo ("{\n");
		echo ("  var list = document.attachdeleteform.$this->field;\n");
		
		echo ("  waiting.style.visibility = 'visible';\n");
		echo ("  document.attachdeleteform.db.value = top.opener.document.inputform.db.value;\n");
		echo ("  document.attachdeleteform.rem.value = list.options[list.selectedIndex].value;\n");
		echo ("  document.attachdeleteform.submit();\n");
		echo ("}\n");
		
		echo ("function send_file() {\n");
		echo ("  var i = 0;\n");
		echo ("  var topfrm = top.opener.document.inputform.$this->field;\n");
		echo ("  var thisfrm = document.attachdeleteform.$this->field;\n");
		
		echo ("  if( topfrm.length > 0 )\n");
		echo ("  topfrm.length = 0;\n");
		
		echo ("  topfrm.length = thisfrm.length+1;\n");
		
		echo ("  for ( i = 0 ; i < thisfrm.length ; i++){\n");
		echo ("    topfrm.options[i].value = thisfrm.options[i].value;\n");
		echo ("    topfrm.options[i].text = thisfrm.options[i].text;\n");
		echo ("  }\n");
		echo ("}\n");
		echo ("-->\n");
		echo ("</script>");
	}
	function attachform() {
		global $sysconf, $btn;
		
		$attachments = $this->session->getsess ( $this->field );
		
		$total_size = 0;
		foreach ( $attachments as $key => $value )
			$total_size = $total_size + intval ( $value ["size"] );
		
		$total_size = bytesize ( $total_size );
		
		echo ("<body bgcolor=#ffffff topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 onload='send_file();'>\n");
		echo ("<center>\n");
		echo ("<table width='450' border='0' cellspacing='0' cellpadding='0'>\n");
		echo ("  <tr>\n");
		echo ("    <td height='5' bgcolor='4782bd'></td>\n");
		echo ("  </tr>\n");
		echo ("  <tr>\n");
		echo ("    <td height='1'></td>\n");
		echo ("  </tr>\n");
		echo ("  <tr>\n");
		echo ("    <td height='40' background='$sysconf[img_image]/img_fileadd.gif'>&nbsp; </td>\n");
		echo ("  </tr>\n");
		echo ("  <tr>\n");
		echo ("    <td height='20' >&nbsp;</td>\n");
		echo ("  </tr>\n");
		
		echo ("  <tr>\n");
		echo ("    <td>\n");
		echo ("      <table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>\n");
		
		echo ("\n<!-- attachaddform design -->\n");
		echo ("<form action='$this->prog' enctype=multipart/form-data method=post name=attachaddform>\n");
		echo ("<input type='hidden' name='db'   value=''>\n");
		echo ("<input type='hidden' name='mode' value='add'>\n");
		
		echo ("      <tr>\n");
		echo ("        <td height='22'><img src='$sysconf[img_image]/i_blue.gif'> <b>첨부파일 찾기/추가</b></td>\n");
		echo ("      </tr>\n");
		
		echo ("      <tr style='padding-left: 10px;'>\n");
		echo ("        <td>\n");
		echo ("        <script language='javascript'>\n");
		echo ("        if(navigator.appname == 'netscape')\n");
		echo ("        document.write('<input name=userfile size=17 type=file class=button>');\n");
		echo ("        else\n");
		echo ("        document.write('<input name=userfile size=23 type=file class=button>');\n");
		echo ("        </script>\n");
		echo ("        <input type='button' value='$btn[add]' class='button' onclick='javascript:add_file()'>\n");
		echo ("      </td></tr>\n");
		echo ("      </tr>\n");
		echo ("</form>\n");
		
		echo ("      <tr style='padding-left: 10px;'>\n");
		echo ("        <td>파일찾기에서 해당 파일을 선택후 추가 버튼을 클릭하세요.<br>\n");
		echo ("            추가 버튼 클릭후 파일 업로드가 끝날때까지 잠시 기다려주십시오.<br>\n");
		echo ("            파일 업로드가 끝나면 자동으로 첨부파일 목록에 추가됩니다. </td>\n");
		echo ("      </tr>\n");
		
		echo ("      <tr>\n");
		echo ("        <td height='1' background='$sysconf[img_image]/dots.gif'></td>\n");
		echo ("      </tr>\n");
		
		echo ("      <tr><td height='10'></td>\n");
		echo ("      </tr>\n");
		
		echo ("\n<!-- attachdeleteform design -->\n");
		echo ("<form action='$this->prog' method=get name=attachdeleteform>\n");
		echo ("<input type='hidden' name='db'   value=''>\n");
		echo ("<input type='hidden' name='rem'  value=''>\n");
		echo ("<input type='hidden' name='mode' value='delete'>\n");
		
		echo ("      <tr>\n");
		echo ("        <td height='22'><img src='$sysconf[img_image]/i_blue.gif'> <b>첨부파일/목록</b>\n");
		echo ("        <font size=2>(현재 총 <font color=ff4e00><b>$total_size</b></font>&nbsp바이트)</font></td>\n");
		echo ("      </tr>\n");
		echo ("      <tr style='padding-left: 10px;'>\n");
		echo ("        <td>\n");
		echo ("        <small><select name='$this->field' size=6>\n");
		echo ("        <option selected value='-1'>---------- 첨부할 파일목록 -----------</option>\n");
		foreach ( $attachments as $key => $value )
			echo ("        <option value='$key'>$value[name]($value[size]byte)</option>\n");
		echo ("        </select></small>\n");
		echo ("        <input type='button' value='$btn[del]' class='button' onclick='javascript:del_file()'>\n");
		echo ("        </td>\n");
		echo ("      </tr>\n");
		echo ("</form>\n");
		
		echo ("      </table>\n");
		echo ("  </td></tr>\n");
		
		echo ("  <tr>\n");
		echo ("    <td height='20' >&nbsp;</td>\n");
		echo ("  </tr>\n");
		
		echo ("  <tr>\n");
		echo ("    <td height='35' bgcolor='E0E1E1' align=center><input type='button' value='$btn[close]' class='button' onclick='window.close()'></td>\n");
		echo ("  </tr>\n");
		
		echo ("  <tr>\n");
		echo ("    <td height='1'></td>\n");
		echo ("  </tr>\n");
		
		echo ("  <tr>\n");
		echo ("    <td height='5' bgcolor='4782bd'></td>\n");
		echo ("  </tr>\n");
		
		echo ("</table>\n");
		echo ("</center>\n");
		echo ("<br></body>\n");
	}
	/**
	 * **********************************************************************\
	 * --.에러 코드(php 4.2.0 이상 부터 지원)
	 * --.$_FILES["attach"]["error"]
	 *
	 * UPLOAD_ERR_OK (0) - 업로드 성공
	 * UPLOAD_ERR_INI_SIZE (1) - php.ini 에서 제한한 MAX_FILE_SIZE 초과
	 * UPLOAD_ERR_FORM_SIZE (2) - HTML 폼에서 제한한 MAX_FILE_SIZE 초과
	 * UPLOAD_ERR_PARTIAL (3) - 파일의 일부분만 업로드 되었음
	 * UPLOAD_ERR_NO_FILE (4) - 업로드된 파일이 없음
	 * \***********************************************************************
	 */
	function attachfile() {
		global $sysconf;
		global $userfile;
		
		if (isset ( $userfile ) && ((! is_array ( $userfile ) && is_uploaded_file ( $userfile )) || is_uploaded_file ( $userfile ["tmp_name"] ))) {
			
			if (is_version_up ( "4.0.2" )) {
				$userfile_name = $userfile ["name"];
				$userfile_type = $userfile ["type"];
				$userfile_size = $userfile ["size"];
				$userfile_temp = $userfile ["tmp_name"];
			} // end if
			
			if (! is_array ( $attachments = $this->session->getsess ( $this->field ) ))
				$ind = 0;
			else
				$ind = count ( $attachments );
				
				// 같은 파일이 있을 경우(의미없군)
			foreach ( $attachments as $key => $value )
				if ($value ["name"] == $userfile_name) {
					$ind = $key;
					break;
				}
			
			_debug ( $sysconf ['path_upload'] );
			
			// 확장자 검사 후 변경하기
			$filename = checkfilename ( $userfile_name );
			$filename = getuniquefile ( $sysconf ['path_upload'], $filename );
			$full_name = path_fix ( $sysconf ['path_upload'] . DIRECTORY_SEPARATOR . $filename );
			/**
			 * **********************************************************************\
			 * 첨부파일가 있을때
			 * 1.is_uploaded_file 함수 php-4.0.1pl2 이하인 경우 오류(2001.10.17)
			 * 2.move_uploaded_file 함수 php-4.0.1pl2 이하인 경우 오류(2001.10.17)
			 * \***********************************************************************
			 */
			if (is_version_up ( '4.0.3' ))
				move_uploaded_file ( $userfile_temp, $full_name );
			else {
				copy ( $userfile_temp, $full_name );
				unlink ( $userfile_temp );
			} // endif else
			  // todo::
			  // $attachments[$ind]["localname"] = $full_name;
			$attachments [$ind] ["name"] = $filename;
			$attachments [$ind] ["type"] = $userfile_type;
			$attachments [$ind] ["size"] = $userfile_size;
			$attachments [$ind] ["down"] = 0;
			
			$this->session->setsess ( $this->field, $attachments );
			
			// 작은그림파일
			if ($small_image = image_resize ( $full_name, 120, 90 ))
				$this->session->setsess ( "small_image", basename ( $small_image ) );
		} // end if
	}
	function deletefile() {
		global $sysconf;
		global $rem;
		
		$attachments = $this->session->getsess ( $this->field );
		
		$filename = $attachments [$rem] ["name"];
		$filename = path_fix ( "$sysconf[path_upload]/$filename" );
		
		if (is_writeable ( $filename ))
			unlink ( $filename );
		unset ( $attachments [$rem] );
		
		$this->session->setsess ( $this->field, $attachments );
	}
	function upload($fvars) {
		for($i = 0, reset ( $fvars ); $i < count ( $fvars ); $i ++, next ( $fvars )) {
			$attachments [$i] [0] = key ( $fvars ); // vars name
			$attachments [$i] [1] = $fvars [key ( $fvars )] ["size"]; // filesize
			$attachments [$i] [2] = $fvars [key ( $fvars )] ["type"]; // mime type
			$attachments [$i] [3] = $fvars [key ( $fvars )] ["name"]; // original name
			$attachments [$i] [4] = $fvars [key ( $fvars )] ["tmp_name"]; // temporary name
		} // end for
		
		return $attachments;
	}
}

$inst = new form1upload ();

if (! empty ( $field ))
	$inst->session->setsess ( "field", $field );
$inst->field = $inst->session->getsess ( "field" );

// 모드설정
$inst->mode ( $_REQUEST );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->htmlheader ( false );
		$inst->attachform ();
		$inst->htmlbottom ( false );
		break;
	
	case 'add' :
		$inst->htmlheader ( false );
		$inst->attachfile ();
		$inst->attachform ();
		$inst->htmlbottom ( false );
		break;
	
	case 'delete' :
		$inst->htmlheader ( false );
		$inst->deletefile ();
		$inst->attachform ();
		$inst->htmlbottom ( false );
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>
