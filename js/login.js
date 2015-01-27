function chkFormular3 () {
  if (document.Userlogin.mail.value == "") {
    alert("Please enter your email address!");
    document.Userlogin.mail.focus();
    return false;
  }
  if (document.Userlogin.mail.value.indexOf("@") == -1) {
   alert("No email address!");
   document.Userlogin.Mail.focus();
   return false;
  }
  if(document.Userlogin.password.value == "") {
   alert("Please enter your password!");
   document.Userlogin.password.focus();
   return false;
  }
}