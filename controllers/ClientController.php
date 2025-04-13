<?php 
class ClientController
{
    public $modelClients; 

    public function __construct()
    {
        $this->modelClients = new ClientModels(); 
    }

    public function home() {
        $listDanhMuc = $this->modelClients->getAllDanhMuc();
        $datas = $this ->modelClients->getAllProductsByCategory();
        $top10 = $this -> modelClients -> getTop10Sp();




        if(isset($_SESSION['buy'])){
            unset($_SESSION['buy']);
        }
        $newestProducts = $this->modelClients->getNewestProducts(4); // Mặc định lấy 4 sản phẩm mới nhất
        // var_dump($top10);
        // var_dump($listDanhMuc);
        require_once '../views/Clients/home.php';
        // require_once '../views/Clients/header.php';
        // require_once '../views/Clients/footer.php';
    }
    // Cập nhật tài khoản
    public function updateAcount() {
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            extract($_SESSION['user']);
            $imgPath = './../' . $avatar;
            $avt = $imgPath ? $imgPath : './img/userNo.jpg';
   
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $sdt = $_POST['sdt'];
   
                // Xử lý ảnh đại diện
                $avatar = $avt;
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                    $file_save = uploadFile($_FILES['avatar'], 'uploads/users');
                    if ($file_save) {
                        $avatar = $file_save; // Gán đường dẫn ảnh mới
                    } else {
                        echo "Lỗi khi lưu tệp ảnh.";
                        return;
                    }
                }
   
                // Cập nhật thông tin
                $current_img = $_POST['current_img'];
                if ($this->modelClients->updateAccout($id, $username, $email, $avatar ?: $current_img, $address, $sdt)) {
                    // Cập nhật thành công
                    // Lấy lại thông tin người dùng mới
                    $_SESSION['user'] = $this->modelClients->getAccountById($id);
                    header('Location: http://localhost/base_test_DA1/public/');
                    exit;
                } else {
                    echo "Lỗi khi cập nhật tài khoản.";
                }
            } else {
                require '../views/Clients/UpdateTaiKhoan/updateTk.php';
                exit;
            }
        }
   
        // Gọi view cập nhật tài khoản
        require_once '../views/Clients/UpdateTaiKhoan/updateTk.php';
    }
     
   
    public function login() {
        require_once '../views/Clients/accounts/login.php';
    }
    public function postLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userOrEmail = $_POST['username'];
            $pass = $_POST['password'];
            $check = $this->modelClients->checkAcc($userOrEmail, $pass);
            if (is_array($check)) {
                if($check['active'] === 1){
                    echo '<script>
                    // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Tài khoản của bạn đã bị khoá",
                        icon: "error",
                        confirmButtonColor: "#C62E2E"
                        });
                  </script>';
                  require_once '../views/Clients/accounts/login.php';
                }else{
                    $_SESSION['user'] = $check;
                    echo '<script>
                    // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Đăng nhập thành công",
                        icon: "success",
                        confirmButtonColor: "#C62E2E"
                        });
                  </script>';
                    header('Location: http://localhost/base_test_DA1/public/');
                    exit;
                }
            } else {
                $thongbao = 'Tài khoản hoặc mật khẩu không đúng';
            }
        } else {
            echo 'Lỗi';
        }
        require_once '../views/Clients/accounts/login.php';
    }


    public function logOut(){
        session_unset();
        session_destroy();
        header('Location: http://localhost/base_test_DA1/public/');
    }
    public function signUp() {
        require_once '../views/Clients/accounts/signUp.php';
    }
    // Đăng kí tài khoản
    public function addAccount(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $sdt = $_POST['sdt'];
            $password = $_POST['password'];
            if($this->modelClients->addAccount($username, $email, $password, $sdt)){
                header('location: http://localhost/base_test_DA1/public/');
            }
        }
    }
    // quên mật khẩu
   
    public function forgot_password(){
        require_once '../views/Clients/accounts/forgot_password.php' ;
    }
    // lẫy mã
    public function verify_code(){
        require_once '../views/Clients/accounts/verify_code.php';
    }
    public function reset_password(){
        require_once '../views/Clients/accounts/reset_password.php';
    }
    public function chitietSP(){
        require_once '../views/Clients/productDetails/chitietSP.php';
    }
    public function sanphamchitiet() {
        $id = $_GET['id'];
        $sanPhamChiTiet = $this->modelClients->getSPById($id);
        extract($sanPhamChiTiet);
        $soLuongConLai = $sanPhamChiTiet['quantity'] > 0 ?  $sanPhamChiTiet['quantity'] -1 : 0;
        $sanPhamChung = $this->modelClients->load_sanpham_cungloai($id, $iddm);
        $sanPhamNoiBat = $this->modelClients->getTop10Sp();
        $comments = $this->modelClients->getCommentsByProductId($id);
        $soluongMua = $sanPhamChiTiet['quantity'] > 0 ?  1 : 0;
        $tongtien = $sanPhamChiTiet['price'];
       
        require_once '../views/Clients/productDetails/chitietSP.php';
    }
    public function tangGiamMuaNgay(){
        $id = $_GET['id'];
   
        // Kiểm tra nếu có flag reset_session, reset thông tin sản phẩm trong session
        if (isset($_SESSION['reset_session']) && $_SESSION['reset_session'] == true) {
            unset($_SESSION['buy'][$id]);  // Xóa thông tin sản phẩm trong session
            $_SESSION['reset_session'] = false;  // Reset flag sau khi đã xử lý
        }
       
        // Lấy thông tin sản phẩm chi tiết
        $sanPhamChiTiet = $this->modelClients->getSPById($id);
        extract($sanPhamChiTiet);
       
        // Kiểm tra số lượng sản phẩm còn trong kho
        $quantityProduct = $this->modelClients->getCartQuantity($id);
        $soLuongConLai = isset($_POST['soluongconlai']) ? $_POST['soluongconlai'] : $quantityProduct;
       
        // Kiểm tra số lượng trong session cho sản phẩm cụ thể
        $soluongMua = isset($_POST['soluongMua']) ?  $_POST['soluongMua']: 1;
        $tongtien = isset($_POST['tongtien']) ?  $_POST['tongtien'] : $sanPhamChiTiet['price'];
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra nút "tăng"
            if (isset($_POST['tang'])) {
                if ($quantityProduct > $soluongMua) {
                    $soluongMua++;
                    $soLuongConLai--; // Giảm đi 1 khi tăng số lượng
                    $tongtien = $sanPhamChiTiet['price'] * $soluongMua;  // Tính lại tổng tiền
                    $_SESSION['buy'][$id] = [
                        'id' => $id,
                        'quantity' => $soluongMua,
                        'thanhtien' => $tongtien,
                        'soluongconlai' => $soLuongConLai
                    ];
                } else {
                    echo '<script>
                        Swal.fire({
                            text: "Số lượng trong kho đã hết!",
                            icon: "warning",
                            confirmButtonColor: "#C62E2E"
                        });
                    </script>';
                }
            }
   
            // Kiểm tra nút "giảm"
            if (isset($_POST['giam'])) {
                if ($soluongMua > 1) {
                    $soluongMua--;
                    $soLuongConLai++; // Tăng lên 1 khi giảm số lượng
                    $tongtien = $sanPhamChiTiet['price'] * $soluongMua;  // Tính lại tổng tiền
                    $_SESSION['buy'][$id] = [
                        'id' => $id,
                        'quantity' => $soluongMua,
                        'thanhtien' => $tongtien,
                        'soluongconlai' => $soLuongConLai
                    ];
                } else {
                    echo '<script>
                        Swal.fire({
                            text: "Giới hạn giảm là 1!",
                            icon: "error",
                            confirmButtonColor: "#C62E2E"
                        });
                    </script>';
                }
            }
        }
   
        // Tính lại tổng tiền sau khi thay đổi số lượng và lưu vào session
        $price = $this->modelClients->getPriceByPro($id);
        $tongtien = $price * $soluongMua;  // Cập nhật lại tổng tiền khi số lượng thay đổi
   
        // Lưu lại vào session nếu không có thao tác tăng/giảm (tức là lần đầu truy cập)
        if (!isset($_SESSION['buy'][$id])) {
            $_SESSION['buy'][$id] = [
                'id' => $id,
                'quantity' => $soluongMua,
                'thanhtien' => $tongtien,
                'soluongconlai' => $soLuongConLai
            ];
        }
        // Lấy các sản phẩm liên quan
        $sanPhamChung = $this->modelClients->load_sanpham_cungloai($id, $iddm);
        $sanPhamNoiBat = $this->modelClients->getTop10Sp();
        $comments = $this->modelClients->getCommentsByProductId($id);
   
        require_once '../views/Clients/productDetails/chitietSP.php';
    }
    public function search() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])){
            $search = $_POST['search'];
            $datasSearch = $this->modelClients->getAllSP($search);


            // var_dump($datasSearch);
        }else{
            header('location: http://localhost/base_test_DA1/public/');
        }
        require_once '../views/Clients/products/products.php';
    }


    // Giỏ hàng
    public function carts() {
        // Kiểm tra nếu giỏ hàng chưa tồn tại, khởi tạo mảng giỏ hàng
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $userRole = $_SESSION['user']['role'] ?? null;
        if( $userRole == 1){
           
            echo '<script>
            // Khắc phục lỗi mất thanh cuộn
            document.body.style.overflowX = "auto";
            document.body.style.overflowY = "auto";  
            Swal.fire({
                text: "Admin không thể mua hàng",
                icon: "warning",
                confirmButtonColor: "#C62E2E"
                });
        </script>';
        $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
        // var_dump($listCarts);
        // header('location: http://localhost/base_test_DA1/public/');
        $this->home();
        exit();
        }


        if(!isset($_SESSION['user'])){
            echo '<script>
            // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Bạn cần đăng nhập để mua hàng",
                        icon: "error",
                        confirmButtonColor: "#C62E2E"
                    });
                </script>';
                $this->login();
                exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            $id = $_POST['id'];
            // Xem số lượng trong bảng products là bao nhiêu
            $currentQuantity = $this->modelClients->getCartQuantity( $id);
            if($currentQuantity  > 0){
                $namesp = $_POST['namesp'];
                $img = $_POST['img'];
                $price = (float) $_POST['price'];
                $mota = $_POST['mota'];
                $sumQuantityMuaNgay =  $_SESSION['buy'][$id]['quantity'] ?? 1;
                // var_dump($_SESSION['buy'][$id]);
                // var_dump($sumQuantityMuaNgay); die();
                // $soluong = isset($_POST['soluong']) ? $_POST['soluong'] : 1;
                $soluong = $sumQuantityMuaNgay;
                // var_dump($soluong); die();
                $getSoLuongCarts = $this->modelClients->getSoLuongCarts($id, $_SESSION['user']['id']);
                // $tongTien = ($getSoLuongCarts + 1 ) * $price;
                $tongTien = ($getSoLuongCarts + $sumQuantityMuaNgay ) * $price;
                 // Xem số lượng trong bảng products là bao nhiêu
                 $newPro = $currentQuantity - 1;
           
                // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng chưa
                $exists = $this->modelClients->checkCarts($_SESSION['user']['id'], $id);
               
                if ($exists) {
                    $this->modelClients->updateQuantity($_SESSION['user']['id'], $id, $getSoLuongCarts + $sumQuantityMuaNgay, $tongTien);
               
                    // Cập nhật số lượng kho nếu còn hàng
                    if ($currentQuantity > 0) {
                         $this->modelClients->updateQuantityProducts($id, $currentQuantity - $sumQuantityMuaNgay );
                        $this->modelClients->updateRemainingQuantity($id, $currentQuantity - $sumQuantityMuaNgay ?? $getSoLuongCarts  );
                    } else {
                        echo '<script>
                                     // Khắc phục lỗi mất thanh cuộn
                                    document.body.style.overflowX = "auto";
                                    document.body.style.overflowY = "auto";  
                                    Swal.fire({
                                        text: "Sản phẩm đã hết hàng!",
                                        icon: "error",
                                        confirmButtonColor: "#C62E2E"
                                    });
                                </script>';
                                exit();
                    }
                    $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
                    require_once '../views/Clients/carts/cart.php';
                }
           
                // Nếu sản phẩm chưa tồn tại, thêm sản phẩm mới vào giỏ hàng
                if (!$exists) {
                    $newProduct = [
                        'id' => $id,
                        'namesp' => $namesp,
                        'img' => $img,
                        'price' => $price,
                        'soluong' => $soluong,
                        'tongTien' => $tongTien,
                        'mota' => $mota,
                        'remaining_quantity' => $currentQuantity,
                    ];
                    $this->modelClients->updateQuantityProducts($id, $currentQuantity - $soluong);
                    array_push($_SESSION['cart'], $newProduct);
                    $this->modelClients->addCarts($_SESSION['user']['id'], $id, $img, $namesp, $price, $soluong , $tongTien, $mota, $currentQuantity - $soluong);
                }
                // Lấy danh sách giỏ hàng của người dùng
                $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                require_once '../views/Clients/carts/cart.php';
                // Chuyển hướng về trang hiện tại để tránh gửi lại POST
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }else{
                $previousPage = $_SERVER['HTTP_REFERER'];
                 // Hiển thị thông báo và chuyển hướng
                    echo "<script>
                    alert('Sản phẩm trong kho đã hết!');
                    window.location.href = '$previousPage';
                </script>";
            }
           
           
            if(!isset($_SESSION['user'])){
            echo '<script>
                    // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Bạn cần đăng nhập để truy cập giỏ hàng",
                        icon: "warning",
                        confirmButtonColor: "#C62E2E"
                        });
                  </script>';
                  require_once '../views/Clients/accounts/login.php';
                //   $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
                  // var_dump($listCarts);
            }
        }
       
        $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
        require_once '../views/Clients/carts/cart.php';
    }
   
   


    // Xử lí tăng thông số
    public function tangGiam(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])){
            if(isset($_POST['tang'])){
                $id = $_GET['id'];
                $idpro = $this->modelClients->getIdProduct($id);
                $price = $this->modelClients->getPriceByIdCart($id);
                // Gọi số lượng sản phẩm hiện có trong đơn hàng ra
                $QuantityCart = $this->modelClients->getSoLuongById($id);
                $thanhTien = $price * ($QuantityCart + 1);
                // Lấy số lượng của sản phẩm đó trong bảng products;
                $quantityPro = $this->modelClients->getCartQuantity($idpro);
                // Trừ đi số lượng trong products và số lượng còn lại trong carts
               if($quantityPro > 0){
                    $this->modelClients->updateQuantityProducts($idpro, $quantityPro - 1 );
                    $this->modelClients->updateRemainingQuantity($idpro, $quantityPro - 1);


                    // Update các chỉ số xoay quanh số lượng
                    $this->modelClients->updateQuantity($_SESSION['user']['id'], $idpro, $QuantityCart + 1, $thanhTien);
               }else{
                        echo '<script>
                        // Khắc phục lỗi mất thanh cuộn
                            document.body.style.overflowX = "auto";
                            document.body.style.overflowY = "auto";  
                            Swal.fire({
                                text: "Sản phẩm đã hết hàng!",
                                icon: "error",
                                confirmButtonColor: "#C62E2E"
                            });
                        </script>';
               }
            }elseif(isset($_POST['giam'])){
                $id = $_GET['id'];
                $idpro = $this->modelClients->getIdProduct($id);
                $price = $this->modelClients->getPriceByIdCart($id);
                // Gọi số lượng sản phẩm hiện có trong đơn hàng ra
                $QuantityCart = $this->modelClients->getSoLuongById($id);
                $thanhTien = $price * ($QuantityCart - 1);
                // Lấy số lượng của sản phẩm đó trong bảng products;
                $quantityPro = $this->modelClients->getCartQuantity($idpro);
                // Trừ đi số lượng trong products và số lượng còn lại trong carts
               if($QuantityCart <= 1){
                echo '<script>
                // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Giới hạn giảm là 1!",
                        icon: "error",
                        confirmButtonColor: "#C62E2E"
                    });
                </script>';
               }else{
                $this->modelClients->updateQuantityProducts($idpro, $quantityPro + 1 );
                $this->modelClients->updateRemainingQuantity($idpro, $quantityPro + 1);


                // Update các chỉ số xoay quanh số lượng
                $this->modelClients->updateQuantity($_SESSION['user']['id'], $idpro, $QuantityCart - 1, $thanhTien);
   
               }
            }
        }
        $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
        require_once '../views/Clients/carts/cart.php';
    }


    public function viewCarts(){
        if(!isset($_SESSION['user'])){
            echo '<script>
                    // Khắc phục lỗi mất thanh cuộn
                    document.body.style.overflowX = "auto";
                    document.body.style.overflowY = "auto";  
                    Swal.fire({
                        text: "Bạn cần đăng nhập để truy cập giỏ hàng",
                        icon: "warning",
                        confirmButtonColor: "#C62E2E"
                        });
                  </script>';
                  require_once '../views/Clients/accounts/login.php';
            }else{
                $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
                // print_r($listCarts);
                require_once '../views/Clients/carts/cart.php';
            }
    }
    public function deleteCarts() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletecart'])) {
            $id = $_POST['id'];
            $userId = $_SESSION['user']['id'];
            $idPro = $this->modelClients->getIdProduct($id);
            $quantityCarts = $this->modelClients->getRemaining_quantity($id);
            // Số lượng sản phẩm được mua
            $quantityProducts = $this->modelClients->getSoLuongById($id);
            $quant = $quantityCarts + $quantityProducts;
            // $quantityProducts = $this->modelClients->getSoLuongCarts($idPro, $userId);
            //  var_dump($idPro);die();
                $this->modelClients->updateQuantityProducts($idPro, $quant);
                $this->modelClients->deleteCarts( $id);
                // Lấy danh sách giỏ hàng mới từ database
                $listCarts = $this->modelClients->listCartByUser($userId);
                // Cập nhật lại session giỏ hàng dựa trên danh sách mới
                $_SESSION['cart'] = $listCarts;
                header('location: http://localhost/base_test_DA1/public/?act=viewcart');
        }
        $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
        require_once '../views/Clients/carts/cart.php';
    }


    // Trang cảm ơn
    public function bill_items() {
        $id = $_GET['id'];
        $listBill = $this->modelClients->getBillById($id);
        // var_dump($listBill);
        require_once '../views/Clients/carts/bill_items.php';
    }
    public function billConfirm() {
        if (count($_SESSION['cart']) > 0) {
                if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pttt'] !== '2' ) {
                    $nameUser = $_SESSION['user']['username'];
                    $email = isset($_POST['email']) ? $_POST['email'] : $_SESSION['user']['email'];
                    $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : $_SESSION['user']['sdt'];
                    $address = isset($_POST['address']) ? $_POST['address'] : $_SESSION['user']['address'];  
                    $user_id = $_SESSION['user']['id'];
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $ngaydathang = date('d-m-Y H:i:s');
                    $pttt = isset($_POST['pttt']) ? $_POST['pttt'] : 0;
                    $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                    $quantity = count($listCarts);
                    // Tính tổng giá trị đơn hàng
                    $tongCong = 0;
                    foreach ($listCarts as $cart) {
                        extract($cart);
                        $tongCong += $thanhtien;
                    }
                    $errors = [];
           
                    $fields = [
                        'name' => $nameUser,
                        'email' => $email,
                        'sdt' => $sdt,
                        'pttt' => $pttt,
                        'address' => $address
                    ];
           
                    // Kiểm tra nếu giá trị rỗng
                    foreach ($fields as $key => $value) {
                        if (empty($value)) {
                            $errors[$key] = 'Thanh toán thất bại.';
                        }
                    }
           
           
                    // Nếu có lỗi thì hiển thị thông báo và không tiếp tục
                    if (!empty($errors)) {
                        foreach ($errors as $message) {
                            echo "<script>
                                document.body.style.overflowX = 'auto';
                                document.body.style.overflowY = 'auto';  
                                Swal.fire({
                                    text: '$message',
                                    icon: 'error',
                                    confirmButtonColor: '#C62E2E'
                                });
                            </script>";
                        }
                        require_once '../views/Clients/carts/thanhtoan.php'; // Trả về trang thanh toán sau khi có lỗi
                    } else {
                        $idbill = $this->modelClients->addBill($user_id, $address, $sdt, $email, $tongCong, $ngaydathang, $pttt, $quantity);
                        echo "<script>
                        Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng đợi...',
                        allowOutsideClick: false,
                        didOpen: () => {
                        Swal.showLoading();
                        }
                        });


                        setTimeout(function () {
                        Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: 'Đặt hàng thành công',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `http://localhost/base_test_DA1/public/?act=bill_item&id={$idbill}`;
                        }
                        });
                        }, 2000);


                    </script>";
                        $cart_items = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                        foreach($cart_items as $item){
                                $product_id = $item['idpro'];
                                $quantity = $item['soluong'];
                                $price = $item['price'];
                                // Lưu sản phẩm vào bill_items
                                $this->modelClients->addBillItem($idbill, $product_id, $quantity, $price);
                                // $total+=$quantity * $price;
                        }
                        $this->modelClients->clearCart($_SESSION['user']['id']);
                   
                        // Cập nhật lại session giỏ hàng dựa trên danh sách mới
                        $_SESSION['cart'] = [];
                        require_once '../views/Clients/carts/thanhtoan.php';
                    }            
                   
                }else if(isset($_POST['payUrl']) &&  isset($_POST['pttt']) == 2){
                    $nameUser = $_SESSION['user']['username'];
                    $email = isset($_POST['email']) ? $_POST['email'] : $_SESSION['user']['email'];
                    $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : $_SESSION['user']['sdt'];
                    $address = isset($_POST['address']) ? $_POST['address'] : $_SESSION['user']['address'];  
                    $user_id = $_SESSION['user']['id'];
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $ngaydathang = date('d-m-Y H:i:s');
                    $pttt = isset($_POST['pttt']) ? $_POST['pttt'] : 0;
                    $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                    $quantity = count($listCarts);
                    // Tính tổng giá trị đơn hàng
                    $tongCong = 0;
                    foreach ($listCarts as $cart) {
                        extract($cart);
                        $tongCong += $thanhtien;
                    }
                    $errors = [];
           
                    $fields = [
                        'name' => $nameUser,
                        'email' => $email,
                        'sdt' => $sdt,
                        'pttt' => $pttt,
                        'address' => $address
                    ];
           
                    // Kiểm tra nếu giá trị rỗng
                    foreach ($fields as $key => $value) {
                        if (empty($value)) {
                            $errors[$key] = 'Thanh toán thất bại.';
                        }
                    }
           
           
                    // Nếu có lỗi thì hiển thị thông báo và không tiếp tục
                    if (!empty($errors)) {
                        foreach ($errors as $message) {
                            echo "<script>
                                document.body.style.overflowX = 'auto';
                                document.body.style.overflowY = 'auto';  
                                Swal.fire({
                                    text: '$message',
                                    icon: 'error',
                                    confirmButtonColor: '#C62E2E'
                                });
                            </script>";
                        }
                        require_once '../views/Clients/carts/thanhtoan.php'; // Trả về trang thanh toán sau khi có lỗi
                    } else {
                        $this->xuliPtttAll();
                        $idbill = $this->modelClients->addBill($user_id, $address, $sdt, $email, $tongCong, $ngaydathang, $pttt, $quantity);
                        $cart_items = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                        foreach($cart_items as $item){
                                $product_id = $item['idpro'];
                                $quantity = $item['soluong'];
                                $price = $item['price'];
                                // Lưu sản phẩm vào bill_items
                                $this->modelClients->addBillItem($idbill, $product_id, $quantity, $price);
                                // $total+=$quantity * $price;
                        }
                        $this->modelClients->clearCart($_SESSION['user']['id']);
                   
                        // Cập nhật lại session giỏ hàng dựa trên danh sách mới
                        $_SESSION['cart'] = [];
                        require_once '../views/Clients/carts/thanhtoan.php';
                    }
                   
                }
           
        }
    }
    public function postMuaNgay() {
           $userRole = $_SESSION['user']['role'] ?? null;
        if( $userRole == 1){
            echo '<script>
            // Khắc phục lỗi mất thanh cuộn
            document.body.style.overflowX = "auto";
            document.body.style.overflowY = "auto";  
            Swal.fire({
                text: "Admin không thể mua hàng",
                icon: "warning",
                confirmButtonColor: "#C62E2E"
                });
        </script>';
        $listCarts= $this->modelClients->listCartByUser($_SESSION['user']['id']);
        // var_dump($listCarts);
        // header('location: http://localhost/base_test_DA1/public/');
        $this->home();
        exit();
        }
        $id_pro = $_GET['id'];
        $icheck = $this->modelClients->getCartQuantity($id_pro);
        if($icheck<= 0){
            $id_pro = $_GET['id'];
                echo '<script>alert("Sản phẩm này hiện đã hết hàng. Xin vui lòng quay lại sau hoặc chọn sản phẩm khác. Cảm ơn bạn!");</script>';
                $this->sanphamchitiet();
            }else{
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']) {
                $nameUser = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
                $email = isset($_SESSION['user']['email']) && !empty($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';
                $sdt = isset($_SESSION['user']['sdt']) && !empty($_SESSION['user']['sdt']) ? $_SESSION['user']['sdt'] : '';
                $address = isset($_SESSION['user']['address']) && !empty($_SESSION['user']['address']) ? $_SESSION['user']['address'] : '';
                $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';
                $id_pro = $_GET['id'];
                // print_r($_SESSION['buy'][$id_pro]);
                // Đặt tên tạm vậy thôi
                $listCarts = $this->modelClients->getProductById($id_pro);
                extract($listCarts);
                $soluong = $_SESSION['buy'][$id_pro]['quantity'] ?? 1;
                // var_dump($soluong); die();
                $thanhtien = $soluong * $price;
                $remaining_quantity = $_SESSION['buy'][$id_pro]['soluongconlai'] ?? $quantity - 1;
                require_once '../views/Clients/carts/thanhtoan2.php';
                }else{
                    echo '<script>
                    // Khắc phục lỗi mất thanh cuộn
                            document.body.style.overflowX = "auto";
                            document.body.style.overflowY = "auto";  
                            Swal.fire({
                                text: "Bạn cần đăng nhập để mua hàng",
                                icon: "error",
                                confirmButtonColor: "#C62E2E"
                            });
                        </script>';
                        require_once '../views/Clients/accounts/login.php';
               
                }
            }
         
        }
    public function bills(){
        if(count($_SESSION['cart']) > 0){
            if (isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] == 'POST') {
                // Lấy thông tin người dùng từ session
                $nameUser = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : '';
                $email = isset($_SESSION['user']['email']) && !empty($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';
                $sdt = isset($_SESSION['user']['sdt']) && !empty($_SESSION['user']['sdt']) ? $_SESSION['user']['sdt'] : '';
                $address = isset($_SESSION['user']['address']) && !empty($_SESSION['user']['address']) ? $_SESSION['user']['address'] : '';
                $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '';
                $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
                    $tongCong = 0;
                    foreach ($listCarts as $cart) {
                        extract($cart);
                        $tongCong += $thanhtien;
                    }
            }
            require_once '../views/Clients/carts/thanhtoan.php';
        }else{
            echo '<script>
            // Khắc phục lỗi mất thanh cuộn
            document.body.style.overflowX = "auto";
            document.body.style.overflowY = "auto";  
            Swal.fire({
                text: "Không có sản phẩm trong giỏ hàng",
                icon: "warning",
                confirmButtonColor: "#C62E2E"
                });
        </script>';
        $listCarts = $this->modelClients->listCartByUser($_SESSION['user']['id']);
        require_once '../views/Clients/carts/cart.php';
        }
    }
        // Thanh toán online
        public function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            return $result;
        }
   
       public function xuliPtttAll() {
           $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
           $partnerCode = 'MOMOBKUN20180529';
           $accessKey = 'klm05TvNBzhg7h7j';
           $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
           $orderInfo = "Thanh toán qua MoMo";
           $amount = "10000";
           $orderId = time() ."";
           $redirectUrl = "http://localhost/base_test_DA1/public/";
           $ipnUrl = "http://localhost/base_test_DA1/public/";
           $extraData = "";


   
           if (!empty($_POST)) {
               $partnerCode = $partnerCode;
               $accessKey = $accessKey;
               $secretKey = $secretKey;
               $orderId = $orderId; // Mã đơn hàng
               $orderInfo = $orderInfo;
               $amount = $amount;
               $ipnUrl = $ipnUrl;
               $redirectUrl = $redirectUrl;
               $extraData = $extraData;
   
               $requestId = time() . "";
               $requestType = "payWithATM";
               // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
               //before sign HMAC SHA256 signature
               $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
               $signature = hash_hmac("sha256", $rawHash, $secretKey);
               $data = array('partnerCode' => $partnerCode,
                   'partnerName' => "Test",
                   "storeId" => "MomoTestStore",
                   'requestId' => $requestId,
                   'amount' => $amount,
                   'orderId' => $orderId,
                   'orderInfo' => $orderInfo,
                   'redirectUrl' => $redirectUrl,
                   'ipnUrl' => $ipnUrl,
                   'lang' => 'vi',
                   'extraData' => $extraData,
                   'requestType' => $requestType,
                   'signature' => $signature);
               $result = $this->execPostRequest($endpoint, json_encode($data));
               $jsonResult = json_decode($result, true);  // decode json
   
               //Just a example, please check more in there
               header('Location: ' . $jsonResult['payUrl']);
               }
           }
        public function xuLiMuaNgay(){
            $id_pro = $_GET['id'];
            $icheck = $this->modelClients->getCartQuantity($id_pro);
            if($icheck > 0){
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user'] && $_POST['pttt'] !== '2' ) {
                    $nameUser = $_SESSION['user']['username'];
                    $email = isset($_POST['email']) ? $_POST['email'] : $_SESSION['user']['email'];
                    $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : $_SESSION['user']['sdt'];
                    $address = isset($_POST['address']) ? $_POST['address'] : $_SESSION['user']['address'];  
                    $user_id = $_SESSION['user']['id'];
                        $id_pro = $_GET['id'];
                        // Đặt tên tạm vậy thôi
                        $listCarts = $this->modelClients->getProductById($id_pro);
                        extract($listCarts);
                        $soluong = $_SESSION['buy'][$id_pro]['quantity'] ?? 1;
                        $thanhtien = $soluong * $price;
                        $remaining_quantity = $_SESSION['buy'][$id_pro]['soluongconlai'] ?? $quantity - 1;
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $ngaydathang = date('d-m-Y H:i:s');
                        $pttt = isset($_POST['pttt']) ? $_POST['pttt'] : 0;
                            $errors = [];
   
                            // Mảng các trường cần kiểm tra
                            $fields = [
                                'name' => $nameUser,
                                'email' => $email,
                                'sdt' => $sdt,
                                'pttt' => $pttt,
                                'address' => $address
                            ];
   
                            // Kiểm tra nếu giá trị rỗng
                            foreach ($fields as $key => $value) {
                                if (empty($value)) {
                                    $errors[$key] = 'Thanh toán thất bại.';
                                }
                            }
   
                            // Nếu có lỗi thì hiển thị thông báo và không tiếp tục
                            if (!empty($errors)) {
                                foreach ($errors as $message) {
                                    echo "<script>
                                        document.body.style.overflowX = 'auto';
                                        document.body.style.overflowY = 'auto';  
                                        Swal.fire({
                                            text: '$message',
                                            icon: 'error',
                                            confirmButtonColor: '#C62E2E'
                                        });
                                    </script>";
                                }
                                require_once '../views/Clients/carts/thanhtoan2.php';
                                return; // Dừng thực thi tại đây nếu có lỗi
                            }else{
                                 $this->modelClients->updateQuantityProducts($id_pro, $remaining_quantity);
                                $idbill = $this->modelClients->addBill($user_id, $address, $sdt, $email, $thanhtien, $ngaydathang, $pttt, $quantity);
                                echo "<script>
                                Swal.fire({
                                title: 'Đang xử lý...',
                                text: 'Vui lòng đợi...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                Swal.showLoading();
                                }
                                });


                                setTimeout(function () {
                                Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Đặt hàng thành công',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = `http://localhost/base_test_DA1/public/?act=bill_item&id={$idbill}`;
                                }
                                });
                                }, 2000);


                            </script>";
                                // Lưu sản phẩm vào bill_items
                                $this->modelClients->addBillItem($idbill, $id_pro, $soluong, $price);
                            }
                         
   
                        require_once '../views/Clients/carts/thanhtoan2.php';
                   
                    }else if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user'] && $_POST['pttt'] == '2'){
                        $nameUser = $_SESSION['user']['username'];
                        $email = isset($_POST['email']) ? $_POST['email'] : $_SESSION['user']['email'];
                        $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : $_SESSION['user']['sdt'];
                        $address = isset($_POST['address']) ? $_POST['address'] : $_SESSION['user']['address'];  
                        $user_id = $_SESSION['user']['id'];
                            $id_pro = $_GET['id'];
                            // Đặt tên tạm vậy thôi
                            $listCarts = $this->modelClients->getProductById($id_pro);
                            extract($listCarts);
                            $soluong = $_SESSION['buy'][$id_pro]['quantity'];
                            $thanhtien = $soluong * $price;
                            $remaining_quantity = $_SESSION['buy'][$id_pro]['soluongconlai'];
                            date_default_timezone_set('Asia/Ho_Chi_Minh');
                            $ngaydathang = date('d-m-Y H:i:s');
                            $pttt = isset($_POST['pttt']) ? $_POST['pttt'] : 0;
                                $errors = [];
       
                                // Mảng các trường cần kiểm tra
                                $fields = [
                                    'name' => $nameUser,
                                    'email' => $email,
                                    'sdt' => $sdt,
                                    'pttt' => $pttt,
                                    'address' => $address
                                ];
       
                                // Kiểm tra nếu giá trị rỗng
                                foreach ($fields as $key => $value) {
                                    if (empty($value)) {
                                        $errors[$key] = 'Thanh toán thất bại.';
                                    }
                                }
       
                                // Nếu có lỗi thì hiển thị thông báo và không tiếp tục
                                if (!empty($errors)) {
                                    foreach ($errors as $message) {
                                        echo "<script>
                                            document.body.style.overflowX = 'auto';
                                            document.body.style.overflowY = 'auto';  
                                            Swal.fire({
                                                text: '$message',
                                                icon: 'error',
                                                confirmButtonColor: '#C62E2E'
                                            });
                                        </script>";
                                    }
                                    require_once '../views/Clients/carts/thanhtoan2.php';
                                    return; // Dừng thực thi tại đây nếu có lỗi
                                }else{
                                    $this->xuliPtttAll();
                                    $this->modelClients->updateQuantityProducts($id_pro, $remaining_quantity);
                                    $idbill = $this->modelClients->addBill($user_id, $address, $sdt, $email, $thanhtien, $ngaydathang, $pttt, $quantity);
                                        // Lưu sản phẩm vào bill_items
                                    $this->modelClients->addBillItem($idbill, $id_pro, $soluong, $price);
                                }
       
                            require_once '../views/Clients/carts/thanhtoan2.php';
                       
                    }
            }else{
                echo "<script>
                alert('Bạn mua chậm tay rồi!')
            </script>";
                $this->home();
            }
       
        }


        public function infoBills(){
        $listBill = $this->modelClients->getAllBillByIdUser($_SESSION['user']['id']);
        if(!$listBill){
            echo '<div class="d-flex justify-content-center align-items-center mt-5">
                    <div class="col-sm-5">
                        <p class= "text-center">GIỏ hàng của bạn không có sản phẩm nào!</p>
                    </div>
                </div>';
        }
        // var_dump($listBill);
        require_once '../views/Clients/carts/bill.php';
    }


    // Huỷ đơn
    public function huyDon(){
        $id=$_GET['id'];
        $listBill = $this->modelClients->getAllBillByIdUser($_SESSION['user']['id']);
        $noidung = $_POST['cancel_reason'];
        // var_dump($noidung); die();
        // Huỷ đơn
        $test = $this->modelClients->insertCancellation($id, $_SESSION['user']['id'],$noidung);
        // var_dump($test); die();
        // print_r($listBill); die();
        if(!$listBill){
            echo '<div class="d-flex justify-content-center align-items-center mt-5">
                    <div class="col-sm-5">
                        <p class= "text-center">GIỏ hàng của bạn không có sản phẩm nào!</p>
                    </div>
                </div>';
        }
        $this->modelClients->updateOrderStatus($id,4);
       
        require_once '../views/Clients/carts/bill.php';
    }
   
    //comment
   
    public function formComment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idpro = $_GET['id'] ?? null;
   
            if (!$idpro) {
                echo "Không tìm thấy sản phẩm để bình luận.";
                return;
            }
   
            $noidung = htmlspecialchars($_POST['comment'] ?? '');
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $time = date('Y-m-d H:i:s');
   
            if (isset($_SESSION['user']) && $_SESSION['user']['id']) {
                $idUser = $_SESSION['user']['id'];
   
                if ($this->modelClients->addComment($idpro, $idUser, $noidung, $time)) {
                    header('Location: ?act=sanphamchitiet&id=' . $idpro);
                    exit();
                } else {
                    echo "Không thể thêm bình luận. Vui lòng thử lại.";
                }
            } else {
                echo "Bạn cần <a href='?act=login'>đăng nhập</a> để gửi bình luận.";
            }
        }
    }
   


    public function deleteComment() {
        $commentId = $_GET['id']; // Get the comment ID
        $comment = $this->modelClients->getCommentById($commentId); // Retrieve the comment from the database
   
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id']; // Get logged-in user ID
   
            // If the user is the one who made the comment or if the user is an admin
            if ($comment['idUser'] == $userId || $_SESSION['user']['role'] == 'admin') {
                $this->modelClients->deleteComment($commentId);
                header('Location: ?act=sanphamchitiet&id=' . $comment['idpro']);
                exit();
            } else {
                echo "Bạn không có quyền xóa bình luận này.";
            }
        } else {
            echo "Bạn cần đăng nhập để xóa bình luận.";
        }
    }
   
      //  
      public function productByCasterri(){
       
        $id = $_GET['id'] ;
        $data = $this->modelClients->productByCasterri($id) ;
       
       
        require_once '../views/Clients/productByCasteri/productByCasterri.php';
       
      }
      ///yeuthich
      public function addFavourite() {
        if (isset($_SESSION['user']) && isset($_GET['id'])) {
            $userId = $_SESSION['user']['id'];
            $productId = $_GET['id'];
           
            // Thiết lập múi giờ là giờ Hà Nội (UTC+7)
            date_default_timezone_set('Asia/Ho_Chi_Minh');
           
            // Lấy thời gian hiện tại theo giờ Hà Nội
            $addedAt = date('Y-m-d H:i:s');
           
            if ($this->modelClients->checkFavourite($userId, $productId)) {
                echo "Sản phẩm đã có trong danh sách yêu thích.";
            } else {
                if ($this->modelClients->addToFavourite($userId, $productId, $addedAt)) {
                    header("Location: ?act=listFavourites");
                    exit();
                } else {
                    echo "Không thể thêm sản phẩm vào danh sách yêu thích.";
                }
            }
        } else {
            echo "Bạn cần đăng nhập để sử dụng chức năng này.";
        }
    }
   
    public function listFavourites() {
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $favourites = $this->modelClients->getFavouritesByUser($userId);
            require_once '../views/Clients/favourites/listFavourites.php';
        } else {
            echo "Bạn cần đăng nhập để xem danh sách yêu thích.";
        }
    }
    public function removeFavourite() {
        if (isset($_SESSION['user']) && isset($_POST['product_id'])) {
            $userId = $_SESSION['user']['id'];
            $productId = $_POST['product_id'];
           
            if ($this->modelClients->removeFavourite($userId, $productId)) {
                header("Location: ?act=listFavourites");
                exit();
            } else {
                echo "Không thể xóa sản phẩm khỏi danh sách yêu thích.";
            }
        } else {
            echo "Bạn cần đăng nhập để xóa sản phẩm yêu thích.";
        }
    }


   
    
        
}  