<?
/************************************************************************\
 * 프로그램명 : 스킨파일(자료실)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/03
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1pds extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf;
		
		$envconf ["titlebkcol"] = ""; // 제목 헤더 배경색
		$envconf ["titletxtcol"] = "#000000"; // 제목 헤더 글자색
		
		$envconf ["listheadbkcol"] = "pink"; // 목록 헤더 배경색
		$envconf ["listheadtxtcol"] = "#000000"; // 목록 헤더 글자색
		$envconf ["listbkcolodd"] = "pink"; // 목록 홀수 배경색
		$envconf ["listtxtcolodd"] = "#000000"; // 목록 홀수 글자색
		$envconf ["listbkcoleven"] = "pink"; // 목록 짝수 배경색
		$envconf ["listtxtcoleven"] = "#000000"; // 목록 짝수 글자색
		
		$envconf ["dataheadbkcol"] = "pink"; // 본문 제목 배경색
		$envconf ["dataheadtxtcol"] = "#000000"; // 본문 제목 글자색
		$envconf ["databkcol"] = ""; // 본문 내용 배경색
		$envconf ["datatxtcol"] = "#000000"; // 본문 내용 글자색
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
		// 홀짝줄에 대한 색상
		$listbkcol = array (
				$envconf ["listbkcolodd"],
				$envconf ["listbkcoleven"] 
		);
		$listtxtcol = array (
				$envconf ["listtxtcolodd"],
				$envconf ["listtxtcoleven"] 
		);
		
		// 글 출력 시작
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		foreach ( $index as $key => $data ) {
			extract ( $data );
			$num --;
			
			$bg = ($num & 1);
			
			// 본문내용 파일 읽기
			$content = $this->obj->loadcontentfile ( $data, $db, $row );
			
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
			
			echo ("\n<!-- $key -->\n");
			echo ("<tr align=center>\n");
			
			// 파일 아이콘
			if (intval ( $envconf ["fileicon"] )) {
				$attach_name = is_array ( $attachfile ) && isset ( $attachfile [0] ["name"] ) ? $attachfile [0] ["name"] : "";
				$attach_size = is_array ( $attachfile ) && isset ( $attachfile [0] ["size"] ) ? $attachfile [0] ["size"] : 0;
				
				// 파일타입에 해당되는 아이콘을 구한다.
				$fileicon = getfileicon ( $attach_name );
				$attach = empty ( $attach_size ) ? "" : sprintf ( "%s(%sbyte)", $attach_name, bytesize ( $attach_size ) );
				
				echo ("\n<!-- file download link -->\n");
				echo ("<td nowrap bgcolor='$listbkcol[$bg]'>\n");
				
				print_image ( $fileicon, $sysconf [type_image], $attach );
				echo ("</td>\n");
			} // endif
			  
			// 목록 제목
			echo ("<td nowrap bgcolor='$listbkcol[$bg]' width='75%' align='left'>\n");
			
			// 현재 글밑으로 답변글이 존재하는지 검사한다.
			// thread depth만큼 공백을 준다.
			$width = $depth * 10;
			echo ("<img src='$sysconf[icon_image]/blank.gif' width='$width' height='16' border='0'>\n");
			
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			
			echo ("\n<!-- remotewindow link -->\n");
			echo ("<a href=\"javascript:remotewindow('$this->prog?db=$db&mode=remote&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw')\">\n");
			echo ("<font color='$listtxtcol[$bg]'><b>$subject</b></font></a></td>\n");
			
			// 이름이 긴경우 줄여서 표시
			$name = kstrcut ( $name, $envconf ["short_name"] );
			
			// 작성자(이메일)
			$name = is_email ( $email ) ? $name . "&nbsp(" . print_email ( $email ) . ")" : $name;
			echo ("<td nowrap bgcolor='$listbkcol[$bg]' width='9%'><font color='$listtxtcol[$bg]'>$name</font></td>\n");
			
			// 글 작성날짜
			$in_date = date ( longdateformat, $in_time );
			echo ("<td nowrap bgcolor='$listbkcol[$bg]' width='9%'><font color='$listtxtcol[$bg]'>$in_date</font></td>\n");
			
			// 다운수
			echo ("<td nowrap bgcolor='$listbkcol[$bg]' width='6%'><font color='$listtxtcol[$bg]'></font></td>\n");
			
			echo ("<td nowrap bgcolor='$listbkcol[$bg]'>\n");
			// 글수정
			echo ("<font color='$listtxtcol[$bg]'>\n");
			echo ("<a href='$this->prog?db=$db&mode=modify&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[modify]</a>\n");
			// 글삭제
			echo ("<a href='$this->prog?db=$db&mode=delete&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[delete]</a>\n");
			echo ("</font></td>\n");
			
			echo ("</tr>\n");
			
			// 내용보이기
			echo ("<tr align='left' bgcolor='$envconf[databkcol]'>\n");
			echo ("<td colspan='6'>\n");
			
			// 본문내용
			echo ("<table width='100%' bgcolor='$envconf[databkcol]'><tr><td><font color='$envconf[datatxtcol]'>$content</font></td></tr></table>\n");
			
			echo ("<div align=right class=small>\n");
			// homeurl
			if (intval ( $envconf ["show_homepage"] ) && is_home ( $homeurl ))
				echo ("$label[homeurl]:" . hyperlink ( $homeurl ) . "&nbsp;<br>\n");
				
				// ip,domain
			if (intval ( $envconf ["show_ip"] ) && $ip)
				echo ("$label[ip]:<font color=deeppink>$ip</font>&nbsp;<br>\n");
			
			echo ("<a href='#top'><b>▲up</b></a>&nbsp;</div>\n");
			echo ("</td></tr>\n");
			
			// 수평 분리선
			echo ("<tr align=left bgcolor='$envconf[databkcol]'>\n");
			echo ("<td colspan=6>\n");
			echo ("<hr size=1 noshade>\n");
			echo ("</td></tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
}
?>
