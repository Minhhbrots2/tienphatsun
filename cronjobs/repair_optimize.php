<?php
// Cấu hình kết nối MySQL
$host = 'localhost'; // Địa chỉ máy chủ
$username = 'root';  // Tên đăng nhập MySQL
$password = '';      // Mật khẩu MySQL
$database = 'ten_database'; // Tên cơ sở dữ liệu bạn muốn tối ưu

// Kết nối tới cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

echo "Đã kết nối thành công.\n";

// Lấy danh sách tất cả các bảng trong cơ sở dữ liệu
$result = $conn->query("SHOW TABLES");
if (!$result) {
    die("Lỗi khi lấy danh sách bảng: " . $conn->error);
}

while ($row = $result->fetch_row()) {
    $table = $row[0];

    // Sửa chữa bảng
    $repair = $conn->query("REPAIR TABLE `$table`");
    if ($repair) {
        echo "Sửa chữa bảng `$table` thành công.\n";
    } else {
        echo "Lỗi khi sửa chữa bảng `$table`: " . $conn->error . "\n";
    }

    // Tối ưu bảng
    $optimize = $conn->query("OPTIMIZE TABLE `$table`");
    if ($optimize) {
        echo "Tối ưu bảng `$table` thành công.\n";
    } else {
        echo "Lỗi khi tối ưu bảng `$table`: " . $conn->error . "\n";
    }
}

// Đóng kết nối
$conn->close();
echo "Hoàn thành.";
?>