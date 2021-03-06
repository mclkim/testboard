---------------------------------------------------------------------
[알림]
---------------------------------------------------------------------
[].테스트보드(TestBoard)는 PHP 형태의 프로그램으로서,
   PHP(4.0.6 이상)를 지원하는 계정에 직접 설치해서 사용하는 게시판입니다.

[].메인프레임기능,일반게시판,방명록,자료실,블러그등 다양한 기능을 포함하고,
   또한 만들수 있는 게시판입니다.

[].여러 개의 게시판을 만들 수 있는 다중 게시판으로서 설치가 쉽고,
   관리자 기능을 갖고 있는 게시판입니다.

[].게시물 수에 아무런 제한이 없고,업로드 기능 및 스킨기능을 지원합니다.

[].개인적으로 WINDOWS 버젼을 호환(개발)중이라,
   공개한 게시판이 LINUX 와 WIN98/WIN2000/WINNT 에서 버그가 예상됨니다.

[].TestBoard Rev 2001.04.01 부터 게시판 파일 형식을 바꿔,
   예전 버젼과 호환성이 없습니다.

[].많은 테스트 부탁드립니다.

---------------------------------------------------------------------
[].Apache + PHP + 텍스트 파일(TEXT FILE) 을(를) 이용한 게시판 소스 입니다.
[].Apache + PHP + MySQL(mysql-max-3.23.41) 을(를) 이용한 게시판 소스 입니다.
[].Apache + PHP + 오라클(ORACLE 8.1.6) 을(를) 이용한 게시판 소스 입니다.
---------------------------------------------------------------------
[개발내용]

1.파일데이타 베이스 사용(기본)
2.복수 게시물 열람 기능
3.입력기능(파일첨부가능)
4.수정기능
5.삭제기능
6.답변기능
7.관리자 링크기능
8.자동으로 링크걸어주는 함수 사용
9.검색기능(grep 명령 사용 - MSDOS 지원)
10.HTML 태그 문서 기능
11.스킨(skin) 파일 기능
12.투표하기 기능(skin1poll.php 사용)
13.메인프레임 기능(skin1main.php 사용)
14.정렬기능(grep 명령 사용 - MSDOS 지원)
15.비공개문서 기능
16.회원관리 링크 기능
17.의견달기 기능
18.세션(Session) 기능(회원전용게시판에 적용)
19.메일보내기 기능(파일첨부가능)
20.파일/미리보기 업로드 파일크기 제한 기능
21.오라클(ORACLE 8.1.6) 연결 기능
22.MySQL(mysql-max-3.23.41) 연결 기능
23.접속통계 기능
24.엘범 (skin1album.php 사용) 기능
25.다이어리 (skin1calendar.php 사용) 기능
26.pop3(post office protocol)연결 기능
27.스킨파일(skin1blog 사용) 기능
28.복수첨부파일 기능
29.업데이트 없는 리플방법 사용

---------------------------------------------------------------------
[].개발자는 김명철(durk007@chollian.net) 입니다.
---------------------------------------------------------------------
[향후계획]

1.프로그램 설치방법 설명서(도움말 링크기능)
2.메일링 리스트 기능
3.메모장,낙서장 기능
4.연락처 기능
5.간단한 쇼핑몰 기능

---------------------------------------------------------------------
[].이 게시판은 다른 이름으로 프로그램 보호를 받고 있습니다.
[].이 게시판 사용으로 인한 어떠한 피해에도 개발자는 책임이 없습니다.
---------------------------------------------------------------------
[개발환경&테스트환경]

Linux 2.2.12-20 or Microsoft(R) Windows 98
Apache 1.3.14
PHP 4.0.6(이상)
Mysql-3.23.41(Option)
ORACLE 8.1.6(Option)

---------------------------------------------------------------------
[].퍼미션을 줄 수 없는 서버에서는 이 프로그램을 사용하실 수 없습니다.
[].위 개발환경(OS+Apache+PHP+ORACLE+MySQL+POP3)의 설치방법은 생략합니다.
---------------------------------------------------------------------
[주의]
1.본 소스는 초기 일반텍스트 버젼으로 설정되어 있습니다.
2.본 소스는 초기 패스워드가 없습니다.

[설치방법]
1.자신의 홈 디렉토리에 압축파일을 푼다.
2.db,docs,images,inc,skins 등 디렉토리가 나온다.
3.db디렉토리가 없으면 생성한다.(디렉토리안에 내용없슴)
   $ mkdir db
4.이 디렉토리는 모두 자신의 홈디렉토리에 있어야 한다.
5.db(이하)디렉토리에 퍼미션을 반드시 777로 준다.(Linux/Unix 경우)
   $ chmod -R 777 db
6."http://자신의 홈페이지 주소/setup.php"
[].본 소스는 초기 패스워드가 없습니다.
7.설치끝

---------------------------------------------------------------------
[디렉토리구조]
---------------------------------------------------------------------
testboard[게시판의 최상위 디렉토리{755}]
        |
        +- db[게시판db{707}]
                +- ...이하 자동생성....
                +- data[데이터파일들]
                +- upload[업로드파일들]
                +- ...
        +- docs[일반문서&SQL script]
        +- images[이미지파일들]
                +- icon[아이콘들]
                +- type[파일타입아이콘들]
        +- inc[환경설정파일들]
        +- skins[스킨파일들]
        +- testadmin.php*
        +- testboard.php*
        +- testmail.php*
        +- testmember.php*
        +- ...

---------------------------------------------------------------------
[].문의사항이나 버그가 발견되면 durk007@chollian.net 메일주세요.
---------------------------------------------------------------------
[설치방법]
"http://자신의 홈페이지 주소/setup.php"
[].본 소스는 초기 패스워드가 없습니다.

[실행방법]
"http://자신의 홈페이지 주소/testboard.php?db=사용자DB명"
[]."사용자DB명"은 영문으로 하세요.
[].관리자비밀번호를 입력해야 생성합니다.

[게시판볼수있는곳]
[].한시적으로 사이트를 빌려서...고맙습니다.
"http://testboard.ce.ro"
"http://testboard.cafe2.net"

---------------------------------------------------------------------
[].클래스계승순서(Enheritance)
---------------------------------------------------------------------
[특징]
클래스의 계승순서를 이해하면,프로그램 절반을 이해한 것과 같다.
클래스를 이용하여 수정이 간편하다.

0.참고파일::php1config,php1lib,php1session,php1post,php1upload,test
1.기본::DB>>php1new>>php1save>>php1form>>php1list>>php1board>>testboard
2.관리자::DB>>php1new>>php1save>>php1form>>php1admin>>testadmin
3.스킨사용::DB>>php1new>>php1save>>php1form>>php1list>>php1board>>skin1????>>testboard
4.회원관리::DB>>php1new>>php1save>>php1form>>php1list>>php1board>>php1member>>testmember
5.메일관리::DB>>php1new>>php1save>>php1form>>php1mail>>testmail
6.설치관리::DB>>php1new>>php1save>>php1form>>setup

[기본스킨파일]
skin1album.php*
skin1babel.php*
skin1beos.php*
skin1blog.php*
skin1link.php*
skin1main.php*
skin1notice.php*
skin1pds.php*
skin1pink.php*
skin1poll.php*
skin1rand.php*

[].스킨파일을 사용하면 php1board,testboard 사이에 자동으로 정의된다.
[].스킨파일은 여러분이 한번 만들어 보세요
---------------------------------------------------------------------
이 게시판이 프로그래머들에게 많은 도움이 되었으면 하는 바램입니다.
항상 몸 건강히 지내시길...
감사합니다.
---------------------------------------------------------------------
