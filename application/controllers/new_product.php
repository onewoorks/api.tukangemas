<?php

class New_Product_Controller {

    public static function Route($category) {
        $detail = self::SubCategory($category);
        return $detail;
    }

    private static function SubCategory($category) {
        $commonModel = new Common_Model();
        $categoryDetail = $commonModel->basicCategory;
        $detail = array();
        foreach ($categoryDetail as $cat):
            foreach ($cat as $key => $c):
                $detail[$key] = $c;
            endforeach;
        endforeach;
        return self::DulangCompare($detail[$category]['sankyu'], $detail[$category]['tukangemas']);
    }

    private static function DulangCompare($sk, $te, $option = 'new_product') {
        $sankyu = array();
        $tukangemas = array();
        $listSankyu = Product_Controller::DulangSankyu($sk, false, true);
        $listTE = Product_Controller::DulangTukangEmas($te, true);
        foreach ($listSankyu as $ls):
            $sankyu[] = $ls['no_siri_Produk'];
        endforeach;
        foreach ($listTE as $ls):
            $tukangemas[] = $ls['no_tag'];
        endforeach;
        $listNoSiri = Product_Controller::CompareDulangResult($sankyu, $tukangemas);
        $product = new Product_Model();
        $productController = new Product_Controller();
        $listProduct = array();
        foreach ($listNoSiri[$option] as $nosiri):
            $product->noSiri = $nosiri;
            $productInfo = $product->ReadStokByNoSiri();
            $upah = $productController->UpahBarangEmas($productInfo['Upah'], $productInfo['Upah_Jualan']);
            $listProduct[] = array(
                'no_siri_Produk' => $productInfo['no_siri_Produk'],
                'berat' => Format::Weight($productInfo['Berat']),
                'upah_modal' => Format::Currency($upah['modal']),
                'upah_normal' => Format::Currency($upah['normal']),
                'upah_member' => Format::Currency($upah['member']),
                'upah_dealer' => Format::Currency($upah['dealer'])
            );
        endforeach;
        return $listProduct;
    }

}
