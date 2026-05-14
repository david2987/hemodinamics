<?php
require_once 'model/clientes.php';

class ClientesController {
    private $model;

    public function __CONSTRUCT() {
        $this->model = new Clientes();
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
        require_once 'view/clientes/index.php';
        require_once 'view/footer.php';
    }

    public function Crud() {
        $alm = new Clientes();
        if(isset($_REQUEST['cod_cliente'])) {
            $alm = $this->model->Obtener($_REQUEST['cod_cliente']);
        }
        require_once 'view/header.php';
        require_once 'view/clientes/form.php';
        require_once 'view/footer.php';
    }

    public function Guardar() {
        $alm = new Clientes();
        $alm->cod_cliente = $_REQUEST['cod_cliente'];
        $alm->nombre = $_REQUEST['nombre'];
        $alm->domicilio = $_REQUEST['domicilio'];
        $alm->localidad = $_REQUEST['localidad'];
        $alm->cod_postal = $_REQUEST['cod_postal'];
        $alm->telefono = $_REQUEST['telefono'];
        $alm->celular = $_REQUEST['celular'];
        $alm->email = $_REQUEST['email'];
        $alm->cuit = $_REQUEST['cuit'];
        $alm->iva = $_REQUEST['iva'];
        $alm->CliNomCon1 = $_REQUEST['CliNomCon1'];
        $alm->CliTelCon1 = $_REQUEST['CliTelCon1'];
        $alm->CliMaiCon1 = $_REQUEST['CliMaiCon1'];
        $alm->CliNomCon2 = $_REQUEST['CliNomCon2'];
        $alm->CliTelCon2 = $_REQUEST['CliTelCon2'];
        $alm->CliMaiCon2 = $_REQUEST['CliMaiCon2'];
        $alm->CliNomCon3 = $_REQUEST['CliNomCon3'];
        $alm->CliTelCon3 = $_REQUEST['CliTelCon3'];
        $alm->CliMaiCon3 = $_REQUEST['CliMaiCon3'];
        $alm->CliNomCon4 = $_REQUEST['CliNomCon4'];
        $alm->CliTelCon4 = $_REQUEST['CliTelCon4'];
        $alm->CliMaiCon4 = $_REQUEST['CliMaiCon4'];
        $alm->CliLocCod = $_REQUEST['CliLocCod'];
        $alm->CcliCod = $_REQUEST['CcliCod'];
        $this->model->Guardar($alm);
        header('Location: index.php?c=clientes');
    }

    public function Eliminar() {
        $this->model->Eliminar($_REQUEST['cod_cliente']);
        header('Location: index.php?c=clientes');
    }
}
