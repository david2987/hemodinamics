<?php
class Categoriapresupuesto {
    private $pdo;

    public $CprCod;
    public $CprDes;

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
                $where = 'WHERE ' . implode(' OR ', ['CprDes LIKE :search']);
            }
            $sql = "SELECT * FROM categoriapresupuesto $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['CprDes LIKE :search']);
            }
            $sql = "SELECT count(*) FROM categoriapresupuesto $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM categoriapresupuesto WHERE CprCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM categoriapresupuesto WHERE CprCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->$pk)) {
                $sql = "UPDATE categoriapresupuesto SET CprDes = ? WHERE CprCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CprDes,
                    $data->CprCod
                ));
            } else {
                $sql = "INSERT INTO categoriapresupuesto (CprCod, CprDes) VALUES (?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->CprCod,
                    $data->CprDes,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
