<?php 

class AdminModels 
{
    public $conn;

    public function __construct() { // Hàm khởi tạo kết nối đối tượng
        $this->conn = connectDB();
    }

    public function deleteDm($id){
        try {
            $sql = 'DELETE FROM categories WHERE id = :id ';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;


        }catch(Exception $e){
            echo 'err'.$e->getMessage();
        }
    }
    public function updateDm($id, $name) {
        try {
            $sql = "UPDATE categories SET name = :name WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['name'=>$name, 'id'=>$id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


    // Tài khoản
    public function getAllAcounts(){
        try {
            $sql = 'SELECT * FROM accounts ORDER BY id DESC';
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetchAll();
           
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
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
    public function postDm($name) {
        try {
            $sql = 'INSERT INTO categories(name) VALUES(:name)';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['name'=>$name]);
            return true;
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }


    public function getDmById( $id){
        try {
            $sql = 'SELECT * FROM categories WHERE id = '.$id;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch();


        }catch(Exception $e){
            echo 'err'.$e->getMessage();
        }
    }

    // Sản phẩm
    public function getAllSP() {
        try {
            $sql = 'SELECT * FROM products ORDER BY id DESC';
   
            $stmt = $this->conn->prepare($sql);
       
            $stmt->execute();


            return $stmt->fetchAll();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public function postSP($namesp, $price, $img, $mota, $iddm, $quantity) {
        try {
            // Thực hiện câu lệnh INSERT để thêm sản phẩm mới
            $sql = "INSERT INTO products (namesp, price, img, mota, iddm, quantity)
                    VALUES (:namesp, :price, :img, :mota, :iddm, :quantity)";
           
            // Chuẩn bị câu lệnh SQL
            $stmt = $this->conn->prepare($sql);
           
            // Các giá trị cần truyền vào câu lệnh
            $stmt->execute([
                'namesp' => $namesp,
                'price' => $price,
                'img' => $img,
                'mota' => $mota,
                'iddm' => $iddm,
                'quantity' => $quantity,
            ]);
           
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    public function getSPById($id) {  
        try {  
            $sql = 'SELECT * FROM products WHERE id = :id';  
            $stmt = $this->conn->prepare($sql);  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID as an integer to prevent SQL injection  
            $stmt->execute();  
           
            $product = $stmt->fetch();
           
            // Check if a product was found
            if ($product) {
                return $product;
            } else {
                echo "Product not found.";
                return false;
            }
        } catch(Exception $e) {  
            echo 'Error: ' . $e->getMessage();  
            return false;  
        }  
    }
   
    public function getAllProductsByCategory($categoryId = null, $searchTerm = null) {  
        $sql = "SELECT  
                    products.id,  
                    products.namesp,  
                    products.price,  
                    products.img,  
                    products.mota,  
                    products.luotxem,  
                    products.quantity,  
                    categories.name AS category_name  
                FROM categories  
                LEFT JOIN products ON categories.id = products.iddm";  
       
        $conditions = [];  
       
        if ($categoryId) {  
            $conditions[] = "products.iddm = :categoryId";  
        }  
        if ($searchTerm) {  
            $conditions[] = "products.namesp LIKE :searchTerm";  
        }  
   
        if (count($conditions) > 0) {  
            $sql .= " WHERE " . implode(' AND ', $conditions);  
        }  
   
        $sql .= " ORDER BY categories.name, products.price ASC";  
   
        $stmt = $this->conn->prepare($sql);  
   
        if ($categoryId) {  
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);  
        }  
        if ($searchTerm) {  
            $searchTerm = '%' . $searchTerm . '%';
            $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);  
        }  
   
        $stmt->execute();  
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  

    }

    public function deleteSP($id) {  
        try {  
            $sql = 'DELETE FROM products WHERE id = :id';  
            $stmt = $this->conn->prepare($sql);  
            $stmt->execute(['id' => $id]);  
            return true;  
        } catch(Exception $e) {  
            echo 'Error: ' . $e->getMessage();  
            return false;  
        }  
    }  


    public function updateSP($id, $namesp, $price, $img, $mota, $iddm, $quantity) {  
        try {  
            $sql = "UPDATE products SET namesp = :namesp, price = :price, img = :img, mota = :mota, iddm = :iddm, quantity = :quantity WHERE id = :id";  
            $stmt = $this->conn->prepare($sql);  
            $stmt->execute([  
                'namesp' => $namesp,  
                'price' => $price,  
                'img' => $img,  
                'mota' => $mota,  
                'iddm' => $iddm,
                'quantity' => $quantity,
                'id' => $id  
            ]);  
            return true;  
        } catch (Exception $e) {  
            echo 'Error: ' . $e->getMessage();  
            return false;  
        }  
    }  


     //đơn hàng
     public function getAllBill() {
        try {
            $sql = 'SELECT
                        bills.id AS bill_id,
                        bills.*,
                        accounts.username AS user_name,
                        GROUP_CONCAT(p.namesp ORDER BY p.namesp ASC) AS product_names,
                        GROUP_CONCAT(bi.quantity ORDER BY p.namesp ASC) AS product_quantities,
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
                    GROUP BY
                        bills.id
                    ORDER BY
                        bills.id DESC;
                    ';
           
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
       
    }
    public function getAllBill_3() {
        try {
            $sql = 'SELECT
                        bills.*,
                        accounts.username AS user_name,
                        GROUP_CONCAT(p.namesp ORDER BY p.namesp ASC) AS product_names,
                        GROUP_CONCAT(bi.quantity ORDER BY p.namesp ASC) AS product_quantities,
                        GROUP_CONCAT(bi.price ORDER BY p.namesp ASC) AS product_prices,
                        GROUP_CONCAT(p.img ORDER BY p.namesp ASC) AS product_images,
                        SUM(bi.quantity) AS total_quantity  -- Tổng số lượng sản phẩm
                    FROM bills
                    JOIN accounts ON bills.idUser = accounts.id
                    LEFT JOIN bill_items bi ON bills.id = bi.bill_id
                    LEFT JOIN products p ON bi.product_id = p.id
                    WHERE bills.bill_status IN (0,4)
                    GROUP BY bills.id
                    ORDER BY bills.id DESC';
   
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
     // Tổng số sản phẩm
     public function sumProducts(){
        try {
            $sql = "SELECT COUNT(*) AS total FROM products";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
              // Lấy kết quả trả về
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ? $row['total'] : 0 ;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    // Tổng bình luận
    public function sumComments(){
        try {
            $sql = "SELECT COUNT(*) AS total FROM comments";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
              // Lấy kết quả trả về
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ? $row['total'] : 0 ;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    public function getDailyRevenue() {
        $sql = "SELECT
                DAYOFWEEK(STR_TO_DATE(ngaydathang, '%Y-%m-%d')) AS day_of_week,
                SUM(total) AS total_revenue
                FROM bills
                WHERE bill_status = 3
                GROUP BY day_of_week
                ORDER BY day_of_week";
       
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $listRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // Lấy ngày hôm nay (0 đến 6, từ Chủ Nhật đến Thứ Bảy)
        $currentDay = date('w'); // day of the week (0 for Sunday, 6 for Saturday)
       
        // Khởi tạo mảng doanh thu cho các ngày trong tuần (0 đến 6)
        $revenues = array_fill(0, 7, 0); // Mảng chứa doanh thu cho 7 ngày trong tuần
       
        // Gán doanh thu cho đúng ngày trong tuần
        foreach ($listRevenue as $data) {
            if ($data['day_of_week'] !== NULL) {
                $revenues[$data['day_of_week'] - 1] = $data['total_revenue']; // Gán doanh thu vào mảng
            }
        }
       
        // Thêm giá trị cho ngày hôm nay vào mảng nếu chưa có
        if ($revenues[$currentDay] == 0) {
            // Nếu hôm nay chưa có doanh thu, gán giá trị 0 hoặc tính toán doanh thu cho ngày hôm nay
            // Ví dụ: gán $revenues[$currentDay] = 0; hoặc gán doanh thu tính toán
        }
   
        return $revenues; // Trả về mảng doanh thu
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


    // Lấy id product theo id bill
    function getProductIdsByBillId($bill_id) {
        // SQL truy vấn để lấy tất cả product_id từ bảng bill_items theo bill_id
        $sql = "SELECT product_id
                FROM bill_items
                WHERE bill_id = :bill_id";
   
        // Chuẩn bị truy vấn
        $stmt = $this->conn->prepare($sql);
   
        // Thực thi truy vấn với tham số bill_id
        $stmt->execute([':bill_id' => $bill_id]);
   
        // Lấy tất cả kết quả dưới dạng mảng
        $product_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
        // Kiểm tra nếu có kết quả và trả về mảng product_id
        if ($product_ids) {
            return $product_ids;
        } else {
            return []; // Trả về mảng rỗng nếu không có kết quả
        }
    }


    // Lấy số lượng products hiện tại
    public function getQuantityPro($id) {
        try {
            // Câu lệnh SQL để lấy quantity theo product_id
            $sql = 'SELECT quantity FROM products WHERE id = :id';
           
            // Chuẩn bị câu lệnh SQL
            $stmt = $this->conn->prepare($sql);
           
            // Thực thi truy vấn với tham số id
            $stmt->execute([':id' => $id]);
           
            // Lấy kết quả (PDO::FETCH_ASSOC trả về mảng kết hợp)
            $result = $stmt->fetch();
           
            // Kiểm tra xem $result có phải là mảng hay không, nếu có trả về quantity
            if ($result && isset($result['quantity'])) {
                return $result['quantity']; // Trả về quantity của sản phẩm
            } else {
                return 0;  // Nếu không có sản phẩm, trả về 0
            }
           
        } catch (Exception $e) {
            // In lỗi nếu có exception
            echo 'Error: ' . $e->getMessage();
            return false;  // Trả về false nếu có lỗi
        }
    }

     //binh luan
     public function getAllComments() {
        try {
            $sql = 'SELECT comments.*, products.namesp AS product_name
                    FROM comments
                    LEFT JOIN products ON comments.idpro = products.id
                    ORDER BY comments.time DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
   




    public function getCommentsByProduct($idpro) {
        try {
            $sql = 'SELECT * FROM comments WHERE idpro = :idpro ORDER BY time DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['idpro' => $idpro]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }


   


    public function deleteComment($id) {
        try {
            $sql = 'DELETE FROM comments WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    public function getCommentById($id) {
        try {
            $sql = 'SELECT * FROM comments WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
   
    public function updateCommentStatus($id, $status) {
        try {
            $sql = 'UPDATE comments SET status = :status WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['status' => $status, 'id' => $id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
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
    public function updateAccoutAtive($id,$active){
        try {
            $sql = "UPDATE accounts SET active = :active WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['active'=>$active, 'id'=>$id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    public function updateAccoutRole($id,$role){
        try {
            $sql = "UPDATE accounts SET role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['role' => $role, 'id'=>$id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // Lấy sản phẩm theo id bảng bill_items
    function getQuantitiesByBillId($bill_id) {
        $sql = "SELECT product_id, quantity
                FROM bill_items
                WHERE bill_id = :bill_id";
       
        // Chuẩn bị truy vấn
        $stmt = $this->conn->prepare($sql);
       
        // Thực thi truy vấn với bill_id
        $stmt->execute([':bill_id' => $bill_id]);
       
        // Lấy tất cả các product_id và quantity theo bill_id
        $quantities = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        // Kiểm tra và trả về kết quả
        if ($quantities) {
            return $quantities;  // Trả về mảng chứa product_id và quantity của từng bản ghi
        } else {
            return [];  // Nếu không có kết quả, trả về mảng rỗng
        }
    }


    public function updateQuantityPro($product_id, $new_quantity) {
        try {
            // Câu lệnh SQL để cập nhật số lượng sản phẩm trong bảng products
            $sql = "UPDATE products SET quantity = :new_quantity WHERE id = :product_id";
   
            // Chuẩn bị truy vấn
            $stmt = $this->conn->prepare($sql);
   
            // Thực thi truy vấn với các tham số
            $stmt->execute([
                ':new_quantity' => $new_quantity,
                ':product_id' => $product_id
            ]);
   
            // Kiểm tra số dòng bị ảnh hưởng để biết việc cập nhật có thành công hay không
            if ($stmt->rowCount() > 0) {
                echo "Cập nhật thành công số lượng cho product_id = $product_id.\n";
            } else {
                echo "Không tìm thấy sản phẩm với product_id = $product_id để cập nhật.\n";
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
   
    // Lấy trạng thái đơn hàng
    public function getBillStatus($orderId) {
        $sql = "SELECT bill_status FROM bills WHERE id = :orderId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() ?: 0;
    }


    public function __destruct() {  // Hàm hủy kết nối đối tượng
        $this->conn = null;
    }



}