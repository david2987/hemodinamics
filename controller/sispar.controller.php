<?php
require_once 'model/sispar.php';

class SisparController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Sispar();
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
        require_once 'view/sispar/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Sispar();
        if(isset($_REQUEST['ParCod'])) {
            $alm = $this->model->Obtener($_REQUEST['ParCod']);
        }
        require_once 'view/header.php';
        require_once 'view/sispar/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Sispar();
        $alm->ParCod = isset($_REQUEST['ParCod']) && !empty($_REQUEST['ParCod']) ? $_REQUEST['ParCod'] : null;
        $alm->ParDsc = $_REQUEST['ParDsc'];
        $alm->ParVarNum = !empty($_REQUEST['ParVarNum']) ? $_REQUEST['ParVarNum'] : 0;
        $alm->ParVarChr = $_REQUEST['ParVarChr'];
        $alm->ParFec = !empty($_REQUEST['ParFec']) ? $_REQUEST['ParFec'] : date('Y-m-d');
        $alm->ParUsr = $_REQUEST['ParUsr'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=sispar');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['ParCod']);
        header('Location: index.php?c=sispar');
    }
}
