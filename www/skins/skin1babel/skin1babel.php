<?
/************************************************************************\
 * 프로그램명 : 스킨파일(방명록)
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
class skin1babel extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf;
		
		$envconf ["attachfile"] = 0; // 첨부파일 가능여부
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
		
		// 목록 출력하기
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		foreach ( $index as $key => $data ) {
			extract ( $data );
			$num --;
			
			$bg = ($num & 1);
			
			// 본문내용 파일 읽기
			$content = $this->obj->loadcontentfile ( $data, $db, $key );
			
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
			echo ("<tr>\n");
			
			echo ("<td bgcolor='$envconf[listheadbkcol]' align='center' width='70' class='babel'>\n");
			// 글수정
			echo ("<a href='$this->prog?db=$db&mode=modify&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[modify]</a>\n");
			// 글삭제
			echo ("<a href='$this->prog?db=$db&mode=delete&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>$btn[delete]</a>\n");
			echo ("</td>\n");
			
			// date
			$in_date = date ( longdateformat, $in_time );
			echo ("<td bgcolor='$listbkcol[$bg]' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$in_date</font>&nbsp;\n");
			
			// ip,domain
			if (intval ( $envconf ["show_ip"] ) && $ip) {
				echo ("<font color='$listtxtcol[$bg]'>$label[ip]:</font>\n");
				echo ("<a href='http://ipwhois.nic.or.kr/servlet/whois.ipwhois?ip_qtype=ipno&ip_qword=$ip' target='new'>$ip</a>\n");
			} // endif
			
			echo ("</td></tr>\n");
			
			// name,email
			echo ("<tr>\n");
			echo ("<td bgcolor='$envconf[listheadbkcol]' align='center' width='70' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$label[name]:</font></td>\n");
			
			// 이름이 긴경우 줄여서 표시
			$name = kstrcut ( $name, $envconf ["short_name"] );
			echo ("<td bgcolor='$listbkcol[$bg]' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$name</font>\n");
			
			// email
			if (is_email ( $email ))
				echo print_email ( $email );
			
			echo ("</td></tr>\n");
			
			// home
			echo ("<tr>\n");
			echo ("<td bgcolor='$envconf[listheadbkcol]' align='center' width='70' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$label[homeurl]:</font></td>\n");
			echo ("<td bgcolor='$listbkcol[$bg]' class='babel'>\n");
			
			if (intval ( $envconf ["show_homepage"] ) && is_home ( $homeurl ))
				echo hyperlink ( $homeurl );
			
			echo ("&nbsp;</td>\n");
			echo ("</tr>\n");
			
			// 본문내용
			echo ("<tr>\n");
			echo ("<td bgcolor='$envconf[listheadbkcol]' align='center' width='70' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$label[content]:</font></td>\n");
			echo ("<td bgcolor='$listbkcol[$bg]' class='babel'>\n");
			echo ("<table width='100%' bgcolor='$listbkcol[$bg]'><tr><td><font color='$listtxtcol[$bg]'>$content</font></td></tr></table>\n");
			
			// 글 목록 번호
			echo ("<p align=right class=small>no. $num &nbsp;</p>\n");
			
			echo ("</td></tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
}
?>
