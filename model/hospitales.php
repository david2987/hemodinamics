<?php
class Hospitales {
    private $pdo;

    public $HospCod;
    public $HospDesc;
    public $HospMail;
    public $HospCUIT;
    public $HospTel;
    public $HospDom;
    public $HospCUFE;
    public $HospLoc;
    public $HospDesc2;
    public $HospLocCod;

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
                $where = 'WHERE ' . implode(' OR ', ['HospCod LIKE :search', 'HospDesc LIKE :search', 'HospDesc2 LIKE :search', 'HospCUIT LIKE :search', 'HospTel LIKE :search', 'HospDom LIKE :search', 'HospLoc LIKE :search']);
            }
            $sql = "SELECT * FROM hospitales $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['HospCod LIKE :search', 'HospDesc LIKE :search', 'HospDesc2 LIKE :search', 'HospCUIT LIKE :search', 'HospTel LIKE :search', 'HospDom LIKE :search', 'HospLoc LIKE :search']);
            }
            $sql = "SELECT count(*) FROM hospitales $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM hospitales WHERE HospCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM hospitales WHERE HospCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            $data->HospLocCod = !empty($data->HospLocCod) ? (int)$data->HospLocCod : null;

            if (!empty($data->HospCod)) {
                $sql = "UPDATE hospitales SET HospDesc = ?, HospMail = ?, HospCUIT = ?, HospTel = ?, HospDom = ?, HospCUFE = ?, HospLoc = ?, HospDesc2 = ?, HospLocCod = ? WHERE HospCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->HospDesc,
                    $data->HospMail,
                    $data->HospCUIT,
                    $data->HospTel,
                    $data->HospDom,
                    $data->HospCUFE,
                    $data->HospLoc,
                    $data->HospDesc2,
                    $data->HospLocCod,
                    $data->HospCod
                ));
            } else {
                $sql = "INSERT INTO hospitales (HospCod, HospDesc, HospMail, HospCUIT, HospTel, HospDom, HospCUFE, HospLoc, HospDesc2, HospLocCod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->HospCod,
                    $data->HospDesc,
                    $data->HospMail,
                    $data->HospCUIT,
                    $data->HospTel,
                    $data->HospDom,
                    $data->HospCUFE,
                    $data->HospLoc,
                    $data->HospDesc2,
                    $data->HospLocCod,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
