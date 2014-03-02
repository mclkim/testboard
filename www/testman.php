<?php
/************************************************************************\
 * 프로그램명 : 데이타파일관리
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/10
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('../lib/php1board.php');
class testman extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		$envconf ["useuser"] = 0; // 회원관리
		
		$envconf ["show_list"] = 0; // 본문 밑으로 문서목록 보이기
		$envconf ["show_findmenu"] = 0; // 검색메뉴 보이기
		
		$envconf ["access_read"] = 1; // 글 읽기권한
		$envconf ["access_write"] = 1; // 글 쓰기권한
		
		$envconf ["pageline"] = "30"; // 한 페이지당 출력할 목록 갯수
	}
	
	// 전체(파일) 목록 구하기(2001.07.05)
	function listdatahome() {
		global $sysconf;
		
		unset ( $row );
		
		$dir = path_fix ( "$sysconf[path_host]/db" );
		$directory = dir ( $dir );
		
		while ( $entry = $directory->read () ) {
			if (! eregi ( '^[.]', $entry ) && is_dir ( path_fix ( "$dir/$entry" ) ))
				$row [] = $entry;
			
			flush ();
		} // end while
		$directory->close ();
		
		return count ( $row ) > 0 ? $row : null;
	}
	
	// 파일목록보이기
	function testboardlist() {
		$this->htmlheader ( false );
		$this->columnheader ();
		
		$this->showlist ( $this->db, $this->index );
		$this->showpagemenu ( $this->db );
		
		$this->columnbottom ();
		$this->htmlbottom ( false );
	}
	
	// 파일목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
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
		echo ("<tr align=center bgcolor='$envconf[listheadbkcol]'>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[no]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[boardtitle]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[db_engine]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$label[boarditem]</font></th>\n");
		
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[set]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[conv]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[adjust]/$btn[permit]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[create]</font></th>\n");
		echo ("<th><font color='$envconf[listheadtxtcol]'>$btn[delete]</font></th>\n");
		echo ("</tr>\n");
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
		// 목록시작
		for(reset ( $index ), $ii = 0; list ( $key, $t_db ) = each ( $index );) {
			$num --;
			
			echo ("\n<!-- $key -->\n");
			$bg = ($num & 1); // 다른방법으로 한번 해봄
			                  
			// 필요한 파일명과 경로 읽기
			$sys = setsystemconfig ( $t_db );
			
			// 환경설정 초기값 읽기
			$env = loadenvironmentconfig ( $sys ["file_cfg"] );
			
			extract ( $env );
			
			// 데이타베이스엔진
			$obj = $this->factory ( $db_engine );
			$count = $obj->loadtotal ( $t_db );
			/*
			 * todo:: //디렉토리 사이즈 $dirsize=dirsize("$sysconf[path_host]/db/$t_db"); $dirsize=bytesize($dirsize);
			 */
			echo ("<tr align=center bgcolor='$listbkcol[$bg]'>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$num</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$boardtitle</font></td>\n");
			echo ("<td><a href='$env[testboard]?db=$t_db&mode=list'>$t_db</a>\n");
			echo ("<font color='$listtxtcol[$bg]'>[$db_engine]</font></td>\n");
			echo ("<td><font color='$listtxtcol[$bg]'>$count</font></td>\n");
			// 수정,삭제,생성
			echo ("<td><a href=\"javascript:remotewindow('$sysconf[testadmin]?db=$t_db&mode=config')\">$btn[set]</a></td>\n");
			echo ("<td><a href=\"javascript:remotewindow('test4conv.php?db=$t_db&mode=admin_conv')\">$btn[conv]</a></td>\n");
			echo ("<td><a href=\"javascript:remotewindow('test4conv.php?db=$t_db&mode=admin_adjust')\">$btn[adjust]/$btn[permit]</a></td>\n");
			// echo("<td><a href=\"javascript:remotewindow('$sysconf[testdata]?db=$t_db')\">$btn[modify]</a></td>\n");
			echo ("<td><a href=\"javascript:remotewindow('test4conv.php?db=$t_db&mode=admin_sql')\">$btn[create]</a></td>\n");
			echo ("<td><a href='$sysconf[testadmin]?db=$t_db&mode=admin_delete'>$btn[delete]</a></td>\n");
			
			echo ("</tr>\n");
		} // end for
		
		echo ("<tr><td colspan=10>\n");
		echo ("\n<!-- create db design -->\n");
		echo ("<form name='createdb' method='post' action='$sysconf[testadmin]' autocomplete='off'>\n");
		echo ("<input type='hidden' name='mode'   value='new_folder'>\n");
		// 데이타베이스 엔진을 환경파일에서 선택,연결하기
		$this->showselect ( "db_engine", $envconf ["db_engine"] );
		$this->showinput ( 'text', "db", '', 10, 10 );
		$this->showbutton ( 'create db', "submit()" );
		echo ("</td></tr>\n");
		echo ("</form>\n");
		
		echo ("</table>\n");
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new testman ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 한 화면에 출력할 목록 갯수
if (intval ( $envconf ["pageline"] ))
	$inst->pageline = intval ( $envconf ["pageline"] );
	
	// 기본 인덱스 파일을 읽는다.
$inst->index = $inst->listdatahome ();

$inst->total_data = count ( $inst->index );
$inst->total_page = max ( 1, ceil ( $inst->total_data / $inst->pageline ) );
$inst->current_page = min ( max ( 1, $inst->page ), $inst->total_page );

$index = array_chunk ( $inst->index, $inst->pageline, true );
$inst->index = $index [$inst->current_page - 1];

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
	case 'list' :
		$inst->testboardlist ();
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>
