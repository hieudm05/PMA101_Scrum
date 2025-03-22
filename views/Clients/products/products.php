<div class="container mt-3">
    <div class="row"> <!-- Sử dụng <div class="row"> để tạo hàng cho các sản phẩm -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kết quả tìm kiếm "<?=$search ?>"</li>
            </ol>
        </nav>

        <?php foreach ($datasSearch as $product):
            $linkSp = "?act=sanphamchitiet&id=" . $product["id"];
            $imgPath = '../'.$product['img'];
        ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mt-2"> <!-- Điều chỉnh các kích thước cột cho phù hợp -->
                <div class="card" style="width: 100%; position: relative;">
                    <img src="<?= $imgPath ?>" class="imgHieuUng" alt="<?= $product['namesp'] ?>" style="height: 180px; object-fit: cover;">
                    <div class="card-body" style="font-size: 0.85rem;">
                        <a href="<?=$linkSp?>" class="text-dark text-decoration-none"><h5 class="card-title" style="font-size: 1rem;"><?= $product['namesp'] ?></h5></a>
                        <h6><span class="card-text text-danger"><?= $product['price'] ?>đ</span></h6>
                    </div>
                    <!-- Nút giỏ hàng và yêu thích -->
                    <div class="product-actions">
                        <button class="btn-cart"><i class="bi bi-cart-plus"></i></button>
                        <button class="btn-wishlist"><i class="bi bi-heart"></i></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>  <!-- End of .row -->
</div>
