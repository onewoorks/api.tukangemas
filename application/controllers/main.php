<?php

class Main_Controller extends Common_Controller {
    public $par = URL_ARRAY;
    public function __construct() {
    }

    public function main(array $getVars, array $params = null) {
        echo 'test';
        $request = $_SERVER['QUERY_STRING'];
        $parsed = explode('&', $request);
        $page = array_shift($parsed);

//        switch ($page):
//            case 'info':
//                $info = new info_controller();
//                $info->main($getVars);
//                break;
//            case 'product':
//                $view = new View_Model('product');
////                $view->assign('data'=>)
//                
//                break;
//            default:
//                $view = new View_Model('main');
//                $content_result = $this->content_page($params);
//                $view->assign('page', $content_result);
//                break;
//        endswitch;
    }

    private function content_page(array $params) {
        $result = array();
        switch ($params[$this->par]):
            default:
                $result[0] = 'application/views/modules/index.php';
                break;
        endswitch;
        return $result;
    }

}