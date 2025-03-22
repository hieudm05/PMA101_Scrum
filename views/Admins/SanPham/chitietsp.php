<div class="container my-5">
        <!-- Chi tiết đơn hàng -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Chi tiết đơn hàng</h5>
            </div>
            <div class="card-body">
                <?php
                // Chia các giá trị trong mảng từ database
                $product_names_array = explode(",", $listBill[0]['product_names']);
                $product_images_array = explode(",", $listBill[0]['product_images']);
                $product_price_array = explode(",", $listBill[0]['product_prices']);
                $product_quantity_array = explode(",", $listBill[0]['product_quantities']);

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
                    <!-- Sản phẩm -->
                    <div class="row align-items-center mb-4">
                        <!-- Hình ảnh sản phẩm -->
                        <div class="col-md-3 text-center ">
                            <img  src="../../<?= $product_images_array[$i] ?>" class="img-fluid rounded card" alt="<?= $product_names_array[$i] ?>" style="max-width: 100%; height: auto;">
                        </div>
                        
                        <!-- Thông tin sản phẩm -->
                        <div class="col-md-6">
                            <h6><strong>Tên sản phẩm:</strong> <?= $product_names_array[$i] ?></h6>
                            <p><strong>Giá:</strong> <?= number_format($product_price, 0, ',', '.') ?> VNĐ</p>
                            <p><strong>Số lượng:</strong> <?= $product_quantity ?></p>
                            <p><strong>Thành tiền:</strong> <?= number_format($product_total, 0, ',', '.') ?> VNĐ</p>
                        </div>
                    </div>
                <?php endfor; ?>
                <?php if ($listBill[0]['bill_status'] == 4) : ?>
                <div><strong>Lý do huỷ đơn:</strong> <?= isset($lydo) ? '<mark>' . $lydo . '</mark>' : 'Không có lý do hủy' ?> </div>
            <?php endif; ?>



                <!-- Tổng cộng -->
                <div class="text-end">
                    <h5 class="text-danger mb-0">Tổng cộng: <?= number_format($total_amount, 0, ',', '.') ?> VNĐ</h5>
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> <?= $listBill[0]['user_name'] ?></p>
                <p><strong>Email:</strong> <?= $listBill[0]['bill_email'] ?></p>
                <p><strong>Số điện thoại:</strong> <?= $listBill[0]['bill_sdt'] ?></p>
                <p><strong>Địa chỉ:</strong> <?= $listBill[0]['bill_address'] ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?= isset($listBill[0]['bill_pttt']) ? 'Trực tiếp' : 'Online' ?></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <a href="http://localhost/base_test_DA1/views/Admins/router.php" class="btn btn-danger">Về trang chủ</a>
            <p class="mt-3 text-muted">&copy; 2024 Bannes&noble.</p>
        </div>
    </div>