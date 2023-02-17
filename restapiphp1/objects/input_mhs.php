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

// ambil data post
// file_get_contents untuk membaca seluruh file menjadi string
$data = json_decode(file_get_contents("php://input"));

// validasi data yg akan diinput
if(
    !empty($data->nim) && !empty($data->nama) && !empty($data->jenis_kelamin) && !empty($data->tempat_lahir) && !empty($data->tanggal_lahir) && !empty($data->alamat)
){
    // set properti mhs
    $mahasiswa->nim = $data->nim;
    $mahasiswa->nama = $data->nama;
    $mahasiswa->jenis_kelamin = $data->jenis_kelamin;
    $mahasiswa->tempat_lahir = $data->tempat_lahir;
    $mahasiswa->tanggal_lahir = $data->tanggal_lahir;
    $mahasiswa->alamat = $data->alamat;

    // memanggil query input_mhs di kelas mahasiswa
    if($mahasiswa->input_mhs()){
        //jika validasi data berhasil dan proses penginputan berhasil maka akan menampilkan respon sukses 
        $respone = array(
            'message'=>'input success',
            'code'=>http_response_code(200)
        );
    }else{
        // jika validasi data berhasil namun proses penginputan gagal maka akan menampilkan respon failed
        http_response_code(400);
        $respone = array(
            'message'=>'input failed',
            'code'=>http_response_code()
        );
    }
}else{
    // jika validasi data gagal maka akan menampilkan respon input failed wrong parameter
    http_response_code(400);
    $respone = array(
        'message'=>'input failed - wrong parameter',
        'code'=>http_response_code()
    );
}

// menampilkan nilai dari variabel $respone
echo json_encode($respone);