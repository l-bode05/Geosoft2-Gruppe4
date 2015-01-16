function chkFormular4 () {
  if (document.Userregist.nickname.value == "") {
    alert("Please enter a Nickname!");
    document.Userregist.mail.focus();
    return false;
  }
  if (document.Userregist.mail.value == "") {
    alert("Please enter your email address!");
    document.Userregist.mail.focus();
    return false;
  }
  if (document.Userregist.mail.value.indexOf(".") == -1) {
   alert("No email address!");
   document.Userregist.Mail.focus();
   return false;
}
  if (document.Userregist.mail.value.indexOf(".") >= 3) {
   alert("No email address!");
   document.Userregist.Mail.focus();
   return false;
}
  if (document.Userregist.mail.value.indexOf("@") == -1) {
   alert("No email address!");
   document.Userregist.Mail.focus();
   return false;
  }
  if(document.Userregist.password.value == "") {
   alert("Please enter your password!");
   document.Userregist.password.focus();
   return false;
  }
   if(document.Userregist.password2.value != document.Userregist.password.value) {
   alert("Passwords are not equal!");
   document.Userregist.password2.focus();
   return false;
  }
}