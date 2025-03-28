<div class="container">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Danh Sách Đơn Hàng</h3>
    </div>
    <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3 d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control me-2" placeholder="Tìm kiếm đơn hàng" aria-label="Search" style="width: 400px;">
            <button id="searchButton" class="btn btn-primary" style="width: 120px;" type="button">Tìm kiếm</button>
        </div>

        <!-- Grid to display orders -->
        <div class="row">
            <?php foreach($listOrders as $order): ?>
                <?php   
                    $updateStatus = "router.php?act=updateOrder&id=" . $order['id'];
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Đơn Hàng #<?= $order['id'] ?></h5>
                            <p class="card-text"><strong>Khách Hàng:</strong> <?= $order['user_name'] ?></p>
                            <p class="card-text"><strong>Sản Phẩm:</strong> <?= $order['product_names'] ?></p>
                            <p class="card-text"><strong>Ngày Đặt Hàng:</strong> <?= date('d/m/Y', strtotime($order['ngaydathang'])) ?></p>
                            <p class="card-text"><strong>Số Lượng:</strong> <?= $order['quantity'] ?></p>
                            <p class="card-text"><strong>Tổng Giá Trị:</strong> <?= number_format($order['total'], 0, ',', '.') ?> ₫</p>
                            <p class="card-text"><strong>Phương Thức Thanh Toán:</strong> <?= $order['bill_pttt'] == 1 ? 'Thanh toán trực tiếp' : 'Chuyển khoản' ?></p>


                            <form action="<?= $updateStatus ?>" method="POST">
                            <div class="form-group">
                                <label for="statusSelect<?= $order['id'] ?>">Tình Trạng</label>
                                <select name="bill_status" id="statusSelect<?= $order['id'] ?>" class="form-control">
                                    <option value="0" <?= $order['bill_status'] == 0 ? 'selected' : '' ?>>Đơn hàng mới</option>
                                    <option value="1" <?= $order['bill_status'] == 1 ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="2" <?= $order['bill_status'] == 2 ? 'selected' : '' ?>>Đang giao hàng</option>
                                    <option value="3" <?= $order['bill_status'] == 3 ? 'selected' : '' ?>>Đã giao</option>
                                    <?php if($order['bill_status'] == 4 || $order['bill_status'] ==5): ?>
                                    <option value="4" <?= $order['bill_status'] == 4 ? 'selected' : '' ?>>Xác nhận huỷ</option>
                                    <option value="5" <?= $order['bill_status'] == 5 ? 'selected' : '' ?>>Huỷ đơn hàng</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-warning">Cập Nhật</button>
                        </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
