<!DOCTYPE html>
<html>
<head>
    <style>
        /* CSS để căn giữa bảng */
        .table-container {
            margin: 0 auto; /* Căn giữa theo chiều ngang */
            width: 80%; /* Độ rộng của bảng */
        }
        table {
            border: solid 1px black;
            width: 100%; /* Bảng chiếm toàn bộ chiều rộng của container */
        }
        th, td {
            border: solid 1px black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="table-container">
    <table>
        <tr>
            <th>MSSV</th>
            <th>Họ Tên</th>
            <th>Kỳ</th>
            <th>Môn Học</th>
        </tr>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "123456";
        $dbname = "database";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT SinhVien.MSSV, SinhVien.HoTen, MonHoc.TenMH AS TenMH, DangKy.Ky
                                    FROM SinhVien
                                    INNER JOIN DangKy ON SinhVien.MSSV = DangKy.MSSV
                                    INNER JOIN MonHoc ON MonHoc.MaMH = DangKy.MaMH");
            $stmt->execute();

            // Hiển thị dữ liệu
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['MSSV']."</td>";
                echo "<td>".$row['HoTen']."</td>";
                echo "<td>".$row['Ky']."</td>";
                echo "<td>".$row['TenMH']."</td>";
                echo "</tr>";
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
    </table>
</div>

</body>
</html>