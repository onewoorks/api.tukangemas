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
            'loket emas'    => array('sankyu' => 7, 'tukangemas' => 105)
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
            'cincin belah rotan'    => array('sankyu' => 3, 'tukangemas' => 96),
            'cincin emas permata'   => array('sankyu' => 3, 'tukangemas' => 107)
        ),
        'gelang' => array('gelang emas' => array('sankyu' => 6, 'tukangemas' => 99))
    );
    
    public function ListCategory(){
        return $this->basicCategory;
    }
//    private $mainKategori = array('biasa', 'rantai', 'cincin', 'gelang');
//    private $subKategoriRantai = array('rantai kaki', 'rantai leher', 'rantai padu', 'rantai tangan kosong', 'rantai tangan fesyen');
//    private $prefixBiasa = array('35' => 'subang_emas', '36' => 'loket_emas');
//    private $prefixRantai = array('32' => 'rantai_tangan', '33' => 'rantai_leher', '34' => 'rantai_kaki');
//    private $prefixCincin = array(27 => 'cincin_perak', 28 => 'cincin_emas', 29 => 'cincin_emas_permata_916', 30 => 'cincin_emas_permata_750', 31 => 'cincin_emas_permata_585');
//    private $prefixGelang = array(37 => 'gelang_emas');
//    private $rantaiKaki = array(4);
//    private $rantaiLeher = array(8);
//    private $rantaiPadu = array(9);
//    private $rantaiTanganKosong = array(10);
//    private $rantaiTanganFesyen = array(11, 12, 13, 14);
//    private $cincinPerak = array(1);
//    private $cincinEmas = array(2);
//    private $cincinEmasPermata = array(3);
//    private $subangEmas = array(5);
//    private $gelangEmas = array(6);

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
