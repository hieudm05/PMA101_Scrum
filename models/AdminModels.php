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


   
}