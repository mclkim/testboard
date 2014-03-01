<?
/************************************************************************\
 * 프로그램명 : 스킨파일(앨범)
 * 특기사항   :
 * 관련테이블 :
 * services   :
 * 작 성 자   : 김명철
 * 작성일자   : 2002/09
 * 수 정 자   :
 * 수정일자   :
 * 수정내역   :
\************************************************************************/
require_once ('php1board.php');

// <주의>파일이름과 클래스이름을 일치해야...
class skin1album extends form1board {
	var $cellh = 90;
	var $cellw = 120;
	var $dan = 4;
	var $dan_width = "25%"; // 단의 넓이
	                        
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf, $btn;
		
		$sysconf ["skin_image"] = ("$sysconf[home_skins]/skin1album");
		
		$envconf ["cellspacing"] = 0; // 셀 간격
		$envconf ["cellpadding"] = 2;
		$envconf ["attachfile"] = 1; // 첨부파일 가능여부
		$envconf ["show_findmenu"] = 0;
		$envconf ["show_sortmenu"] = 0;
		
		$this->dan = max ( $this->dan, $envconf ["dan_size"] ); // 단의 갯수
		$this->dan_width = round ( 100 / $this->dan ) . '%'; // 단의 넓이
	}
	
	// 목록 보이기
	function showlist($db, $index) {
		global $sysconf, $envconf, $btn, $label;
		
		$width = array (
				120,
				90 
		);
		$height = array (
				90,
				120 
		);
		
		// 글 목록 번호
		$num = $this->total_data - max ( 0, $this->current_page - 1 ) * $this->pageline + 1;
		
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
			
			// 앨범그리기
		foreach ( $index as $key => $data ) {
			extract ( $data );
			$num --;
			
			// 제목이 긴경우 줄여서 표시
			$subject = kstrcut ( $subject, $envconf ["short_subject"] );
			$title = "$label[readcount]:$readcount";
			
			// 그림방향에 따라 출력
			if (is_file ( $filename = path_fix ( "$sysconf[path_upload]/$small_image" ) )) {
				$size = getimagesize ( $filename );
				$align = ($size [0] > $size [1]);
			} // end if
			  
			// 파일이름이 한글인경우 오류발생(2001.12.21)
			$image = urlencode ( $small_image );
			
			// 단설정
			echo ("\n<!-- $key -->\n");
			if ($ii != 0 && $ii % $this->dan == 0)
				echo "<tr>\n";
			
			echo ("<td align='center' valign='top' class='babel'>\n");
			// //////////////////단시작////////////////////
			echo ("  <table width=100% border=0 cellspacing=0 cellpadding=0>\n");
			echo ("    <tr bgcolor='white'>\n");
			echo ("      <td width='20' height='20'>$num</td>\n");
			echo ("      <td width='$width[$align]' height='20'>&nbsp;</td>\n");
			echo ("      <td width='20' height='20'></td>\n");
			echo ("    </tr>\n");
			
			echo ("    <tr>\n");
			echo ("      <td width='20' height='$height[$align]'>&nbsp;</td>\n");
			echo ("      <td width='$width[$align]' height='$height[$align]'>\n");
			print_link ( "$this->prog?db=$db&mode=read&id=$key&page=$this->page&ff=$this->ff&fw=$this->fw", make_image ( $image, $sysconf ["home_image"], $title ), '', "title='$title'" );
			echo ("      <td width='20' height='$height[$align]'>&nbsp;</td>\n");
			echo ("    </tr>\n");
			
			echo ("    <tr>\n");
			echo ("      <td width='20' height='20'>&nbsp;</td>\n");
			echo ("      <td width='$width[$align]' height='20'>$subject</td>\n");
			echo ("      <td width='20' height='20'></td>\n");
			echo ("    </tr>\n");
			echo ("  </table>\n");
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
	}
}
?>
