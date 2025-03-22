<?php 
class HomeController
{
    public $modelAdmin;

    public function __construct() {
        $this->modelAdmin = new AdminModels();
    }
    public function home() {
        $listBillStatus = $this->modelAdmin->getAllBill_3();
        $sumBills = $this->modelAdmin->getTotalOrders();
        $sumTotalBill = $this->modelAdmin->sumTotalOrders();
        $sumProducts = $this->modelAdmin->sumProducts();
        $sumComments = $this->modelAdmin->sumComments();
        $revenues = $this->modelAdmin->getDailyRevenue();
        // print_r($revenues);
        require_once '../../views/Admins/home/home.php';
    }
   
    public function confirmOrder() {
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

}