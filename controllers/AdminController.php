<?php
class HomeController
{
    public $modelAdmin;

    public function __construct()
    {
        $this->modelAdmin = new AdminModels();
    }

    public function home()
    {
        $listBillStatus = $this->modelAdmin->getAllBill_3();
        $sumBills = $this->modelAdmin->getTotalOrders();
        $sumTotalBill = $this->modelAdmin->sumTotalOrders();
        $sumProducts = $this->modelAdmin->sumProducts();
        $sumComments = $this->modelAdmin->sumComments();
        $revenues = $this->modelAdmin->getDailyRevenue();
        // print_r($revenues);
        require_once '../../views/Admins/home/home.php';
    }

    public function confirmOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id']; // Lấy id hóa đơn
            $soluong = $this->modelAdmin->getQuantitiesByBillId($id); // Lấy thông tin sản phẩm và số lượng từ bảng bill_items
            $bill_status = $this->modelAdmin->getBillStatus($id);
            foreach ($soluong as $item) {
                $product_id = $item['product_id'];  // Lấy product_id
                $quantity_bill = $item['quantity']; // Lấy số lượng sản phẩm từ bill_items

                // Lấy số lượng hiện tại của sản phẩm trong bảng products
                $quantity_product = $this->modelAdmin->getQuantityPro($product_id);

                // Cộng số lượng đã hủy vào bảng products
                $new_quantity = $quantity_product + $quantity_bill;

                // Cập nhật lại số lượng sản phẩm trong bảng products
                $this->modelAdmin->updateQuantityPro($product_id, $new_quantity);
            }
            // var_dump($soluong);die();
            $this->modelAdmin->updateOrderStatus($id, $bill_status + 1);
            header('location: router.php');
        }
    }

    // Danh Mục
    public function formAddDm()
    {
        require_once '../../views/Admins/DanhMuc/formAddDM.php';
    }

    public function listDm()
    {
        $listDanhMuc = $this->modelAdmin->getAllDanhMuc();
        require_once '../../views/Admins/DanhMuc/listDm.php';
    }
    public function postDm()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
            $name = $_POST['name'];
            if ($this->modelAdmin->postDm($name)) {
                header('location: router.php?act=listDm');
                exit;
            }
        }
    }
    public function deleteDm()
    {
        $id = $_GET['id'];
        $record = $this->modelAdmin->getDmById($id);
        if ($record) { // Kiểm tra xem bản ghi có tồn tại không
            if ($this->modelAdmin->deleteDm($id)) {
                header('Location: router.php?act=listDm');
                exit;
            } else {
                echo "Không thể xóa danh mục.";
            }
        } else {
            echo "Danh mục không tồn tại.";
        }
    }

    public function formSuaDm()
    {
        $id = $_GET['id'];
        $danhMuc = $this->modelAdmin->getDmById($id);
        require_once '../../views/Admins/DanhMuc/updateDm.php';
    }

    public function updateDm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];

            if ($this->modelAdmin->updateDm($id, $name)) {
                header('Location: router.php?act=listDm');
                exit;
            }
        }
    }
    // Tài khoản
    public function listTaiKhoan()
    {
        $listTaiKhoan = $this->modelAdmin->getAllAcounts();
        require_once '../../views/Admins/TaiKhoan/listTaiKhoan.php';
    }
    public function accoutAtive()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $active = $_POST['active'];

            if ($this->modelAdmin->updateAccoutAtive($id, $active)) {
                header('Location: router.php?act=listTaiKhoan');
                exit;
            }
        }
    }
    public function accoutRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $role = $_POST['role'];

            if ($this->modelAdmin->updateAccoutRole($id, $role)) {
                header('Location: router.php?act=listTaiKhoan');
                exit;
            }
        }
    }

    ///SanPham
    public function formAddSP()
    {
        $listDanhMuc = $this->modelAdmin->getAllDanhMuc();
        require_once '../../views/Admins/SanPham/formAddSP.php';
    }
    public function listSP()
    {
        $listDanhMuc = $this->modelAdmin->getAllDanhMuc();

        $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

        $listProducts = $this->modelAdmin->getAllProductsByCategory($categoryId, $searchTerm);
        // var_dump($listProducts);
        require_once '../../views/Admins/SanPham/listSP.php';
    }
    public function postSP()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $namesp = $_POST['namesp'];
            $price = $_POST['price'];
            $mota = $_POST['mota'];
            $iddm = $_POST['iddm'];
            $quantity = $_POST['id_soluong'];

            // Kiểm tra file ảnh  
            if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
                $file_save = uploadFile($_FILES['img'], 'uploads');
                if ($file_save) {
                    if ($this->modelAdmin->postSP($namesp, $price, $file_save, $mota, $iddm, $quantity)) {
                        header('Location: router.php?act=listSP');
                        exit();
                    } else {
                        echo "Lỗi khi thêm sản phẩm vào cơ sở dữ liệu.";
                    }
                } else {
                    echo "Lỗi khi lưu tệp ảnh.";
                }
            }
        } else {
            header('Location: /router.php?act=listSP');
            exit();
        }
    }

    public function deleteSP()
    {
        $id = $_GET['id'];

        $record = $this->modelAdmin->getSPById($id);

        if ($record) {
            if ($this->modelAdmin->deleteSP($id)) {
                header('Location: router.php?act=listSP');
                exit;
            } else {
                echo "Không thể xóa sản phẩm.";
            }
        } else {
            echo "Sản phẩm không tồn tại.";
        }
    }
    public function formSuaSP()
    {
        $id = $_GET['id'];
        $product = $this->modelAdmin->getSPById($id);
        $listDanhMuc = $this->modelAdmin->getAllDanhMuc();

        if ($product) {
            require_once '../../views/Admins/SanPham/formupdateSP.php';
        } else {
            echo "Sản phẩm không tồn tại.";
        }
    }


    public function updateSP()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $namesp = $_POST['namesp'];
            $price = $_POST['price'];
            $mota = $_POST['mota'];
            $iddm = $_POST['iddm'];
            $quantity = $_POST['quantity'];
            $img = ''; // Xử lý ảnh mới  

            if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
                // Xử lý upload ảnh mới  
                $file_save = uploadFile($_FILES['img'], 'uploads');
                if ($file_save) {
                    $img = $file_save; // Gán đường dẫn ảnh mới  
                } else {
                    echo "Lỗi khi lưu tệp ảnh.";
                    return;
                }
            }

            // Cập nhật sản phẩm
            $current_img = $_POST['current_img'];
            if ($this->modelAdmin->updateSP($id, $namesp, $price, $img ?: $current_img, $mota, $iddm, $quantity)) {
                header('Location: router.php?act=listSP');
                exit;
            } else {
                echo "Lỗi khi cập nhật sản phẩm.";
            }
        } else {
            header('Location: router.php?act=listSP');
            exit();
        }
    }
    //đơn hàng
    public function listBills()
    {
        $listDanhMuc = $this->modelAdmin->getAllDanhMuc();
        $listOrders = $this->modelAdmin->getAllBill();
        // var_dump($listOrders);
        require_once '../../views/Admins/donHang/listDonHang.php';
    }
    public function bill_items()
    {
        $id = $_GET['id'];
        $listBill = $this->modelAdmin->getBillById($id);
        // var_dump($listBill);
        $lydo = $this->modelAdmin->getLyDoHuyHang($id);
        // var_dump($lydo);
        require_once '../../views/Admins/SanPham/chitietsp.php';
    }


    public function updateStatusBills()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bill_status = (int) $_POST['bill_status'];
            $id = $_GET['id'];

            // Lấy trạng thái hiện tại
            $currentStatus = $this->modelAdmin->getBillStatus($id);
            // Kiểm tra trạng thái hợp lệ
            if ($bill_status == $currentStatus + 1) {
                if ($this->modelAdmin->updateOrderStatus($id, $bill_status)) {
                    header('location: router.php?act=listDonHang');
                } else {
                    header('location: router.php?act=listDonHang');
                }
            } else {
                echo "Lỗi";
                header('location: router.php?act=listDonHang');
                // exit;
            }
        }
    }


    ///bình luận
    public function listComments()
    {
        $listBinhLuan = $this->modelAdmin->getAllComments();
        require_once '../../views/Admins/BinhLuan/listComments.php';
    }


    public function deleteComment()
    {
        $id = $_GET['id'];
        if ($this->modelAdmin->deleteComment($id)) {
            header('Location: router.php?act=listComments');
            exit;
        } else {
            echo "Không thể xóa bình luận.";
        }
    }
    public function toggleComment()
    {
        $id = $_GET['id'];
        $comment = $this->modelAdmin->getCommentById($id);
        if ($comment) {
            $newStatus = $comment['status'] ? 0 : 1;
            if ($this->modelAdmin->updateCommentStatus($id, $newStatus)) {
                header('Location: router.php?act=listComments');
                exit;
            } else {
                echo "Không thể cập nhật trạng thái bình luận.";
            }
        } else {
            echo "Bình luận không tồn tại.";
        }
    }
}
