<?php 

class ClientModels 
{
    public $conn;

    public function __construct() { // Hàm khởi tạo kết nối đối tượng
        $this->conn = connectDB();
    }

    
    public function __destruct() {  // Hàm hủy kết nối đối tượng
        $this->conn = null;
    }   

}