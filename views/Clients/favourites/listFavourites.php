<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Danh sách yêu thích</li>
        </ol>
    </nav>

    <h2>Danh sách yêu thích</h2>
    <div class="row"> 
        <?php if (!empty($favourites)) : ?>
            <?php foreach ($favourites as $item) :
                $imgPath = '../' . $item['img']; 
                $linkSp = "?act=sanphamchitiet&id=" . $item['product_id'];
            ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mt-2"> 
            <div class="card" style="width: 100%; position: relative;">
                <img src="<?= $imgPath ?>" class="imgHieuUng" alt="<?= htmlspecialchars($item['namesp']) ?>" style="height: 180px; object-fit: cover;">
                
                <form method="POST" action="?act=removeFavourite" style="position: absolute; top: 5px; right: 5px;">
                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 50%; padding: 0; width: 30px; height: 30px;">
                        &times;
                    </button>
                </form>

                <div class="card-body" style="font-size: 0.85rem;">
                    <a href="<?= $linkSp ?>" class="text-dark text-decoration-none">
                        <h5 class="card-title" style="font-size: 1rem;"><?= htmlspecialchars($item['namesp']) ?></h5>
                    </a>
                    <h6><span class="card-text text-danger"><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</span></h6>
                    <a href="?act=sanphamchitiet&id=<?= $item['product_id'] ?>" class="btn btn-danger">Xem chi tiết</a>
                </div>
            </div>
        </div>
    

            <?php endforeach; ?>
        <?php else : ?>
            <p>Danh sách yêu thích của bạn đang trống.</p>
        <?php endif; ?>
    </div>
</div>
