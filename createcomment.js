
function chkFormular () {
  if (document.createcomment.title.value == "") {
    alert("Please enter a title!");
    document.createcomment.title.focus();
    return false;
  }
  if(document.createcomment.url.value == "") {
   alert("Please enter a Url!");
   document.createcomment.url.focus();
   return false;
  }
  if(document.createcomment.comment.value == "") {
   alert("Please enter a comment!");
   document.createcomment.url.focus();
   return false;
  }
  if(document.createcomment.datum.value == "") {
   alert("Please enter a datum!");
   document.createcomment.url.focus();
   return false;
  }
}