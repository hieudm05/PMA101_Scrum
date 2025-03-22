<div class="container mt-3 ">
<div class="d-flex justify-content-center align-items-center">
            <div class="d-flex justify-content-evenly col-md-6 mt-2">
                <span ><i class="bi bi-bag-fill"></i> Giỏ hàng</span>
                <i class="bi bi-arrow-right"></i>
                <span class="text-danger fw-bold"><i class="bi bi-bag-check-fill"></i> Đặt hàng</span>
                <i class="bi bi-arrow-right"></i>
                <span><i class="bi bi-clipboard-check-fill"></i> Hoàn thành</span>
            </div>
        </div>
    <div class="row d-flex justify-content-center align-items-center">
       
        <!-- Phần thông tin giao hàng -->
        <div class="col-md-7 ">
            <div class="p-3 shadow-sm rounded">
                <h5 class="mb-3">Thông tin giao hàng</h5>
                <form action="?act=billconfirm" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full-name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="username" id="full-name" value="<?= $nameUser ?>" placeholder="Nhập họ và tên">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone-number" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="sdt" id="phone-number" value="<?= $sdt ?>" placeholder="Nhập số điện thoại">
                    </div>
                </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ giao hàng</label>
                        <input type="text" class="form-control" id="address" name="address"  value="<?= $address?>"  placeholder="Nhập địa chỉ giao hàng">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Email</label>
                        <input type="email" class="form-control" id="address" name="email"  value="<?= $email?>"  placeholder="Nhập địa chỉ email">
                    </div>
                    <!-- <div class="mb-3">
                        <label for="delivery-method" class="form-label">Phương thức giao hàng</label>
                        <select name="pttt" class="form-control" id="delivery-method">
                            <option value="1">Giao hàng tiêu chuẩn (3-5 ngày)</option>
                            <option value="2">Giao hàng nhanh (1-2 ngày)</option>
                        </select>
                    </div> -->
                    <div class="mb-3">
                        <label class="form-label">Phương thức thanh toán</label>
                        <div class="d-flex gap-2">
                            <!-- Nút Thanh toán khi nhận hàng -->
                            <button  type="button" class="btn btn-outline-danger" id="btn-cod" onclick="selectPaymentMethod(1)">
                                Thanh toán COD
                            </button>

                            <!-- Nút Thanh toán trực tuyến -->
                            <button type="button" name="payUrl" class="btn btn-outline-danger" id="btn-online" onclick="selectPaymentMethod(2)">
                                Thanh toán Online
                            </button>
                        </div>

                    <!-- Input ẩn để lưu giá trị đã chọn -->
                    <input type="hidden" name="pttt" id="payment-method-hidden" value="<?= isset($_POST['pttt']) ? $_POST['pttt'] : ''; ?>">
                </div>

            </div>
        </div>

        <!-- Phần tóm tắt đơn hàng -->
        <div class="col-md-4">
            <div class="p-3 shadow-sm rounded">
                <h5 class="mb-3">Đơn hàng</h5>
                <!-- <div class="d-flex justify-content-between">
                    <span>Tạm tính:</span>
                    <span class="text-danger">4,040,000₫</span>
                </div> -->
                <div class="d-flex justify-content-between">
                    <span>Phí vận chuyển:</span>
                    <span class="text-success">Miễn phí</span>
                </div>
                <hr>
                  <!-- Thêm ô nhập Voucher -->
                  
                <div class="d-flex justify-content-between">
                    <span class="text-danger">Tạm tính:</span>
                    <span class=""><?= number_format($tongCong, 0, ',', '.') ?> VND</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5 class="text-danger">Tổng tiền:</h5>
                    <h5 class="text-danger"><?= number_format($tongCong, 0, ',', '.') ?> VNĐ</h5>
                </div>
                <button name="payUrl"  class="btn btn-danger">Hoàn thành đơn hàng <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
        </form>
    </div>
    <div class="d-flex justify-content-center align-items-center">
    <div class="col-sm-11 ">
    <table class="table table-bordered table-striped table-hover">
    <thead class="thead-light">
        <tr>
            <th>STT</th>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $tongCong = 0; 
    $dem=0; 
    foreach($listCarts as $cart): 
        extract($cart);
        $imgPath = './../' . $img; 
        $tongCong += $thanhtien; 
    ?>
        <tr>
            <td>
                <?= $dem +1 ?>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <img src="<?= $imgPath ?>" alt="Sản phẩm" class="img-fluid" style="max-width: 60px; margin-right: 10px;">
                    <div>
                        <strong><?= $name ?></strong><br>
                        <small class="text-muted">Số lượng còn lại: <?= $remaining_quantity ?></small><br>
                        <small class="text-muted">Mã hàng: <?= $id ?></small>
                    </div>
                </div>
            </td>
            <td class="text-danger">
                <?= number_format($price, 0, ',', '.') ?> VND
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control text-center" value="<?= $soluong ?>" style="max-width: 50px;">
                </div>
            </td>
            <td><?= number_format($thanhtien, 0, ',', '.') ?> VND</td>
        </tr>
        <?php $dem+=1; ?>
    <?php endforeach; ?>
    </tbody>
</table>
    </div>
    </div>
</div>
</form>

<script>
    function selectPaymentMethod(value) {
        // Gán giá trị đã chọn vào input hidden
        document.getElementById('payment-method-hidden').value = value;

        // Xóa trạng thái active của cả hai nút
        document.getElementById('btn-cod').classList.remove('btn-danger');
        document.getElementById('btn-cod').classList.add('btn-outline-danger');
        document.getElementById('btn-online').classList.remove('btn-danger');
        document.getElementById('btn-online').classList.add('btn-outline-danger');

        // Gắn trạng thái active vào nút đã chọn
        if (value == 1) {
            document.getElementById('btn-cod').classList.remove('btn-outline-danger');
            document.getElementById('btn-cod').classList.add('btn-danger');
        } else if (value == 2) {
            document.getElementById('btn-online').classList.remove('btn-outline-danger');
            document.getElementById('btn-online').classList.add('btn-danger');
        }
    }
</script>

