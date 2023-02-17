<?php 
//untuk mengakses fetch json beda domain, supaya dapat menerima permintaan dari domain lain
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include_once untuk menyisipkan file namun pemanggilannya hanya sekali
include_once '../config/database.php';
include_once '../objects/mahasiswa.php';

// instansiasi class database
$database  = new Database();
$dbname = $database->koneksi();

// instansiasi class mahasiswa
$mahasiswa = new Mahasiswa($dbname); 

/* ambil input post nim dan hasil input disimpan di variabel $data
Fungsi json_decode() digunakan untuk mendekode atau mengubah objek JSON menjadi objek PHP */
$data = json_decode(file_get_contents("php://input"));

$mahasiswa->nim = $data->nim;

// memanggil query delete_mhs di kelas mahasiswa
if($mahasiswa->delete_mhs()){
    //jika proses menghapus data berhasil akan menampilkan respon success
    $respone = array(
        'message'=> 'delete success',
        'code'=>http_response_code(200)
    );
}else{
    //jika proses menghapus data gagal akan menampilkan respon failed
    http_response_code(400);
    // variabel $respone untuk menyimpan respon 
    $respone = array(
        'message'=>'delete failed',
        'code'=>http_response_code()
    );
}

//fungsi json_encode adalah untuk convert array ke format JSON
//echo  menampilkan nilai dari variabel $respone
echo json_encode($respone);