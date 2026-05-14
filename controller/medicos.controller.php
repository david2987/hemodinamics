<?php
require_once 'model/medicos.php';

class MedicosController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Medicos();
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
        require_once 'view/medicos/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Medicos();
        if(isset($_REQUEST['cod_medico'])) {
            $alm = $this->model->Obtener($_REQUEST['cod_medico']);
        }
        require_once 'view/header.php';
        require_once 'view/medicos/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Medicos();
        $alm->cod_medico = $_REQUEST['cod_medico'];
        $alm->localidad = $_REQUEST['localidad'];
        $alm->mediconombre = $_REQUEST['mediconombre'];
        $alm->medicodomicilio = $_REQUEST['medicodomicilio'];
        $alm->medicocod_postal = $_REQUEST['medicocod_postal'];
        $alm->medicotelefono = $_REQUEST['medicotelefono'];
        $alm->medicocelular = $_REQUEST['medicocelular'];
        $alm->medicoemail = $_REQUEST['medicoemail'];
        $alm->MelLocCod = $_REQUEST['MelLocCod'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=medicos');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['cod_medico']);
        header('Location: index.php?c=medicos');
    }
}
