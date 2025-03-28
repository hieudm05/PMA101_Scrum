
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center text-danger fw-bold mb-4">Thông Tin Tài Khoản</h2>
                        <div class="row">
                            <!-- Avatar -->
                            <div class="col-md-3 text-center">
                            <img src="<?= $avt?>" alt="Avatar" class="rounded-circle border border-danger mb-3" width="100" height="100">
                            <form method="post" action="?act=updateAcount" enctype="multipart/form-data">
                            <input type="hidden" name="current_img" value="<?= $avt ?>">
                            <input type="file" class="form-control form-control-sm" id="avatar" name="avatar" aria-label="Chọn ảnh">
                            </div>

                            <!-- Form thông tin -->
                            <div class="col-md-9">
                              
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Tên đăng nhập</label>
                                            <input type="hidden" name="id" value="<?= $id ?>">
                                            <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $email?>" placeholder="Nhập email của bạn">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" name="sdt" value="<?= $sdt ?>" placeholder="Nhập số điện thoại của bạn">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control" id="address" name="address" value="<?= $address  ?>" placeholder="Nhập địa chỉ cụ thể của bạn">
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-danger">Lưu Thay Đổi</button>
                                    </div>
                                </form>
                                <!-- Kết thúc form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
