<?php
class Usuarios {
    private $pdo;

    public $UsrCod;
    public $UsrPas;
    public $UsrInf;
    public $GruCod;
    public $UsrAdm;
    public $SucCod;
    public $UsrAct;

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
                $where = 'WHERE ' . implode(' OR ', ['usuarios.UsrCod LIKE :search', 'usuarios.UsrPas LIKE :search', 'usuarios.UsrInf LIKE :search', 'usuarios.UsrAct LIKE :search']);
            }
            $sql = "SELECT usuarios.*, sisgru.GruDsc AS grupo_nombre 
                    FROM usuarios 
                    LEFT JOIN sisgru ON usuarios.GruCod = sisgru.GruCod 
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
                $where = 'WHERE ' . implode(' OR ', ['usuarios.UsrCod LIKE :search', 'usuarios.UsrPas LIKE :search', 'usuarios.UsrInf LIKE :search', 'usuarios.UsrAct LIKE :search']);
            }
            $sql = "SELECT count(*) FROM usuarios $where";
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
            $stm = $this->pdo->prepare("SELECT usuarios.*, sisgru.GruDsc AS grupo_nombre 
                                        FROM usuarios 
                                        LEFT JOIN sisgru ON usuarios.GruCod = sisgru.GruCod 
                                        WHERE usuarios.UsrCod = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM usuarios WHERE UsrCod = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            $exists = false;
            if (!empty($data->UsrCod)) {
                $check = $this->pdo->prepare("SELECT count(*) FROM usuarios WHERE UsrCod = ?");
                $check->execute(array($data->UsrCod));
                $exists = $check->fetchColumn() > 0;
            }

            if ($exists) {
                $sql = "UPDATE usuarios SET UsrPas = ?, UsrInf = ?, GruCod = ?, UsrAdm = ?, SucCod = ?, UsrAct = ? WHERE UsrCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->UsrPas,
                    $data->UsrInf,
                    $data->GruCod,
                    $data->UsrAdm,
                    $data->SucCod,
                    $data->UsrAct,
                    $data->UsrCod
                ));
            } else {
                $sql = "INSERT INTO usuarios (UsrCod, UsrPas, UsrInf, GruCod, UsrAdm, SucCod, UsrAct) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->UsrCod,
                    $data->UsrPas,
                    $data->UsrInf,
                    $data->GruCod,
                    $data->UsrAdm,
                    $data->SucCod,
                    $data->UsrAct,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
