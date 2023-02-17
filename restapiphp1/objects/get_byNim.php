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
$mahasiswa = new mahasiswa($dbname);

//fungsi isset untuk mengecek apakah variabel sudah diatur atau belum
$mahasiswa->nim = isset($_GET['nim']) ? $_GET['nim'] : die();

//memanggil query get_mhs byNim di kelas mahasiswa
$mahasiswa->get_byNim();

if ($mahasiswa->nama != null) {
    //atur variabel yang akan menampung array data dari mahasiswa yang dipanggil nimnya
    $mhs_byNim = array(
        'nim' => $mahasiswa->nim, 'nama' => $mahasiswa->nama, 'jenis_kelamin' => $mahasiswa->jenis_kelamin, 
        'tempat_lahir' => $mahasiswa->tempat_lahir, 'tanggal_lahir' => $mahasiswa->tanggal_lahir, 
        'alamat' => $mahasiswa->alamat
    );

    $respone = array(
        /*jika semua kondisi terpenuhi maka akan menampilkan respon sukses 
        dan menampilkan data dari mahasiswa yg dipanggil nimnya*/
        'status' =>  array(
            'messsage' => 'Success', 'code' => (http_response_code(200))
        ), 'data' => $mhs_byNim
    );
} else {
    //jika tidak ada data yang ditemukan sesuai nim yang diinput maka akan menampilkan respon no data found
    http_response_code(404);
    $respone = array(
        'status' =>  array(
            'messsage' => 'No Data Found', 'code' => http_response_code()
        )
    );
}

//fungsi json_encode adalah untuk menyandikan nilai ke format JSON
// echo menampilkan nilai dari variabel $respone
echo json_encode($respone);