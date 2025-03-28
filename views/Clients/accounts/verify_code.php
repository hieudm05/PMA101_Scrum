
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';
    $email = $_GET['email'];
    $code = $_POST['code'];

    $stmt = $conn->prepare("SELECT reset_expires FROM accounts WHERE email = ? AND reset_code = ?");
    $stmt->bind_param('ss', $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && strtotime($user['reset_expires']) > time()) {
        
        header("Location: ?act=reset_password&email=$email");
        exit;
    } else {
        echo "<script>alert('Mã xác nhận không hợp lệ!');</script>";
    }
}
?>

<div class="container mt-5">
    <h3>Nhập mã xác nhận</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Mã xác nhận</label>
            <input type="text" class="form-control" name="code" required>
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>
</div>
