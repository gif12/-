$servername = "localhost";
$username = "[ali]";
$password = "[1qaz]";
$dbname = "[ali]";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// ثبت نام کاربران
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // بررسی تکراری نبودن ایمیل کاربر
  $query = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    echo "این ایمیل قبلاً در سیستم ثبت شده است.";
    exit();
  }

  $query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')";
  mysqli_query($conn, $query);

  echo "ثبت نام با موفقیت انجام شد.";
}

// ورود به حساب کاربری
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // پیدا کردن نام کاربری با ایمیل
    $user = mysqli_fetch_assoc($result);
    $username = $user['username'];

    // ذخیره اطلاعات کاربر در سطح جلسه
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    echo "شما وارد حساب کاربری خود شدید.";
  } else {
    echo "ایمیل یا رمز عبور اشتباه است.";
  }
}

// بستن اتصال به دیتابیس
mysqli_close($conn);
.
