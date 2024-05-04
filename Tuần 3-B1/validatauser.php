<?php
session_start();

// Kết nối CSDL và kiểm tra thông tin đăng nhập
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy dữ liệu từ form đăng nhập
    $username = $_POST['username'];
    $password = $_POST['password'];
    /*if($username == "user1" && $password = "1"){
        echo "Welcome " .$username;
    } else{
        echo"Password khong chinh xac";
    }*/

    // Kiểm tra thông tin đăng nhập
    $stmt = $conn->prepare("SELECT * FROM loginuser WHERE user = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Nếu thông tin đăng nhập hợp lệ
    if ($stmt->rowCount() == 1) {
        // Thiết lập biến Session
        $_SESSION["IsLogin"] = true;
        header("Location: dashboard.php"); // Chuyển hướng đến trang dashboard
    } else {
        // Nếu thông tin đăng nhập không hợp lệ, redirect về trang login
        header("Location: login.htm");
    } 
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
$conn = null;
?>
