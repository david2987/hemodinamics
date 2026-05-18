<?php
class Sisgru {
    private $pdo;

    public $GruCod;
    public $GruDsc;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarAll() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM sisgru ORDER BY GruDsc ASC");
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
                $where = 'WHERE GruDsc LIKE :search';
            }
            $sql = "SELECT * FROM sisgru $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE GruDsc LIKE :search';
            }
            $sql = "SELECT count(*) FROM sisgru $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM sisgru WHERE GruCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM sisgru WHERE GruCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->GruCod)) {
                $sql = "UPDATE sisgru SET GruDsc = ? WHERE GruCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->GruDsc,
                    $data->GruCod
                ));
            } else {
                $sql = "INSERT INTO sisgru (GruDsc) VALUES (?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->GruDsc
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
