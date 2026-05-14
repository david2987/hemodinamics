<?php
class Medicos {
    private $pdo;

    public $cod_medico;
    public $localidad;
    public $mediconombre;
    public $medicodomicilio;
    public $medicocod_postal;
    public $medicotelefono;
    public $medicocelular;
    public $medicoemail;
    public $MelLocCod;

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
                $where = 'WHERE ' . implode(' OR ', ['localidad LIKE :search', 'mediconombre LIKE :search', 'medicodomicilio LIKE :search', 'medicocod_postal LIKE :search', 'medicotelefono LIKE :search', 'medicocelular LIKE :search', 'medicoemail LIKE :search']);
            }
            $sql = "SELECT * FROM medicos $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['localidad LIKE :search', 'mediconombre LIKE :search', 'medicodomicilio LIKE :search', 'medicocod_postal LIKE :search', 'medicotelefono LIKE :search', 'medicocelular LIKE :search', 'medicoemail LIKE :search']);
            }
            $sql = "SELECT count(*) FROM medicos $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM medicos WHERE cod_medico = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM medicos WHERE cod_medico = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->$pk)) {
                $sql = "UPDATE medicos SET localidad = ?, mediconombre = ?, medicodomicilio = ?, medicocod_postal = ?, medicotelefono = ?, medicocelular = ?, medicoemail = ?, MelLocCod = ? WHERE cod_medico = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->localidad,
                    $data->mediconombre,
                    $data->medicodomicilio,
                    $data->medicocod_postal,
                    $data->medicotelefono,
                    $data->medicocelular,
                    $data->medicoemail,
                    $data->MelLocCod,
                    $data->cod_medico
                ));
            } else {
                $sql = "INSERT INTO medicos (localidad, mediconombre, medicodomicilio, medicocod_postal, medicotelefono, medicocelular, medicoemail, MelLocCod) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->localidad,
                    $data->mediconombre,
                    $data->medicodomicilio,
                    $data->medicocod_postal,
                    $data->medicotelefono,
                    $data->medicocelular,
                    $data->medicoemail,
                    $data->MelLocCod,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
