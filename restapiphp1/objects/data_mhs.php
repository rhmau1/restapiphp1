<?php 
//untuk mengakses fetch json beda domain, supaya dapat menerima permintaan dari domain lain
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include_once untuk menyisipkan file namun pemanggilannya hanya sekali
include_once '../config/database.php';
include_once '../objects/mahasiswa.php';

// instansiasi class database
$database = new Database();
$dbname = $database->koneksi();

// instansiasi class mahasiswa
$mahasiswa = new Mahasiswa($dbname);

// memanggil query get_mhs di kelas mahasiswa
$stmt = $mahasiswa->get_mhs();
$num = $stmt->rowCount();

$respone = [];
if ($num>0){ 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        //variabel untuk menampung array data seluruh mahasiswa yang ada dalam tabel
        $mhs_item[]=array(
            "nim"=>$nim, "nama"=>$nama, "jenis_kelamin"=>$jenis_kelamin, 
            "tempat_lahir"=>$tempat_lahir, "tanggal_lahir"=>$tanggal_lahir, "alamat"=>$alamat);            
    }

    // format json yang akan dikirim ke client
    $respone = array(
        /*jika semua kondisi terpenuhi maka akan menampilkan respon sukses 
        dan menampilkan data dari mahasiswa yg dipanggil nimnya*/
        'status'=>array(
            'message'=>'success', 'code'=>http_response_code(200)
        ), 'data'=> $mhs_item
    );
}else{
    //jika tidak ada data yang ditemukan maka akan menampilkan respon no data found
    http_response_code(404);
    $respone = array(
        'status'=>array(
            'message'=>'No data found', 'code'=>http_response_code()
        )
        );
}
// menampilkan nilai dari variabel $respone
echo json_encode($respone["data"]);