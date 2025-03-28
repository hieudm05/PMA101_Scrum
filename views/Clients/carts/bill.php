<div class="container mt-5">
    <h2 class="text-center mb-4">Quản lý Đơn Hàng</h2>

    <!-- Giỏ hàng -->
    <?php foreach ($listBill as $bill) : extract($bill); ?>
        <div class="card mb-3 shadow-lg border-light rounded">
            <div class="card-body d-flex justify-content-between align-items-center">
                
                <!-- Thông tin đơn hàng -->
                <div class="d-flex flex-column">
                    <h5><strong>Mã Đơn Hàng:</strong> <?= $id ?></h5>
                    <p><strong>Trạng thái:</strong> 
                        <?php
                            if ($bill_status == 0) {
                                echo '<span class="badge bg-warning text-dark">Đang chờ xác nhận</span>';
                            } elseif ($bill_status == 1) {
                                echo '<span class="badge bg-info">Đang xử lý</span>';
                            } elseif ($bill_status == 2) {
                                echo '<span class="badge bg-success">Đang giao hàng</span>';
                            } elseif ($bill_status == 3) {
                                echo '<span class="badge bg-primary">Đã giao</span>';
                            } elseif ($bill_status == 4) {
                                echo '<span class="badge bg-danger">Đã chờ hủy</span>';
                            }
                            elseif ($bill_status == 5) {
                                echo '<span class="badge bg-danger">Đã hủy</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Không xác định</span>';
                            }
                        ?>
                    </p>
                </div>

                <!-- Các nút hành động -->
                <div class="d-flex flex-column align-items-end">
                    <!-- Xem chi tiết đơn hàng -->
                    <button type="button" class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#orderModal<?= $id ?>">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </button>
                    
                    <?php if ($bill_status < 2): ?>
                        <!-- Hủy đơn hàng -->
                        <button type="button" class="btn btn-danger btn-sm mb-2" data-bs-toggle="collapse" data-bs-target="#cancelReasonForm<?= $id ?>">
                            Hủy Đơn
                        </button>

                        <!-- Form nhập lý do huỷ (toggle) -->
                        <div id="cancelReasonForm<?= $id ?>" class="collapse mt-3">
                            <form action="?act=huyDon&id=<?=$id?>" method="POST">
                                <input type="hidden" name="order_id" value="<?= $id ?>">
                                <div class="mb-3">
                                    <label for="cancel_reason" class="form-label">Lý do huỷ đơn</label>
                                    <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Xác nhận huỷ</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đơn hàng -->
        <div class="modal fade" id="orderModal<?= $id ?>" tabindex="-1" aria-labelledby="orderModalLabel<?= $id ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="orderModalLabel<?= $id ?>">Chi tiết Đơn Hàng #<?= $id ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Phần thông tin tổng quát -->
                        <div class="mb-4">
                            <h6><strong>Thông Tin Tổng Quát</strong></h6>
                            <p><strong>Email:</strong> <?= $bill_email ?></p>
                            <p><strong>Số Điện Thoại:</strong> <?= $bill_sdt ?></p>
                            <p><strong>Địa Chỉ:</strong> <?= $bill_address ?></p>
                            <p><strong>Ngày Đặt Hàng:</strong> <?= $ngaydathang ?></p>
                            <p><strong>Phương Thức Thanh Toán:</strong> 
                                <?= $bill_pttt == 1 ? 'Thanh toán khi nhận hàng' : 'Thanh toán trực tuyến'; ?>
                            </p>
                            <p><strong>Trạng thái:</strong> 
                                <?php
                                    if ($bill_status == 0) {
                                        echo '🟡 Đang chờ xác nhận';
                                    } elseif ($bill_status == 1) {
                                        echo '🔵 Đang xử lý';
                                    } elseif ($bill_status == 2) {
                                        echo '🟢 Đang giao hàng';
                                    } elseif ($bill_status == 3) {
                                        echo '✅ Đã giao';
                                    } elseif ($bill_status == 4) {
                                        echo 'Đang chờ huỷ';
                                    }
                                    elseif ($bill_status == 5) {
                                        echo 'Đã huỷ';
                                    } else {
                                        echo '❓ Không xác định';
                                    }
                                ?>
                            </p>
                        </div>

                        <!-- Phần bảng thông tin chi tiết đơn hàng -->
                        <h6><strong>Chi Tiết Đơn Hàng</strong></h6>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Chia các giá trị trong mảng từ database
                                $product_names_array = explode(",", $product_names);
                                $product_images_array = explode(",", $product_images);
                                $product_price_array = explode(",", $product_prices);
                                $product_quantity_array = explode(",", $product_quantities);

                                $total_amount = 0; // Khởi tạo tổng số tiền của đơn hàng

                                // Lặp qua từng sản phẩm
                                for ($i = 0; $i < count($product_names_array); $i++) :
                                    // Chuyển giá trị giá sản phẩm và số lượng thành số thực (float) và tính tổng giá trị của sản phẩm
                                    $product_price = floatval($product_price_array[$i]);
                                    $product_quantity = intval($product_quantity_array[$i]);
                                    
                                    // Tính thành tiền cho mỗi sản phẩm
                                    $product_total = $product_price * $product_quantity;

                                    // Cộng dồn tổng thành tiền
                                    $total_amount += $product_total;
                                ?>
                                <tr>
                                    <!-- Hiển thị tên sản phẩm và hình ảnh -->
                                    <td>
                                        <img src="./../<?= $product_images_array[$i] ?>" alt="<?= $product_names_array[$i] ?>" width="50" height="50">
                                        <?= $product_names_array[$i] ?>
                                    </td>
                                    
                                    <!-- Hiển thị số lượng -->
                                    <td><?= $product_quantity ?></td>
                                    
                                    <!-- Hiển thị giá sản phẩm -->
                                    <td><?= number_format($product_price, 0, ',', '.') ?> VNĐ</td>
                                    
                                    <!-- Hiển thị thành tiền của mỗi sản phẩm -->
                                    <td><?= number_format($product_total, 0, ',', '.') ?> VNĐ</td>
                                </tr>
                                <?php endfor; ?>
                                
                                <!-- Hiển thị tổng thành tiền của đơn hàng -->
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>
