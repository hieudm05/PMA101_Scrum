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
    public function listCartByUser($idUser){
        try {
            $sql = 'SELECT * FROM carts WHERE idUser = :idUser ORDER BY id DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':idUser' => $idUser
            ]);


            return $stmt->fetchAll();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Xoá hết sản phẩm theo user
    public function deleteAllCarts($id){
        $sql = "DELETE FROM carts WHERE idUser = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    // Xoá cụ thể sản phẩm theo user
    public function deleteCarts($id) {
        try {
            $sql = 'DELETE FROM carts WHERE id =:id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id
            ]);
   
            return true;
        } catch (Exception $e) {
            // Xử lý lỗi nếu có
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    // Thanh toán
    public function addBill($iduser, $bill_address, $bill_sdt, $bill_email, $total, $ngaydathang, $bill_pttt, $quantity) {
        try {
            $sql = 'INSERT INTO bills (iduser, bill_address, bill_sdt, bill_email, total, ngaydathang, bill_pttt, quantity)
                    VALUES (:iduser, :bill_address, :bill_sdt, :bill_email, :total, :ngaydathang, :bill_pttt, :quantity)';
            $stmt = $this->conn->prepare($sql);
           
            $stmt->execute([
                ':iduser' => $iduser,
                ':bill_address' => $bill_address,
                ':bill_sdt' => $bill_sdt,
                ':bill_email' => $bill_email,
                ':total' => $total,
                ':ngaydathang' => $ngaydathang,
                ':bill_pttt' => $bill_pttt,
                ':quantity' => $quantity,
            ]);
            $idBill = $this->conn->lastInsertId();
            return $idBill;
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }


    // Chuyển dữ liệu từ bảng carts sang bảng bill_item
    public function addBillItem($bill_id, $product_id, $quantity, $price){
        $sql = 'INSERT INTO bill_items (bill_id, product_id, quantity, price) VALUES (:bill_id, :product_id, :quantity, :price)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':bill_id' => $bill_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':price' => $price
        ]);
        return true;
    }
   


    // update bills
   
    // Sau khi thanh toán thành công thì xoá sản phẩm trong carts
    public function clearCart($user_id) {
        // Xóa tất cả sản phẩm trong giỏ hàng của người dùng sau khi đặt hàng
        $sql = "DELETE FROM carts WHERE idUser = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }


    // Xử lí dữ liệu mua ngay


    public function getAllBillByIdUser($idUser){
        $sql = 'SELECT
                    bills.id AS bill_id,
                    bills.*,
                    accounts.username AS user_name,
                    GROUP_CONCAT(p.img ORDER BY p.img ASC) AS img,
                    GROUP_CONCAT(p.namesp ORDER BY p.namesp ASC) AS product_names,
                    GROUP_CONCAT(bi.quantity ORDER BY p.namesp ASC) AS product_quantities,
                    GROUP_CONCAT(bi.total ORDER BY p.namesp ASC) AS pro_total,
                    GROUP_CONCAT(bi.price ORDER BY p.namesp ASC) AS product_prices,
                    GROUP_CONCAT(p.img ORDER BY p.namesp ASC) AS product_images
                FROM
                    bills
                JOIN
                    accounts ON bills.idUser = accounts.id
                LEFT JOIN
                    bill_items bi ON bills.id = bi.bill_id
                LEFT JOIN
                    products p ON bi.product_id = p.id
                WHERE idUser = :idUser
                GROUP BY
                    bills.id
                ORDER BY
                    bills.bill_status ASC, bills.id DESC;
                ';
        // $sql = "SELECT * FROM bills  ORDER BY bills.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }


    // Lấy mọi thông tin theo id đơn hàng
    public function getBillById($billId) {
        $sql = 'SELECT
        bills.id AS bill_id,
        bills.*,
        accounts.username AS user_name,
        GROUP_CONCAT(p.img ORDER BY p.img ASC) AS img,
        GROUP_CONCAT(p.namesp ORDER BY p.namesp ASC) AS product_names,
        GROUP_CONCAT(bi.quantity ORDER BY p.namesp ASC) AS product_quantities,
        GROUP_CONCAT(bi.total ORDER BY p.namesp ASC) AS pro_total,
        GROUP_CONCAT(bi.price ORDER BY p.namesp ASC) AS product_prices,
        GROUP_CONCAT(p.img ORDER BY p.namesp ASC) AS product_images
    FROM
        bills
    JOIN
        accounts ON bills.idUser = accounts.id
    LEFT JOIN
        bill_items bi ON bills.id = bi.bill_id
    LEFT JOIN
        products p ON bi.product_id = p.id
    WHERE
        bills.id = :billId
    GROUP BY
        bills.id
    ORDER BY
       CASE
            WHEN bills.bill_status = 4 THEN 0 -- Trạng thái 4 giữ nguyên vị trí
            ELSE 1 -- Các trạng thái khác sắp xếp bình thường
            END,
            bills.bill_status ASC, -- Sắp xếp trạng thái tăng dần
            bills.id DESC';        


                   
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':billId', $billId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
   
    // Cập nhật trạng thái đơn hàng
     public function updateOrderStatus($orderId, $status) {
        // SQL query để cập nhật trạng thái đơn hàng
        $sql = "UPDATE bills SET bill_status = :status WHERE id = :orderId";
       
        // Chuẩn bị câu lệnh
        $stmt = $this->conn->prepare($sql);
       
        // Gắn giá trị vào các tham số
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    // Phản hồi huỷ hàng
    public function insertCancellation($idBill, $idUser, $reasons) {
        // SQL query để chèn bản ghi mới vào bảng cancellation_reasons
        $sql = "INSERT INTO cancellation_reasons (idBill, idUser, reasons, at_time)
                VALUES (:idBill, :idUser, :reasons, NOW())";
       
        // Chuẩn bị câu lệnh
        $stmt = $this->conn->prepare($sql);
       
        // Gắn giá trị vào các tham số
        $stmt->bindParam(':idBill', $idBill, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->bindParam(':reasons', $reasons, PDO::PARAM_STR);
       
        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true; // Thêm bản ghi thành công
        } else {
            // In ra lỗi SQL nếu không thực thi được
            print_r($stmt->errorInfo());
            return false; // Thêm bản ghi thất bại
        }
    }
   
    /// bình luận
    public function getCommentsByProductId($id) {
        $sql = "SELECT c.*, a.username
                FROM comments c
                JOIN accounts a ON c.idUser = a.id
                WHERE c.idpro = :id AND c.status = 1
                ORDER BY c.time DESC";  // Add `c.status = 1` to check visibility
       
        $stmt = $this->conn->prepare($sql);
       
        try {
            $stmt->execute(['id' => $id]);
            $comments = $stmt->fetchAll();
            return $comments;
        } catch (PDOException $e) {
            throw $e;
        }
    }
   
    public function deleteComment($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
    public function getCommentById($id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function productByCasterri($id) {
        try {
            // Lấy sản phẩm và tên danh mục
            $sql = "SELECT p.*, c.name AS category_name
                FROM products p
                INNER JOIN categories c ON p.iddm = c.id
                WHERE c.id = :id";
   
            $stmt = $this->conn->prepare($sql);
   
            // Gắn giá trị cho tham số :id
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   
            // Thực thi câu lệnh
            $stmt->execute();
   
            // Trả về kết quả
            return $stmt->fetchAll();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    //yeuthich
    public function checkFavourite($userId, $productId) {
        try {
            $sql = "SELECT * FROM favorites WHERE user_id = :user_id AND pro_id = :pro_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['
            :user_id' => $userId,
            ':pro_id' => $productId
            ]);
           
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Lỗi khi kiểm tra sản phẩm yêu thích: " . $e->getMessage();
            return false;
        }
    }
   
    public function addToFavourite($userId, $productId, $addedAt) {
        try {
            $sql = "INSERT INTO favorites (user_id, pro_id, added_at) VALUES (:user_id, :pro_id, :added_at)";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':user_id' => $userId,
                ':pro_id' => $productId,
                ':added_at' => $addedAt
            ]);
           
            return $result;
        } catch (Exception $e) {
            echo "Lỗi khi thêm sản phẩm vào yêu thích: " . $e->getMessage();
            return false;
        }
    }
   
    public function getFavouritesByUser($userId) {
        try {
            $sql = "
                SELECT
                    f.id AS favorite_id,
                    f.added_at,
                    p.id AS product_id,
                    p.namesp,
                    p.price,
                    p.img,
                    a.username AS user_name,
                    a.email AS user_email
                FROM favorites f
                JOIN products p ON f.pro_id = p.id
                JOIN accounts a ON f.user_id = a.id
                WHERE f.user_id = :user_id
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
           
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Lỗi khi lấy danh sách yêu thích của người dùng: " . $e->getMessage();
            return [];
        }
    }
    public function removeFavourite($userId, $productId) {
        try {
            $sql = "DELETE FROM favorites WHERE user_id = :user_id AND pro_id = :pro_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':pro_id' => $productId
            ]);
        } catch (Exception $e) {
            echo "Lỗi khi xóa sản phẩm yêu thích: " . $e->getMessage();
            return false;
        }
    }

    // Tìm kiếm theo sản phẩm
    public function getAllSP($search) {
        try {
            $sql = "SELECT * FROM products WHERE namesp LIKE '%$search%'";
           
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
   
            return $stmt->fetchAll();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getSPById($id) {  
        try {
            $sql = 'SELECT * FROM products WHERE id ='.$id;
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetch();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
   
    public function load_sanpham_cungloai($id, $iddm){
       try {
        $sql= "SELECT * FROM products WHERE iddm = ".$iddm." AND id <> ".$id;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
       } catch (Exception $e) {
        echo $e->getMessage();
        }
    }
    public function getNewestProducts($limit = 4) {
        try {
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Trả về mảng rỗng nếu không có kết quả
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }
   
   
    ///comment
    public function addComment($idpro, $idUser, $noidung, $time) {
        try {
            $sql = "INSERT INTO comments (idpro, idUser, noidung, time)
                    VALUES (:idpro, :idUser, :noidung, :time)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':idpro' => $idpro,
                ':idUser' => $idUser,
                ':noidung' => $noidung,
                ':time' => $time,
            ]);
   
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            // Ghi log lỗi
            file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
            return false;
        }
    }
   
    // Giỏ hàng
    public function getProductById($id){
        try {
            $sql = 'SELECT * FROM products WHERE id ='.$id;
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetch();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function __destruct() {  // Hàm hủy kết nối đối tượng
        $this->conn = null;
    }  



}