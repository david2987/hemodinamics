<?php
class Vtavnd {
    private $pdo;

    public $VndCod;
    public $VndNom;
    public $VndDir;
    public $VndTel;
    public $VndCel;
    public $VndCom;
    public $VndMai;
    public $VndUsr;

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
                $where = 'WHERE ' . implode(' OR ', ['VndNom LIKE :search', 'VndDir LIKE :search', 'VndTel LIKE :search', 'VndCel LIKE :search', 'VndUsr LIKE :search']);
            }
            $sql = "SELECT * FROM vtavnd $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['VndNom LIKE :search', 'VndDir LIKE :search', 'VndTel LIKE :search', 'VndCel LIKE :search', 'VndUsr LIKE :search']);
            }
            $sql = "SELECT count(*) FROM vtavnd $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM vtavnd WHERE VndCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM vtavnd WHERE VndCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            $data->VndCod = !empty($data->VndCod) ? (int)$data->VndCod : null;

            if (!empty($data->VndCod)) {
                $sql = "UPDATE vtavnd SET VndNom = ?, VndDir = ?, VndTel = ?, VndCel = ?, VndCom = ?, VndMai = ?, VndUsr = ? WHERE VndCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->VndNom,
                    $data->VndDir,
                    $data->VndTel,
                    $data->VndCel,
                    $data->VndCom,
                    $data->VndMai,
                    $data->VndUsr,
                    $data->VndCod
                ));
            } else {
                $sql = "INSERT INTO vtavnd (VndCod, VndNom, VndDir, VndTel, VndCel, VndCom, VndMai, VndUsr) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->VndCod,
                    $data->VndNom,
                    $data->VndDir,
                    $data->VndTel,
                    $data->VndCel,
                    $data->VndCom,
                    $data->VndMai,
                    $data->VndUsr,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
