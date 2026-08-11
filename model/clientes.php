<?php
class Clientes {
    private $pdo;

    public $cod_cliente;
    public $nombre;
    public $domicilio;
    public $localidad;
    public $cod_postal;
    public $telefono;
    public $celular;
    public $email;
    public $cuit;
    public $iva;
    public $CliNomCon1;
    public $CliTelCon1;
    public $CliMaiCon1;
    public $CliNomCon2;
    public $CliTelCon2;
    public $CliMaiCon2;
    public $CliNomCon3;
    public $CliTelCon3;
    public $CliMaiCon3;
    public $CliNomCon4;
    public $CliTelCon4;
    public $CliMaiCon4;
    public $CliLocCod;
    public $CcliCod;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar($search = '', $limit = 20, $offset = 0) {
        try {
            $where = '';
            if (!empty($search)) {
                $search = '%' . $search . '%';
                $where = 'WHERE ' . implode(' OR ', ['clientes.nombre LIKE :search', 'clientes.domicilio LIKE :search', 'clientes.localidad LIKE :search', 'clientes.cod_postal LIKE :search', 'clientes.telefono LIKE :search', 'clientes.celular LIKE :search', 'clientes.email LIKE :search', 'clientes.cuit LIKE :search', 'clientes.iva LIKE :search']);
            }
            $sql = "SELECT clientes.*, localidades.LcoNom AS localidad_nombre 
                    FROM clientes 
                    LEFT JOIN localidades ON clientes.CliLocCod = localidades.LcoCod 
                    $where 
                    LIMIT :limit OFFSET :offset";
            $stm = $this->pdo->prepare($sql);
            if (!empty($search)) $stm->bindValue(':search', $search, PDO::PARAM_STR);
            $stm->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stm->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Count($search = '') {
        try {
            $where = '';
            if (!empty($search)) {
                $search = '%' . $search . '%';
                $where = 'WHERE ' . implode(' OR ', ['clientes.nombre LIKE :search', 'clientes.domicilio LIKE :search', 'clientes.localidad LIKE :search', 'clientes.cod_postal LIKE :search', 'clientes.telefono LIKE :search', 'clientes.celular LIKE :search', 'clientes.email LIKE :search', 'clientes.cuit LIKE :search', 'clientes.iva LIKE :search']);
            }
            $sql = "SELECT count(*) FROM clientes $where";
            $stm = $this->pdo->prepare($sql);
            if (!empty($search)) $stm->bindValue(':search', $search, PDO::PARAM_STR);
            $stm->execute();
            return $stm->fetchColumn();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id) {
        try {
            $stm = $this->pdo->prepare("SELECT clientes.*, localidades.LcoNom AS localidad_nombre 
                                        FROM clientes 
                                        LEFT JOIN localidades ON clientes.CliLocCod = localidades.LcoCod 
                                        WHERE clientes.cod_cliente = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM clientes WHERE cod_cliente = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            $data->CliTelCon1 = !empty($data->CliTelCon1) ? (int)$data->CliTelCon1 : null;
            $data->CliTelCon2 = !empty($data->CliTelCon2) ? (int)$data->CliTelCon2 : null;
            $data->CliTelCon3 = !empty($data->CliTelCon3) ? (int)$data->CliTelCon3 : null;
            $data->CliTelCon4 = !empty($data->CliTelCon4) ? (int)$data->CliTelCon4 : null;
            $data->CcliCod = !empty($data->CcliCod) ? (int)$data->CcliCod : null;
            $data->CliLocCod = !empty($data->CliLocCod) ? (int)$data->CliLocCod : 0;

            if (!empty($data->cod_cliente)) {
                $sql = "UPDATE clientes SET nombre = ?, domicilio = ?, localidad = ?, cod_postal = ?, telefono = ?, celular = ?, email = ?, cuit = ?, iva = ?, CliNomCon1 = ?, CliTelCon1 = ?, CliMaiCon1 = ?, CliNomCon2 = ?, CliTelCon2 = ?, CliMaiCon2 = ?, CliNomCon3 = ?, CliTelCon3 = ?, CliMaiCon3 = ?, CliNomCon4 = ?, CliTelCon4 = ?, CliMaiCon4 = ?, CliLocCod = ?, CcliCod = ? WHERE cod_cliente = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->nombre,
                    $data->domicilio,
                    $data->localidad,
                    $data->cod_postal,
                    $data->telefono,
                    $data->celular,
                    $data->email,
                    $data->cuit,
                    $data->iva,
                    $data->CliNomCon1,
                    $data->CliTelCon1,
                    $data->CliMaiCon1,
                    $data->CliNomCon2,
                    $data->CliTelCon2,
                    $data->CliMaiCon2,
                    $data->CliNomCon3,
                    $data->CliTelCon3,
                    $data->CliMaiCon3,
                    $data->CliNomCon4,
                    $data->CliTelCon4,
                    $data->CliMaiCon4,
                    $data->CliLocCod,
                    $data->CcliCod,
                    $data->cod_cliente
                ));
            } else {
                $sql = "INSERT INTO clientes (nombre, domicilio, localidad, cod_postal, telefono, celular, email, cuit, iva, CliNomCon1, CliTelCon1, CliMaiCon1, CliNomCon2, CliTelCon2, CliMaiCon2, CliNomCon3, CliTelCon3, CliMaiCon3, CliNomCon4, CliTelCon4, CliMaiCon4, CliLocCod, CcliCod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->nombre,
                    $data->domicilio,
                    $data->localidad,
                    $data->cod_postal,
                    $data->telefono,
                    $data->celular,
                    $data->email,
                    $data->cuit,
                    $data->iva,
                    $data->CliNomCon1,
                    $data->CliTelCon1,
                    $data->CliMaiCon1,
                    $data->CliNomCon2,
                    $data->CliTelCon2,
                    $data->CliMaiCon2,
                    $data->CliNomCon3,
                    $data->CliTelCon3,
                    $data->CliMaiCon3,
                    $data->CliNomCon4,
                    $data->CliTelCon4,
                    $data->CliMaiCon4,
                    $data->CliLocCod,
                    $data->CcliCod,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
