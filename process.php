// ایجاد اتصال با دیتابیس
$servername = "localhost";
$username = "[نام کاربری دیتابیس]";
$password = "[رمز عبور دیتابیس]";
$dbname = "[نام دیتابیس]";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// ثبت نام کاربر
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
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

// ورود کاربر
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
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

    // ریدایرکت به داشبورد کاربری
    header("Location: dashboard.php");
    exit();
  } else {
    echo "عملیات ورود ناموفق بود. لطفاً اطلاعات ورودی را بررسی کنید.";
  }
}

mysqli_close($conn);
