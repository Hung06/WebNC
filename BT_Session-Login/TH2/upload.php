<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testlogin";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hàm để xác định loại tệp
function getFileType($fileName) {
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        return "image";
    } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
        return "document";
    } else {
        return "other";
    }
}

foreach($_FILES['myfile']['error'] as $key => $error){
    if($error == 0){
        $tmp_name = $_FILES['myfile']['tmp_name'][$key];
        $name = $_FILES['myfile']['name'][$key];
        $fileSize = $_FILES['myfile']['size'][$key];
        // Sử dụng hàm getFileType để lấy loại tệp
        $fileType = getFileType($name);
        $datePart = date("Ymd");
        $uploadTime = date("Y-m-d H:i:s");
        if($fileSize > 2097152){
            echo "Kích thước tệp vượt quá giới hạn cho phép.";
            continue;
        }
        
        $sha1Part = sha1_file($tmp_name);
        $newFileName = $datePart . "_" . $sha1Part;
        move_uploaded_file($tmp_name, "upload/$newFileName");
        $sql = "INSERT INTO files (file_name, file_size, file_type, upload_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // Thay đổi chuỗi định nghĩa kiểu để phù hợp với số lượng biến
        $stmt->bind_param("sdsd", $newFileName, $fileSize, $fileType, $uploadTime);
        $stmt->execute();
    }
}
header("Location: dsfile.php");
?>
