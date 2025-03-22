
<div class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
  <div class="card shadow p-4" style="max-width: 500px; width: 100%; margin-top: -70px">
    <div class=" mb-4">
      <h3 class="text-danger">Đăng ký</h3>
    </div>
    <form action="?act=postAddAcount" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="email" class="form-label">Địa chỉ email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="">
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="username" class="form-label">Tên người dùng</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="">
        </div>
        <div class="col-md-6">
          <label for="contact" class="form-label">Số điện thoại</label>
          <input type="text" class="form-control" id="contact" name="sdt" placeholder="">
        </div>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Nhập mật khẩu của bạn</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="">
      </div>
      <button type="submit" class="btn btn-danger w-100">Đăng ký</button>
    </form>

    <div class="d-flex align-items-center my-3">
      <hr class="flex-grow-1">
      <span class="mx-2">hoặc</span>
      <hr class="flex-grow-1">
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary w-50">Google</button>
      <button class="btn btn-outline-secondary w-50">Facebook</button>
    </div>
    <div class="d-flex justify-content-center gap-2 mt-3">
      <p class="text-secondary">Bạn đã có tài khoản?</p>
      <a href="?act=login" class="text-decoration-none text-danger">Đăng nhập</a>
    </div>
  </div>
  </div> 

 