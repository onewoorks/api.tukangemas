<?php

class Product_Model extends Common_Model {

    public $noSiri;

    public function __construct() {
        $this->db = new Mysql_Driver();
    }

    public function ReadStokByNoSiri($statusItem = 10) {
        $sql = "SELECT "
                . "kod_kategori_Produk, "
                . "no_siri_Produk, "
                . "Dulang,"
                . "Upah,"
                . "Upah_Jualan,"
                . "kategori_produk_ID,"
                . "kod_Purity,"
                . "dimension_Panjang,"
                . "dimension_Lebar,"
                . "dimension_Dia,"
                . "dimension_Saiz,"
                . "remarks,"
                . "kod_Kategori_Produk,"
                . "Berat,"
                . "kod_purity,"
                . "Harga_item,"
                . "Supplier_ID,"
                . "kategori_Produk, "
                . "kategori_produk_ID, code_Supplier, receiving_Status, "
                . "Dulang, "
                . "no_siri_Produk FROM data_database WHERE statusItem='$statusItem' AND no_Siri_Produk='$this->noSiri'";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return ($result) ? $result[0] : false;
    }
    
    public function ReadStokByNoSiriPlain() {
        $sql = "SELECT "
                . "kod_kategori_Produk, "
                . "no_siri_Produk, "
                . "Dulang,"
                . "Upah,"
                . "Upah_Jualan,"
                . "kategori_produk_ID,"
                . "kod_Purity,"
                . "dimension_Panjang,"
                . "dimension_Lebar,"
                . "dimension_Dia,"
                . "dimension_Saiz,"
                . "remarks,"
                . "kod_Kategori_Produk,"
                . "Berat,"
                . "kod_purity,"
                . "Harga_item,"
                . "Supplier_ID,"
                . "kategori_Produk, "
                . "kategori_produk_ID, "
                . "Dulang, , code_Supplier, receiving_Status, "
                . "no_siri_Produk FROM data_database WHERE no_Siri_Produk='$this->noSiri'";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return ($result) ? $result[0] : false;
    }

    public function ReadStatistikProduct($kategoryId) {
        $sql = "SELECT kategori_Produk as kategori, "
                . " count(id) as jumlah"
                . " FROM data_database"
                . " WHERE statusItem=$this->statusItemDalamStok"
                . " AND kategori_produk_ID = $kategoryId LIMIT 1";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result[0];
    }

    public function ReadCountRantai(array $dulangRantai) {
        $sql = "SELECT "
                . " count(id) as jumlah"
                . " FROM data_database"
                . " WHERE statusItem=$this->statusItemDalamStok"
                . " AND Dulang IN (" . implode(',', $dulangRantai) . ")";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result[0]['jumlah'];
    }


    public function ReadProductByKategori($kategoriId) {
        $sql = "SELECT no_siri_Produk as no_siri"
                . " FROM data_database "
                . " WHERE kategori_produk_ID = '$kategoriId' "
                . " AND statusItem=$this->statusItemDalamStok";
//        echo $sql.'  ';
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result;
    }

    public function ReadProductByDulang(array $dulangList) {
        $sql = "SELECT no_siri_Produk as no_siri"
                . " FROM data_database "
                . " WHERE Dulang IN (" . implode(',', $dulangList) . ") "
                . " AND statusItem=$this->statusItemDalamStok";
//        echo $sql.'  ';
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result;
    }


    public function ReadAllProduct() {
        $sql = "SELECT no_siri_Produk FROM data_database";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return $this->db->fetchOut();
    }

    public function ReadAllProductByDulang($dulangNo, $object = true, $berat = false) {
        $whereDulang = (is_array($dulangNo)) ? ' IN (' . implode(',', $dulangNo) . ')' : ' = ' . $dulangNo;
        if ($dulangNo == 3):
            $whereBerat = ($berat) ? ' AND Berat IS NOT NULL ' : 'AND Berat IS NULL ';
        else:
            $whereBerat = '';
        endif;
        $sql = "SELECT no_siri_Produk, Berat FROM data_database WHERE Dulang $whereDulang AND statusItem=10";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return ($object) ? $this->db->fetchOut('array') : count($this->db->fetchOut());
    }

    public function ReadAllProductByDulangTukangEmas($dulangNo, $object = true) {
        $url = 'https://tukangemas.my/api/public/product/' . $dulangNo;
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

        $results = curl_exec($cURL);
        curl_close($cURL);
        $final = array();
        if ($results != 'false'):
            $r = json_decode($results);
            foreach ($r as $k => $v):
                if ($v->category == $dulangNo):
                    $final[] = array('no_tag' => $v->isbn . $v->model);
                endif;
            endforeach;
        endif;
        return ($object) ? $final : count($final);
    }

    public function ReadStokTelahJual($category) {
        $sql = "SELECT no_siri_Produk FROM data_database WHERE statusItem=11 and Dulang='" . (int) $category . "'";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return $this->db->fetchOut();
    }

    public function ReadStokAda() {
        $sql = "SELECT "
                . "kod_kategori_Produk, "
                . "no_siri_Produk, "
                . "Dulang,"
                . "Upah,"
                . "Upah_Jualan,"
                . "kategori_produk_ID,"
                . "kod_Purity,"
                . "dimension_Panjang,"
                . "dimension_Saiz,"
                . "remarks,"
                . "kod_Kategori_Produk,"
                . "Berat,"
                . "Harga_item,"
                . "Supplier_ID,"
                . "kategori_Produk, "
                . "kategori_produk_ID, "
                . "Dulang, "
                . "code_Supplier, "
                . "receiving_Status, "
                . "no_siri_Produk FROM data_database WHERE statusItem=10";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return $this->db->fetchOut('array');
    }

    public function ReadStokAdaDetail() {
        $where = $this->WhereKategoryTerpilih();
        $sql = "SELECT "
                . " no_siri_Produk as code_no,"
                . " purity_id AS purity,"
                . " kategori_produk_id AS category,"
                . " dimension_Panjang AS ukuran,"
                . " remarks AS product_name"
                . " FROM data_database WHERE $where AND statusItem=10 LIMIT 10";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return $this->db->fetchOut('array');
    }

}
