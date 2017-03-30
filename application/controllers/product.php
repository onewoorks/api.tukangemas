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

    public function main(array $getVars, array $params = null, $request = null) {
        print_r($request);
        $product = new Product_Model();
        $case = $params[URL_ARRAY + 1];
        $result = array();
        $ajax = false;
        $page = 'product';
        switch ($case):
            case 'statistik':
                $result = $this->Statistic();
                break;
            case 'plain-product':
                $ajax = true;
                echo '<pre>';
                print_r(Product_Controller::DulangTukangEmas(105, true));
                echo '</pre>';

                break;
            case 'curl-product':
                $ajax = true;
                $url = 'https://tukangemas.my/api/public/product/95';
                $cURL = curl_init();
                curl_setopt($cURL, CURLOPT_URL, $url);
                curl_setopt($cURL, CURLOPT_HTTPGET, true);
                curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

                $results = curl_exec($cURL);
                curl_close($cURL);
                $final = array();
                echo '<pre>';
                print_r(json_decode($results));
                echo '</pre>';
                if ($results != 'false'):
                    $r = json_decode($results);
                    foreach ($r as $k => $v):
                        $final[] = array();
                    endforeach;
                endif;
                echo '<pre>';
                print_r($final);
                echo '</pre>';
//                $result['result'] = $results;

                break;
            case 'check-in':
                $result = $this->CheckIn();
                break;
            case 'new-product':
                $category = str_replace('-', ' ', $params[URL_ARRAY + 2]);
                $vars = $this->CheckExtraParams(URL_ARRAY + 3, $params);
                $newProduct = $this->NewProduct($category, $vars);
                $page = $newProduct['page'];
                $result['category_name'] = ucwords($category);
                $result['result'] = $newProduct['data'];
                break;
            case 'sold-product':
                $category = str_replace('-', ' ', $params[URL_ARRAY + 2]);
                $vars = $this->CheckExtraParams(URL_ARRAY + 3, $params);
                $soldProduct = $this->SoldProduct($category, $vars);
                $page = $soldProduct['page'];
                $result['category_name'] = ucwords($category);
                $result['result'] = $soldProduct['data'];
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
            $dealer = $modalUpah + ($member > 0) ? 15 : 0;
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
    public static function DulangTukangEmas($dulangNo, $object = false) {
        $modelProduct = new Product_Model();
        return $modelProduct->ReadAllProductByDulangTukangEmas($dulangNo, $object);
    }

    public static function DulangEqual($dulangSankyu, $dulangTE, $berat = false) {
        $modelProduct = new Product_Model();
        $sankyuItem = $modelProduct->ReadAllProductByDulang($dulangSankyu, true, $berat);
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

    private function NewProduct($category, $vars = null) {
        $result = false;
        $varsCase = ($vars) ? $vars[0] : false;

        switch ($varsCase):
            case 'export':
                $result['page'] = 'product/new-product-export';
                $result['data'] = New_Product_Controller::Route($category, true);
                break;
            case 'export-clean':
                $result['page'] = 'product/new-product-export-clean';
                $result['data'] = New_Product_Controller::Route($category, true);
                break;
            default :
                $result['page'] = 'product/new-product';
                $result['data'] = New_Product_Controller::Route($category);
        endswitch;
        return $result;
    }
    
    private function SoldProduct($category, $vars = null) {
        $result = false;
        $varsCase = ($vars) ? $vars[0] : false;

        switch ($varsCase):
            case 'export':
                $result['page'] = 'product/sold-product-export';
                $result['data'] = New_Product_Controller::Route($category, true, 'sold_product');
                break;
            case 'export-clean':
                $result['page'] = 'product/sold-product-export-clean';
                $result['data'] = New_Product_Controller::Route($category, true, 'sold_product');
                break;
            default :
                $result['page'] = 'product/sold-product';
                $result['data'] = New_Product_Controller::Route($category, false, 'sold_product');
        endswitch;
        return $result;
    }

    private function CheckExtraParams($startArray, $paramList) {
        $vars = array();
        for ($i = $startArray; $i < count($paramList); $i++):
            $vars[] = $paramList[$i];
        endfor;
        return $vars;
    }
    
    private static function SoldProductItem($category){
        $productModel = new Product_Model();
        return $productModel->ReadStokTelahJual($category);
    }

}
