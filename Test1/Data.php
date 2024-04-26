<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test1";
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}
$sql = "SELECT SinhVien.MSSV, SinhVien.HoTen, DangKy.Ky, DangKy.MaMH
FROM SinhVien
INNER JOIN DangKy ON SinhVien.MSSV = DangKy.MSSV";
$result=$conn->query($sql);
if($result->num_rows>0){
    echo "<table><tr><th>MSSV</th><th>Họ tên</th><th>Kỳ</th><th>Đăng ký</th></tr>";
    while($row = $result->fetch_assoc()){
        echo "<tr ><td>".$row["MSSV"]."</td><td>".$row["HoTen"]."</td><td>".$row["Ky"]."</td><td>".$row["MaMH"]."</td></tr>";
    }
    echo "</table>";
    } else {
        echo "Không có dữ liệu.";
    }
// Đóng kết nối
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table {
  width: 100%;
  margin: 20px auto;
  table-layout: auto;
}
table,
td,
th {
  border-collapse: collapse;
}

th,
td {
  padding: 10px;
  border: solid 1px;
  text-align: center;
}
</style>
<body>
    <h1>Test1</h1>

</body>
</html>

