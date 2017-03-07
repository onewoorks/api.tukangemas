<?php

class Common_Controller {

    private $parentCategory = array(
        'biasa' => 59,
        'rantai' => 61,
        'gelang' => 65,
        'cincin' => 70
    );
    public $subKategoriRantai = array('rantai kaki', 'rantai leher', 'rantai padu', 'rantai tangan kosong', 'rantai tangan fesyen');

    public function NoSiriBaru($prefix, $noSiri) {
        $prefixLen = strlen($prefix);
        $result = substr($noSiri, $prefixLen);
        return $result;
    }

    public function DefineMainCategory($categoryId, $dulang = null) {
        $teMap = false;
        $subCategory = false;
        $categoryName = false;

        // rantai
        if ($dulang):
            $dulangInfo = $this->KategoriRantai($dulang);
            $teMap = 61;
            $categoryName = $dulangInfo['category_name'];
            $subCategory = $dulangInfo['sub_category'];
        endif;
        switch ($categoryId):
            // biasa
            case 35:
                $categoryName = 'subang_emas';
                $subCategory = 98;
                $teMap = 59;
                break;
            case 36:
                $categoryName = 'loket_emas';
                $subCategory = 105;
                $teMap = 59;
                break;

            //cincin
            case 27:
                $categoryName = 'cincin_perak';
                $subCategory = 74;
                $teMap = 70;
                break;
            case 28:
                $categoryName = 'cincin_emas';
                $subCategory = 80;
                $teMap = 70;
                break;
            case 29:
                $categoryName = 'cincin_emas_permata_916';
                $subCategory = 107;
                $teMap = 70;
                break;
            case 30:
                $categoryName = 'cincin_emas_permata_750';
                $subCategory = 108;
                $teMap = 70;
                break;
            case 31:
                $categoryName = 'cincin_emas_permata_585';
                $subCategory = 109;
                $teMap = 70;
                break;
            //gelang
            case 37:
                $categoryName = 'gelang_emas';
                $subCategory = 99;
                $teMap = 65;
                break;
        endswitch;
        return array('main_category' => $teMap, 'sub_category' => $subCategory, 'category_name' => $categoryName);
    }

    private function KategoriRantai($dulangId) {
        $categoryName = false;
        $subCategory = false;
        switch ($dulangId):
            // rantai fesyen
            case 11:
            case 12:
            case 13:
            case 14:
                $categoryName = 'rantai_fesyen';
                $subCategory = 103;
                break;
            // rantai kaki
            case 4:
                $categoryName = 'rantai_kaki';
                $subCategory = 97;
                break;
            // rantai leher
            case 8:
                $categoryName = 'rantai_leher';
                $subCategory = 100;
                break;
            // rantai padu
            case 9:
                $categoryName = 'rantai_padu';
                $subCategory = 101;
                break;
            // rantai tangan kosong
            case 10:
                $categoryName = 'rantai_tangan_kosong';
                $subCategory = 102;
                break;
        endswitch;
        return array('category_name' => $categoryName, 'sub_category' => $subCategory);
    }

    public function ProductSyncrohization() {
        
    }

    public function RenderOutput($file, $vars = null) {
        if (is_array($vars) && !empty($vars)) {
            extract($vars);
        }
        ob_start();
        include VIEW . '/' . $file . '.php';
        return ob_get_clean();
    }

}
