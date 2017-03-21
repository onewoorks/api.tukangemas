<?php

class Product_Model extends Common_Model {

    public $noSiri;

    public function __construct() {
        $this->db = new Mysql_Driver();
    }

    public function CreateProduct(array $productInfo) {
        $sql = "INSERT INTO oc_product "
                . " (model,sku,upc,ean,jan,isbn,mpn,location,quantity,stock_status_id,image,manufacturer_id,shipping,price,points,tax_class_id,date_available,weight,weight_class_id,length,width,height,length_class_id,subtract,minimum,sort_order,status,viewed,date_added,date_modified,user_id,ring_size)"
                . " VALUES "
                . " ("
                . "'" . $productInfo['model'] . "',"
                . "'" . $productInfo['sku'] . "',"
                . "'" . $productInfo['upc'] . "',"
                . "'" . $productInfo['ean'] . "',"
                . "'" . (int) $productInfo['jan'] . "',"
                . "'" . $productInfo['isbn'] . "',"
                . "'" . $productInfo['mpn'] . "',"
                . "'" . $productInfo['location'] . "',"
                . "'" . (int) $productInfo['quantity'] . "',"
                . "'" . (int) $productInfo['stock_status_id'] . "',"
                . "'" . $productInfo['image'] . "',"
                . "'" . (int) $productInfo['manufacturer_id'] . "',"
                . "'" . (int) $productInfo['shipping'] . "',"
                . "'" . (double) $productInfo['price'] . "',"
                . "'" . $productInfo['points'] . "',"
                . "'" . (int) $productInfo['tax_class_id'] . "',"
                . "'" . $productInfo['date_available'] . "',"
                . "'" . (float) $productInfo['weight'] . "',"
                . "'" . (int) $productInfo['weight_class_id'] . "',"
                . "'" . (float) $productInfo['length'] . "',"
                . "'" . (float) $productInfo['width'] . "',"
                . "'" . (float) $productInfo['height'] . "',"
                . "'" . (int) $productInfo['length_class_id'] . "',"
                . "'" . (int) $productInfo['substract'] . "',"
                . "'" . (int) $productInfo['minumum'] . "',"
                . "'" . (int) $productInfo['sort_order'] . "',"
                . "'" . (int) $productInfo['status'] . "',"
                . "'" . (int) $productInfo['viewed'] . "',"
                . "'" . $productInfo['date_added'] . "',"
                . "'" . $productInfo['date_modified'] . "',"
                . "'" . (int) $productInfo['user_id'] . "',"
                . "'" . (float) $productInfo['ring_size'] . "'"
                . ")";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        return $this->db->getLastId();
    }

    public function CreateProductDescription(array $productDescription) {
        $sql = "INSERT INTO oc_product_description"
                . " (product_id,language_id,name,description,tag,meta_title,meta_description,meta_keyword) VALUES "
                . " ("
                . "'" . (int) $productDescription['product_id'] . "',"
                . "'" . (int) $productDescription['languange_id'] . "', "
                . "'" . $productDescription['name'] . "', "
                . "'" . $productDescription['description'] . "', "
                . "'', "
                . "'" . $productDescription['meta_title'] . "', "
                . "'" . $productDescription['meta_description'] . "', "
                . "''"
                . ")";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
    }

    public function CreateProductToCategory(array $product) {
        $sql = "INSERT INTO oc_product_to_category (product_id,category_id) VALUES ('" . (int) $product['product_id'] . "','" . (int) $product['category_id'] . "')";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
    }

    public function CreateProductToStore($productId, $storeId) {
        $sql = "INSERT INTO oc_product_to_store (product_id,store_id) VALUES ('" . (int) $productId . "', '" . (int) $storeId . "')";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
    }

    public function CreateProductToLayout($productId, $storeId, $layoutId) {
        $sql = "INSERT INTO oc_product_to_layout (product_id,store_id,layout_id) VALUES ('" . (int) $productId . "', '" . (int) $storeId . "', '" . (int) $layoutId . "')";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
    }

    public function ReadStokByNoSiri() {
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
                . "remarks,"
                . "kod_Kategori_Produk,"
                . "Berat,"
                . "kod_purity,"
                . "Harga_item,"
                . "Supplier_ID,"
                . "kategori_Produk, "
                . "kategori_produk_ID, "
                . "Dulang, "
                . "no_siri_Produk FROM data_database WHERE statusItem=10 AND no_Siri_Produk='$this->noSiri'";
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

    public function ReadCountRantaiTE(array $dulangRantai) {
        $sql = "SELECT "
                . " count(product_id) as jumlah"
                . " FROM oc_product_to_category"
                . " WHERE category_id IN (" . implode(',', $dulangRantai) . ")";
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

    public function ReadRantaiTE(array $dulangRantai) {
        $sql = "SELECT CONCAT(p.isbn,p.model) AS no_siri"
                . " FROM oc_product_to_category c "
                . " LEFT JOIN oc_product p ON p.product_id=c.product_id"
                . " WHERE c.category_id IN (" . implode(',', $dulangRantai) . ")"
                . " AND p.status=1";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return ($result) ? $result : array();
    }

    public function ReadProductByKategoriTE($kategoriId) {
        $sql = "SELECT CONCAT(p.isbn,p.model) AS no_siri"
                . " FROM oc_product_to_category c "
                . " LEFT JOIN oc_product p ON p.product_id=c.product_id"
                . " WHERE c.category_id = '$kategoriId' "
                . " AND p.status=1";
//        echo $sql.'  ';
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result;
    }

    public function ReadStatisticProductTE($kategoryId) {
        $sql = "SELECT count(product_id) as jumlah FROM oc_product_to_category WHERE category_id='$kategoryId'";
        $this->db->connect();
        $this->db->prepare($sql);
        $this->db->queryexecute();
        $result = $this->db->fetchOut('array');
        return $result[0];
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
                . "remarks,"
                . "kod_Kategori_Produk,"
                . "Berat,"
                . "Harga_item,"
                . "Supplier_ID,"
                . "kategori_Produk, "
                . "kategori_produk_ID, "
                . "Dulang, "
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
