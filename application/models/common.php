<?php

class Common_Model {

    public $statusItemDalamStok = 10;
    public $statusItemTelahJual = 11;
    private $allowCategory = array('916', '999', '750', '585', '925');

    /**
     * key = kategory, value = no dulang
     */
    public $basicCategory = array(
        'biasa' => array(
            'subang emas'   => array('sankyu' => 5, 'tukangemas' => 98),
            'loket emas'    => array('sankyu' => 7, 'tukangemas' => 105),
            'choker'        => array('sankyu' => 15,'tukangemas' => 110)
        ),
        'rantai' => array(
            'rantai kaki'   => array('sankyu' => 4, 'tukangemas' => 97),
            'rantai leher'  => array('sankyu' => 8, 'tukangemas' => 100),
            'rantai tangan padat'   => array('sankyu' => 9, 'tukangemas' => 101),
            'rantai tangan kosong'  => array('sankyu' => 10, 'tukangemas' => 102),
            'rantai tangan fesyen'  => array('sankyu' => array(11, 12, 13), 'tukangemas' => 103),
            'rantai tangan gantung' => array('sankyu' => 14, 'tukangemas' => 104),
        ),
        'cincin' => array(
            'cincin perak'  => array('sankyu' => 1, 'tukangemas' => 93),
            'cincin emas'   => array('sankyu' => 2, 'tukangemas' => 95),
            'cincin belah rotan'    => array('sankyu' => 3, 'tukangemas' => 96)
//            'cincin emas permata'   => array('sankyu' => 3, 'tukangemas' => 107)
        ),
        'gelang' => array('gelang emas' => array('sankyu' => 6, 'tukangemas' => 99))
    );
    
    public function ListCategory(){
        return $this->basicCategory;
    }

    public function WhereKategoryTerpilih() {
        $where = 'kod_Purity = ' . implode(' OR kod_Purity = ', $this->allowCategory);
        return $where;
    }

    public function SenaraiKategori() {
        $categories = $this->basicCategory;
        $info = array();
        foreach ($categories as $key => $subCategory):
            $info[$key] = $subCategory;
        endforeach;
        return $info;
    }

}
