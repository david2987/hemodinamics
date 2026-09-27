<?php
class Facturacion {
    private $pdo;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ListarFacturas($plcCod)
    {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM facturacion_facturas WHERE PlcCod = ? ORDER BY Anulada ASC, FacCod DESC");
            $stm->execute(array($plcCod));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function AgregarFactura($plcCod, $nroFac, $letraFac, $fechaFac, $importeFac, $usuario)
    {
        try {
            $sql = "INSERT INTO facturacion_facturas (PlcCod, NroFac, TipoFac, FechaFac, ImporteFac, ConsumoValorizado, Observaciones, FacUsuario, Anulada)
                    VALUES (?, ?, ?, ?, ?, 0, '', ?, 0)";
            $this->pdo->prepare($sql)->execute(array($plcCod, $nroFac, $letraFac, $fechaFac, $importeFac, $usuario));
            return $this->pdo->lastInsertId();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function AnularFactura($facCod, $usuario)
    {
        try {
            $sql = "UPDATE facturacion_facturas SET Anulada = 1, FechaAnulacion = CURDATE(), UsuarioAnulacion = ? WHERE FacCod = ?";
            $this->pdo->prepare($sql)->execute(array($usuario, $facCod));
            return true;
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
