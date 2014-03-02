<?php
/************************************************************************\
 * 프로그램명 : 게시판정의
 * 특기사항   : 1.파일의 확장자가 php 등의 첨부파일 방지하기
                2.비공개문서 기능(2001.04.23)
                3.회원외 접속(2001.04.23)
                5.답변글 목록보이기 기능(2002.03.12)
                6.내용을 엑셀로 만들기(예정)
                7.파일명중 공백삭제(2001.10.15)
                8.내용 출력폼 만들기(예정)
                9.의견달기 본문에서 분리(2002.02.28)
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/01
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1new.php');
class form1save extends form1new {
	// 페이지 이동메뉴 출력 갯수
	var $total_data = 0;
	var $total_page = 1;
	var $tabsperpage = 10;
	var $pageline = 13;
	var $current_page = 1;
	function testboardindex($refr = true) {
		global $envconf;
		
		// 한 화면에 출력할 목록 갯수
		if (intval ( $envconf ["pageline"] ))
			$this->pageline = intval ( $envconf ["pageline"] );
			
			// 공지여부기능 추가(2003.04.15)
		$this->note = array ();
		if (intval ( $envconf ["show_note"] ) && $envconf ["notefile"])
			$this->note = explode ( ",", $envconf ["notefile"] );
			
			// 게시판 접속수 읽기
		$this->access = $this->readhitcount ();
		
		$this->total_data = $this->obj->loadtotal ( $this->db, $this->ff, $this->fw );
		$this->total_page = max ( 1, ceil ( $this->total_data / $this->pageline ) );
		$this->current_page = min ( max ( 1, $this->page ), $this->total_page );
		
		if (! $refr = true) {
			$this->data = $this->session->getsess ( "data" );
			return;
		} // end if
		  
		// 조건검색인지?
		if ($this->ff && $this->fw)
			$this->index = $this->obj->findindexfile ( $this->db, $this->ff, $this->fw, $this->total_data, $this->current_page, $this->pageline );
		else
			$this->index = $this->obj->loadindexfile ( $this->db, $this->total_data, $this->current_page, $this->pageline );
			
			// 공지파일이 있을 경우 인덱스파일에서 제외시킨다.
		if (count ( $this->index ) > 0 && count ( $this->note ) > 0)
			$this->index = array_diff ( $this->index, $this->note );
			
			// 공지파일기능 추가(2003.04.15)
		$this->index = array_merge ( $this->note, $this->index );
		
		// 한페이지 게시물
		$index = array_chunk ( $this->index, $this->pageline, true );
		if (count ( $index ) < $this->total_page)
			$index = $index [0];
		else
			$index = $index [$this->current_page - 1];
		
		$this->data = $this->obj->loaddatalist ( $this->db, $index );
		
		$this->session->setsess ( "data", $this->data );
	}
	
	// 전체 목록 보이기
	function testboardlist() {
		global $envconf;
		
		$this->htmlheader ();
		$this->userheader ();
		
		$this->showpage ();
		// todo::
		// $this->loginform();
		$this->columnheader ();
		
		if ($envconf ["listbuttonpos"] & 1)
			$this->showlistbutton ( $this->db );
		$this->showlist ( $this->db, $this->data );
		$this->showpagemenu ( $this->db );
		if ($envconf ["listbuttonpos"] & 2)
			$this->showlistbutton ( $this->db );
		
		$this->columnbottom ();
		$this->userbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 내용 보이기
	function testboardread() {
		global $envconf;
		
		$this->htmlheader ();
		$this->userheader ();
		
		// 본문아래 답글목록(예정)
		if (intval ( $envconf ["show_list"] ) == 2)
			$this->showreplyfile ( $this->db, $this->id );
		
		$this->columnheader ();
		if ($envconf ["contbuttonpos"] & 1)
			$this->showdatabutton ( $this->db, $this->id );
		$this->showdata ( $this->db, $this->id );
		if ($envconf ["contbuttonpos"] & 2)
			$this->showdatabutton ( $this->db, $this->id );
			
			// 본문아래 목록 보이기
		if (intval ( $envconf ["show_list"] )) {
			$this->showpage ();
			if ($envconf ["listbuttonpos"] & 1)
				$this->showlistbutton ( $this->db );
			$this->showlist ( $this->db, $this->data );
			$this->showpagemenu ( $this->db );
			if ($envconf ["listbuttonpos"] & 2)
				$this->showlistbutton ( $this->db );
		} // endif
		
		$this->columnbottom ();
		$this->userbottom ();
		$this->htmlbottom ();
		$this->savehitcount ( $this->db, $this->id );
	}
	
	// 선택글 보이기
	function testboardreadlist($selected) {
		global $envconf;
		
		$this->htmlheader ();
		$this->userheader ();
		$this->columnheader ();
		
		$select_list = explode ( ';', $selected );
		for($ii = count ( $select_list ) - 2; $ii >= 0; $ii --) {
			if ($envconf ["contbuttonpos"] & 1)
				$this->showdatabutton ( $this->db, $select_list [$ii] );
			$this->showdata ( $this->db, $select_list [$ii] );
			if ($envconf ["contbuttonpos"] & 2)
				$this->showdatabutton ( $this->db, $select_list [$ii] );
			$this->htmlhrline ( $envconf [tablewidth] );
			$this->savehitcount ( $this->db, $select_list [$ii] );
		} // end for
		
		$this->columnbottom ();
		$this->userbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 내용 팝업보이기
	function testboardremote() {
		global $envconf;
		
		$envconf ["tablewidth"] = '100%'; // 임시
		
		$this->htmlheader ();
		$this->columnheader ();
		$this->showdata ( $this->db, $this->id );
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 본문 입력하기
	function testboardinput() {
		$this->htmlheader ();
		$this->userheader ();
		$this->columnheader ();
		
		$this->inputform ();
		
		$this->columnbottom ();
		$this->userbottom ();
		$this->htmlbottom ();
	}
	
	// 사용자로그인
	function testboarduserlogin($userid, $cmd) {
		$this->htmlheader ( false );
		$this->userloginform ( $userid, $cmd );
		$this->htmlbottom ( false );
	}
	
	// 관리자로그인
	function testboardadminlogin($userid, $cmd) {
		$this->htmlheader ( false );
		$this->adminloginform ( $userid, $cmd );
		$this->htmlbottom ( false );
	}
	
	// 글 차단 기능(2003.03.13)
	function checkfilter($http_vars) {
		global $envconf;
		
		// 글 차단
		if (intval ( $envconf ["badprotect"] ) == 0)
			return;
			
			// 음란어 차단
		$badwords = explode ( ",", $envconf ["badword"] );
		// remove special characters
		$f_subject = eregi_replace ( "[^a-z]+", "", strip_tags ( $http_vars ["subject"] ) );
		$f_content = eregi_replace ( "[^a-z]+", "", strip_tags ( $http_vars ["content"] ) );
		
		// 더 좋은방법이 있으면...
		foreach ( $badwords as $key => $val )
			if (is_alike ( $val, $f_subject ) || is_alike ( $val, $f_content ))
				return $val;
			
			// 광고글 차단
		$badwords = explode ( ",", $envconf ["badbanner"] );
		// remove special characters
		$f_subject = eregi_replace ( "([\_\-\./~@?=%&!*]+)", "", strip_tags ( $http_vars ["subject"] ) );
		$f_content = eregi_replace ( "([\_\-\./~@?=%&!*]+)", "", strip_tags ( $http_vars ["content"] ) );
		
		// 더 좋은방법이 있으면...
		foreach ( $badwords as $key => $val )
			if (is_alike ( $val, $f_subject ) || is_alike ( $val, $f_content ))
				return $val;
	}
	
	// 글 파일로 저장하기
	// 회원일 경우 해당 회원의 점수 주기(예정)
	function testboardsave($http_vars, $cmd) {
		global $sysconf, $envconf, $msg, $def_data;
		
		// 데이타 초기값
		$data = $temp = $def_data;
		
		// 데이타(글) 임시 저장하기
		$temp = a4b ( $temp, $http_vars );
		
		// 본문내용만 다른변수로 저장하기
		if (get_magic_quotes_gpc ())
			$content = stripslashes ( $temp ["content"] );
		else
			$content = ($temp ["content"]);
			
			// 제목없을때 본문내용을 일부 대신한다.(2001.04.20)
		if (empty ( $temp ["subject"] ))
			$temp ["subject"] = utf8_strcut ( $content, $envconf ["short_subject"] );
			
			// 제목에 태그삭제(2002.01.07)
			// 태그 부분허용(예정)
		$temp ["subject"] = strip_tags ( $temp ["subject"] );
		
		switch ($cmd) {
			// //////////////////신규 글쓰기일 때////////////////////
			case 'write' :
				// 데이타(글) 저장하기
				$data = a4b ( $data, $temp );
				
				// 타임스템
				$this->id = floor ( getmicrotime () * 10 );
				
				$data ["id"] = $data ["ppid"] = $this->id;
				$data ["attachfile"] = $this->session->getsess ( "attachfile" );
				$data ["small_image"] = $this->session->getsess ( "small_image" );
				
				$this->obj->savedatafile ( $data, $this->db, $cmd );
				$this->obj->savecontentfile ( $content, $this->db, $this->id );
				
				// 자동 입력을 위하여 쿠기 등록 처리.
				if (! defined ( '_debug_' )) {
					setcookie ( "cook_user[name]", $data ["name"], time () + 3600 );
					setcookie ( "cook_user[email]", $data ["email"], time () + 3600 );
					setcookie ( "cook_user[homeurl]", $data ["homeurl"], time () + 3600 );
				} // endif
				  
				// 이메일전송처리(예정)
				$tomail = array (
						"from_name" => $data ["name"],
						"from_email" => $data ["email"],
						"to_name" => $envconf ["admin_name"],
						"to_email" => $envconf ["admin_mail"] 
				);
				
				// 이메일 발송하기
				if (intval ( $envconf ["postadmin"] ))
					$this->forwardmail ( $http_vars, $tomail );
				break;
			
			// //////////////////답변글일때////////////////////
			case 'reply' :
				// 데이타(글) 저장하기
				$data = a4b ( $data, $temp );
				
				// 글 파일 읽기
				if (! $temp = $this->data [$this->id])
					$temp = $this->obj->loaddatafile ( $this->db, $this->id );
					
					// 타임스템
				$this->id = floor ( getmicrotime () * 10 );
				
				$data ["id"] = $this->id;
				$data ["pid"] = $temp ["id"];
				$data ["ppid"] = $temp ["ppid"];
				$data ["depth"] = $temp ["depth"] + 1;
				$data ["attachfile"] = $this->session->getsess ( "attachfile" );
				$data ["small_image"] = $this->session->getsess ( "small_image" );
				
				$this->obj->savedatafile ( $data, $this->db, $cmd );
				$this->obj->savecontentfile ( $content, $this->db, $this->id );
				
				// 이메일전송처리(예정)
				$tomail = array (
						"from_name" => $data ["name"],
						"from_email" => $data ["email"],
						"to_name" => $temp ["name"],
						"to_email" => $temp ["email"] 
				);
				
				// 이메일 발송하기
				if (intval ( $envconf ["postreply"] ))
					$this->forwardmail ( $http_vars, $tomail );
				break;
			
			// //////////////////수정글일때////////////////////
			case 'modify' :
				// 글 파일 읽기
				if (! $data = $this->data [$id])
					$data = $this->obj->loaddatafile ( $this->db, $this->id );
					
					// 데이타(글) 저장하기
					// 이전값을 보전해야 한다.
				$data = a4b ( $data, $http_vars );
				
				$data ["attachfile"] = $this->session->getsess ( "attachfile" );
				$data ["small_image"] = $this->session->getsess ( "small_image" );
				
				// 수정일자변경
				$data ["mo_time"] = time_now;
				
				$this->obj->savedatafile ( $data, $this->db, $cmd );
				$this->obj->savecontentfile ( $content, $this->db, $this->id );
				break;
			
			// //////////////////삭제////////////////////
			case 'delete' :
				break;
			
			default :
				$this->htmlerror ( $msg ["err_no_param"] );
				break;
		} // end switch
		  
		// 임시
		$this->session->setsess ( "attachfile", null );
		$this->session->setsess ( "small_image", null );
	}
	
	// 글 삭제하기
	function testboardmove() {
		global $msg;
		
		// 답변을 먼저 삭제해야...
		if (count ( $this->obj->findindexfile ( $this->db, 'pid', $this->id ) ) > 0)
			$this->htmlerror ( $msg ["err_exist_reply"] );
			
			// 글 파일 삭제
		$this->obj->movedatafile ( $this->db, $this->id );
	}
	function testboarddown() {
		global $sysconf;
		
		// 글 파일 읽기
		if (! $data = $this->data [$this->id])
			$data = $this->obj->loaddatafile ( $this->db, $this->id );
		
		$attachfile = $data ["attachfile"];
		$filename = $attachfile [$this->fn] ["name"];
		$down_file = $attachfile [$this->fn] ["filename"];
		$inline = is_inline ( $filename ) ? "inline" : "attachment";
		// todo::
		if (! file_exists ( $down_file ))
			$down_file = path_fix ( "$sysconf[path_upload]/$filename" );
		
		if (! isset ( $filename ) || ! file_exists ( $down_file ))
			die ( 'file not found! or access deny!' );
		/**
		 * **********************************************************************\
		 * 브라우즈에서 실시간으로 음악을 다운로드 받는 간단한 예제
		 *
		 * header("cache-control: private");
		 * header("content-type: audio/mp3");
		 * header("content-length: ".filesize($filename));
		 * header("content-disposition: filename={$filename}");
		 * flush();
		 * \***********************************************************************
		 */
		if (! headers_sent ()) {
			header ( "cache-control: no-cache" );
			header ( "cache-control: must-revalidate" );
			header ( "pragma: no-cache" );
		}
		
		header ( "content-type:  application/x-msdownload" );
		header ( "content-length:  " . filesize ( $down_file ) );
		header ( "content-disposition: $inline;filename=$filename" );
		header ( "expires:  0" );
		
		clearstatcache ();
		if (! $fp = fopen ( $down_file, 'rb' ))
			exit ();
		if (! fpassthru ( $fp ))
			fclose ( $fp );
		exit (); /* [중요].다운로드파일 변화없게 */
	}
	function testboardnewfolder() {
		global $sysconf;
		
		// 폴더생성
		mkdir ( "$sysconf[path_db]", mode_symbols );
		mkdir ( "$sysconf[path_users]", mode_symbols );
		mkdir ( "$sysconf[path_group]", mode_symbols );
		mkdir ( "$sysconf[path_data]", mode_symbols );
		mkdir ( "$sysconf[path_image]", mode_symbols );
		mkdir ( "$sysconf[path_upload]", mode_symbols );
		
		// [팁]각 폴더를 볼수 없게...파일복사(2001.03.23)
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_db]/index.html" ) );
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_users]/index.html" ) );
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_group]/index.html" ) );
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_data]/index.html" ) );
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_image]/index.html" ) );
		copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_upload]/index.html" ) );
		
		return (file_exists ( $sysconf ["path_db"] ) && is_dir ( $sysconf ["path_db"] ));
	}
	
	// 재설정 함수들...
	// 기타 함수들...
	function testboardpoll() {
	}
	
	// 내용을 엑셀로 만들기
	function testboardexcel() {
		header ( "content-type: application/vnd.ms-excel" );
		header ( "content-disposition: attachment;filename=output.xls" );
		header ( "content-description: oracleadmin by 거친마루 with php4" );
	}
	function testboardconfig() {
		$this->htmlheader ();
		$this->configform ();
		$this->htmlbottom ();
	}
	
	// 접속통계 기능(2001.10.17)
	function testboardstat() {
		global $envconf;
		
		$envconf ["tablewidth"] = '100%'; // 임시
		
		$data = $this->loadlogfile ( yy () );
		
		$this->htmlheader ();
		$this->columnheader ();
		$this->showlogstat ( $data );
		$this->columnbottom ();
		$this->htmlbottom ();
	}
	
	// 의견달기 기능(2001.09.06)
	// 프로그램 수정(예정)
	function saveopinion($name, $opinion) {
		if (empty ( $name ) || empty ( $opinion ))
			return;
		
		if (get_magic_quotes_gpc ())
			$opinion = stripslashes ( $opinion );
			
			// 본문 밑에 의견달기
		$text .= "[" . gettodate () . "] ";
		$text .= "ps." . strip_tags ( $name ) . "..." . strip_tags ( $opinion );
		
		// 본문내용 파일 읽기
		$text .= "\n" . $this->obj->loadopinionfile ( $this->db, $this->id );
		
		// 본문내용 파일 저장하기
		$this->obj->saveopinionfile ( $text, $this->db, $this->id );
	}
	
	// 조회수 증가하기
	function savehitcount($db, $id) {
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
		
		if (empty ( $data ))
			return;
		
		$data ["readcount"] = intval ( $data ["readcount"] ) + 1;
		$this->obj->savedatafile ( $data, $db, $this->cmd );
	}
	
	// 다운수 증가하기
	function savehitdown($db, $id) {
		if (! $data = $this->data [$id])
			$data = $this->obj->loaddatafile ( $db, $id );
		
		if (empty ( $data ))
			return;
		
		$attachfile [$this->fn] ["down"] = intval ( $attachfile [$this->fn] ["down"] ) + 1;
		
		$this->obj->savedatafile ( $data, $db, $this->cmd );
	}
	
	// 회원방문횟수 기록하기(2002.10.01)
	function savequerycount($uid) {
		$data = $this->loaduserfile ( $uid );
		
		if (empty ( $data ))
			return;
			// 최종방문일수정
		$data ["lastlogin"] = time_long;
		$data ["querycount"] = intval ( $data ["querycount"] ) + 1;
		$this->saveuserfile ( $data );
	}
	
	// 글 전달하기
	function forwardmail($http_vars = '', $to = '') {
		global $sysconf, $envconf;
		
		// 데이타 초기값
		$temp = $this->mail;
		
		// 글 파일 읽기
		if (! $data = $this->data [$this->id])
			$data = $this->obj->loaddatafile ( $this->db, $this->id );
			
			// 데이타(글) 임시 저장하기
		$temp = a4b ( $temp, $data );
		
		// 데이타(글) 저장하기
		$temp = a4b ( $temp, $to );
		
		// 데이타(글) 저장하기
		$temp = a4b ( $temp, $http_vars );
		
		// 본문내용 파일 읽기
		// 보안노출때문에 임의의 세션을 전달후 비교하기(2002.08.13)
		$sid = $this->session->sid;
		$content = url2text ( "$sysconf[path_home]/$envconf[testboard]?db=$this->db&mode=print&id=$this->id&sid=$sid" );
		
		// 제목없을때 본문내용을 일부 대신한다.(2001.04.20)
		if (empty ( $temp ["subject"] ))
			$temp ["subject"] = utf8_strcut ( $content, $envconf ["short_subject"] );
			
			// 태그삭제(2002.01.07)
		$temp ["subject"] = strip_tags ( $temp ["subject"] );
		
		// 개체 인스턴스를 작성한다.
		$mail = new mime ();
		
		// 모든 데이터 슬롯들을한다.
		$mail->fname = $temp ["from_name"];
		$mail->from = $temp ["from_email"];
		$mail->subject = $temp ["subject"];
		$mail->tname = $temp ["to_name"];
		$mail->to = $temp ["to_email"];
		$mail->_attach ( $content, "", "text/html" );
		
		// 메일서버(smtp)가 없는지?
		if (empty ( $envconf ["smtp_server"] ))
			return $mail->send ();
			
			// 개체 인스턴스를 작성한다.
		$smtp = new smtp ( $envconf ["smtp_server"] );
		
		// 전자메일을 보낸다.
		return $smtp->send ( $mail->from, $mail->to, $mail->_mail () );
	}
}
?>
