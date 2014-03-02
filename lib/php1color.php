<?php
/************************************************************************\
 * 프로그램명 : 컬러선택
 * 특기사항   : 1.컬러설정하는 팝메뉴 기능
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2002/02
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1new.php');
class form1color extends form1new {
	function resetstyle() {
		echo ("\n<!-- resetstyle -->\n");
		echo ("<style type='text/css'>\n");
		echo ("<!--\n");
		echo ("body       { font-family:굴림;font-size:9pt;background-color:buttonface;padding:5 }\n");
		echo ("p,td,input { font-family:굴림;font-size:9pt;}\n");
		echo ("fieldset   { width:260;padding:4 8 8 8;background-color:buttonface;}\n");
		echo ("//-->\n");
		echo ("</style>\n");
	}
	function resetconfig() {
	}
	function resetjavascript() {
	}
	function colorform() {
		global $sysconf;
		
		echo ("<script language='javascript'>\n");
		echo ("<!--\n");
		
		echo ("addary=new Array();//red\n");
		echo ("addary[0]=new Array(0,1,0);//red green\n");
		echo ("addary[1]=new Array(-1,0,0);//green\n");
		echo ("addary[2]=new Array(0,0,1);//green blue\n");
		echo ("addary[3]=new Array(0,-1,0);//blue\n");
		echo ("addary[4]=new Array(1,0,0);//red blue\n");
		echo ("addary[5]=new Array(0,0,-1);//red\n");
		echo ("addary[6]=new Array(255,1,1);\n");
		echo ("clrary=new Array(360);\n\n");
		
		echo ("for(i=0;i < 6;i++)\n");
		echo ("  for(j=0;j < 60;j++) {\n");
		echo ("    clrary[60 * i + j]=new Array(3);\n");
		echo ("    for(k=0;k < 3;k++) {\n");
		echo ("      clrary[60 * i + j][k]=addary[6][k];\n");
		echo ("      addary[6][k] += (addary[i][k] * 4);\n");
		echo ("    }//end for\n");
		echo ("  }//end for\n\n");
		
		echo ("function capture()\n");
		echo ("{\n");
		echo ("  if (document.layers) {\n");
		echo ("    layobj=document.layers['wheel'];\n");
		echo ("    layobj.document.captureevents(event.mousemove);\n");
		echo ("    layobj.document.onmousemove=moved;\n");
		echo ("  }//endif\n");
		echo ("  else {\n");
		echo ("    layobj=document.all['wheel'];\n");
		echo ("    layobj.onmousemove=moved;\n");
		echo ("  }//end else\n");
		echo ("}\n\n");
		
		echo ("function moved(e)\n");
		echo ("{\n");
		echo ("  y=4 * ((document.layers)?e.layerX:event.offsetX);\n");
		echo ("  x=4 * ((document.layers)?e.layerY:event.offsetY);\n");
		echo ("  sx=x - 512;\n");
		echo ("  sy=y - 512;\n");
		echo ("  qx=(sx < 0)?0:1;\n");
		echo ("  qy=(sy < 0)?0:1;\n");
		echo ("  q=2 * qy + qx;\n");
		echo ("  quad=new Array(-180,360,180,0);\n");
		echo ("  xa=Math.abs(sx);\n");
		echo ("  ya=Math.abs(sy);\n");
		echo ("  d=ya * 45 / xa;\n");
		
		echo ("  if (ya > xa) d=90 - (xa * 45 / ya);\n");
		
		echo ("  deg=Math.floor(Math.abs(quad[q] - d));\n");
		echo ("  n=0;\n");
		echo ("  sx=Math.abs(x - 512);\n");
		echo ("  sy=Math.abs(y - 512);\n");
		echo ("  r=Math.sqrt((sx * sx) + (sy * sy));\n");
		
		echo ("  if (x == 512 & y == 512) {\n");
		echo ("    c='000000';\n");
		echo ("  } else {\n");
		echo ("    for(i=0;i < 3;i++) {\n");
		echo ("    r2=clrary[deg][i] * r / 256;\n");
		echo ("    if (r > 256) r2 += Math.floor(r - 256);\n");
		echo ("    if (r2 > 255) r2=255;\n");
		echo ("    n=256 * n + Math.floor(r2);\n");
		echo ("    }//end for\n");
		echo ("    c=n.toString(16);\n");
		echo ("    while(c.length < 6) c='0' + c;\n");
		echo ("  }//end else if\n");
		
		echo ("  if (document.layers) {\n");
		echo ("    document.layers['wheel'].document.f.t.value='#' + c;\n");
		echo ("    document.layers['wheel'].bgcolor='#' + c;\n");
		echo ("  } else {\n");
		echo ("    document.all['wheel'].document.f.t.value='#' + c;\n");
		
		if (empty ( $exec ))
			$color = 'backgroundcolor';
		else
			$color = 'color';
		
		echo ("    document.all['wheel'].style.$color='#' + c;\n");
		echo ("  }//end else if\n");
		
		echo ("  return false;\n");
		echo ("}\n\n");
		
		echo ("function choice()\n");
		echo ("{\n");
		if ($this->field) {
			echo ("  config=opener.document.configform;\n");
			echo ("  config.$this->field.value=document.all['wheel'].document.f.t.value;\n");
			echo ("  config.$this->field.focus();\n");
		} // endif
		echo ("  window.close();\n");
		echo ("}\n\n");
		echo ("//-->\n");
		echo ("</script>\n\n");
		
		echo ("<body bgcolor='#ffffff' leftmargin=0 topmargin=0 onload='capture()'>\n");
		echo ("<fieldset>\n");
		echo ("<legend>컬러선택</legend>\n");
		echo ("<table width=256 height=130 border=0 cellpadding=0 cellspacing=1>\n");
		echo ("<tr>\n");
		echo ("<td>\n");
		echo ("<div id=wheel style='margin-top:4;cursor:hand' onclick='choice();'>\n");
		echo ("<img src='$sysconf[img_image]/colorwheel.jpg' width=256 height=256 border=0>\n");
		echo ("</div>\n");
		echo ("</td>\n");
		echo ("</tr>\n");
		echo ("<tr>\n");
		echo ("<td align='center'>\n");
		echo ("<br>\n");
		
		echo ("<form name='f'>\n");
		echo ("선택된 컬러 : <input type='text' name='t' size=12 class=editbox>\n");
		echo ("</form>\n");
		
		echo ("<table width=100% border=0>\n");
		echo ("<tr><td>&nbsp;\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		
		echo ("</td>\n");
		echo ("</tr>\n");
		echo ("</table>\n");
		echo ("</fieldset>\n");
		echo ("</body>\n");
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new form1color ();

if (! empty ( $field ))
	$inst->session->setsess ( "field", $field );
$inst->field = $inst->session->getsess ( "field" );

// 모드설정
$inst->mode ( $_REQUEST );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->htmlheader ( false );
		$inst->colorform ();
		$inst->htmlbottom ( false );
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>
