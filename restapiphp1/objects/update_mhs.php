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

// get nim 
$data = json_decode(file_get_contents("php://input"));

$mahasiswa->nim = $data->nim;

// set property mahasiswa yang ingin di update 
$mahasiswa->nama = $data->nama;
$mahasiswa->jenis_kelamin = $data->jenis_kelamin;
$mahasiswa->tempat_lahir = $data->tempat_lahir;
$mahasiswa->tanggal_lahir = $data->tanggal_lahir;
$mahasiswa->alamat = $data->alamat;

// memanggil query update_mhs di kelas mahasiswa
if ($mahasiswa->update_mhs()) {

    //format json yang dikirim ke client apabila update berhasil
    $respone = array(
        'messsage' => 'Update Success',
        'code' => http_response_code(200)

    );
} else {
    // set respone 400 'Bad Request' jika update gagal
    http_response_code(400);
    $respone = array(
        'messsage' => 'Update Failed',
        'code' => http_response_code()
    );
}

// menampilkan nilai dari variabel $respone
echo json_encode($respone);