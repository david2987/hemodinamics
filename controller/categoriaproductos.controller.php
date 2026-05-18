<?php
require_once 'model/categoriaproductos.php';

class CategoriaproductosController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Categoriaproductos();
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
        require_once 'view/categoriaproductos/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Categoriaproductos();
        if(isset($_REQUEST['CptId'])) {
            $alm = $this->model->Obtener($_REQUEST['CptId']);
        }
        require_once 'view/header.php';
        require_once 'view/categoriaproductos/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Categoriaproductos();
        $alm->CptId = isset($_REQUEST['CptId']) && !empty($_REQUEST['CptId']) ? $_REQUEST['CptId'] : null;
        $alm->CptDes = $_REQUEST['CptDes'];
        $alm->CptAbv = $_REQUEST['CptAbv'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=categoriaproductos');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['CptId']);
        header('Location: index.php?c=categoriaproductos');
    }
}
