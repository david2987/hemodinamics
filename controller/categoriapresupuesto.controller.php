<?php
require_once 'model/categoriapresupuesto.php';

class CategoriapresupuestoController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Categoriapresupuesto();
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
        require_once 'view/categoriapresupuesto/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Categoriapresupuesto();
        if(isset($_REQUEST['CprCod'])) {
            $alm = $this->model->Obtener($_REQUEST['CprCod']);
        }
        require_once 'view/header.php';
        require_once 'view/categoriapresupuesto/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Categoriapresupuesto();
        $alm->CprCod = $_REQUEST['CprCod'];
        $alm->CprDes = $_REQUEST['CprDes'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=categoriapresupuesto');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['CprCod']);
        header('Location: index.php?c=categoriapresupuesto');
    }
}
