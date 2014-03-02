<?php
/************************************************************************\
 * 프로그램명 : 게시판(기본스킨)
 * 특기사항   : 1.그라데이션 기능(2003.04.01)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1list.php');
/**
 * **********************************************************************\
 * [].스킨을 사용한다면,스킨을 읽어들이고,환경설정을 재초기화 한다.
 * \***********************************************************************
 */
class testboard {
	// 스킨을 사용한다면,스킨을 읽어들이고,환경설정을 재초기화 한다.
	static function &skins($classname) {
		global $sysconf, $envconf;
		
		$skin_file = path_fix ( "$sysconf[path_skins]/${classname}/${classname}.php" );
		
		if (intval ( $envconf ["useskin"] ) && is_readable ( $skin_file ))
			require_once ($skin_file); // or die("skin file open");
		
		if (class_exists ( $classname ))
			$obj = & new $classname ();
		else
			$obj = & new form1board ();
		
		return $obj;
	}
}
/**
 * **********************************************************************\
 * [].게시판(기본스킨)
 * \***********************************************************************
 */
class form1board extends form1list {
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $label;
		
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
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		// //////////////////라벨시작////////////////////
		// 선택글 모두보기 기능(2003.04.01)
		if (intval ( $envconf ["show_checkbox"] ))
			echo ("<th width=1%><font color='$envconf[listheadtxtcol]'><a href='javascript:view_all()'>$label[checkbox]</a></font></th>\n");
			
			// 목록번호
		if (intval ( $envconf ["show_docnum"] ))
			echo ("<th width=5%><font color='$envconf[listheadtxtcol]'>$label[no]</font></th>\n");
			
			// 파일 아이콘
		if (intval ( $envconf ["fileicon"] ))
			echo ("<th width=1%><font color='$envconf[listheadtxtcol]'>$label[fileicon]</font></th>\n");
			
			// 목록제목
		echo ("<th width=56%><font color='$envconf[listheadtxtcol]'>$label[subject]</font></th>\n");
		
		// 이름
		if (intval ( $envconf ["show_name"] ))
			echo ("<th width=10%><font color='$envconf[listheadtxtcol]'>$label[name]</font></th>\n");
			
			// 등록일자
		if (intval ( $envconf ["show_indate"] ))
			echo ("<th width=8%><font color='$envconf[listheadtxtcol]'>$label[in_date]</font></th>\n");
			
			// 수정일자
		if (intval ( $envconf ["show_modate"] ))
			echo ("<th width=8%><font color='$envconf[listheadtxtcol]'>$label[mo_date]</font></th>\n");
			
			// 조회수
		if (intval ( $envconf ["readcount"] ))
			echo ("<th width=7%><font color='$envconf[listheadtxtcol]'>$label[readcount]</font></th>\n");
			
			// //////////////////라벨끝////////////////////
		echo ("</tr>\n");
		
		// 선택글 모두보기
		echo ("\n<!-- list design -->\n");
		echo ("<form name='list' method='post' action='$this->prog'>\n");
		echo ("<input type='hidden' name='db'      value='$db'>\n");
		echo ("<input type='hidden' name='id'      value='$this->id'>\n");
		echo ("<input type='hidden' name='page'    value='$this->page'>\n");
		echo ("<input type='hidden' name='ff'      value='$this->ff'>\n");
		echo ("<input type='hidden' name='fw'      value='$this->fw'>\n");
		echo ("<input type='hidden' name='fn'      value='$this->fn'>\n");
		echo ("<input type='hidden' name='sid'     value='$this->sid'>\n");
		echo ("<input type='hidden' name='selected'>\n");
		echo ("<input type='hidden' name='mode'>\n");
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
		// 목록시작
		for(reset ( $index ), $ii = 0; list ( $key, $data ) = each ( $index );) {
			extract ( $data );
			$num --;
			
			echo ("\n<!-- $key -->\n");
			$bg = ($num & 1); // 다른방법으로 한번 해봄
			                  
			// 그라데이션 기능(수정)
			if (intval ( $envconf ["usegrad"] ))
				$listbkcol [$bg] = lightgradation ( $envconf ["gradcolor"], $ii ++, 8 );
			
			echo ("<tr align=center bgcolor='$listbkcol[$bg]'>\n");
			
			// 선택글 모두보기 기능(2003.04.01)
			if (intval ( $envconf ["show_checkbox"] )) {
				// 비밀문서인경우 해당외(2003.04.01)
				if ($privatetype)
					echo ("<td></td>\n");
				else
					echo ("<td><input type=checkbox name=checkbox value='$key'></td>\n");
			} // endif
			  
			// 목록 번호
			$docnum = ($this->head2index ( $this->note, $key ) >= 0) ? "공지" : $num; // 공지사항일때
			
			if (intval ( $envconf ["show_docnum"] ))
				echo ("<td><font color='$listtxtcol[$bg]'>$docnum</font></td>\n");
				
				// 파일 아이콘을 출력한다.
			if (intval ( $envconf ["fileicon"] )) {
				// 파일타입에 해당되는 아이콘을 구한다.
				$attach_name = is_array ( $attachfile ) && isset ( $attachfile [0] ["name"] ) ? $attachfile [0] ["name"] : "";
				$attach_size = is_array ( $attachfile ) && isset ( $attachfile [0] ["size"] ) ? $attachfile [0] ["size"] : 0;
				
				$fileicon = getfileicon ( $attach_name );
				$attach = empty ( $attach_size ) ? "" : sprintf ( "%s(%sbyte)", $attach_name, bytesize ( $attach_size ) );
				
				echo ("<td nowrap bgcolor='$listbkcol[$bg]'>\n");
				print_image ( $fileicon, $sysconf ["type_image"], $attach );
				echo ("</td>\n");
			} // endif
			  
			// thread depth 만큼 공백을 준다.
			$space = $depth * 10;
			
			// 폴더 아이콘
			$foldericon = getfoldericon ( $data, $this->id == $key );
			
			// 제목이 긴경우 줄여서 표시
			$subject = utf8_strcut ( $subject, $envconf ["short_subject"] );
			
			// 이름이 긴경우 줄여서 표시
			$name = utf8_strcut ( $name, $envconf ["short_name"] );
			
			// 글 작성날짜
			$in_date = date ( shortdateformat, $in_time );
			$mo_date = date ( shortdateformat, $mo_time );
			
			// 검색어에 해당하는 글자를 빨간색으로 바꾸어줌(2003.04.12)(임시)
			if ($this->ff && $this->fw)
				${$this->ff} = preg_replace ( "/$this->fw/i", "<font style='background-color:#ffff00'><b>$this->fw</b></font>", ${$this->ff} );
				
				// //////////////////제목시작////////////////////
			echo ("<td align=left>\n");
			// depth에 의한 들임값을 정함
			echo ("<img src='$sysconf[icon_image]/blank.gif' width='$space' height='16' border='0'>\n");
			
			// 폴더 아이콘을 출력한다.
			if (intval ( $envconf ["foldericon"] ))
				print_image ( $foldericon, $sysconf ["icon_image"] );
			
			echo ("<font color='$listtxtcol[$bg]'>\n");
			print_link ( "$this->prog?db=$db&mode=read&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw", $subject, '', "title='$num 번 내용 보기'" );
			print_image ( getstaticon ( $data ), $sysconf ["icon_image"] );
			echo ("</font></td>\n");
			
			// //////////////////제목끝////////////////////
			
			// 이름
			if (intval ( $envconf ["show_name"] ))
				echo ("<td><font color='$listtxtcol[$bg]'>$name</font></td>\n");
				
				// 글 작성일자
			if (intval ( $envconf ["show_indate"] ))
				echo ("<td><font color='$listtxtcol[$bg]'>$in_date</font></td>\n");
				
				// 글 수정일자
			if (intval ( $envconf ["show_modate"] ))
				echo ("<td><font color='$listtxtcol[$bg]'>$mo_date</font></td>\n");
				
				// 조회수
			if (intval ( $envconf ["readcount"] ))
				echo ("<td><font color='$listtxtcol[$bg]'>$readcount</font></td>\n");
			
			echo ("</tr>\n");
		} // end for
		
		echo ("</form>\n");
		echo ("</table>\n");
	}
	
	// 본문 보이기
	function showdata($db, $id) {
		global $sysconf, $envconf, $menu, $label, $step;
		
		// 글 파일 읽기
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
			
			// 임시
		if (empty ( $data ))
			return;
			
			// 본문내용 파일 읽기
		$data ["content"] = $this->obj->loadcontentfile ( $data, $db, $id );
		
		extract ( $data );
		
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
		  
		// 본문 시작
		echo ("\n<!-- showdata design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		
		// 제목
		echo ("<tr align='center' bgcolor='$envconf[dataheadbkcol]' height='24'><td>\n");
		echo ("<font color='$envconf[dataheadtxtcol]'><b>$subject</b></font>\n");
		echo ("</td></tr>\n");
		
		// 글 번호,작성 날짜
		$in_date = date ( longdateformat, $in_time );
		echo ("<tr align=left bgcolor='$envconf[databkcol]'><td>\n");
		echo ("$label[id]:<font color='$envconf[datatxtcol]'>$id</font>\n");
		echo ("&nbsp;(<font color='deeppink'>$in_date</font>)\n");
		echo ("</td></tr>\n");
		
		// 작성자
		$name = is_email ( $email ) ? $name . "&nbsp(" . print_email ( $email ) . ")" : $name;
		
		echo ("<tr align=left bgcolor='$envconf[databkcol]'><td>$label[name]:$name\n");
		echo ("</td></tr>\n");
		
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
			
		// 내용 보이기
		echo ("<tr align=left bgcolor='$envconf[databkcol]'><td>\n");
		
		// 본문내용
		echo ("<table width='100%' bgcolor='$envconf[databkcol]'><tr><td><font color='$envconf[datatxtcol]'>$content</font></td></tr></table>\n");
		
		// 첨부이미지파일(2004.03.10)
		if (intval ( $envconf ["attachfile"] ) && is_array ( $attachfile )) {
			foreach ( $attachfile as $key => $value ) {
				// 파일이름이 한글인 경우 오류발생(2001.12.21)
				$image = urlencode ( $value ["name"] );
				if (is_image ( $image ))
					print_image ( $image, $sysconf ["db_image"], $value ["name"] );
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
		// //////////////////의견시작////////////////////
		// 의견달기 기능(2001.09.06)
		if (intval ( $envconf ["show_opinion"] )) {
			echo ("<tr><td>\n");
			echo ("\n<!-- showcomment design -->\n");
			echo ("<form name='show_opinion' method='post' action='$this->prog' autocomplete='off'>\n");
			echo ("<input type='hidden' name='db'   value='$db'>\n");
			echo ("<input type='hidden' name='cmd'  value='$this->mode'>\n");
			echo ("<input type='hidden' name='page' value='$this->page'>\n");
			echo ("<input type='hidden' name='id'   value='$id'>\n");
			echo ("<input type='hidden' name='selected'   value='$this->selected'>\n");
			echo ("<input type='hidden' name='mode' value='opinion'>\n");
			echo ("<input type='text' name=name value='$label[name]' size='10' onblur='onexit(this)' onfocus='onenter(this)' class=editbox>\n");
			echo ("<input type='text' name=opinion value='$label[opinion]' size='30'  maxlength='80' onblur='onexit(this)' onfocus='onenter(this)' class=editbox>\n");
			/**
			 * **********************************************************************\
			 * $this->showinput('text','name',$label[name],10,20);
			 * $this->showinput('text','opinion',$label[opinion],30,80);
			 * \***********************************************************************
			 */
			echo ("<input type='submit' value='$step[opinion]' class='button'>\n");
			echo ("<font class=hint>html $menu[disable].</font>\n");
			
			echo ("</td></tr>\n");
			echo ("</form>\n");
		} // endif
		  // //////////////////의견끝////////////////////
		echo ("</table>\n");
		
		// 의견달기 파일분리(2002.02.28)
		$opinion = $this->obj->loadopinionfile ( $db, $id );
		$opinion = nl2br ( $opinion );
		echo ("\n<!-- show_opinion design -->\n");
		echo ("<table width='100%' border='$envconf[tableborder]' cellspacing='$envconf[cellspacing]' cellpadding='$envconf[cellpadding]'>\n");
		echo ("<tr><td>\n");
		echo ("<font class=hint>\n");
		echo ("<table>$opinion</table>");
		echo ("</font>\n");
		echo ("</td></tr>\n");
		echo ("</table>\n");
	}
}
?>
