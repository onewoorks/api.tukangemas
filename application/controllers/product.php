<?php

class Product_Controller extends Common_Controller {

    public $par = URL_ARRAY;

    public function __construct() {
        
    }

    private $rantaiKaki = array(4);
    private $rantaiLeher = array(8);
    private $rantaiPadu = array(9);
    private $rantaiTanganKosong = array(10);
    private $rantaiTanganFesyen = array(11, 12, 13, 14);
    private $productToSync = array();
    private $newProduct = array();

    public function main(array $getVars, array $params = null) {
        $product = new Product_Model();
        $case = $params[URL_ARRAY + 1];
        $result = array();
        $ajax = false;
        $page = 'product';
        switch ($case):
            case 'testsql':
                $productInfo = $product->ReadStokAda();
                $this->InsertStatement($productInfo);
                break;
            case 'statistik':
                $result = $this->Statistic();
                break;

            case 'check-in':
                $result = $this->CheckIn();
                break;
            case 'new-product':
                $category = str_replace('-',' ',$params[URL_ARRAY+2]);
                $page = 'new-product';
                $result['category_name'] = ucwords($category);
                $result['result'] = New_Product_Controller::Route($category);
                break;
            case 'sync':
                $ajax = true;
                $this->SyncProcessing();
                break;
            default:
                $result['stocks'] = $product->ReadStokAda();
                break;
        endswitch;

        if (!$ajax):
            $result['header'] = $this->RenderOutput('main');
            $result['footer'] = $this->RenderOutput('footer');
            $view = new View_Model($page);
            $view->assign('content', $result);
        endif;
    }

    private function RantaiDulangMapping(array $dulangArray) {
        $mapped = array();
        $categoryId = 61;
        foreach ($dulangArray as $dulang):
            $category = $this->DefineMainCategory($categoryId, $dulang);
            $mapped[] = $category['sub_category'];
        endforeach;
        return $mapped;
    }

    private function StatementSummary(array $categoryArray) {
        $product = new Product_Model();
        $result = array(
            'sankyu' => $product->ReadCountRantai($categoryArray),
            'sankyu_product' => $product->ReadProductByDulang($categoryArray),
            'te_online' => $product->ReadCountRantaiTE($this->RantaiDulangMapping($categoryArray)),
            'te_online_product' => $product->ReadRantaiTE($this->RantaiDulangMapping($categoryArray)));
        return $result;
    }

    private function CountStatementRantai($rantai) {
        $result = array();
        switch ($rantai):
            case 'rantai kaki':
                $result = $this->StatementSummary($this->rantaiKaki);
                break;
            case 'rantai leher':
                $result = $this->StatementSummary($this->rantaiLeher);
                break;
            case 'rantai padu':
                $result = $this->StatementSummary($this->rantaiPadu);
                break;
            case 'rantai tangan kosong':
                $result = $this->StatementSummary($this->rantaiTanganKosong);
                break;
            case'rantai tangan fesyen':
                $result = $this->StatementSummary($this->rantaiTanganFesyen);
                break;
        endswitch;
        return $result;
    }

    private function Statistic($backCall = false) {
        $product = new Product_Model();
        $categories = $product->SenaraiKategori();
        $info = array();
        $done = false;
        $definedCategory = '';
        foreach ($categories as $category => $values):
            foreach ($values as $v => $categoryName):
                if ($v == 32 or $v == 33 or $v == 34):
                    if (!$done):
                        $info['rantai'] = $this->ReadCountKategoryRantai($values);
                        $done = true;
                    endif;
                else:
                    if ($definedCategory != $category):
                        $definedCategory = $category;
                        $info[$category] = $this->ReadCountKategory($values);
                    endif;
                endif;
            endforeach;
        endforeach;
        $result['result'] = $info;
        if ($backCall):
            $this->CleanProductToSync();
        endif;
        return $result;
    }

    private function CheckIn() {
        $product = new Product_Model();
        $categories = $product->SenaraiKategori();
        $result['result'] = $categories;
        return $result;
    }

    private function ReadCountKategory($indexKategori) {
        $product = new Product_Model();
        $info = array();
        foreach ($indexKategori as $kategori => $categoryName):
            $teSubCategory = $this->DefineMainCategory($kategori);
            $information = $this->CompareProduct($kategori);
            $info[$categoryName] = array(
                'sankyu' => $product->ReadStatistikProduct($kategori),
                'te_online' => $product->ReadStatisticProductTE($teSubCategory['sub_category']),
                'information' => array(
                    'equal' => $information['equal'],
                    'new' => $information['new'],
                    'sold' => $information['sold']
                )
            );
            array_push($this->newProduct, $information['new_product']);
        endforeach;
        return $info;
    }

    private function ReadCountKategoryRantai($indexKategori) {
        $info = array();
        foreach ($this->subKategoriRantai as $rantai):
            $countRantai = $this->CountStatementRantai($rantai);
//            echo '<pre>';
//            print_r($countRantai['te_online_product']);
//            echo '</pre>';
            $information = $this->ComparePreparedList($countRantai['sankyu_product'], $countRantai['te_online_product']);
            $new = array();
            $equal = array();
            $sold = array();

            foreach ($information['new_product'] as $n):
                $new[] = $n['no_siri'];
            endforeach;

            foreach ($information['equal_product'] as $e):
                $equal[] = $e['no_siri'];
            endforeach;

            foreach ($information['sold_product'] as $s):
                $sold[] = $s['no_siri'];
            endforeach;

            $keyName = strtolower(str_replace(' ', '_', $rantai));
            $info[$keyName] = array(
                'sankyu' => array('kategori' => strtoupper($rantai), 'jumlah' => $countRantai['sankyu']),
                'te_online' => array('kategori' => strtoupper($rantai), 'jumlah' => $countRantai['te_online']),
                'information' => array(
                    'equal' => $information['equal'],
                    'new' => $information['new'],
                    'sold' => $information['sold']
                ),
                'new_product' => $new,
            );
            array_push($this->newProduct, $new);
        endforeach;
        return $info;
    }

    private function InsertStatement(array $products) {
        $productModel = new Product_Model();

        foreach ($products as $productInfo):
            $category = $this->DefineMainCategory($productInfo['kategori_produk_ID']);
            $product = array(
                'model' => $this->NoSiriBaru($productInfo['kod_kategori_Produk'], $productInfo['no_siri_Produk']),
                'sku' => $productInfo['Dulang'],
                'ean' => (!$productInfo['Berat'] == '') ? json_encode($this->UpahBarangEmas($productInfo['Upah'], $productInfo['Upah_Jualan'])) : 0,
                'isbn' => $productInfo['kod_Kategori_Produk'],
                'jan' => ($productInfo['Berat'] > 0) ? 1 : 0,
                'mpn' => $category['main_category'],
                'location' => $productInfo['kod_purity'],
                'quantity' => 1,
                'stock_status_id' => 5,
                'image' => 'catalog/product_online/' . $productInfo['no_siri_Produk'] . '.jpg',
                'manufacturer_id' => $productInfo['Supplier_ID'],
                'shipping' => 1,
                'price' => ($productInfo['Berat'] > 0) ? 0 : $productInfo['Harga_item'],
                'points' => 0,
                'tax_class_id' => 0,
                'date_available' => date('Y-m-d'),
                'weight' => $productInfo['Berat'],
                'weight_class_id' => 2,
                'length' => $productInfo['dimension_Panjang'],
                'width' => $productInfo['dimension_Panjang'],
                'height' => $productInfo['dimension_Panjang'],
                'length_class_id' => 2,
                'substract' => 1,
                'minimum' => 1,
                'sort_order' => 1,
                'status' => 1,
                'viewed' => 0,
                'date_added' => date('Y-m-d h:i:s'),
                'date_modified' => date('Y-m-d h:i:s'),
                'user_id' => 1,
                'ring_size' => $productInfo['dimension_Panjang']
            );
            $productId = $productModel->CreateProduct($product);
            $this->InsertProductDescription($productId, $productInfo);
            $this->InsertProductToCategory($productId, $this->DefineMainCategory($productInfo['kategori_produk_ID'], $productInfo['Dulang']));
            $this->InsertProductToStore($productId, 0);
            $this->InsertProductToLayout($productId, 0, 0);
        endforeach;
    }

    private function InsertProductToStore($productId, $storeId) {
        $productModel = new Product_Model();
        $productModel->CreateProductToStore($productId, $storeId);
        return true;
    }

    private function InsertProductToLayout($productId, $storeId, $layoutId) {
        $productModel = new Product_Model();
        $productModel->CreateProductToLayout($productId, $storeId, $layoutId);
        return true;
    }

    private function InsertProductDescription($productId, array $productInfo) {
        $productModel = new Product_Model();
        for ($i = 0; $i < 2; $i++):
            $productDescription = array(
                'product_id' => $productId,
                'languange_id' => ($i + 1),
                'name' => ($productInfo['remarks'] == '') ? $productInfo['kategori_Produk'] : $productInfo['remarks'],
                'description' => ($productInfo['remarks'] == '') ? $productInfo['kategori_Produk'] : $productInfo['remarks'],
                'meta_title' => ($productInfo['remarks'] == '') ? $productInfo['kategori_Produk'] : $productInfo['remarks'],
                'meta_description' => ($productInfo['remarks'] == '') ? $productInfo['kategori_Produk'] : $productInfo['remarks'],
            );
            $productModel->CreateProductDescription($productDescription);
        endfor;
        return true;
    }

    private function InsertProductToCategory($productId, $categoryId) {
        $productModel = new Product_Model();
        $product = array('product_id' => $productId, 'category_id' => $categoryId['sub_category']);
        $productModel->CreateProductToCategory($product);
        return true;
    }

    private function ComparePreparedList($listSankyu, $listTE) {
        $sankyu = array();
        $teOnline = array();

        foreach ($listSankyu as $s):
            $sankyu[] = $s['no_siri'];
        endforeach;

        foreach ($listTE as $t):
            $teOnline[] = $t['no_siri'];
        endforeach;
        return $this->CompareResult($listSankyu, $listTE);
    }

    private function CompareProduct($kategoriId) {
        $product = new Product_Model();
        $productSankyu = $product->ReadProductByKategori($kategoriId);
        $sankyu = array();
        foreach ($productSankyu as $ps):
            $sankyu[] = $ps['no_siri'];
        endforeach;

        $teSubCategory = $this->DefineMainCategory($kategoriId);
        $productTE = $product->ReadProductByKategoriTE($teSubCategory['sub_category']);
        $teOnline = array();
        foreach ($productTE as $pte):
            $teOnline[] = $pte['no_siri'];
        endforeach;

        return $this->CompareResult($sankyu, $teOnline);
    }

    private function CompareResult(array $listSankyu, array $listTE) {
        $new = array_diff($listSankyu, $listTE);
        $same = array_intersect($listSankyu, $listTE);
        $sold = array_diff($listTE, $listSankyu);
        $result['new'] = count($new);
        $result['equal'] = count($same);
        $result['sold'] = count($sold);

        $result['new_product'] = $new;
        $result['equal_product'] = $same;
        $result['sold_product'] = $sold;

        return $result;
    }

    static function CompareDulangResult(array $listSankyu, array $listTE) {
        $new = array_diff($listSankyu, $listTE);
        $same = array_intersect($listSankyu, $listTE);
        $sold = array_diff($listTE, $listSankyu);
        $result['new'] = count($new);
        $result['equal'] = count($same);
        $result['sold'] = count($sold);

        $result['new_product'] = $new;
        $result['equal_product'] = $same;
        $result['sold_product'] = $sold;

        return $result;
    }

    private function ProductToSyncronize(array $products) {
        $new = array();
        $equal = array();
        $sold = array();

        foreach ($products as $product):
            $new[] = $product['new'];
            $equal[] = $product['equal'];
            $sold[] = $product['sold'];
        endforeach;

        $productArray = array('new' => $new, 'equal' => $equal, 'sold' => $sold);
        $allProducts = array_push($this->productToSync, $productArray);
        $this->productToSync[] = $products;
        return $products;
    }

    private function CleanProductToSync() {
        $products = $this->newProduct;
        $new = array();
        $uniqueSiri = array();
        foreach ($products as $product):
            foreach ($product as $p):
                $new[] = $p;
            endforeach;
        endforeach;
        $uniqueSiri['new'] = array_unique($new);
        return $uniqueSiri;
    }

    public function UpahBarangEmas($modalUpah, $upahJualan) {
        $normal = $upahJualan;

        if ($modalUpah >= 0 and $modalUpah <= 30):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 15;
        elseif ($modalUpah > 30 and $modalUpah <= 60):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 25;
        elseif ($modalUpah > 60 and $modalUpah <= 80):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 30;
        elseif ($modalUpah > 80 and $modalUpah <= 100):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 40;
        elseif ($modalUpah > 100 and $modalUpah <= 150):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 45;
        elseif ($modalUpah > 150 and $modalUpah <= 300):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 50;
        elseif ($modalUpah > 300):
            $member = $upahJualan - (($upahJualan * 25) / 100);
            $dealer = $modalUpah + 70;
        endif;

        $upah = array(
            "normal" => $normal,
            "member" => $member,
            "dealer" => $dealer,
            "modal" => $modalUpah
        );
        return $upah;
    }

    private function SyncProcessing() {
        $this->Statistic(true);
        $product = $this->CleanProductToSync();
        $newProduct = $product['new'];
        $insertStatement = array();
        $productModel = new Product_Model();

        foreach ($newProduct as $new):
            $productModel->noSiri = $new;
            $sankyuProduct = $productModel->ReadStokByNoSiri();
            $this->InsertStatement($sankyuProduct);
//            print_r($sankyuProduct);
        endforeach;
    }

    public static function DulangSankyu($dulangNo, $berat = false, $object = false) {
        $modelProduct = new Product_Model();
        if ($berat):
            $execute = $modelProduct->ReadAllProductByDulang($dulangNo, $object, true);
        else:
            $execute = $modelProduct->ReadAllProductByDulang($dulangNo, $object);
        endif;
        return $execute;
    }

    /**
     * 
     * @param type $dulangNo
     * @return type
     */
    public static function DulangTukangEmas($dulangNo,$object = false) {
        $modelProduct = new Product_Model();
        return $modelProduct->ReadAllProductByDulangTukangEmas($dulangNo, $object);
    }

    public static function DulangEqual($dulangSankyu, $dulangTE, $berat = false) {
        $modelProduct = new Product_Model();
        $sankyuItem = $modelProduct->ReadAllProductByDulang($dulangSankyu,true,$berat);
        $sankyu = array();
        foreach ($sankyuItem as $si):
            $sankyu[] = $si['no_siri_Produk'];
        endforeach;
        $tukangemas = array();
        $tukangemasItem = $modelProduct->ReadAllProductByDulangTukangEmas($dulangTE);
        foreach ($tukangemasItem as $ti):
            $tukangemas[] = $ti['no_tag'];
        endforeach;

        $result = self::CompareDulangResult($sankyu, $tukangemas);
        return $result;
    }

}
