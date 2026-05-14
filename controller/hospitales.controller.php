<?php
require_once 'model/hospitales.php';

class HospitalesController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Hospitales();
    }

    public function Index() {
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $items = $this->model->Listar($search, $limit, $offset);
        $total = $this->model->Count($search);
        $pages = ceil($total / $limit);

        require_once 'view/header.php';
        require_once 'view/hospitales/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Hospitales();
        if(isset($_REQUEST['HospCod'])) {
            $alm = $this->model->Obtener($_REQUEST['HospCod']);
        }
        require_once 'view/header.php';
        require_once 'view/hospitales/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Hospitales();
        $alm->HospCod = $_REQUEST['HospCod'];
        $alm->HospDesc = $_REQUEST['HospDesc'];
        $alm->HospMail = $_REQUEST['HospMail'];
        $alm->HospCUIT = $_REQUEST['HospCUIT'];
        $alm->HospTel = $_REQUEST['HospTel'];
        $alm->HospDom = $_REQUEST['HospDom'];
        $alm->HospCUFE = $_REQUEST['HospCUFE'];
        $alm->HospLoc = $_REQUEST['HospLoc'];
        $alm->HospDesc2 = $_REQUEST['HospDesc2'];
        $alm->HospLocCod = $_REQUEST['HospLocCod'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=hospitales');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['HospCod']);
        header('Location: index.php?c=hospitales');
    }
}
