<?php
// تنظیمات فایل و زمان
date_default_timezone_set('Asia/Tehran');
define('APP_PATH', dirname(__FILE__));
define('DATA_PATH', APP_PATH . '/data');
define('CONFIG_FILE', DATA_PATH . '/config.txt');
define('USERS_FILE', DATA_PATH . '/users.txt');
define('LOG_FILE', DATA_PATH . '/log.txt');

// تابع بررسی وجود پوشه و فایل‌ها
function check_folders_files() {
    if(!is_dir(DATA_PATH)) {
        mkdir(DATA_PATH);
        chmod(DATA_PATH, 0755);
    }
    if(!file_exists(CONFIG_FILE)) {
        $fp = fopen(CONFIG_FILE, 'w');
        fclose($fp);
        chmod(CONFIG_FILE, 0600);
    }
    if(!file_exists(USERS_FILE)) {
        $fp = fopen(USERS_FILE, 'w');
        fclose($fp);
        chmod(USERS_FILE, 0600);
    }
    if(!file_exists(LOG_FILE)) {
        $fp = fopen(LOG_FILE, 'w');
        fclose($fp);
        chmod(LOG_FILE, 0600);
    }
}

// بررسی وجود پوشه‌ها و فایل‌ها
check_folders_files();

// خواندن تنظیمات اتصال به دیتابیس
$db_config = parse_ini_file(CONFIG_FILE);

// اتصال به پایگاه داده
$conn = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['dbname']);

// بررسی وضعیت اتصال
if(!$conn) {
    die("اتصال به پایگاه داده با خطا مواجه شد.");
}

// شروع نشست
session_start();

// تنظیم متغیر خطا
$error = '';

// پردازش فرم‌ها
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch($_POST['form']) {
        case 'register':
            // ثبت‌نام کاربر جدید
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $email = $_POST['email'];

            // بررسی تکراری نبودن ایمیل
            $existing_users = file(USERS_FILE, FILE_IGNORE_NEW_LINES);
            foreach($existing_users as $existing_user) {
                $user_data = explode("|", $existing_user);
                if($user_data[2] === $email) {
                    $error = "این ایمیل قبلا در سیستم ثبت شده است.";
                    break;
                }
            }

            // ثبت‌نام کاربر جدید
            if(empty($error) && strlen($username) >= 4 && strlen($password) >= 6 && $password === $confirm_password && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $new_user = "$username|$password_hash|$email\n";
                file_put_contents(USERS_FILE, $new_user, FILE_APPEND);
                header("Location: welcome.php");
                exit;
            } else {
                $error = "فرم را با دقت پر کنید.";
            }

            break;
        case 'login':
            // ورود کاربر
            $email = $_POST['email'];
            $password = $_POST['password'];
            $existing_users = file(USERS_FILE, FILE_IGNORE_NEW_LINES);
            $user_found = false;

            // بررسی وجود کاربر
            foreach($existing_users as $existing_user) {
                $user_data = explode("|", $existing_user);
                if($user_data[2] === $email && password_verify($password, $user_data[1])) {
                    $user_found = true;
                    break;
                }
            }

            if($user_found) {
                header("Location: welcome.php");
                exit;
            } else {
                $error = "ایمیل یا رمز عبور اشتباه است.";
            }

            break;
    }
}
?>
