<?php

class Login_Controller extends Common_Controller {

    public function main(array $getVars) {
        $view = new View_Model('login');
        $this->checkuser();
    }

    private function checkuser() {
        if (isset($_REQUEST['password']) && isset($_REQUEST['username'])):
            $cikgu = new Cikgu_Model();
            $cikgu->nama_pengguna = $_REQUEST['username'];
            $cikgu->kata_laluan = $_REQUEST['password'];
            $a = $cikgu->read_cikgu_is_exist();
            if(count($a)==1):
                $_SESSION['uid'] = $a[0]['cikgu_id'];
                $_SESSION['sek_id'] = $a[0]['sek_id'];
                header('Location: '.SITE_ROOT);
            endif;
        endif;
    }

}

?>