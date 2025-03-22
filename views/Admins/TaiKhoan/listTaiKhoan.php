<div class="container">
        <h2 class="mb-3">Danh sách tài khoản</h2>
        <table class="table table-bordered table-responsive">
            <thead class="thead-light">
                <tr>
                    <th>Avatar</th>
                    <th>Tên đăng nhập</th>
                    <th>Mật khẩu</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listTaiKhoan as $acount): ?>
                <tr>
                    <td><img src="<?= $acount['avatar'] ?>" height="50" alt="Avatar"></td>
                    <td><?= $acount['username'] ?></td>
                    <td><?= $acount['password'] ?></td>
                    <td><?= $acount['email'] ?></td>
                    <td><?= $acount['sdt'] ?></td>
                    <td><?= $acount['address'] ?></td>
                    <td>
                        <form action="router.php?act=update_account_role" method="POST">
                            <input type="hidden" name="id" value="<?= $acount['id'] ?>">
                            <select name="role" class="form-select form-select-sm" aria-label="Small select example">
                                <option value="0" <?= $acount['role'] == 0 ? 'selected' : '' ?>>Khách</option>
                                <option value="1" <?= $acount['role'] == 1 ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-warning btn-sm mt-2">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form action="router.php?act=update_account_status" method="POST">
                            <input type="hidden" name="id" value="<?= $acount['id'] ?>">
                            <input type="hidden" name="active" value="<?= $acount['active'] == 1 ? 0 : 1 ?>">
                            <button type="submit" class="btn <?= $acount['active'] == 1 ? ' btn-success' : 'btn-danger' ?> btn-sm">
                                <?= $acount['active'] == 1 ? 'Hiện' : 'Ẩn' ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
