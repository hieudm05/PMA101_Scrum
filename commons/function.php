<?php

// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}
function uploadFile($file, $folderSave) {
    $file_upload = $file;
    $filename = uniqid() . '-' . basename($file_upload['name']); // Tạo tên file duy nhất
    $pathStorage = rtrim($folderSave, '/') . '/' . $filename; // Đường dẫn tương đối từ thư mục gốc
    $pathSave = PATH_ROOT . $pathStorage; // Kết hợp với PATH_ROOT để tạo đường dẫn tuyệt đối

    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists(dirname($pathSave))) {
        mkdir(dirname($pathSave), 0777, true);
    }

    // Di chuyển file
    if (move_uploaded_file($file_upload['tmp_name'], $pathSave)) {
        return $pathStorage; // Trả về đường dẫn tương đối để lưu vào DB
    }
    return null;
}

function deleteFile($file){
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); 
    }
}
