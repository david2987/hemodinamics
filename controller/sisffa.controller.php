<?php
require_once 'model/sisffa.php';

class SisffaController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Sisffa();
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
        require_once 'view/sisffa/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Sisffa();
        if(isset($_REQUEST['FfaCod'])) {
            $alm = $this->model->Obtener($_REQUEST['FfaCod']);
        }
        require_once 'view/header.php';
        require_once 'view/sisffa/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Sisffa();
        $alm->FfaCod = isset($_REQUEST['FfaCod']) && !empty($_REQUEST['FfaCod']) ? $_REQUEST['FfaCod'] : null;
        $alm->FfaDesc = $_REQUEST['FfaDesc'];
        $alm->FfaFec = !empty($_REQUEST['FfaFec']) ? (int)$_REQUEST['FfaFec'] : 0;
        $this->model->Guardar($alm);
        header('Location: index.php?c=sisffa');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['FfaCod']);
        header('Location: index.php?c=sisffa');
    }
}
