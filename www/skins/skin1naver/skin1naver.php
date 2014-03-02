<?
/************************************************************************\
 * 프로그램명 : 스킨파일(네이버)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2003/04
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1naver extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		// 스킨에서 사용한 이미지(image)파일의 상대위치
		$sysconf ["skin_image"] = ("$sysconf[home_skins]/" . get_class ( $this ));
		$envconf ["cellspacing"] = "1";
		
		$envconf ["listheadbkcol"] = "#8caed6"; // 목록 헤더 배경색
		$envconf ["listheadtxtcol"] = "#ffffff"; // 목록 헤더 글자색
		
		$envconf ["listbkcolodd"] = "#f6f6f6"; // 목록 홀수 배경색
		$envconf ["listtxtcolodd"] = "#000000"; // 목록 홀수 글자색
		$envconf ["listbkcoleven"] = "#fcfcfc"; // 목록 짝수 배경색
		$envconf ["listtxtcoleven"] = "#000000"; // 목록 짝수 글자색
		
		$envconf ["dataheadbkcol"] = "#8caed6"; // 본문 제목 배경색
		$envconf ["dataheadtxtcol"] = "#ffffff"; // 본문 제목 글자색
		$envconf ["databkcol"] = "#ffffff"; // 본문 내용 배경색
		$envconf ["datatxtcol"] = "#000000"; // 본문 내용 글자색
		
		$btn ["write"] = "<img src='$sysconf[skin_image]/write.gif' border=0>";
		$btn ["list"] = "<img src='$sysconf[skin_image]/list.gif' border=0>";
		$btn ["prev"] = "<img src='$sysconf[skin_image]/prev.gif' border=0>";
		$btn ["next"] = "<img src='$sysconf[skin_image]/next.gif' border=0>";
		$btn ["blank"] = "<testboard>";
		$btn ["home"] = "<testboard>";
		$btn ["help"] = "<testboard>";
		$btn ["admin"] = "<img src='$sysconf[skin_image]/boardkey.gif' border=0>";
		$btn ["find"] = "<img src='$sysconf[skin_image]/search.gif' border=0>";
		$btn ["modify"] = "<img src='$sysconf[skin_image]/modify.gif' border=0>";
		$btn ["reply"] = "<img src='$sysconf[skin_image]/reply.gif' border=0>";
		$btn ["delete"] = "<img src='$sysconf[skin_image]/delete.gif' border=0>";
	}
	
	// 본문 보이기
	function showdata($db, $id) {
		global $sysconf, $envconf, $menu, $label;
		
		// 글 파일 읽기
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
			
			// 임시
		if (empty ( $data ))
			return;
		
		extract ( $data );
		
		// 본문내용 파일 읽기
		$content = $this->obj->loadcontentfile ( $data, $db, $id );
		
		// htmltype에 따라
		switch (intval ( $htmltype )) {
			case 0 :
				// 특수문자들을 변환
				$content = htmlspecialchars ( trim ( $content ) );
				// 본문내에 url이 있으면 자동 인식
				if (intval ( $envconf ["autolink"] ))
					$content = hyperlink ( $content );
					// cr/lf 처리 옵션
				if (intval ( $envconf ["entertype"] ))
					$content = nl2br ( $content );
				break;
			
			case 1 :
				break;
			
			case 2 :
				$content = nl2br ( $content );
				break;
		} // end switch
		  
		// 작성자
		$name = is_email ( $email ) ? $name . "&nbsp(" . print_email ( $email ) . ")" : $name;
		$in_date = date ( longdateformat, $in_time );
		
		// 본문 시작
		echo ("\n<!-- showdata design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr><td width=100% bgcolor='$envconf[dataheadbkcol]'>\n");
		
		echo ("<table width=100% cellpadding=5 cellspacing=1>\n");
		
		echo ("      <tr align=center bgcolor='$envconf[dataheadbkcol]'>\n");
		echo ("      <td>\n");
		echo ("        <table width=100% cellspacing=2 cellpadding=2>\n");
		echo ("        <tr>\n");
		echo ("        <td width=15% bgcolor='$envconf[dataheadbkcol]' nowrap><font size=2 color='$envconf[dataheadtxtcol]'><b> $label[id]: $id </b></font></td>\n");
		echo ("        <td width=25% bgcolor='$envconf[dataheadbkcol]' nowrap><font size=2 color='$envconf[dataheadtxtcol]'><b> $label[name]: $name </b></font></td>\n");
		echo ("        <td width=40% bgcolor='$envconf[dataheadbkcol]' align=center nowrap><font size=2 color='$envconf[dataheadtxtcol]'><b> $label[in_date]: $in_date </b></font></td>\n");
		echo ("        <td width=20% bgcolor='$envconf[dataheadbkcol]' align=right nowrap><font size=2 color='$envconf[dataheadtxtcol]'><b> $label[readcount]: $readcount </b></font></td>\n");
		echo ("        </tr>\n");
		echo ("        </table>\n");
		echo ("      </td>\n");
		echo ("      </tr>\n");
		
		// 제목
		echo ("      <tr>\n");
		echo ("        <td bgcolor='$envconf[databkcol]'><b><font color='$envconf[datatxtcol]'>$subject</font></b></td>\n");
		echo ("      </tr>\n");
		
		// 첨부파일(2004.03.10)
		if (intval ( $envconf ["attachfile"] ) && is_array ( $attachfile )) {
			
			echo ("\n<!-- file download link -->\n");
			echo ("<tr align=left bgcolor='$envconf[databkcol]'>\n");
			echo ("<td>$label[attachfile]:\n");
			
			foreach ( $attachfile as $key => $value ) {
				
				// 파일타입에 해당하는 아이콘을 구한다.
				$fileicon = getfileicon ( $value ["name"] );
				$attach = empty ( $value ["size"] ) ? "" : sprintf ( "%s (%sbyte)", $value ["name"], bytesize ( $value ["size"] ) );
				
				print_link ( "$sysconf[path_home]/$this->prog?db=$db&mode=down&id=$id&fn=$key&page=$this->page&ff=$this->ff&fw=$this->fw", make_image ( $fileicon, $sysconf ["type_image"], $attach ) . "&nbsp;$attach&nbsp;" );
			} // end foreach
			
			echo ("</td></tr>\n");
		} // endif
		  
		// //////////////////검색어구분시작////////////////////
		  // 검색어에 해당하는 글자를 빨간색으로 바꾸어줌(임시)
		if ($this->ff && $this->fw)
			${$this->ff} = preg_replace ( "/$this->fw/i", "<font style='background-color:#ffff00'><b>$this->fw</b></font>", ${$this->ff} );
			// //////////////////검색어구분끝////////////////////
		
		echo ("<tr align=left bgcolor='$envconf[databkcol]'><td>\n");
		
		// //////////////////본문내용////////////////////
		echo ("<table width='100%' bgcolor='$envconf[databkcol]'><tr><td><font color='$envconf[datatxtcol]'>$content</font></td></tr></table>\n");
		
		// 첨부이미지파일(2004.03.10)
		if (intval ( $envconf ["attachfile"] ) && is_array ( $attachfile )) {
			foreach ( $attachfile as $key => $value ) {
				// 파일이름이 한글인경우 오류발생(2001.12.21)
				$image = urlencode ( $value ["name"] );
				if (is_image ( $image ))
					print_image ( $image, $sysconf ["home_image"], $value ["name"] );
				echo ("<p>\n");
			} // end foreach
		} // endif
		
		echo ("<div align=right class=small>\n");
		// homeurl
		if (intval ( $envconf ["show_homepage"] ) && is_home ( $homeurl ))
			echo ("$label[homeurl]:" . hyperlink ( $homeurl ) . "&nbsp;<br>\n");
			
			// ip,domain
		if (intval ( $envconf ["show_ip"] ) && $ip)
			echo ("$label[ip]:<font color=deeppink>$ip</font>&nbsp;<br>\n");
		
		echo ("<a href='#top'><b>▲up</b></a>&nbsp;</div>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
		// //////////////////본문내용////////////////////
		echo ("</table>\n");
	}
}
?>
