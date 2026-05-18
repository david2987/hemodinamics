<?php
require_once 'model/usuarios.php';

class UsuariosController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Usuarios();
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
        require_once 'view/usuarios/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Usuarios();
        if(isset($_REQUEST['UsrCod'])) {
            $alm = $this->model->Obtener($_REQUEST['UsrCod']);
        }
        require_once 'model/sisgru.php';
        $sisgruModel = new Sisgru();
        $grupos = $sisgruModel->ListarAll();

        require_once 'view/header.php';
        require_once 'view/usuarios/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Usuarios();
        $alm->UsrCod = $_REQUEST['UsrCod'];
        $alm->UsrPas = $_REQUEST['UsrPas'];
        $alm->UsrInf = $_REQUEST['UsrInf'];
        $alm->GruCod = $_REQUEST['GruCod'];
        $alm->UsrAdm = $_REQUEST['UsrAdm'];
        $alm->SucCod = $_REQUEST['SucCod'];
        $alm->UsrAct = $_REQUEST['UsrAct'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=usuarios');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['UsrCod']);
        header('Location: index.php?c=usuarios');
    }
}
