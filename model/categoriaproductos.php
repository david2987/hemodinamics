<?php
class Categoriaproductos {
    private $pdo;

    public $CptId;
    public $CptDes;
    public $CptAbv;

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
                $where = 'WHERE ' . implode(' OR ', ['CptDes LIKE :search', 'CptAbv LIKE :search']);
            }
            $sql = "SELECT * FROM categoriaproductos $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['CptDes LIKE :search', 'CptAbv LIKE :search']);
            }
            $sql = "SELECT count(*) FROM categoriaproductos $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM categoriaproductos WHERE CptId = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM categoriaproductos WHERE CptId = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->CptId)) {
                $sql = "UPDATE categoriaproductos SET CptDes = ?, CptAbv = ? WHERE CptId = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CptDes,
                    $data->CptAbv,
                    $data->CptId
                ));
            } else {
                $sql = "INSERT INTO categoriaproductos (CptDes, CptAbv) VALUES (?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CptDes,
                    $data->CptAbv
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
