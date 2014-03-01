<?
/************************************************************************\
 * 프로그램명 : 스킨파일(노란)
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
class skin1beos extends form1board {
	// 환경설정 초기값 재정의
	function resetconfig() {
		global $sysconf, $envconf;
		
		$envconf ["listheadbkcol"] = "#ffdd00"; // 목록 헤더 배경색
		$envconf ["listheadtxtcol"] = "#000000"; // 목록 헤더 글자색
		
		$envconf ["listbkcolodd"] = "#f0f0f0"; // 목록 홀수 배경색
		$envconf ["listtxtcolodd"] = "#000000"; // 목록 홀수 글자색
		$envconf ["listbkcoleven"] = "#fcfcfc"; // 목록 짝수 배경색
		$envconf ["listtxtcoleven"] = "#000000"; // 목록 짝수 글자색
		
		$envconf ["dataheadbkcol"] = "#ffdd00"; // 본문 제목 배경색
		$envconf ["dataheadtxtcol"] = "#000000"; // 본문 제목 글자색
		$envconf ["databkcol"] = "#f0f0f0"; // 본문 내용 배경색
		$envconf ["datatxtcol"] = "#000000"; // 본문 내용 글자색
	}
}
?>
