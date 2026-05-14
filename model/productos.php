<?php
class Productos {
    private $pdo;

    public $cod_producto;
    public $detalle;
    public $producto_titulo;
    public $producto_precio;
    public $productoPrecDis;
    public $CptId;
    public $producto_fechaaviso;
    public $producto_aviso;

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
                $where = 'WHERE ' . implode(' OR ', ['detalle LIKE :search', 'producto_titulo LIKE :search', 'producto_aviso LIKE :search']);
            }
            $sql = "SELECT * FROM productos $where LIMIT :limit OFFSET :offset";
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
                $where = 'WHERE ' . implode(' OR ', ['detalle LIKE :search', 'producto_titulo LIKE :search', 'producto_aviso LIKE :search']);
            }
            $sql = "SELECT count(*) FROM productos $where";
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
            $stm = $this->pdo->prepare("SELECT * FROM productos WHERE cod_producto = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Eliminar($id) {
        try {
            $stm = $this->pdo->prepare("DELETE FROM productos WHERE cod_producto = ?");
            $stm->execute(array($id));
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Guardar($data) {
        try {
            if (!empty($data->$pk)) {
                $sql = "UPDATE productos SET detalle = ?, producto_titulo = ?, producto_precio = ?, productoPrecDis = ?, CptId = ?, producto_fechaaviso = ?, producto_aviso = ? WHERE cod_producto = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $data->detalle,
                    $data->producto_titulo,
                    $data->producto_precio,
                    $data->productoPrecDis,
                    $data->CptId,
                    $data->producto_fechaaviso,
                    $data->producto_aviso,
                    $data->cod_producto
                ));
            } else {
                $sql = "INSERT INTO productos (detalle, producto_titulo, producto_precio, productoPrecDis, CptId, producto_fechaaviso, producto_aviso) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)->execute(array(
                    $data->detalle,
                    $data->producto_titulo,
                    $data->producto_precio,
                    $data->productoPrecDis,
                    $data->CptId,
                    $data->producto_fechaaviso,
                    $data->producto_aviso,
                ));
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
