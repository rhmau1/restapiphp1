<?php 
class Mahasiswa{

    /*variabel public dapat diakses diluar kelas mahasiswa
    variabel private tidak dapat diakses diluar kelas mahasiswa */
    public $nim;
    public $nama;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;    

    private $kon;
    // nama tabel disamakan dengan nama tabel di database
    private $tabel = "tbl_mahasiswa";
    public $page;
    
    // __construct fungsi yang akan dijalankan pertama kali otomatis tanpa dipanggil lagi saat instansiasi
    public function __construct($dbname){
        $this->kon = $dbname;
    }

    /* nama function: function get
    function get untuk mendapatkan semua data mahasiswa yang ada di dalam tabel mahasiswa dan menampilkannya    
    route: localhost/restapiphp1/objects/get_mhs.php */
    function get_byPage(){
        if(isset($_GET['page']) && $_GET['page']>0){
            $page = $_GET['page'];
            $awalData = ($page - 1)*10;
            
            // mempersiapkan query yang akan dijalankan
        $query = "SELECT * FROM " . $this->tabel . " LIMIT $awalData,10";
        $stmt = $this->kon->prepare($query);

        // mengeksekusi variabel $stmt
        $stmt->execute();

        // mengembalikan nilai dari variabel $stmt 
        return $stmt;
        }else{
            $query1 = "SELECT * FROM " . $this->tabel . "";
            $result = $this->kon->prepare($query1);
            $result->execute();            
            return $result;
        }
        
    }
    
    
    function search_mhs(){
        if(isset($_GET['keyword'])){
            $keyword = $_GET['keyword'];
            
            // mempersiapkan query yang akan dijalankan
        $query = "SELECT * FROM " . $this->tabel . " WHERE nama LIKE '%$keyword%' OR 
        nim LIKE '%$keyword%' OR 
        tempat_lahir LIKE '%$keyword%' OR 
        tanggal_lahir LIKE '%$keyword%' OR 
        alamat LIKE '%$keyword%' ";
        $stmt = $this->kon->prepare($query);

        // mengeksekusi variabel $stmt
        $stmt->execute();

        // mengembalikan nilai dari variabel $stmt 
        return $stmt;
        }else{
           return "data yang dicari tidak ada";
        }
        
    }

    function sorting(){
        if(isset($_GET['kolom'])&&($_GET['order'])){
            $kolom = $_GET['kolom'];
            $order = $_GET['order'];

            $query ="SELECT * FROM " . $this->tabel . " ORDER BY $kolom $order";
            
            $stmt = $this->kon->prepare($query);

            $stmt->execute();
            
            return $stmt;
        }else{
            return "order tidak tersedia";

        }
        
    }    
    
    function get_mhs(){
        // mempersiapkan query yang akan dijalankan
        $query = "SELECT * FROM " . $this->tabel . "";
        $stmt = $this->kon->prepare($query);

        // mengeksekusi variabel $stmt
        $stmt->execute();

        // mengembalikan nilai dari variabel $stmt 
        return $stmt;
    }

    /*fungsi get mahasiswa by nim
    untuk mendapatkan dan menampilkan data mahasiswa yang ada dalam tabel mahasiswa sesuai nim yang diinputkan
    route: localhost/restapiphp1/objects/get_byNim.php*/
    function get_byNim()
    {
        // menyiapkan query yang akan dijalankan tetapi tanpa data, dimana bagian data diganti dengan tanda tanya
        $query = "SELECT * FROM " . $this->tabel . " m          
                WHERE
                    m.nim = ?
                LIMIT
                0,1";
        $stmt = $this->kon->prepare($query);

        // kita akan mengirimkan data yang sudah ditandai pada proses prepare
        $stmt->bindParam(1, $this->nim);
        
        //menjalankan statement yang sudah disiapkan
        $stmt->execute();

        // mengembalikan array yang diindeks dengan nama kolom
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // memasukkan nilai ke object      
        $this->nama = $row['nama'];
        $this->tempat_lahir = $row['tempat_lahir'];
        $this->jenis_kelamin = $row['jenis_kelamin'];
        $this->tanggal_lahir = $row['tanggal_lahir'];
        $this->alamat = $row['alamat'];
    }

    /*nama function input
    untuk menginputkan data mahasiswa ke dalam tabel mahasiswa dalam database
    route: localhost/restapiphp1/objects/input_mhs.php */ 
    function input_mhs(){

        //mempersiapkan query yang akan dijalankan
        $query = "INSERT INTO 
            " . $this->tabel . "
        SET 
            nim=:nim, nama=:nama, tempat_lahir=:tempat_lahir, jenis_kelamin=:jenis_kelamin, 
            tanggal_lahir=:tanggal_lahir, alamat=:alamat";

        $stmt = $this->kon->prepare($query);

        // kita akan mengirimkan data yang sudah ditandai pada proses prepare
        $stmt->bindParam('nim', $this->nim);
        $stmt->bindParam('nama', $this->nama);
        $stmt->bindParam('jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam('tempat_lahir', $this->tempat_lahir);
        $stmt->bindParam('tanggal_lahir', $this->tanggal_lahir);
        $stmt->bindParam('alamat', $this->alamat);

        //menjalankan statement
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /*nama function update
    untuk mengupdate data mahasiswa yang sudah ada di dalam tabel mahasiswa dalam database
    route: localhost/restapiphp1/objects/update_mhs.php */
        function update_mhs(){

        //mempersiapkan query yang akan dijalankan
        $query = "UPDATE " . $this->tabel . " SET nama=:nama, tempat_lahir=:tempat_lahir, 
        jenis_kelamin=:jenis_kelamin, tanggal_lahir=:tanggal_lahir, alamat=:alamat WHERE nim=:nim";
        $stmt = $this->kon->prepare($query);

        //mengirimkan data yang sudah ditandai pada proses prepare
        $stmt->bindParam('nim', $this->nim);
        $stmt->bindParam('nama', $this->nama);
        $stmt->bindParam('jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam('tempat_lahir', $this->tempat_lahir);
        $stmt->bindParam('tanggal_lahir', $this->tanggal_lahir);
        $stmt->bindParam('alamat', $this->alamat);
        
        //menjalankan statement yang sudah disiapkan
        if ($stmt->execute()){
            //apabila sudah sesuai akan mengembalikan nilai true
            return true;

        //sebaliknya apabila tidak sesuai akan mengembalikan nilai false
        }return false;
    }

    /*function delete 
    untuk menghapus data mahasiswa dari tabel mahasiswa dalam database
    route:localhost/restapiphp1/objects/delete_mhs.php*/ 
    function delete_mhs(){

        //mempersiapkan query yang akan dijalankan
        $query = "DELETE FROM ". $this->tabel . " WHERE nim = ?";
        $stmt = $this->kon->prepare($query);

        //mengirimkan data yang sudah ditandai pada proses prepare
        $stmt->bindParam(1, $this->nim);

        //menjalankan statement yang sudah disiapkan
        if($stmt->execute()){
            return true;
        }return false;
    }
}
