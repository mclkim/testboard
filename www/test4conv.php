<?php
/************************************************************************\
 * 프로그램명 : 데이타파일 일괄정리
 * 특기사항   : 1.이미지파일 image 폴더에 저장하기
                2.접속현황 파일읽기
                3.인덱스파일수정하기
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2001/10
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('../lib/php1board.php');
require_once ('../lib/file.php');

// 사용자 db가 없다면...
if (empty ( $db )) {
	showerror ( "not defined db name! please read readme.txt" );
} // endif
class test4conv extends form1board {
	// 데이타(파일) 퍼미션 수정하기
	function db_file_chmod($sysconf) {
		$filename = $sysconf ["path_db"];
		$common_query = "$sysconf[accecmd] '$filename'";
		
		return runshell ( $common_query );
	}
	
	// 인덱스(파일) 수정하기
	function idx_file_adjust($sysconf) {
		// 파일 찾기 시작
		$common_query = "$sysconf[listcmd] " . path_fix ( "$sysconf[path_data]/*c.cgi" );
		$index = runshell ( $common_query );
		
		// 파일이름 추출하기
		if (is_array ( $index ))
			$index = array_map ( "basename", $index );
			
			// 인덱스분리 추출하기
		foreach ( $index as $file ) {
			$filename = path_fix ( "$sysconf[path_data]/$file" );
			$row = file2array ( $filename );
			
			if ($row ["id"] > 0)
				$rows [] = "$row[id];$row[pid];$row[ppid]";
		} // end foreach
		
		$filename = $sysconf ["file_idx"];
		$f = fopen ( $filename, "wb" );
		flock ( $f, LOCK_EX );
		$ret = fwrite ( $f, implode ( "\n", $rows ) );
		flush ( $f );
		flock ( $f, LOCK_UN );
		fclose ( $f );
	}
	
	// 파일 변환하기
	function file_data_conv($sysconf) {
		global $def_data;
		
		// 파일 찾기 시작
		$common_query = "$sysconf[listcmd] " . path_fix ( "$sysconf[path_data]/*c.cgi" );
		$index = runshell ( $common_query );
		
		// 파일이름 추출하기
		if (is_array ( $index ))
			$index = array_map ( "basename", $index );
		
		$add_id = floor ( getmicrotime () );
		
		// 인덱스분리 추출하기
		foreach ( $index as $file ) {
			$filename = path_fix ( "$sysconf[path_data]/$file" );
			$temp = file2array ( $filename );
			
			$data = $this->decodedheader ( $temp );
			
			$data ["id"] = to_index ( $data ["id"] ) > 0 ? $data ["id"] : to_index ( $data ["ino"] );
			$data ["pid"] = to_index ( $data ["pid"] ) > 0 ? $data ["pid"] : to_index ( $data ["pid"] );
			$data ["ppid"] = to_index ( $data ["ppid"] ) > 0 ? $data ["ppid"] : to_index ( $data ["id"] );
			
			if ($data ["id"] == $data ["ppid"])
				$data ["ppid"] = to_index ( $data ["pid"] ) > 0 ? $data ["pid"] : to_index ( $data ["ppid"] );
			
			if (isset ( $data ["nfile"] ))
				$data ["attachfile"] [] = array (
						"name" => $data ["nfile"],
						"size" => $data ["sfile"],
						"down" => $data ["downcount"] 
				);
			
			if (isset ( $data ["imgfile"] ))
				$data ["attachfile"] [] = array (
						"name" => $data ["imgfile"],
						"size" => 1,
						"down" => 1 
				);
			
			if (isset ( $data ["subfile"] ))
				$data ["small_image"] = $data ["subfile"];
			
			if (isset ( $data ["image"] ))
				$data ["small_image"] = $data ["image"];
				
				// 작은이미지(임시)
			$idx = min ( $data ["attachfile"] );
			$file = extractfilename ( $idx ["name"] );
			$ext = extractfileext ( $idx ["name"] );
			$small_image = ($file && $ext) ? "{$file}_m.{$ext}" : "";
			
			if (empty ( $data ["small_image"] ) && is_image ( $small_image ))
				$data ["small_image"] = $small_image;
				
				// 일자변환(2004.05.20)
			if (isset ( $data ["modate"] ))
				$data ["mo_time"] = strtodate ( $data ["modate"] );
			if (isset ( $data ["indate"] ))
				$data ["in_time"] = strtodate ( $data ["indate"] );
			if (isset ( $data ["in_time"] ))
				$data ["in_date"] = date ( shortdateformat, $data ["in_time"] );
			
			if (strlen ( $data ["id"] ) < 8 && $data ["id"] > 0)
				$data ["id"] += $add_id;
			if (strlen ( $data ["pid"] ) < 8 && $data ["pid"] > 0)
				$data ["pid"] += $add_id;
			if (strlen ( $data ["ppid"] ) < 8 && $data ["ppid"] > 0)
				$data ["ppid"] += $add_id;
			
			$data = $this->encodedheader ( $data );
			
			$data = a4b ( $def_data, $data );
			array2file ( $filename, $data );
		} // end foreach
	}
	
	// 파일명 변환하기
	function file_name_conv($sysconf) {
		// 파일 찾기 시작
		$common_query = "$sysconf[listcmd] " . path_fix ( "$sysconf[path_data]/*c.cgi" );
		$index = runshell ( $common_query );
		
		// 파일이름 추출하기
		if (is_array ( $index ))
			$index = array_map ( "basename", $index );
			
			// 인덱스분리 추출하기
		foreach ( $index as $datafile ) {
			$contentfile = ereg_replace ( "c.cgi", "d.cgi", chop ( $datafile ) );
			$opinionfile = ereg_replace ( "c.cgi", "e.cgi", chop ( $datafile ) );
			
			$filename = path_fix ( "$sysconf[path_data]/$datafile" );
			$data = file2array ( $filename );
			
			$oldname = path_fix ( "$sysconf[path_data]/$contentfile" );
			$newname = path_fix ( "$sysconf[path_data]/" . $data ["id"] . substr ( $contentfile, - 5 ) );
			if ($oldname != $newname)
				rename ( $oldname, $newname );
			
			$oldname = path_fix ( "$sysconf[path_data]/$opinionfile" );
			$newname = path_fix ( "$sysconf[path_data]/" . $data ["id"] . substr ( $opinionfile, - 5 ) );
			if ($oldname != $newname)
				rename ( $oldname, $newname );
			
			$oldname = path_fix ( "$sysconf[path_data]/$datafile" );
			$newname = path_fix ( "$sysconf[path_data]/" . $data ["id"] . substr ( $datafile, - 5 ) );
			if ($oldname != $newname)
				rename ( $oldname, $newname );
		} // end foreach
	}
	
	// 이미지파일 옮기기
	function image_data_move($sysconf) {
		if (is_dir ( $sysconf ["path_image"] )) {
			mkdir ( "$sysconf[path_upload]", mode_symbols );
			copy ( "$sysconf[file_html]", path_fix ( "$sysconf[path_upload]/index.html" ) );
			
			$common_query = "$sysconf[movecmd] " . path_fix ( "$sysconf[path_image]/* $sysconf[path_upload]" );
			runshell ( $common_query );
		} // endif
	}
	
	// 파일데이타를 mysql로 변환하기
	function file2mysql($sysconf) {
		// 파일 찾기 시작
		$common_query = "$sysconf[listcmd] " . path_fix ( "$sysconf[path_data]/*c.cgi" );
		$index = runshell ( $common_query );
		
		// 파일이름 추출하기
		if (is_array ( $index ))
			$index = array_map ( "basename", $index );
		
		$filename = basename ( $sysconf ["file_sql"] );
		$inline = is_inline ( $filename ) ? "inline" : "attachment";
		
		if (! headers_sent ()) {
			header ( "cache-control: no-cache" );
			header ( "cache-control: must-revalidate" );
			header ( "pragma: no-cache" );
		}
		
		header ( "content-type:  application/x-msdownload" );
		header ( "content-disposition: $inline;filename=$filename" );
		header ( "expires:  0" );
		
		clearstatcache ();
		
		// 인덱스분리 추출하기
		foreach ( $index as $file ) {
			$filename = path_fix ( "$sysconf[path_data]/$file" );
			$data = file2array ( $filename );
			
			// 임시
			if (($id = floor ( $file )) < 0)
				continue;
			$filename = path_fix ( "$sysconf[path_data]/${id}d.cgi" );
			$data ["content"] = file_get_contents ( $filename );
			
			$output = array ();
			foreach ( $data as $key => $val )
				$output [] = "$key=" . quote ( $val );
			
			$query = implode ( ",", $output );
			
			print ("insert into $this->db set " . $query . ";\n") ;
		} // end foreach
		exit ();
	}
	
	// 로그파일 수정하기
	function log_file_adjust($sysconf) {
		$file = $sysconf ["file_log"];
		
		$text = file_get_contents ( $file );
		$text = ereg_replace ( "(\t|\r|\n)", "", $text );
		$text = ereg_replace ( "([0-9]{4})/", "\n\\1/", $text );
		file_put_contents ( $file, trim ( $text ) . "\n" );
	}
}

// 인스턴스 변수 $inst를 new 연산자를 이용해 지정하고 있다.
$inst = new test4conv ();

// 모드설정
$inst->mode ( $_REQUEST );
$inst->mode_swap ( $inst->mode, $inst->cmd );

// 홈페이지 시작
switch ($inst->mode) {
	case '' :
		$inst->refresh ( 'admin_adjust' );
		break;
	
	case 'user_login' :
	case 'admin_login' :
		$inst->testboardadminlogin ( '', $inst->cmd );
		break;
	
	case 'user_logout' :
	case 'admin_logout' :
		$inst->commit ();
		break;
	
	case 'admin_conv' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->file_data_conv ( $sysconf ); // 파일 변환하기
		$inst->file_name_conv ( $sysconf ); // 파일명 변환하기
		$inst->image_data_move ( $sysconf ); // 이미지파일 옮기기
		$inst->htmlinfo ( $msg ["setup_notice"], 'history.go(-3)' );
		break;
	
	case 'admin_adjust' :
	case 'admin_permit' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->idx_file_adjust ( $sysconf ); // 인덱스(파일) 수정하기
		$inst->log_file_adjust ( $sysconf ); // 로그파일 수정하기
		$inst->db_file_chmod ( $sysconf ); // 데이타(파일) 퍼미션 수정하기
		$inst->htmlinfo ( $msg ["setup_notice"], 'history.go(-3)' );
		break;
	
	case 'admin_sql' :
		if (! $inst->checkpassword ( $userid, $passwd ))
			$inst->htmlerror ( $msg ["err_pass"] );
		$inst->file2mysql ( $sysconf ); // 파일데이타를 mysql로 변환하기
		$inst->htmlinfo ( $msg ["setup_notice"], 'history.go(-3)' );
		break;
	
	default :
		$inst->htmlerror ( $msg ["err_no_param"] );
		break;
} // end switch

$inst->free ();
?>
