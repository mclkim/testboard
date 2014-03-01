//<!-- 마우스 오른쪽 버튼을 사용불가 -->
document.oncontextmenu = function(){return false;}

if(document.layers) {
        window.captureEvents(event.mousedown);
        window.onmousedown = function(e){if(e.target == document){return false;}}
}
else {
        document.onmousedown = function(){return false;}
}

//<!-- 화면크기별 팝업창 -->
function fullwindow(theurl)
{
        window.open(theurl,'','fullscreen=yes,type=fullwindow,scrollbars=no');
}

function popupwindow(theurl,w,h)
{
        w=w+32;
        h=h+32;
        window.open(theurl ,'','left=0,top=0,width='+w+',height='+h+',resizable=yes,scrollbars=yes,status=yes');
}

function remotewindow(theurl)
{
        window.open(theurl,'','left=0,top=0,width=500,height=400,resizable=yes,scrollbars=yes,status=no');
}

function printwindow(theurl)
{
        thewin=window.open(theurl,'','left=0,top=0,width=500,height=400,resizable=no,scrollbars=yes,status=no');
        thewin.print();
}

function messagewindow(theurl)
{
        t=document.body.offsetHeight/2-100;
        l=document.body.offsetWidth/2-200;

        window.open(theurl,'','left='+l+',top='+t+',width=410,height=210,resizable=no,scrollbars=no,toolbar=no,location=no,ststus=no,memubar=no');
}

//<!-- 첨부할 파일목록 -->
function attachwindow(field)
{
    var theurl='php1upload.php?field='+field;

//    window.showModelessDialog(theurl,'','dialogHeight:390px; dialogWidth:470px; center: yes; scroll: off; status: off');
    sub = window.open(theurl,'','left=200,top=300,width=450,height=350,resizable=no,scrollbars=no,toolbar=no,location=no,ststus=no,memubar=no');
    sub.focus();
}

//<!-- 컬러 검색창 -->
function setcolor(field)
{
        theurl='php1color.php?field='+field;
        window.open(theurl,'','left=0,top=0,width=300,height=400,resizable=no,scrollbars=no,toolbar=no,location=no,ststus=no,memubar=no');
}

//<!-- 우편번호 검색창 -->
function setpost(zipcode,address)
{
        t=document.body.offsetHeight/2-200;
        l=document.body.offsetWidth/2-250;
        t=100;l=100;

        theurl='php1post.php?f_zipcode='+zipcode+'&f_address='+address;
        window.open(theurl,'','left='+l+',top='+t+',width=500,height=400,resizable=yes,scrollbars=yes,status=no');
}

//<!-- 입력필드에 포커스가 갈때 이전데이타를 숨기기 -->
function onenter(field){ if(field.value == field.defaultValue){ field.value="";} }
function onexit(field){ if(field.value == ""){ field.value=field.defaultValue;} }
function next_focus(){ if(event.keycode == 13){event.keycode = 9;} }

//<!-- 버튼모양 이미지출력(skin) -->
function set_button()
{
        var object=window.event.srcElement

        object.onmouseover=function()
        {
                window.status=object.alt;
                return true
        }

        object.onmouseout=function()
        {
                window.status='';
                return true
        }

        object.onmousedown=function()
        {
                object.style.pixeltop=1;
                object.style.pixelleft=1;
        }

        object.onmouseup=function()
        {
                object.style.pixeltop=0;
                object.style.pixelleft=0;
        }
}

//<!-- 마우스를 올려 놓으면 작은이미지 출력 -->
function msgposit()
{
        imagedisplay.style.postop=event.y+document.body.scrolltop;
        if (event.x>650)
        imagedisplay.style.posleft=event.x-350+document.body.scrollleft;
        else
        imagedisplay.style.posleft=event.x+document.body.scrollleft+10;
}

function msgset(str)
{
        imagedisplay.innerHTML='<table width=220 border=0 cellpadding=6 cellspacing=0><tr><td>'+str+'</td></tr></table>'
}

function msghide()
{
        imagedisplay.innerHTML=''
}

//<!-- 마우스를 클릭하면 큰이미지 출력 -->
var openedwindow,objimg;

function poplargeimage(title)
{
        if(!objimg.complete) { timerloading=setTimeout("poplargeimage('"+title+"')",100); return true; }
        if( openedwindow && (!openedwindow.closed) ) openedwindow.close();

        content = '<html><head><title>' + title + '</title>'
        + '</head><body style="padding:0px; border:0px; margin:0px;">'
        + '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td align="center" valign="center">'
        + '<img src="' + objimg.src + '" alt="" width="' + objimg.width
        + '" height="' + objimg.height + '" style="padding:0px; border:0px; margin:0px;" onclick="window.close();">'
        + '</td></tr></table>'
        + '</body></html>';

        openedwindow = window.open('','','screenx=0,screeny=0,scrollbars=0,toolbar=0,status=0,menubar=0,resizable=1,location=0,directories=0,width='+objimg.width+',height='+objimg.height);
        openedwindow.document.write(content);
}//end of function poplargeimage()

function preloadimage(source,title)
{
        document.body.style.cursor='wait';
        timercursor=setTimeout("document.body.style.cursor='default'",500);
        objimg = new image();
        objimg.src = source;
        poplargeimage(title)
}//end of function preloadimage()
