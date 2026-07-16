<?php
class Sisffa {
    private $pdo;

    public $FfaCod;
    public $FfaDesc;
    public $FfaFec;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarAll() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM sisffa ORDER BY FfaCod ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar($search = '', $limit = 20, $offset = 0) {
        try {
            $where = '';
            if (!empty($search)) {
                $search = '%' . $search . '%';
                $where = 'WHERE FfaDesc LIKE :search';
            }
            $sql = "SELECT * FROM sisffa $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE FfaDesc LIKE :search';
            }
            $sql = "SELECT count(*) FROM sisffa $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM sisffa WHERE FfaCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM sisffa WHERE FfaCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->FfaCod)) {
                $sql = "UPDATE sisffa SET FfaDesc = ?, FfaFec = ? WHERE FfaCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->FfaDesc,
                    $data->FfaFec,
                    $data->FfaCod
                ));
            } else {
                $sql = "INSERT INTO sisffa (FfaDesc, FfaFec) VALUES (?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->FfaDesc,
                    $data->FfaFec
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
