<?php

$etika = new Etika_Model();
switch ($_REQUEST['action']):
    case 'daftar':
        $view = new View_Model('modules/kaunseling/list_rekod_kelakuan');
        $values = $_REQUEST['values'];
        $etika->pel_id = $values[0];
        $etika->jenis_etika = $values[1];
        $etika->kenyataan = $values[2];
        $etika->sumber_maklumat = $values[3];
        $etika->create_etika();        
        
        $view->assign('listKelakuan', $etika->read_etika_by_pel_id());
        break;
endswitch;
?>