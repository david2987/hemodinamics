<?php
require_once 'model/productos.php';

class ProductosController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Productos();
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
        require_once 'view/productos/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Productos();
        if(isset($_REQUEST['cod_producto'])) {
            $alm = $this->model->Obtener($_REQUEST['cod_producto']);
        }
        require_once 'view/header.php';
        require_once 'view/productos/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Productos();
        $alm->cod_producto = $_REQUEST['cod_producto'];
        $alm->detalle = $_REQUEST['detalle'];
        $alm->producto_titulo = $_REQUEST['producto_titulo'];
        $alm->producto_precio = $_REQUEST['producto_precio'];
        $alm->productoPrecDis = $_REQUEST['productoPrecDis'];
        $alm->CptId = $_REQUEST['CptId'];
        $alm->producto_fechaaviso = $_REQUEST['producto_fechaaviso'];
        $alm->producto_aviso = $_REQUEST['producto_aviso'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=productos');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['cod_producto']);
        header('Location: index.php?c=productos');
    }
}
