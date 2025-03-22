<?php 
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = null; // Hoặc giá trị mặc định
}
ob_start();
include '../views/Clients/header.php';


// Require file Common
require_once '../commons/env.php'; // Khai báo biến môi trường
require_once '../commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
// require_once '../controllers/AdminController.php';
require_once '../controllers/ClientController.php';

// Require toàn bộ file Models
require_once '../models/ClientModels.php';
// require_once '../models/AdminModels.php';
// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/' => (new ClientController())->home(),
    // Tài khoản
    'updateAcount' => (new ClientController()) -> updateAcount(),
    'login' => (new ClientController()) -> login(),
    'postLogin' => (new ClientController()) -> postLogin(),
    'signup' => (new ClientController()) -> signUp(),
    'logout' => (new ClientController()) -> logOut(),

    // quên mật khẩu
    'forgot_password' => (new ClientController()) -> forgot_password(),
   
    'verify_code' => (new ClientController()) -> verify_code(),
    
    'reset_password' => (new ClientController()) -> reset_password(),
    
    // Xử lí tài khoản
    'postAddAcount' => (new ClientController()) -> addAccount(),
    

    //
    'chitietSP' => (new ClientController()) -> chitietSP(),


    // Tìm kiếm
    'search' => (new ClientController()) ->search(),
    // Giỏ hàng
    'viewcart' => (new ClientController()) ->viewCarts(),
    'addcart' => (new ClientController()) ->carts(),
    'deletecart' => (new ClientController()) ->deleteCarts(),
    'tangGiam' => (new ClientController()) -> tangGiam(),

    // Bill
    'thanhtoan' => (new ClientController()) ->bills(),
    'billconfirm' => (new ClientController()) ->billConfirm(),
    'billInfo' => (new ClientController()) -> infoBills(),
    'bill_item' => (new ClientController()) -> bill_items(),
    'huyDon' =>(new ClientController()) -> huyDon(),

    // Mua ngay
    'postMuaNgay' => (new ClientController()) ->postMuaNgay(),
    'xuly_thanhtoan' => (new ClientController()) ->xuLiMuaNgay(),

    // Sản phẩm chi tiết
    'sanphamchitiet' => (new ClientController()) -> sanphamchitiet(),
    // Xử lí tăng giảm số lượng mua hàng
    'tangGiamMuaNgay' => (new ClientController()) ->tangGiamMuaNgay(),
    // 'listNewestProducts' => (new ClientController()) -> listNewestProducts(),
    // Comment
    'formComment' => (new ClientController()) -> formComment(),
    'deleteComment' => (new ClientController()) -> deleteComment(),
    // sản phẩm theo danh mục
    'danhmuc'=> (new ClientController())-> productByCasterri(),
    //yeuthich
    'listFavourites' => (new ClientController())->listFavourites(),
    'addFavourite' => (new ClientController())->addFavourite(),
    'removeFavourite' => (new ClientController())->removeFavourite(),

};

include '../views/Clients/footer.php';
ob_end_flush();

