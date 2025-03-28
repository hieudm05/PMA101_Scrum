
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php'; // Kết nối database
    $email = $_POST['email'];
    $code = rand(100000, 999999);
    $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    $stmt = $conn->prepare("UPDATE accounts SET reset_code = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param('sss', $code, $expires, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Gửi email mã xác nhận (mã giả lập)
        echo "<script>
            alert('Mã xác nhận của bạn là: $code');
            window.location.href = '?act=verify_code&email=$email';
        </script>";
        exit;
    } else {
        echo "Email không tồn tại!";
    }
}
?>


<div class="container mt-5">
    <h3>Quên mật khẩu</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Gửi mã xác nhận</button>
    </form>
</div>
