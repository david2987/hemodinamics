<?php
class Categoriaclientes {
    private $pdo;

    public $CcliCod;
    public $CcliDes;

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
                $where = 'WHERE ' . implode(' OR ', ['CcliDes LIKE :search']);
            }
            $sql = "SELECT * FROM categoriaclientes $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['CcliDes LIKE :search']);
            }
            $sql = "SELECT count(*) FROM categoriaclientes $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM categoriaclientes WHERE CcliCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM categoriaclientes WHERE CcliCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            $data->CcliCod = !empty($data->CcliCod) ? (int)$data->CcliCod : null;

            if (!empty($data->CcliCod)) {
                $sql = "UPDATE categoriaclientes SET CcliDes = ? WHERE CcliCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CcliDes,
                    $data->CcliCod
                ));
            } else {
                $sql = "INSERT INTO categoriaclientes (CcliCod, CcliDes) VALUES (?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CcliCod,
                    $data->CcliDes,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
