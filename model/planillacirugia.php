<?php
class Planillacirugia {
    private $pdo;

    public $PlcCod;
    public $PlcFec;
    public $PlcHor;
    public $PlcCor;
    public $PlcTec;
    public $PlcPac;
    public $PlcMed;
    public $PlcSer;
    public $PlcCli;
    public $PlcMat;
    public $PlcMatCx;
    public $cod_presupuesto;
    public $PlcCxRea;

    public function __CONSTRUCT() {
        try {
            $this->pdo = Database::StartUp();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    private function ArmarFiltros($filtros)
    {
        $where = " WHERE 1=1 ";
        $params = [];

        if (!empty($filtros['estado']) && $filtros['estado'] === 'aceptadas') {
            $where .= " AND (planillacirugia.PlcCxRea IS NULL OR planillacirugia.PlcCxRea <> 'S') ";
        } elseif (!empty($filtros['estado']) && $filtros['estado'] === 'realizadas') {
            $where .= " AND planillacirugia.PlcCxRea = 'S' ";
        }
        if (!empty($filtros['vndCod'])) {
            $where .= " AND planillacirugia.PlcCor = :vndCod ";
            $params[':vndCod'] = $filtros['vndCod'];
        }
        if (!empty($filtros['paciente'])) {
            $where .= " AND planillacirugia.PlcPac LIKE :paciente ";
            $params[':paciente'] = '%' . $filtros['paciente'] . '%';
        }
        if (!empty($filtros['medico'])) {
            $where .= " AND medicos.mediconombre LIKE :medico ";
            $params[':medico'] = '%' . $filtros['medico'] . '%';
        }
        if (!empty($filtros['hospCod'])) {
            $where .= " AND planillacirugia.PlcSer = :hospCod ";
            $params[':hospCod'] = $filtros['hospCod'];
        }
        if (!empty($filtros['fechaDesde'])) {
            $where .= " AND planillacirugia.PlcFec >= :fechaDesde ";
            $params[':fechaDesde'] = $filtros['fechaDesde'];
        }
        if (!empty($filtros['fechaHasta'])) {
            $where .= " AND planillacirugia.PlcFec <= :fechaHasta ";
            $params[':fechaHasta'] = $filtros['fechaHasta'];
        }

        return [$where, $params];
    }

    private function SqlBase()
    {
        return "FROM planillacirugia
                INNER JOIN presupuestos ON planillacirugia.cod_presupuesto = presupuestos.cod_presupuesto
                LEFT JOIN medicos ON planillacirugia.PlcMed = medicos.cod_medico
                LEFT JOIN hospitales ON planillacirugia.PlcSer = hospitales.HospCod
                LEFT JOIN vtavnd ON planillacirugia.PlcCor = vtavnd.VndCod";
    }

    public function Listar($filtros = [], $limit = 50, $offset = 0)
    {
        try {
            list($where, $params) = $this->ArmarFiltros($filtros);

            $sql = "SELECT planillacirugia.*, presupuestos.EspCod, presupuestos.SueCod,
                           medicos.mediconombre, hospitales.HospDesc, vtavnd.VndNom
                    " . $this->SqlBase() . "
                    $where
                    ORDER BY planillacirugia.PlcFec ASC, planillacirugia.PlcCod DESC
                    LIMIT :limit OFFSET :offset";

            $stm = $this->pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stm->bindValue($k, $v, PDO::PARAM_STR);
            }
            $stm->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stm->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Count($filtros = [])
    {
        try {
            list($where, $params) = $this->ArmarFiltros($filtros);

            $sql = "SELECT count(*) " . $this->SqlBase() . " $where";

            $stm = $this->pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stm->bindValue($k, $v, PDO::PARAM_STR);
            }
            $stm->execute();
            return $stm->fetchColumn();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id)
    {
        try {
            $sql = "SELECT planillacirugia.*, presupuestos.EspCod, presupuestos.SueCod,
                           medicos.mediconombre, hospitales.HospDesc, vtavnd.VndNom
                    " . $this->SqlBase() . "
                    WHERE planillacirugia.PlcCod = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ObtenerPorPresupuesto($codPresupuesto)
    {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM planillacirugia WHERE cod_presupuesto = ? ORDER BY PlcCod DESC LIMIT 1");
            $stm->execute(array($codPresupuesto));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    // Crea o actualiza la planilla de cirugía asociada a un Presupuesto autorizado,
    // al momento en que el Coordinador programa/cambia la fecha de CX.
    public function GuardarDesdePresupuesto($codPresupuesto, $fecha, $hora, $vndCod, $paciente, $codMedico, $hospCod, $materiales)
    {
        try {
            $existente = $this->ObtenerPorPresupuesto($codPresupuesto);

            if ($existente) {
                $sql = "UPDATE planillacirugia SET
                            PlcFec = ?, PlcHor = ?, PlcCor = ?, PlcPac = ?, PlcMed = ?, PlcSer = ?, PlcMat = ?
                        WHERE PlcCod = ?";
                $this->pdo->prepare($sql)->execute(array(
                    $fecha, $hora, $vndCod, $paciente, $codMedico, $hospCod, $materiales, $existente->PlcCod
                ));
                return $existente->PlcCod;
            }

            $sql = "INSERT INTO planillacirugia
                        (PlcFec, PlcHor, PlcCor, PlcPac, PlcMed, PlcSer, PlcMat, cod_presupuesto,
                         PlcCxRea, PlcNroFac, PlcNroOp, PlcRx, PlcPro, PlcPap, PlcCons, PlcUsuNotAteAtn)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'N', '', 0, 'N', 'N', 'N', 'N', '')";
            $this->pdo->prepare($sql)->execute(array(
                $fecha, $hora, $vndCod, $paciente, $codMedico, $hospCod, $materiales, $codPresupuesto
            ));
            return $this->pdo->lastInsertId();
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarConsumo($plcCod, $texto)
    {
        try {
            $stm = $this->pdo->prepare("UPDATE planillacirugia SET PlcMatCx = ? WHERE PlcCod = ?");
            $stm->execute(array($texto, $plcCod));
            return true;
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function MarcarRealizada($plcCod)
    {
        try {
            $stm = $this->pdo->prepare("UPDATE planillacirugia SET PlcCxRea = 'S' WHERE PlcCod = ?");
            $stm->execute(array($plcCod));
            return true;
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function DetalleProductos($codPresupuesto)
    {
        try {
            $sql = "SELECT d.item, d.cantidad, d.p_unitario, p.producto_titulo
                    FROM detalles_presupuesto d
                    LEFT JOIN productos p ON d.det_producto = p.cod_producto
                    WHERE d.cod_presupuesto = ?
                    ORDER BY d.item";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($codPresupuesto));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
