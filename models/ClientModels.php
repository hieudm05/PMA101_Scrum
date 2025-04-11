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

    public function updateQuantityProducts($id, $quantity) {
        try {
            // Câu lệnh SQL để cập nhật số lượng sản phẩm
            $sql = 'UPDATE products SET quantity = :quantity WHERE id = :id';
           
            // Chuẩn bị và thực thi câu lệnh SQL
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'quantity' => $quantity,
                'id' => $id,
            ]);
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
           
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
   
    // Nếu mà có rồi, thì cập nhật số lượng
    public function updateQuantity($idUser, $idpro, $soluong, $thanhtien) {
        try {
            $sql = 'UPDATE carts SET soluong = :soluong, thanhtien = :thanhtien WHERE idUser = :idUser AND idpro = :idpro';
 
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'idUser' => $idUser,
                'idpro' => $idpro,
                'soluong' => $soluong,
                'thanhtien' => $thanhtien
            ]);
           
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Lấy số lượng sản phẩm tồn kho để trừ xuống
    public function getRemainingQuantity( $idpro) {
        try {
            $sql = 'SELECT remaining_quantity FROM carts WHERE idpro = :idpro';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'idpro' => $idpro
            ]);
           
            $result = $stmt->fetch();
            return $result['remaining_quantity'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


    // Lấy số lượng sản phẩm trong carts
    public function getSoLuongById( $id) {
        try {
            $sql = 'SELECT soluong FROM carts WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
           
            $result = $stmt->fetch();
            return $result['soluong'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Lấy giá ở trong kho để xử lí phần tăng giảm
    public function getPriceByIdCart( $id) {
        try {
            $sql = 'SELECT price FROM carts WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
           
            $result = $stmt->fetch();
            return $result['price'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Cập nhật sản phẩm còn lại trong kho
    public function updateRemainingQuantity( $idpro,$remaining_quantity) {
        try {
            $sql = 'UPDATE carts SET  remaining_quantity = :remaining_quantity WHERE idpro = :idpro';
 
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'idpro' => $idpro,
                'remaining_quantity' => $remaining_quantity
            ]);
           
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
     // Kiểm tra xem giỏ hàng có tồn tại sản phẩm
     public function checkCarts($idUser, $idProduct){
        try {
            $sql = 'SELECT * FROM carts WHERE idUser = :idUser AND idpro = :idProduct';
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute(['idUser'=> $idUser, 'idProduct'=> $idProduct]);


            return $stmt->fetch();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Lưu sản phẩm trong giỏ hàng vào cart
    public function addCarts($idUser, $idpro, $img, $name, $price, $soluong, $thanhtien, $mota, $remaining_quantity) {
        // SQL để thêm sản phẩm vào giỏ hàng
        $sql = 'INSERT INTO carts (idUser, idpro, img, name, price, soluong, thanhtien, mota, created_at, remaining_quantity)
                VALUES (:idUser, :idpro, :img, :name, :price, :soluong, :thanhtien, :mota, NOW(), :remaining_quantity)';
       
        // Chuẩn bị câu lệnh SQL
        $stmt = $this->conn->prepare($sql);
       
        // Thực thi câu lệnh với các tham số
        $stmt->execute([
            'idUser' => $idUser,
            'idpro' => $idpro,
            'img' => $img,
            'name' => $name,
            'price' => $price,
            'soluong' => $soluong,
            'thanhtien' => $thanhtien,
            'mota' => $mota,
            'remaining_quantity' => $remaining_quantity
        ]);
       
        return true;
    }
    // Lấy số lượng trong bảng carts để kiểm tra còn tăng lên chứ
    public function getCartQuantity( $id) {
        try {
            $sql = 'SELECT quantity FROM products WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
           
            $result = $stmt->fetch();
            return $result ? $result['quantity'] : 0; // Nếu không có sản phẩm
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


    public function getSoLuongCarts($idpro, $idUser){
        try {
            $sql = 'SELECT soluong FROM carts WHERE idpro = :idpro AND idUser = :idUser';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'idpro' => $idpro,
                'idUser' => $idUser,
            ]);
           
            $result = $stmt->fetch();
            return $result ? $result['soluong'] : 0; // Nếu không có sản phẩm
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


    public function getRemaining_quantity( $id) {
        try {
            $sql = 'SELECT remaining_quantity FROM carts WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
           
            $result = $stmt->fetch();
            return $result ? $result['remaining_quantity'] : 0; // Nếu không có sản phẩm
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Trả về id của sản phẩm
    public function getIdProduct($id) {
        try {
            $sql = 'SELECT idpro FROM carts WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);
           
            $result = $stmt->fetchColumn();
            return $result; // Trả về mảng các `idPro`
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false; // Trả về false nếu có lỗi xảy ra
        }
    }
   
    // Lấy giá bên products
    public function getPriceByPro($id){
        try {
            $sql = 'SELECT price FROM products WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id
            ]);
           
            $result = $stmt->fetch();
            return $result['price'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


}