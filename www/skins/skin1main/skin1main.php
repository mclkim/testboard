<?
/************************************************************************\
 * 프로그램명 : 스킨파일(메인)
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
class skin1main extends form1board {
	var $dan = 2;
	var $dan_width = "50%"; // 단의 넓이
	                        
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		$envconf ["show_list"] = 0; // 본문 밑으로 문서목록 보이기
		$envconf ["show_findmenu"] = 0; // 검색메뉴 보이기
		$envconf ["show_sortmenu"] = 0;
		$envconf ["musthome"] = 1; // 홈페이지 필수입력(db명 입력)
		
		$envconf ["listbuttonpos"] = 0; // 목록 버튼 위치
		$envconf ["contbuttonpos"] = 0; // 본문 버튼 위치
		$envconf ["access_write"] = 1; // 글 쓰기권한
		
		$envconf ["attachfile"] = 0; // 첨부파일 가능여부
		
		$btn ["blank"] = "";
		$btn ["back"] = "<b>back</b>";
		$btn ["write"] = "<b>write</b>";
		$btn ["list"] = "<b>list</b>";
		$btn ["admin"] = "<b>admin</b>";
		$btn ["reply"] = "<tesboard>";
		$btn ["delete"] = "<tesboard>";
		$btn ["modify"] = "<tesboard>";
		$btn ["prev"] = "<tesboard>";
		$btn ["next"] = "<tesboard>";
		$btn ["forward"] = "<tesboard>";
		$btn ["print"] = "<tesboard>";
		$btn ["close"] = "<b>close</b>";
		
		$this->dan = max ( $this->dan, $envconf ["dan_size"] ); // 단의 갯수
		$this->dan_width = round ( 100 / $this->dan ) . '%'; // 단의 넓이
	}
	
	// 전체 목록 보이기
	function testboardlist() {
		$this->htmlheader ();
		$this->userheader ();
		$this->columnheader ();
		
		$this->showlist ( $this->db, $this->data );
		
		$this->columnbottom ();
		$this->userbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 보이기
	function testboardremote() {
		$this->htmlheader ();
		$this->showpage ();
		$this->columnheader ();
		
		$this->showdata ( $this->db, $this->data );
		$this->showpagemenu ( $this->db );
		$this->showdatabutton ( $this->db, $this->id );
		
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 입력하기
	function testboardinput() {
		$this->htmlheader ();
		$this->columnheader ();
		$this->inputform ();
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 메인목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn;
		
		// 목록 출력하기
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 단 나누기
		for($i = 0; $i < $this->dan; $i ++)
			echo ("<col width='$this->dan_width'></col>\n");
			
			// 앞공백
		echo "<tr>\n";
		for($i = 0, $ii = 0; $i < $ii; $i ++)
			echo ("<td bgcolor='eeeeee' class='babel'>&nbsp;</td>\n");
			
			// 단그리기
		foreach ( $index as $key => $data ) {
			extract ( $data );
			
			// 본문내용 파일 읽기
			$content = $this->obj->loadcontentfile ( $data, $db, $key );
			
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			
			// 단설정
			echo ("\n<!-- $key -->\n");
			if ($ii != 0 && $ii % $this->dan == 0)
				echo "<tr>\n";
			
			echo ("<td valign='top' class='babel'>\n");
			// //////////////////단시작////////////////////
			echo ("<table width=100% border=0 cellspacing=0 cellpadding=0>\n");
			echo ("<tr valign='top'>\n");
			echo ("<td>\n");
			
			// htmltype에 따라
			switch (intval ( $htmltype )) {
				case 0 :
					// 테이블설정
					if (! is_home ( $homeurl ))
						$t_db = basename ( $homeurl );
						
						// 제목을 클릭하면..
					echo ("\t<table width='100%' bgcolor='$envconf[listheadbkcol]' border=0 cellspacing=1 cellpadding=4>\n");
					echo ("\t<td align=left><font color='$envconf[listheadtxtcol]'>\n");
					echo ("\t<a href='$this->prog?db=$t_db&mode=list'>&nbsp;▒&nbsp;<b>$subject</b></a>\n");
					echo ("\t</font></td>\n");
					echo ("\t</table>\n");
					
					$t_index = $this->obj->loadindexfile ( $t_db );
					
					$index = array_chunk ( $t_index, $this->pageline, true );
					$index = $index [$this->current_page - 1];
					
					$t_data = $this->obj->loaddatalist ( $t_db, $index );
					$this->showlocallist ( $t_db, $t_data );
					
					break;
				case 2 :
					$content = nl2br ( $content );
				case 1 :
					echo ("<table width='100%' bgcolor='$envconf[databkcol]'><tr><td><font color='$envconf[datatxtcol]'>$content</font></td></tr></table>\n");
					break;
			} // end switch
			echo ("</td></tr></table>\n");
			// //////////////////단끝////////////////////
			echo ("</td>\n");
			
			// 단설정
			$ii ++;
			if ($ii != 0 && $ii % $this->dan == 0)
				echo "</tr>\n";
		} // end for
		  
		// 뒷공백
		for($i = $ii; $i % $this->dan != 0; $i ++)
			echo ("<td bgcolor='eeeeee' class='babel'>&nbsp;</td>\n");
		
		echo "</tr>\n";
		echo ("</table>\n");
		
		echo ("\n<!-- showlist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr>\n");
		
		echo ("<td align=right nowrap>\n");
		// 글쓰기
		echo ("<a href=\"javascript:remotewindow('$this->prog?db=$db&mode=remote')\">$btn[list]</a>\n");
		// 관리자
		echo ("<a href=\"javascript:remotewindow('$sysconf[testadmin]?db=$db&mode=config')\">$btn[admin]</a>\n");
		echo ("</td>\n");
		echo ("</tr>\n");
		echo ("</table>\n");
	}
	
	// 서브목록 보이기
	function showlocallist($db, $index) {
		global $sysconf, $envconf, $label;
		
		// 글 출력 시작
		echo ("\n<!-- showlocallist design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 목록시작
		foreach ( $index as $key => $data ) {
			extract ( $data );
			
			echo ("\n<!--* $key *-->\n");
			echo ("<tr onmouseover=this.style.background='lavender' onmouseout=this.style.background=''>\n");
			
			// 목록 제목
			echo ("<td align=left>\n");
			
			// 현재 글밑으로 답변글이 존재하는지 검사한다.
			// thread depth 만큼 공백을 준다.
			$width = $depth * 10;
			echo ("<img src='$sysconf[icon_image]/blank.gif' width='$width' height='16' border='0'>\n");
			
			// 폴더 아이콘을 출력한다.
			if (intval ( $envconf ["foldericon"] )) {
				$foldericon = getfoldericon ( $data );
				print_image ( $foldericon, $sysconf ["icon_image"] );
			} // endif
			  
			// 글 작성날짜,조회수
			$in_date = date ( shortdateformat, $in_time );
			$mo_date = date ( shortdateformat, $mo_time );
			$title = "$label[in_date]:$in_date,$label[readcount]:$readcount";
			
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			
			echo ("<font color='$envconf[datatxtcol]'>\n");
			echo ("<a href=javascript:remotewindow('$this->prog?db=$db&mode=remote&page=$this->page&id=$key&ff=$this->ff&fw=$this->fw') title='$title'>$subject</a>\n");
			echo ("</font>\n");
			echo ("</td>\n");
			
			echo ("<td align=right>$mo_date</td>\n");
			echo ("</tr>\n");
		} // end for
		
		echo ("</table>\n");
	}
	
	// 본문 보이기
	function showdata($db, $index) {
		global $sysconf, $envconf, $label;
		
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
		echo ("\n<!-- showdata design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 목록시작
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
			echo ("<a href='$this->prog?db=$db&mode=modify&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>edit</a>\n");
			// 글삭제
			echo ("<a href='$this->prog?db=$db&mode=delete&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw'>del</a>\n");
			echo ("</td>\n");
			
			// date
			echo ("<td bgcolor='$listbkcol[$bg]' class='babel'>\n");
			echo ("<font color='$listtxtcol[$bg]'>$in_date</font> &nbsp;\n");
			
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
