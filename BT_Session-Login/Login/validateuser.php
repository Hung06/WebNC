<?php
session_start();
$_SESSION["IsLogin"] = false;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testlogin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$user = $_POST['usernameForm'];
$pass = SHA1($_POST['passwordForm']);
$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION["IsLogin"] = true;
    
    header("Location: Logout.php");
    exit; 
} else {
    header("Location: login.htm");
    exit; 
    $message = "Tài khoản không chính xác";
    echo "<script>alert('$message');</script>";
}
if ($_SESSION["IsLogin"] == false) {
    header("Location: login.htm");
    exit; 
}
$stmt->close();
$conn->close();