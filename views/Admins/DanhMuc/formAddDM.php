<div class="container">
    <div class="card ">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Thêm danh mục</h3>
          <a href="router.php?act=listDm" class="btn btn-primary btn-sm">Danh sách danh mục</a>
        </div>
        <div class="card-body">
            <div class="col-sm-8">
                <form action="router.php?act=postDm" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tenloai">Tên loại</label>
                        <input id="tenloai" class="form-control" type="text" name="name" required>
                    </div>
                    <input type="submit" class="btn btn-primary" name="themmoi" value="Thêm">
                    <input type="reset" class="btn btn-outline-secondary" value="Nhập lại">
                </form>
            </div>
        </div>
    </div>
</div>
