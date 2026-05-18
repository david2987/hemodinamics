<?php
require_once 'model/sisgru.php';

class SisgruController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Sisgru();
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
        require_once 'view/sisgru/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Sisgru();
        if(isset($_REQUEST['GruCod'])) {
            $alm = $this->model->Obtener($_REQUEST['GruCod']);
        }
        require_once 'view/header.php';
        require_once 'view/sisgru/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Sisgru();
        $alm->GruCod = isset($_REQUEST['GruCod']) && !empty($_REQUEST['GruCod']) ? $_REQUEST['GruCod'] : null;
        $alm->GruDsc = $_REQUEST['GruDsc'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=sisgru');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['GruCod']);
        header('Location: index.php?c=sisgru');
    }
}
