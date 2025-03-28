<div class="container mt-4">
    <!-- Tiến trình mua hàng -->
    <div class="progress-container mb-4">
        <div class="d-flex justify-content-center align-items-center">
            <div class="progress-steps col-md-6 text-center">
                <span class="text-danger fw-bold"><i class="bi bi-bag-fill"></i> Giỏ hàng</span>
                <i class="bi bi-arrow-right mx-2"></i>
                <span><i class="bi bi-bag-check-fill"></i> Đặt hàng</span>
                <i class="bi bi-arrow-right mx-2"></i>
                <span><i class="bi bi-clipboard-check-fill"></i> Hoàn thành</span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Danh sách sản phẩm -->
        <div class="col-md-8">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $tongCong = 0; $dem = 0; foreach($listCarts as $cart): extract($cart); $tongCong += $thanhtien; ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="productCheckbox" value="<?= $id ?>" name="selectedProducts[]">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="./../<?= $img ?>" alt="Sản phẩm" class="img-thumbnail" style="width: 60px; height: 60px; margin-right: 10px;">
                                <div>
                                    <strong><?= $name ?></strong><br>
                                    <small class="text-muted">Mã hàng: <?= $id ?></small><br>
                                    <small class="text-muted">Số lượng còn lại: <?= $remaining_quantity ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-danger"><?= number_format($price, 0, ',', '.') ?> VND</td>
                        <td>
                            <form action="?act=tangGiam&id=<?= $id?>" method="post" class="d-flex justify-content-center align-items-center">
                                <!-- Nút tăng -->
                                <button class="btn btn-danger mx-2" name="tang" type="submit" style="width: 40px; height: 40px; font-size: 18px;">+</button>
                                
                                <!-- Số lượng sản phẩm -->
                                <input type="number" class="form-control text-center" name="soluong" value="<?= $soluong ?>" style="width: 60px;" readonly>
                                
                                <!-- Nút giảm -->
                                <button class="btn btn-danger mx-2" name="giam" type="submit" style="width: 40px; height: 40px; font-size: 18px;">-</button>
                            </form>
                        </td>


                        <td><?= number_format($thanhtien, 0, ',', '.') ?> VND</td>
                        <td>
                            <form action="?act=deletecart" method="POST">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" name="deletecart" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="http://localhost/base_test_DA1/public/" class="btn btn-warning">Tiếp tục mua hàng</a>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="card-title">Tóm tắt đơn hàng</h5>
                <div class="d-flex justify-content-between">
                    <span>Tạm tính:</span>
                    <span><?= number_format($tongCong, 0, ',', '.') ?> VND</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Phí vận chuyển:</span>
                    <span class="text-success">Miễn phí</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Tổng cộng:</h5>
                    <h5 class="text-danger"><?= number_format($tongCong, 0, ',', '.') ?> VND</h5>
                </div>
                <a href="?act=thanhtoan" class="btn btn-danger btn-block mt-3">Thanh toán</a>
            </div>
        </div>
    </div>
</div>
