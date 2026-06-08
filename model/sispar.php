<?php
class Sispar {
    private $pdo;

    public $ParCod;
    public $ParDsc;
    public $ParVarNum;
    public $ParVarChr;
    public $ParFec;
    public $ParUsr;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarAll() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM sispar ORDER BY ParCod ASC");
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
                $where = 'WHERE ParDsc LIKE :search OR ParVarChr LIKE :search';
            }
            $sql = "SELECT * FROM sispar $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ParDsc LIKE :search OR ParVarChr LIKE :search';
            }
            $sql = "SELECT count(*) FROM sispar $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM sispar WHERE ParCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM sispar WHERE ParCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->ParCod)) {
                $sql = "UPDATE sispar SET ParDsc = ?, ParVarNum = ?, ParVarChr = ?, ParFec = ?, ParUsr = ? WHERE ParCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->ParDsc,
                    $data->ParVarNum,
                    $data->ParVarChr,
                    $data->ParFec,
                    $data->ParUsr,
                    $data->ParCod
                ));
            } else {
                $sql = "INSERT INTO sispar (ParDsc, ParVarNum, ParVarChr, ParFec, ParUsr) VALUES (?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->ParDsc,
                    $data->ParVarNum,
                    $data->ParVarChr,
                    $data->ParFec,
                    $data->ParUsr
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
