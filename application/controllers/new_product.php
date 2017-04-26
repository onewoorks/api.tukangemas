<?php

class New_Product_Controller {

    public static function Route($category, $detailInfo = false, $option = 'new_product') {
        $detail = self::SubCategory($category, $detailInfo, $option);
        return $detail;
    }

    private static function SubCategory($category, $detailInfo, $option) {
        $commonModel = new Common_Model();
        $categoryDetail = $commonModel->basicCategory;
        $detail = array();
        foreach ($categoryDetail as $cat):
            foreach ($cat as $key => $c):
                $detail[$key] = $c;
            endforeach;
        endforeach;
        return self::DulangCompare($detail[$category]['sankyu'], $detail[$category]['tukangemas'], $detailInfo, $option);
    }

    private static function DulangCompare($sk, $te, $detailInfo = false, $option= 'new_product') {
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
            switch($option):
                case 'sold_product':
//                    $productInfo = $product->ReadStokByNoSiriPlain();
                      $listProduct[] = $product->noSiri;
                    break;
                default:
                    $productInfo = $product->ReadStokByNoSiri();
                    break;
            endswitch;
            
            if($option!='sold_product'):
            $upah = $productController->UpahBarangEmas($productInfo['Upah'], $productInfo['Upah_Jualan']);
            $productInfo['upah'] = $upah;
            
            if (!$detailInfo):
                $listProduct[] = array(
                    'no_siri_Produk' => $productInfo['no_siri_Produk'],
                    'berat' => Format::Weight($productInfo['Berat']),
                    'upah_modal' => Format::Currency($upah['modal']),
                    'upah_normal' => Format::Currency($upah['normal']),
                    'upah_member' => Format::Currency($upah['member']),
                    'upah_dealer' => Format::Currency($upah['dealer']),
                    'harga' => ($productInfo['receiving_Status'] == 0 ) ? Format::Currency($productInfo['Harga_item']) : Format::Currency($productInfo['code_Supplier'])
                );
            else:
                $productInfo['category'] = $te;
                $listProduct[] = self::DetailProductInfo($productInfo);
            endif;
            endif;
        endforeach;
        return $listProduct;
    }
    
    private static function DetailProductInfo(array $productInfo){
        $result = array(
                    'model' => $productInfo['no_siri_Produk'],
                    'sku' => $productInfo['Dulang'],
                    'ean' => (!$productInfo['Berat'] == '') ? json_encode($productInfo['upah']) : 0,
                    'isbn' => $productInfo['kod_Kategori_Produk'],
                    'jan' => ($productInfo['receiving_Status'] == 0) ? 1 : 0,
                    'mpn' => $productInfo['category'],
                    'location' => $productInfo['kod_purity'],
                    'quantity' => 1,
                    'stock_status_id' => 5,
                    'image' => 'catalog/product_online/' . $productInfo['no_siri_Produk'] . '.jpg',
                    'manufacturer_id' => $productInfo['Supplier_ID'],
                    'shipping' => 1,
                    'price' => ($productInfo['receiving_Status'] == 1) ? $productInfo['code_Supplier'] : $productInfo['Harga_item'],
                    'points' => 0,
                    'tax_class_id' => 0,
                    'date_available' => date('Y-m-d'),
                    'weight' => $productInfo['Berat'],
                    'weight_class_id' => 2,
                    'length' => $productInfo['dimension_Panjang'],
                    'width' => $productInfo['dimension_Lebar'],
                    'height' => $productInfo['dimension_Dia'],
                    'length_class_id' => 2,
                    'substract' => 1,
                    'minimum' => 1,
                    'sort_order' => 1,
                    'status' => 1,
                    'viewed' => 0,
                    'date_added' => date('Y-m-d h:i:s'),
                    'date_modified' => date('Y-m-d h:i:s'),
                    'user_id' => 1,
                    'remarks' => $productInfo['remarks'],
                    'kategori_Produk' => $productInfo['kategori_Produk'],
                    'ring_size' => $productInfo['dimension_Saiz']);
        return $result;
    }

}
