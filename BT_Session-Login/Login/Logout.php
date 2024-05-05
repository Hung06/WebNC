<?php
session_start();
if ($_SESSION["IsLogin"] == false) {
    header("Location: login.htm");
    exit; 
}else{
$message = "Tài khoản chính xác";
echo "<script>alert('$message');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Test login</h1>
    <a href="login.htm">
        <button type="submit" name="dangxuat"> Đăng xuất</button>
    </a>
</body>
</html>
