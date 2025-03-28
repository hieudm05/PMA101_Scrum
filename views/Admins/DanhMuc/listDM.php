<div class="container">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Danh sách loại hàng</h3>
          <a href="router.php?act=addDm" class="btn btn-primary btn-sm">Thêm Danh Mục Mới</a>
        </div>
        <div class="card-body">
          <table class="table table-light">
            <thead class="thead-dark">
              <tr>
                <th>Mã loại</th>
                <th>Tên loại</th>
                <th class = "text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php  foreach ($listDanhMuc as $key => $danhMuc ) : ?>
                  <?php  
                    $suadm = "router.php?act=formSuaDm&id=" . $danhMuc['id'];
                    $xoadm = "router.php?act=xoadm&id=" . $danhMuc['id']; 
                  ?>
                
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $danhMuc['name'] ?></td>
                    <td class = "text-end">
                    <a href="<?= $suadm ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                    <a href="<?= $xoadm ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa sản phẩm')">
                      <i class="bi bi-trash-fill"></i>
                    </a>
                    </td>
                </tr>
              <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>