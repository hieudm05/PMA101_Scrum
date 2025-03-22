<style>
    .card-header {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-red {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-red:hover {
        background-color: #a71d2a;
    }

    .text-highlight {
        color: #dc3545;
    }
</style>

<body>
    <div class="container my-5">
        <!-- Thông báo cảm ơn -->
        <div class="text-center mb-4">
            <h1 class="display-5 text-highlight">Cảm ơn bạn đã đặt hàng!</h1>
            <p class="fs-5">Đơn hàng của bạn đã được đặt thành công.</p>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Họ tên:</strong> <?= $listBill[0]['user_name']  ?></p>
                <p><strong>Email:</strong> <?= $listBill[0]['bill_email']  ?></p>
                <p><strong>Số điện thoại:</strong> <?= $listBill[0]['bill_sdt'] ?> </p>
                <p><strong>Địa chỉ:</strong> <?= $listBill[0]['bill_address'] ?></p>
                <p><strong>Phương thức thanh toán:</strong> <?= isset($listBill[0]['bill_pttt']) ? 'Trực tiếp' : 'Online' ?></p>
            </div>
        </div>

        <!-- Chi tiết đơn hàng -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Chi tiết đơn hàng</h5>
            </div>
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

        <!-- Footer -->
        <div class="text-center mt-4">
            <a href="http://localhost/base_test_DA1/public/" class="btn btn-red">Về trang chủ</a>
            <p class="mt-3 text-muted">&copy; 2024 Bannes&noble.</p>
        </div>
    </div>