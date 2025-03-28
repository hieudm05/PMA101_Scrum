
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Sửa danh mục</h3>
                <a href="router.php?act=listDm" class="btn btn-primary btn-sm">Danh sách danh mục</a>
            </div>
            <div class="card-body">
                <div class="col-sm-8">
                    <form action="router.php?act=postSuaDm" method="post">
                    <input type="hidden" name="id" value="<?php echo $danhMuc['id'] ?>">
                        <div class="form-group">
                            <label for="name">Tên loại</label>
                            <input id="name" class="form-control" type="text" name="name" value="<?= $danhMuc['name'] ?>" required>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Cập nhật">
                        <input type="reset" class="btn btn-outline-secondary" value="Nhập lại">
                    </form>
                </div>
            </div>
        </div>
    </div>

