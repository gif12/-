// ثبت نام کاربر
var registerForm = document.getElementById("register-form");
registerForm.onsubmit = function(e) {
  e.preventDefault();

  var username = registerForm.querySelector("[name='username']").value;
  var email = registerForm.querySelector("[name='email']").value;
  var password = registerForm.querySelector("[name='password']").value;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      alert(this.responseText);
    }
  };
  xmlhttp.open("POST", "process.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("register=true&username=" + username + "&email=" + email + "&password=" + password);
};

// ورود به حساب کاربری
var loginForm = document.getElementById("login-form");
loginForm.onsubmit = function(e) {
  e.preventDefault();

  var email = loginForm.querySelector("[name='email']").value;
  var password = loginForm.querySelector("[name='password']").value;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == "شما وارد حساب کاربری خود شدید.") {
        // ریدایرکت به داشبورد کاربری
        window.location.href = "dashboard.php";
      } else {
        alert(this.responseText);
      }
    }
  };
  xmlhttp.open("POST", "process.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("login=true&email=" + email + "&password=" + password);
};
