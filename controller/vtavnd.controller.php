<?php
require_once 'model/vtavnd.php';

class VtavndController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Vtavnd();
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
        require_once 'view/vtavnd/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Vtavnd();
        if(isset($_REQUEST['VndCod'])) {
            $alm = $this->model->Obtener($_REQUEST['VndCod']);
        }
        require_once 'view/header.php';
        require_once 'view/vtavnd/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Vtavnd();
        $alm->VndCod = $_REQUEST['VndCod'];
        $alm->VndNom = $_REQUEST['VndNom'];
        $alm->VndDir = $_REQUEST['VndDir'];
        $alm->VndTel = $_REQUEST['VndTel'];
        $alm->VndCel = $_REQUEST['VndCel'];
        $alm->VndCom = $_REQUEST['VndCom'];
        $alm->VndMai = $_REQUEST['VndMai'];
        $alm->VndUsr = $_REQUEST['VndUsr'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=vtavnd');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['VndCod']);
        header('Location: index.php?c=vtavnd');
    }
}
