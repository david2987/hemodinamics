<?php
require_once 'model/categoriaclientes.php';

class CategoriaclientesController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Categoriaclientes();
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
        require_once 'view/categoriaclientes/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Categoriaclientes();
        if(isset($_REQUEST['CcliCod'])) {
            $alm = $this->model->Obtener($_REQUEST['CcliCod']);
        }
        require_once 'view/header.php';
        require_once 'view/categoriaclientes/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Categoriaclientes();
        $alm->CcliCod = $_REQUEST['CcliCod'];
        $alm->CcliDes = $_REQUEST['CcliDes'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=categoriaclientes');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['CcliCod']);
        header('Location: index.php?c=categoriaclientes');
    }
}
