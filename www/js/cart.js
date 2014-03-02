function view_all()
{
 var i, chked=0;

 if(confirm('목록을 반전하시겠습니까?\n\n반전을 원하지 않는다면 취소를 누르시면 다음으로 넘어갑니다'))
 {
  for(i=0;i<document.list.length;i++)
  {
   if(document.list[i].type=='checkbox')
     if(document.list[i].checked) { document.list[i].checked=false; }
     else { document.list[i].checked=true; }
  }//end for
 }//end if

 for(i=0;i<document.list.length;i++)
 {
  if(document.list[i].type=='checkbox')
   if(document.list[i].checked) chked=1;
 }//end for

 if(chked) {
  if(confirm('선택된 항목을 보시겠습니까?'))
   {
    document.list.selected.value='';
    document.list.mode.value='view_all';
    for(i=0;i<document.list.length;i++)
    {
     if(document.list[i].type=='checkbox')
       if(document.list[i].checked)
       document.list.selected.value=document.list[i].value+';'+document.list.selected.value;
    }//end for

    document.list.submit();
    return true;
   }//end if
  }//end if
}

function delete_all()
{
  var i, chked=0;

  for(i=0;i<document.list.length;i++)
  {
   if(document.list[i].type=='checkbox')
    if(document.list[i].checked) chked=1;
  }//end for

  if(chked){
   if(confirm('선택된 항목을 삭제하시겠습니까?'))
   {
    document.list.selected.value='';
    document.list.mode.value='delete_all';

    for(i=0;i<document.list.length;i++)
    {
     if(document.list[i].type=='checkbox')
      if(document.list[i].checked)
       document.list.selected.value=document.list[i].value+';'+document.list.selected.value;
    }//end for

    document.list.submit();
    return true;
   }//end if
  }//end if
  else {alert('정리할 게시물을 선택하여 주십시요');}
}
