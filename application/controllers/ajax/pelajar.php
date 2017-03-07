<?php

$pelajar = new Pelajar_Model();
switch ($_REQUEST['action']):
    case 'daftar':
        $values = $_REQUEST['values'];
        $pelajar->nama = $values[0];
        $pelajar->tingkatan = $values[1];
        $pelajar->no_kadpengenalan = $values[2];
        $pelajar->no_daftar_sekolah = $values[3];
        $pelajar->masuk_tahun = $values[4];
        $pelajar->masuk_tingkatan = $values[5];
        $pelajar->keluar_tahun = $values[6];
        $pelajar->keluar_tingkatan = $values[7];
        $pelajar->nama_bapa = $values[8];
        $pelajar->kerja_bapa = $values[9];
        $pelajar->nama_ibu = $values[10];
        $pelajar->kerja_ibu = $values[11];
        $pelajar->no_telefon_rumah = $values[12];
        $pelajar->no_telefon_hp = $values[13];
        $pelajar->alamat = $values[14];
        $pelajar->catatan = $values[15];
        
        $pelajar->create_pelajar();
        break;
    case 'cari':
        $view = new View_Model('modules/pelajar/senarai_pelajar');
        $values = $_REQUEST['values'];
        
        $pelajar->nama = $values[0];
        $view->assign('resultCarian', $pelajar->read_pelajar_cari_array());
        break;
endswitch;
?>