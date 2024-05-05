
<?php
include "upload.php";

session_start();

if (isset($_GET['file'])) {
    $fileName = $_GET['file'];

    // Xóa tệp từ ổ đĩa
    $filePath = "upload/$fileName";
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Xóa thông tin tệp khỏi cơ sở dữ liệu
    $sqlDelete = "DELETE FROM files WHERE file_name = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("s", $fileName);
    $stmtDelete->execute();
}

// Chuyển hướng sau khi hoàn thành xóa
header("Location: dsfile.php");

?>
