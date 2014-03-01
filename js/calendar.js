/*******************************************************************************
<!-- calendarjavascript design -->
*******************************************************************************/
var target;//호출한 object의 저장
var stime;
document.write("<div id=minical oncontextmenu='return false' ondragstart='return false' onselectstart='return false' style='background:buttonface; margin:5; padding:5;margin-top:2;border-top:1 solid buttonshadow;border-left: 1 solid buttonshadow;border-right: 1 solid buttonshadow;border-bottom:1 solid buttonshadow;width:160;display:none;position: absolute; z-index: 99'></div>");

function calendar(obj) {
        var now = obj.value.split("/");
        var x,y;

        target = obj;//object 저장;

        x = (document.layers) ? loc.pageX : event.clientX;
        y = (document.layers) ? loc.pageY : event.clientY;

        minical.style.pixelTop  = y+10;
        minical.style.pixelLeft = x-50;
        minical.style.display = (minical.style.display == "block") ? "none" : "block";

        if (now.length == 3) {//정확한지 검사
                showcalendar(now[0],now[1],now[2]);//넘어온 값을 년월일로 분리
        } else {
                showcalendar(0,0,0);//초기값으로
        }//end if else
}

function doover() {//마우스가 칼렌다위에 있으면
        var el = window.event.srcElement;
        cal_day = el.title;

        if (cal_day.length > 7) {//날자 값이 있으면.
                el.style.bordertopcolor = el.style.borderleftcolor = "buttonhighlight";
                el.style.borderrightcolor = el.style.borderbottomcolor = "buttonshadow";
        }
        window.clearTimeout(stime);//clear
}

function doclick() {//날자를 선택하였을 경우
        cal_day = window.event.srcElement.title;
        window.event.srcElement.style.bordercolor = "red";//테두리 색을 빨간색으로

        if (cal_day.length > 7) {//날자 값이있으면
                target.value=cal_day//값 설정
        }
        minical.style.display='none';//화면에서 지움
}

function doout() {
        var el = window.event.fromElement;
        cal_day = el.title;

        if (cal_day.length > 7) {
                el.style.bordercolor = "white";
        }
        stime=window.setTimeout("minical.style.display='none';",200);
}
/*******************************************************************************/
function isleapyear(year){
          return (year % 4 == 0) && ((year % 100 != 0) || (year % 400 == 0));
}
/*******************************************************************************/
function zerospace(v,n){
        var z= new String("0000000000");
        return (z.substring(0,n-v.toString().length)+v.toString());
}
/*******************************************************************************/
function day2(d) {//2자리 숫자료 변경
        return zerospace(d,2)
}
/*******************************************************************************/
function showcalendar(syear,smonth,sday) {
        var monthdays = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
        var week_name = new Array("<font color=red>일</font>","월","화","수","목","금","<font color=blue>토</font>");
//        var week_name = new Array("su","mo","tu","we","th","fr","sa");
        var intthisyear = new Number(),intthismonth = new Number(),intthisday = new Number();
        var dattoday = new Date();//현재 날자 설정

        document.all.minical.innerHTML = "";

        //시스템 날짜를 년,월,일로 구하기
        nowthisyear = dattoday.getFullYear();//현재 년
        nowthismonth = dattoday.getMonth()+1;//현재 월 값은 실제값 보다 -1 한 값이 되돌려 진다.
        nowthisday = dattoday.getDate();//현재 일

        //오늘 날짜를 년,월,일로 구하기
        intthisyear = parseInt(syear);
        intthismonth = parseInt(smonth,10);
        intthisday = parseInt(sday,10);

        //값이 없을 경우
        if (intthisyear == 0) intthisyear = nowthisyear;
        if (intthismonth == 0) intthismonth = nowthismonth;
        if (intthisday == 0) intthisday = nowthisday;

        //작년,내년 계산
        switch(intthismonth) {
                case 1:
                        intprevyear = intthisyear -1;
                        intprevmonth = 12;
                        intnextyear = intthisyear;
                        intnextmonth = 2;
                        break;
                case 12:
                        intprevyear = intthisyear;
                        intprevmonth = 11;
                        intnextyear = intthisyear + 1;
                        intnextmonth = 1;
                        break;
                default:
                        intprevyear = intthisyear;
                        intprevmonth = (intthismonth) - 1;
                        intnextyear = intthisyear;
                        intnextmonth = (intthismonth) + 1;
                        break;
        }//end switch

        datfirstday = new Date(intthisyear,intthismonth-1,1);//현재 달의 1일로 날자 객체 생성(월은 0부터 11까지의 정수(1월부터 12월))
        intfirstweek = datfirstday.getDay();//현재 달 1일의 요일을 구함 (0:일요일,1:월요일)

        //윤년인경우
        if(isleapyear(intthisyear)) monthdays[2] = 29;
        intlastday = monthdays[intthismonth];//마지막 일자 구함

        cal_html = "<table width=100% border=0 cellpadding=1 cellspacing=1 onmouseover=doover(); onmouseout=doout(); style='font-size:8pt;font-family:tahoma;'>";
        cal_html += "<tr align=center><td colspan=7 nowrap=nowrap align=left>";
        cal_html += "<span title='이전달' style=cursor:hand; onclick='showcalendar("+intprevyear+","+intprevmonth+","+intthisday+");'><font color=navy>◀</font></span>";
        cal_html += "<b style=color:red>"+get_yearinfo(intthisyear,intthismonth,intthisday)+"년"+get_monthinfo(intthisyear,intthismonth,intthisday)+"월</b>";
        cal_html += "<span title='다음달' style=cursor:hand; onclick='showcalendar("+intnextyear+","+intnextmonth+","+intthisday+");'><font color=navy>▶</font></span>";
        cal_html += "</td></tr>";

        //요일
        cal_html += "<tr align=center bgcolor=threedface style='color:white;font-weight:bold;'>";
        for (i=0; i < 7; i++)
        cal_html += "<td>"+week_name[i]+"</td>";
        cal_html += "</tr>";

        //첫번째 주에서 빈칸을 1일전까지 빈칸을 삽입
        cal_html += "<tr align=right valign=top bgcolor=white>";
        for (i=0; i < intfirstweek; i++)
        cal_html += "<td bgcolor='eeeeee'>&nbsp;</td>";

        //달력그리기
        for(day=1;day<=intlastday;day++)
        {
            if(intfirstweek != 0 && intfirstweek%7 == 0) cal_html += "<tr align=right valign=top bgcolor=white>";

            cal_html += "<td onclick=doclick(); title="+intthisyear+"/"+day2(intthismonth)+"/"+day2(day)+" style='cursor:hand;border:1px solid white;";

            //오늘일때
            if (intthisday == day)
            cal_html += "background-color:cyan;";

            switch(intfirstweek%7) {
                case 0://일요일
                cal_html += "color:red;"
                break;

                case 6://토요일
                cal_html += "color:blue;"
                break;

                default://평일
                cal_html += "color:black;"
                break;
            }//end switch

            cal_html += "'>"+day;
            cal_html += "</td>";
            intfirstweek++;
            if(intfirstweek != 0 && intfirstweek%7 == 0) cal_html += "</tr>";
        }//end for

        //선택한 월의 마지막날 이후의 빈테이블 삽입
        for (i=intfirstweek; i%7 != 0; i++)
        cal_html += "<td bgcolor='eeeeee'>&nbsp;</td>";

        cal_html += "</tr>";
        cal_html += "</table>";

        document.all.minical.innerHTML = cal_html;
}

function get_yearinfo(year,month,day) {//년 정보를 콤보 박스로 표시
        var min = parseInt(year) - 100;
        var max = parseInt(year) + 10;
        var i = new Number();
        var str = new String();

        str = "<select onchange='showcalendar(this.value,"+month+","+day+");' onmouseover=doover();>";
        for (i=min; i<=max; i++) {
                if (i == parseInt(year)) {
                        str += "<option value="+i+" selected onmouseover=doover();>"+i+"</option>";
                } else {
                        str += "<option value="+i+" onmouseover=doover();>"+i+"</option>";
                }
        }
        str += "</select>";
        return str;
}

function get_monthinfo(year,month,day) {//월 정보를 콤보 박스로 표시
        var i = new Number();
        var str = new String();

        str = "<select onchange='showcalendar("+year+",this.value,"+day+");' onmouseover=doover();>";
        for (i=1; i<=12; i++) {
                if (i == parseInt(month,10)) {
                        str += "<option value="+i+" selected onmouseover=doover();>"+day2(i)+"</option>";
                } else {
                        str += "<option value="+i+" onmouseover=doover();>"+day2(i)+"</option>";
                }
        }
        str += "</select>";
        return str;
}
