<?php
session_start();

// Kiểm tra xem biến $_FILES['myfile'] có tồn tại và không rỗng
if (isset($_FILES['myfile']) && is_array($_FILES['myfile'])) {
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

    // Hàm mã hoá tên tệp
    function encryptFileName($fileName) {
        $randomString = bin2hex(random_bytes(8)); // Tạo chuỗi ngẫu nhiên với 8 bytes
        return date("ymd") . "_" . $randomString; // Định dạng tên tệp
    }

    foreach ($_FILES['myfile']['error'] as $key => $error) {
        if ($error == 0) {
            $tmp_name = $_FILES['myfile']['tmp_name'][$key];
            $name = $_FILES['myfile']['name'][$key];
            $new_name = encryptFileName($name); // Mã hoá tên tệp
            $upload_path = "upload/$new_name";

            // Di chuyển tệp vào thư mục upload
            move_uploaded_file($tmp_name, $upload_path);

            // Lấy thông tin về tệp
            $file_type = mime_content_type($upload_path);
            $upload_date = date("Y-m-d");
            $file_size = filesize($upload_path);

            // Chèn thông tin về tệp vào cơ sở dữ liệu
            $sql = "INSERT INTO Files (FileName, FileType, UploadDate, FileSize) VALUES ('$new_name', '$file_type', '$upload_date', $file_size)";

            if ($conn->query($sql) === TRUE) {
                echo "Tệp $name đã được tải lên thành công và thông tin đã được lưu vào cơ sở dữ liệu.";
                header("Location: hienthifile.php"); 
            } else {
                echo "Lỗi khi thêm thông tin về tệp vào cơ sở dữ liệu: " . $conn->error;
            }
        }
    }

    // Đóng kết nối
    $conn->close();
} else {
    // Xử lý khi không có tệp nào được tải lên
    echo "Không có tệp nào được tải lên.";
}
?>
