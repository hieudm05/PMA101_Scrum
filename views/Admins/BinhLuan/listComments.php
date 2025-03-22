<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Danh Sách Bình Luận</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Khách Hàng</th>
                        <th>Sản Phẩm</th>
                        <th>Nội Dung</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listBinhLuan)) : ?>
                        <?php foreach ($listBinhLuan as $comment) : ?>
                            <tr>
                                <td><?= htmlspecialchars($comment['id']) ?></td>
                                <td><?= htmlspecialchars($comment['customer_name'] ?? 'Ẩn danh') ?></td>
                                <td><?= htmlspecialchars($comment['product_name'] ?? 'Không xác định') ?></td>
                                <td><?= htmlspecialchars($comment['noidung']) ?></td>
                                <td><?= htmlspecialchars($comment['time']) ?></td>
                                <td><?= $comment['status'] ? 'Hiện' : 'Ẩn' ?></td>
                                <td>
                                    <a href="router.php?act=toggleComment&id=<?= htmlspecialchars($comment['id']) ?>" 
                                    class="btn btn-warning btn-sm">
                                    <?= $comment['status'] ? 'Ẩn' : 'Hiện' ?>
                                    </a>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có bình luận nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
