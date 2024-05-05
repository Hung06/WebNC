<?php
session_start();
echo"Đăng nhập thành công!";
if ($_SESSION["IsLogin"] == false)
header("Location: Login.html");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="Login.html">
        <br><br>
        <button type="submit" name="dangxuat"> Đăng xuất</button>
    </a>
</body>
</html>