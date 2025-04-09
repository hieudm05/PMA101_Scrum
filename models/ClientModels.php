<?php 

class ClientModels 
{
    public $conn;

    public function __construct() { // Hàm khởi tạo kết nối đối tượng
        $this->conn = connectDB();
    }

    // Đăng kí tài khoản
    public function addAccount($username, $email, $password, $sdt){
        $sql = 'INSERT INTO accounts (username, email, password, sdt) VALUES (:username, :email, :password, :sdt)';
        $stmt = $this->conn->prepare($sql);
        $stmt -> execute(['username' => $username, 'email' => $email, 'password' => $password, 'sdt' => $sdt]);
        return true;
    }


    // Cập nhật tài khoản
    public function getAccountById($id){
        try {
            $sql = 'SELECT * FROM accounts WHERE id ='.$id;
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetch();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    public function updateAccout($id, $username, $email, $avatar, $address, $sdt){
        try {
            $sql = "UPDATE accounts
                    SET
                        username = :username,
                        email = :email,
                        avatar = :avatar,
                        address = :address,
                        sdt = :sdt
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'avatar' => $avatar,
                'address' => $address,
                'sdt' => $sdt,
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Login
    public function checkAcc($userOrEmail, $pass) {
        $sql = 'SELECT * FROM accounts WHERE (username = :userOrEmail OR email = :userOrEmail) AND password = :pass';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':userOrEmail' => $userOrEmail,
            ':pass' => $pass
        ]);
        return $stmt->fetch();
    }


    // Danh mục
    public function getAllDanhMuc() {
        try {
            $sql = 'SELECT * FROM categories ORDER BY id DESC';
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetchAll();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    // Lấy sản phẩm theo từng nhóm danh mục
    public function getAllProductsByCategory() {
        $sql = "SELECT
                    products.id,
                    products.namesp,
                    products.price,
                    products.img,
                    products.mota,
                    products.quantity,
                    categories.name AS category_name
                FROM categories
                LEFT JOIN products ON categories.id = products.iddm
                ORDER BY categories.name, products.price ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTop10Sp(){
        try {
            // Câu lệnh SQL để lấy 10 sản phẩm có lượt xem cao nhất
            $sql = "SELECT
                    p.id AS id,
                    p.namesp AS namesp,
                    p.img,
                    p.price,
                    SUM(bi.quantity) AS total_quantity_sold
                FROM bill_items bi
                JOIN bills b ON bi.bill_id = b.id
                JOIN products p ON bi.product_id = p.id
                WHERE b.bill_status = 3
                GROUP BY bi.product_id
                ORDER BY total_quantity_sold DESC
                LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}