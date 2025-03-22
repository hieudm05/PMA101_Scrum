<div class="container my-4">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 bg-light border-end d-none d-md-block">
            <h5 class="text-uppercase py-3 ps-3">Danh mục</h5>
            <?php foreach( $listDanhMuc  as $dm)  :?>
                <?php 
                // var_dump($dm);
                $linkDm = "?act=danhmuc&id=" . $dm["id"];
                ?>
            <ul class="list-unstyled ps-3">
                <li class="d-flex justify-content-between align-items-center py-2">
                    <a class="text-dark text-decoration-none" href="<?= $linkDm ?>"><?= $dm["name"] ?></a>
                </li>
            </ul>
            <?php endforeach; ?>
            
            <!-- Sản phẩm bán chạy đây nhé -->
            <div class="container mt-3">
                <h5 class="">Sách mới bán chạy</h5>
                <div class="d-flex flex-wrap">
                <?php foreach($top10 as $top) :  $imgPath = '../'.$top['img'];  
                $linkSp = "?act=sanphamchitiet&id=" . $top["id"];?>
                    <div class="col-sm-12 mt-2">
                        <div class="d-flex border p-2 position-relative">
                            <div class="badge bg-danger text-light position-absolute" style="top: -10px; left: 10px; z-index: 1;">20%</div>
                            <img  src="<?=  $imgPath ?>" alt="Sách Nổi Bật" style="height: 100px; object-fit: cover; width: 120px;">
                            <div class="ms-3" style="flex-grow: 1; font-size: 0.85rem;">
                                <a href="<?= $linkSp ?>"><h5 style="font-size: 1rem;"><?= $top['namesp'] ?></h5></a>
                                <p><span class="text-danger"><?=$top['price'] ?></span> <del>109,000đ</del></p>
                            </div>
                        </div>
                       
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>


                </div>
        <!-- Banner Section  -->
<div class="col-md-9">
            
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="./img/home_slider_image_1.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="./img/home_slider_image_2.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="./img/home_slider_image_4.webp" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            </div>
        <div class="container d-flex justify-content-between flex-wrap px-0 mt-3">
            <img src="./img/htb_img_1.webp" class="img-fluid" alt="" style="max-width: 48%; height: auto;">
            <img src="./img/htb_img_2.webp" class="img-fluid" alt="" style="max-width: 48%; height: auto;">
        </div>
<div class="container mt-3">
<div class="container mt-3">
    <h5 class="text-danger">Sách mới nhất</h5>
    <div class="d-flex flex-wrap justify-content-between">
        <?php if (!empty($newestProducts)) : ?>
            <?php foreach ($newestProducts as $product) :  
                $imgPath = !empty($product['img']) ? './../' . $product['img'] : './../img/default-image.jpg';
                $linkSp = "?act=sanphamchitiet&id=" . $product["id"]; 
            ?>
                <div class="col-6 col-md-3 col-lg-3 mt-2">
                    <div class="card" style="width: 12rem;">
                    <a href="<?= $linkSp ?>" class="text-dark text-decoration-none">
                            <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= htmlspecialchars($product['namesp']); ?>" style="height: 180px; object-fit: cover;">
                            <div class="card-body" style="font-size: 0.85rem;">
                                <h5 class="card-title" style="font-size: 1rem;"><?= htmlspecialchars($product['namesp']); ?></h5>
                                <p>
                                    <span class="text-danger"><?= number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Không có sản phẩm nào để hiển thị.</p>
        <?php endif; ?>
    </div>
</div>




</div>
<div class="container d-flex justify-content-between flex-wrap px-0 mt-3">
    <!-- Hình ảnh chiếm 3 phần -->
    <div class="col-3">
        <img src="./img/col2_htb_img_1.webp" class="img-fluid" alt="">
    </div>
    
    <!-- Banner chiếm 8 phần -->
    <div class="col-8">
        <img src="./img/col2_htb_img_2.webp" class="img-fluid" alt="">
    </div>
</div>


<!-- Các sản phẩm theo danh mục -->
<?php
// Bước 1: Lọc các danh mục duy nhất
$categories = array_unique(array_column($datas, 'category_name'));

// Bước 2: Hiển thị sản phẩm theo từng danh mục
 foreach ($categories as $category_name): ?>
    <div class="container mt-3 ">
        <h5 class=""><?= $category_name ?></h5>
        <div class="d-flex flex-nowrap" style="overflow-x: scroll; -ms-overflow-style: none; scrollbar-width: none; gap: 10px;">
            <?php foreach ($datas as $product):
                 $linkSp = "?act=sanphamchitiet&id=" . $product["id"];
                $imgPath = '../'.$product['img'];
                if ($product['category_name'] == $category_name):
            ?>
                    <div class="col-6 col-md-4 col-lg-3 mt-2 no">
                        <div class="card" style="width: 12rem; position: relative;">
                            <img src="<?= $imgPath ?>" class="imgHieuUng " alt="<?= $product['namesp'] ?>" style="height: 180px; object-fit: cover;">
                            <div class="card-body" style="font-size: 0.85rem;">
                                <a href="<?=$linkSp?>" class="text-dark text-decoration-none"><h5 class="card-title" style="font-size: 1rem;"><?= $product['namesp'] ?></h5></a>
                                <h6><span class="card-text text-danger"><?= number_format($product['price'], 0, ',', '.') ?>đ</span></h6> 
                            </h6>
                            <?php if($product['quantity'] <= 0)  :?>
                                    <div class="badge bg-danger text-light position-absolute" style="bottom: 0px; width: 100%; left: 0; z-index: 2;">Hết hàng</div>
                            <?php endif;?>

                            </div>
                            <div class="product-actions">
                               <form class="quick_by_form" action="?act=addcart" method="post">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>" >
                                <input type="hidden" name="namesp" value="<?= $product['namesp'] ?>" >
                                <input type="hidden" name="img" value="<?= $product['img'] ?>">
                                 <input type="hidden" name="price" value="<?= $product['price'] ?>"> 
                                 <input type="hidden" name="quantity" value="<?= $product['quantity'] ?>"> 
                                 <input type="hidden" name="mota" value="<?= $product['mota'] ?>"> 
                                 <?php if($product['quantity'] > 0)  :?>
                                <button name="addcart" class="btn-cart"><i class="bi bi-cart-plus"></i></button>
                                <?php endif;?>

                                <!-- Nút giỏ hàng và yêu thích -->
                               </form>
                               <a href="?act=addFavourite&id=<?= $product['id']?>"><button class="btn-wishlist"><i class="bi bi-heart"></i></button></a>
                            </div>
                        </div>
                        
                    </div>
            <?php endif; endforeach; ?>
        </div>  <!-- End of d-flex -->
    </div>  <!-- End of container -->
<?php endforeach; ?>

<div class="container mt-3 overflow-hidden">
<h5 class="">Nhà phát hành</h5>
  <div class="d-flex" id="scrolling-container">
    <img src="./img/quangcao1.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao2.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao3.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao4.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao5.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao6.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao7.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao8.png" class="img-fluid" alt="Sách Nổi Bật" style="height: 110px; object-fit: cover; margin-right: 30px;">
    
    <!-- Nhân đôi nội dung để tạo cuộn vô hạn -->
    <img src="./img/quangcao1.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao2.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao3.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao4.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao5.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao6.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao7.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
    <img src="./img/quangcao8.png" class="img-fluid" style="height: 110px; object-fit: cover; margin-right: 30px;">
  </div>
</div>

<style>
  #scrolling-container {
    animation: scroll-horizontal  15s  linear infinite;
  }
  @keyframes scroll-horizontal {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
  }
</style>






