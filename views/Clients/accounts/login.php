
<div class="bg-light d-flex justify-content-center align-items-center mt-5" >

  <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-center text-danger mb-4">Đăng nhập</h3>
    <form action="?act=postLogin" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="username" class="form-label">Nhập tên tài khoản hoặc Email</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="">
      </div>
      <div class="d-flex justify-content-between">

        <a href="?act=forgot_password" class="text-decoration-none text-body-tertiary">Quên mật khẩu?</a>
      </div>
      <button type="submit" class="btn btn-danger w-100 mt-3">Đăng nhập</button>
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
    <p class="text-center mt-3">
      Bạn chưa có tài khoản? <a href="?act=signup" class="text-decoration-none text-danger">Đăng ký</a>
    </p>
  </div>
</div>
