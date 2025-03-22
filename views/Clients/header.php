
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Example with Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
       /* Áp dụng cho toàn bộ trang */
* {
    transition: all 0.3s ease-in-out;
}

/* Hiệu ứng mượt mà cho phần tử khi hover */
a, button{
    transition: transform 0.3s ease, color 0.3s ease;
}

a:hover, button:hover {
    transform: scale(1.05);  /* Phóng to nhẹ */
}
.no {
    transition: all 0.5s linear; 
}
.no:hover{
    transform: translateY(-10px); 
    opacity: 0.9; 
}
    /* Ẩn các nút khi không hover */
.product-actions {
    display: flex; /* Đảm bảo các nút nằm ngang nhau */
    justify-content:space-between; 
    gap: 20px; /* Khoảng cách giữa các nút */
    position: absolute;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0; 
    transition: opacity 0.5s ease; /* Chỉ thêm transition cho opacity để các nút xuất hiện từ từ */
}


/* Khi hover vào sản phẩm, nút sẽ từ từ xuất hiện */
.card:hover .product-actions {
    opacity: 1; /* Đặt opacity thành 1 khi hover vào sản phẩm */
}

/* Cải thiện kiểu dáng của nút */
.product-actions button {
    padding: 5px 9px;
    background-color: #C62E2E;
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Thêm hiệu ứng thay đổi màu nền khi hover */
}

/* Hiệu ứng khi hover vào nút */
.product-actions button:hover {
    background-color: #F2E8C6; /* Thay đổi màu khi hover vào nút */
}

    </style>
</head>
<body>

<!-- Top Bar -->
<!-- Top Header -->
<!-- Header -->
<div style="background-color: #C62E2E;" class="text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <!-- Contact Info -->
                <div class="col-12 col-md d-flex flex-wrap justify-content-center justify-content-md-start text-center text-md-start mb-2 mb-md-0">
                    <span><i class="bi bi-telephone"></i> 19004953</span>
                    <span class="ms-3 text-sm text-md text-lg">📍 P. Trịnh Văn Bô, Xuân Phương, Nam Từ Liêm, Hà Nội</span>
                </div>
                <!-- Social Links -->
                <div class="col-12 col-md-auto d-flex justify-content-center justify-content-md-end d-none d-md-block">
                    <a href="#" class="text-white ms-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white ms-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white ms-2"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
<header class="sticky-top">
    <!-- Contact Info Bar -->
    

    <!-- Main Header -->
    <div style="background-color: #F2E8C6;" class="text-dark py-2 ">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-12 col-md-4 text-center text-md-start">
                    <div class="h5 m-0">
                        <a href="http://localhost/base_test_DA1/public/" class="text-decoration-none text-dark">BARNES & NOBLE</a>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="col-8 col-md-4">
                    <form action="?act=search" method="post" enctype="multipart/form-data" class="d-flex justify-content-center">
                        <input type="search" 
                               class="form-control form-control-sm" 
                               placeholder="Tìm kiếm sản phẩm..." 
                               style="height: 30px; padding: 0.25rem 0.5rem;" 
                               name ="search" >
                        <button class="btn btn-dark btn-sm border-0" 
                               type="submit" 
                               style="height: 30px; padding: 0 10px; border: none; background-color:#C62E2E;" 
                               >
                               <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                <!-- Contact Info -->
                <div class="col-4 col-md-4 d-flex justify-content-end">
                    <ul class="nav nav d-flex justify-content-between">
                        <li class="nav-item">
                            <span class="d-flex flex-column d-none d-md-block">
                                <div class="small">Tư vấn bán hàng</div>
                                <div class="fw-bold">19004953</div>
                            </span>
                        </li>
                           <li class="nav-item position-relative">
                                <a class="nav-link text-dark" style="font-size: 20px;" href="?act=viewcart">
                                    <i class="bi bi-cart3"></i>
                                </a>
                                <!-- Badge hiển thị số lượng -->
                               
                                <span class="<?= isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ? 'badge rounded-pill bg-danger position-absolute' : '' ?>" style="top: 0; right: 0; font-size: 10px;">
                                    <?php 
                                    if (isset($_SESSION['cart'])) {
                                        $productCount = count($_SESSION['cart']);
                                        echo $productCount > 0 ? $productCount : ''; // Chỉ hiển thị số sản phẩm nếu có
                                    }
                                    ?>
                                </span>  
                            </li>


                        <?php if(isset($_SESSION['user'])){  extract($_SESSION['user']); $imgPath = './../' . $avatar; $avt = $imgPath ? $imgPath : './img/userNo.jpg';  ?>
                        <li class="nav-item dropdown d-flex align-items-center">
                            <a class="dropdown-toggle text-dark" data-bs-toggle="dropdown" href="" role="button" style="font-size: 18px;" aria-expanded="false"><img src="<?= $avt ?>" height="24" width="24"  style="border-radius: 50% ;" alt=""></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?act=updateAcount&id=<?=$id ?>">Cập nhật tài khoản</a></li>
                                <?php if($role === 1 ) { ?>
                                    <li><a class="dropdown-item" href="http://localhost/base_test_DA1/views/Admins/router.php">Đăng nhập admin</a></li>
                                <?php }else{ ?>
                                    <li><a class="dropdown-item" href="?act=signup">Lấy lại pass</a></li>
                                    <li><a class="dropdown-item" href="?act=listFavourites">Sản Phẩm yêu thích</a></li>
                                    <li><a class="dropdown-item" href="?act=billInfo">Đơn hàng của bạn</a></li>
                                <?php } ?>
                                <li><a class="dropdown-item" href="?act=listFavourites">Sản Phẩm yêu thích</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?act=logout">Đăng xuất</a></li> 
                            </ul>
                        </li>
                        <?php }else{?>
                            <li class="nav-item dropdown d-flex align-items-center">
                            <a class="dropdown-toggle text-dark" data-bs-toggle="dropdown" href="#" role="button" style="font-size: 18px;" aria-expanded="false"><i class="bi bi-person-circle"></i></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?act=login">Đăng nhập</a></li>
                                <li><a class="dropdown-item" href="?act=signup">Đăng kí</a></li>
                                <!-- <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li> -->
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light shadow-lg border-bottom border-secondary py-1">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost/base_test_DA1/public/">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Giới thiệu</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sản phẩm
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href=""> Sách </a></li>
                       
                        
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Cửa hàng </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Liên hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tuyển dụng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tin tức</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

</header>






