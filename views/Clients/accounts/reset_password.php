<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';  // Kết nối cơ sở dữ liệu
    $email = $_GET['email'];
    $password = $_POST['password'];
    
    // Đặt giá trị thời gian hết hạn reset_expires (bạn có thể thay đổi thời gian này tùy ý)
    $expires = date('Y-m-d H:i:s', strtotime('+15 minute'));
    
    // Cập nhật mật khẩu và đặt lại các trường reset_code và reset_expires
    $stmt = $conn->prepare("UPDATE accounts SET password = ?, reset_code = NULL, reset_expires = ? WHERE email = ?");
    $stmt->bind_param('sss', $password, $expires, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Mật khẩu đã được đặt lại thành công!');</script>";
        header("Location: ?act=login");
        exit;
    } else {
        echo "mật khẩu của bạn không đổi được vui lòng thử lại!";
    }
}
?>

<div class="container mt-5">
    <h3>Đặt lại mật khẩu</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Mật khẩu mới</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
    </form>
</div>
