<div class="container">  
    <div class="card">  
        <div class="card-header d-flex justify-content-between align-items-center">  
            <h3 class="mb-0">Thêm sản phẩm</h3>  
            <a href="router.php?act=listSP" class="btn btn-primary btn-sm">Danh sách sản phẩm</a>  
        </div>  
        <div class="card-body">  
            <div class="col-sm-8">  
                <form action="router.php?act=postSP" method="post" enctype="multipart/form-data">  
                    <div class="form-group">  
                        <label for="danhmuc">Danh mục</label>  
                        <select name="iddm" id="danhmuc" class="form-control">  
                            <option value="">Chọn danh mục</option>  
                            <?php if (!empty($listDanhMuc)): ?>  
                                <?php foreach ($listDanhMuc as $danhMuc): ?>
                                    <option value="<?= $danhMuc['id'] ?>"><?= $danhMuc['name'] ?></option>  
                                <?php endforeach; ?>  
                            <?php else: ?>  
                                <option value="" disabled>Không có danh mục nào</option>  
                            <?php endif; ?>  
                        </select>  
                    </div>  
                    
                    <div id="product-list"></div>  
                    <div class="form-group">  
                        <label for="tensanpham">Tên sản phẩm</label>  
                        <input id="tensanpham" class="form-control" type="text" name="namesp" required>  
                    </div>  
                    <div class="form-group">  
                        <label for="gia">Giá</label>  
                        <input id="gia" class="form-control" type="number" name="price" min="0" value="0" required>  
                    </div> 
                    <div class="form-group">  
                        <label for="soluong">Số Lượng</label>  
                        <input id="soluong" class="form-control" type="number" name="id_soluong" min="1" value="1" required>  <!-- Changed from 'soluong' to 'id_soluong' -->  
                    </div>  
                    <div class="form-group">  
                        <label for="hinhanh">Hình Ảnh</label>  
                        <input id="hinhanh" class="form-control" type="file" name="img" accept="img/*" required>  
                    </div>  
                    <div class="form-group">
                        <label for="mota">Mô tả</label>    
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="mota" rows="10"></textarea>  
                    </div>  
                    <input type="submit" class="btn btn-primary" name="themmoi" value="Thêm">  
                    <input type="reset" class="btn btn-outline-secondary" value="Nhập lại">  
                </form>  
            </div>  
        </div>  
    </div>  
</div>