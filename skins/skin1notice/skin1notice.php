<?
/************************************************************************\
 * 프로그램명 : 스킨파일(공지사항)
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
class skin1notice extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		// 스킨에서 사용한 이미지(image)파일의 상대위치
		$sysconf ["skin_image"] = ("$sysconf[home_skins]/" . get_class ( $this ));
		
		$envconf ["tablewidth"] = "300"; // 게시판 너비
		$envconf ["cellspacing"] = 0; // 셀 간격
		$envconf ["cellpadding"] = 2;
		
		$envconf ["show_list"] = 0; // 본문 밑으로 문서목록 보이기
		$envconf ["show_findmenu"] = 0; // 검색메뉴 보이기
		
		$envconf ["listbuttonpos"] = 0; // 목록 버튼 위치
		$envconf ["contbuttonpos"] = 0; // 본문 버튼 위치
		$envconf ["access_write"] = 1; // 글 쓰기권한
		
		$envconf ["attachfile"] = 0; // 첨부파일 가능여부
		
		$btn ["blank"] = "&nbsp;&nbsp;";
		$btn ["write"] = "<img src='$sysconf[skin_image]/notice_bg1.gif' border=0 alt='글쓰기'>";
		$btn ["admin"] = "<img src='$sysconf[skin_image]/notice_bg2.gif' border=0 alt='관리자'>";
	}
	
	// 전체 목록 보이기
	function testboardlist() {
		global $envconf;
		
		$this->htmlheader ();
		$this->showlist ( $this->db, $this->data );
		$this->showpagemenu ( $this->db );
		$this->htmlbottom ();
	}
	
	// 본문 보이기
	function testboardremote() {
		$this->htmlheader ();
		$this->showdata ( $this->db, $this->id );
		$this->htmlbottom ();
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
		// 글 출력 시작
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr>\n");
		
		// 관리자
		echo ("<td align=left nowrap>\n");
		echo ("<a href=\"javascript:remotewindow('$sysconf[testadmin]?db=$db&mode=config')\">$btn[admin]</a>\n");
		echo ("</td>\n");
		
		// 글쓰기 버튼
		echo ("<td align=right nowrap>\n");
		echo ("<a href=\"javascript:remotewindow('$this->prog?db=$db&mode=write')\">$btn[write]</a>&nbsp;&nbsp;\n");
		echo ("</td>\n");
		
		echo ("</tr>\n");
		echo ("</table>\n");
		
		// 목록시작
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		foreach ( $index as $key => $data ) {
			extract ( $data );
			$num --;
			
			$bg = ($num & 1);
			
			echo ("\n<!-- $key -->\n");
			echo ("<tr onmouseover=\"this.style.background='lavender'\" onmouseout=\"this.style.background=''\">\n");
			
			// 목록 제목
			echo ("<td align=left nowrap>\n");
			
			// 현재 글밑으로 답변글이 존재하는지 검사한다.
			// thread depth만큼 공백을 준다.
			$width = $depth * 10;
			echo ("<img src='$sysconf[icon_image]/blank.gif' width='$width' height='16' border='0'>\n");
			
			// 적당한 아이콘을 출력한다.
			if (intval ( $envconf ["foldericon"] )) {
				$foldericon = getfoldericon ( $data );
				echo ("<img src='$sysconf[icon_image]/$foldericon' border=0>\n");
			} // endif
			  
			// 타이틀 출력
			$title = "$label[readcount]:$readcount";
			
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			
			echo ("<font color='$envconf[datatxtcol]'>\n");
			echo ("<a href=javascript:remotewindow('$this->prog?db=$db&mode=remote&page=$this->page&id=$key&ff=$this->ff&fw=$this->fw') title='$title'>$subject</a>\n");
			echo ("</font></td>\n");
			
			echo ("</tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
}
?>
