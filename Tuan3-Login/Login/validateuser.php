<?php
session_start(); // Khởi tạo phiên làm việc

// Kiểm tra xem có dữ liệu được gửi từ biểu mẫu POST hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối đến cơ sở dữ liệu
    $servername = "localhost";
    $username = "root"; // Thay thế username bằng tên người dùng của bạn
    $password = ""; // Thay thế password bằng mật khẩu của bạn
    $dbname = "form_login"; // Thay thế myDB bằng tên cơ sở dữ liệu của bạn

    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ biểu mẫu
    $email_or_phone = $_POST['username'];
    $pass = sha1($_POST['password']);

    // Xử lý dữ liệu trước khi truy vấn
    $email_or_phone = mysqli_real_escape_string($conn, $email_or_phone);
    $pass = mysqli_real_escape_string($conn, $pass);

    // Tạo truy vấn SQL để kiểm tra xem người dùng có tồn tại không
    $sql = "SELECT * FROM Users WHERE email_or_phone = '$email_or_phone' AND password = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Đăng nhập thành công
        $_SESSION["IsLogin"] = true; // Thiết lập biến Session cho trạng thái đăng nhập thành công
        header("Location: Logout.php"); // Chuyển hướng đến trang welcome.php
        exit(); // Kết thúc kịch bản PHP
    } else {
        // Đăng nhập thất bại
        header("Location: Login.html"); // Chuyển hướng đến trang login.htm
        exit(); // Kết thúc kịch bản PHP
    }

    $conn->close(); // Đóng kết nối với cơ sở dữ liệu
}
?>
