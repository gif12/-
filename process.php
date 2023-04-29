<?php
// تعریف مسیر پوشه
define('APP_PATH', dirname(__FILE__));
define('DATA_PATH', APP_PATH . '/data');

// بررسی وجود پوشه‌ها و فایل‌ها
if(!is_dir(DATA_PATH)) {
    mkdir(DATA_PATH);
}

if(!file_exists(DATA_PATH.'/users.txt')) {
    file_put_contents(DATA_PATH.'/users.txt', '');
}

if(!file_exists(DATA_PATH.'/config.txt')) {
    file_put_contents(DATA_PATH.'/config.txt', '');
}

if(!file_exists(DATA_PATH.'/log.txt')) {
    file_put_contents(DATA_PATH.'/log.txt', '');
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
            $existing_users = file(DATA_PATH.'/users.txt', FILE_IGNORE_NEW_LINES);
            foreach($existing_users as $existing_user) {
                $user_data = explode("|", $existing_user);
                if($user_data[2] === $email) {
                    $error = "این ایمیل قبلا در سیستم ثبت شده است.";
                    break;
                }
            }

            if(empty($error) && strlen($username) >= 4 && strlen($password) >= 6 && $password === $confirm_password && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // اضافه کردن کاربر جدید
                $user_data = "$username|$email|".md5($password);
                file_put_contents(DATA_PATH.'/users.txt', $user_data.PHP_EOL, FILE_APPEND);
                header("Location: index.php?success");
                exit;
            } else {
                    $error = "لطفاً فرم ثبت نام را با دقت پر کنید.";
            }

            break;

        case 'login':
            // ورود به سایت
            $email = $_POST['email'];
            $password = $_POST['password'];

            // جستجوی کاربر برای ورود
            $existing_users = file(DATA_PATH.'/users.txt', FILE_IGNORE_NEW_LINES);
            foreach($existing_users as $existing_user) {
                $user_data = explode("|", $existing_user);
                if($user_data[1] === $email && $user_data[2] === md5($password)) {
                    // ورود به سایت
                    $_SESSION['user_email'] = $user_data[1];
                    header("Location: welcome.php");
                    exit;
                }
            }

            $error = "نام کاربری و یا کلمه عبور شما اشتباه است.";
            break;
    }
}
