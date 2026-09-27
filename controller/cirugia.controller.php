<?php
require_once 'model/planillacirugia.php';
require_once 'model/presupuesto.php';
require_once 'model/vtavnd.php';
require_once 'model/facturacion.php';

class CirugiaController {
    private $model;
    private $presupuestoModel;
    private $vtavnd;
    private $facturacionModel;

    public function __CONSTRUCT() {
        $this->model = new Planillacirugia();
        $this->presupuestoModel = new Presupuesto();
        $this->vtavnd = new Vtavnd();
        $this->facturacionModel = new Facturacion();
    }

    private function UsuarioActual() {
        return isset($_SESSION['user']['UsrCod']) ? $_SESSION['user']['UsrCod'] : '';
    }

    // Resuelve el VndCod del coordinador logueado. Un Administrador ve todo (null = sin filtro).
    private function VndCodDeSesion() {
        if (EsAdmin()) {
            return null;
        }
        $usrCod = $this->UsuarioActual();
        $coordinador = $this->vtavnd->ObtenerPorUsuario($usrCod);
        return $coordinador ? $coordinador->VndCod : 0; // 0 = no matchea ningún VndCod real, lista vacía
    }

    private function Filtros() {
        return [
            'estado'     => isset($_REQUEST['estado']) && $_REQUEST['estado'] !== '' ? $_REQUEST['estado'] : 'autorizadas',
            'vndCod'     => isset($_REQUEST['vndCod']) ? $_REQUEST['vndCod'] : '',
            'sueCod'     => isset($_REQUEST['sueCod']) ? $_REQUEST['sueCod'] : '',
            'paciente'   => isset($_REQUEST['paciente']) ? $_REQUEST['paciente'] : '',
            'medico'     => isset($_REQUEST['medico']) ? $_REQUEST['medico'] : '',
            'hospCod'    => isset($_REQUEST['hospCod']) ? $_REQUEST['hospCod'] : '',
            'fechaDesde' => isset($_REQUEST['fechaDesde']) ? $_REQUEST['fechaDesde'] : '',
            'fechaHasta' => isset($_REQUEST['fechaHasta']) ? $_REQUEST['fechaHasta'] : '',
        ];
    }

    public function Index() {
        $filtros = $this->Filtros();

        $page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        if ($filtros['estado'] === 'autorizadas') {
            $vndCod = $this->VndCodDeSesion();
            $items = $this->presupuestoModel->ListarParaCoordinador($vndCod, $filtros, $limit, $offset);
            $total = $this->presupuestoModel->ContarParaCoordinador($vndCod, $filtros);
        } else {
            $items = $this->model->Listar($filtros, $limit, $offset);
            $total = $this->model->Count($filtros);
        }
        $pages = ceil($total / $limit);

        $hospitales = $this->presupuestoModel->buscainstitucion();
        $coordinadores = $this->presupuestoModel->ListarCoordinadores();
        $subestados = $this->presupuestoModel->ListarSubestadosAutorizado();
        $motivosAnulacion = $this->presupuestoModel->ListarMotivosAnulacion();

        require_once 'view/header.php';
        require_once 'view/cirugia/index.php';
        require_once 'view/footer.php';
    }

    // Acepta un presupuesto autorizado y programa (o reprograma) la fecha/hora de CX
    // y los materiales a necesitar (texto libre). Crea/actualiza la fila en planillacirugia.
    public function Aceptar() {
        header('Content-Type: application/json');
        try {
            $id = (int)$_REQUEST['cod_presupuesto'];
            $fecha = $_REQUEST['fecha'];
            $hora = isset($_REQUEST['hora']) ? $_REQUEST['hora'] : '';
            $materiales = isset($_REQUEST['materiales']) ? $_REQUEST['materiales'] : '';

            if (empty($fecha)) {
                throw new Exception('Debe indicar la fecha de la cirugía.');
            }

            $datos = $this->presupuestoModel->Obtener($id);
            if (!$datos) {
                throw new Exception('Presupuesto no encontrado.');
            }

            $this->presupuestoModel->ProgramarCx($id, $fecha, $hora, 11); // SueCod=11 Fecha Confirmada de CX

            $this->model->GuardarDesdePresupuesto(
                $id,
                $fecha,
                $hora,
                $datos->VndCod,
                $datos->PresupuestoPaciente,
                $datos->cod_medico,
                $datos->HospCod,
                $materiales
            );

            echo json_encode(['success' => true, 'message' => 'Cirugía aceptada y programada correctamente.']);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // Rechaza un presupuesto autorizado (lo anula, igual que desde el panel de Presupuesto).
    public function Rechazar() {
        header('Content-Type: application/json');
        try {
            $id = (int)$_REQUEST['cod_presupuesto'];
            $sueCod = (int)$_REQUEST['SueCod'];
            $comentario = isset($_REQUEST['PresupVndCom']) ? $_REQUEST['PresupVndCom'] : '';

            if (empty($sueCod)) {
                throw new Exception('Debe indicar el motivo del rechazo.');
            }

            $this->presupuestoModel->AnularPresupuesto($id, $sueCod, $comentario);

            echo json_encode(['success' => true, 'message' => 'Presupuesto rechazado correctamente.']);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function MarcarRealizada() {
        header('Content-Type: application/json');
        try {
            $plcCod = (int)$_REQUEST['PlcCod'];
            $codPresupuesto = (int)$_REQUEST['cod_presupuesto'];

            $this->model->MarcarRealizada($plcCod);
            $this->presupuestoModel->MarcarCxRealizada($codPresupuesto);

            echo json_encode(['success' => true, 'message' => 'Cirugía marcada como realizada.']);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // Guarda el consumo cargado en texto libre (PlcMatCx).
    public function GuardarConsumo() {
        header('Content-Type: application/json');
        try {
            $plcCod = (int)$_REQUEST['PlcCod'];
            $consumo = isset($_REQUEST['Consumo']) ? $_REQUEST['Consumo'] : '';

            $this->model->ActualizarConsumo($plcCod, $consumo);

            echo json_encode(['success' => true, 'message' => 'Consumo guardado correctamente.']);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function ListarFacturasJson() {
        header('Content-Type: application/json');
        try {
            $plcCod = (int)$_REQUEST['PlcCod'];
            $facturas = $this->facturacionModel->ListarFacturas($plcCod);
            echo json_encode(['success' => true, 'facturas' => $facturas]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function GuardarFactura() {
        header('Content-Type: application/json');
        try {
            $plcCod = (int)$_REQUEST['PlcCod'];
            $nroFac = $_REQUEST['NroFac'];
            $letraFac = $_REQUEST['TipoFac'];
            $fechaFac = $_REQUEST['FechaFac'];
            $importeFac = (float)$_REQUEST['ImporteFac'];

            if (empty($nroFac) || empty($fechaFac)) {
                throw new Exception('Debe indicar N° de factura y fecha.');
            }

            $this->facturacionModel->AgregarFactura($plcCod, $nroFac, $letraFac, $fechaFac, $importeFac, $this->UsuarioActual());
            echo json_encode(['success' => true, 'message' => 'Factura cargada correctamente.']);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function AnularFactura() {
        header('Content-Type: application/json');
        try {
            $facCod = (int)$_REQUEST['FacCod'];
            $this->facturacionModel->AnularFactura($facCod, $this->UsuarioActual());
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function EtiquetasPDF() {
        $plcCod = (int)$_REQUEST['id'];
        $plc = $this->model->Obtener($plcCod);
        if (!$plc) {
            die('Cirugía no encontrada');
        }
        $detalles = $this->model->DetalleProductos($plc->cod_presupuesto);

        require_once 'fpdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', [100, 60]); // etiqueta 100x60mm
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetMargins(4, 4, 4);

        $fechaCx = (!empty($plc->PlcFec) && $plc->PlcFec != '0000-00-00') ? date('d/m/Y', strtotime($plc->PlcFec)) : '';

        if (empty($detalles)) {
            $detalles = [(object)['producto_titulo' => '', 'cantidad' => '']];
        }

        foreach ($detalles as $d) {
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, 'Presupuesto N. ' . $plc->cod_presupuesto, 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(0, 5, 'Paciente: ' . utf8_decode((string)$plc->PlcPac), 0, 1);
            $pdf->Cell(0, 5, 'Fecha CX: ' . $fechaCx . '   Hora: ' . (string)$plc->PlcHor, 0, 1);
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->MultiCell(0, 6, utf8_decode('Contenido: ' . $d->producto_titulo));
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, 'Cantidad: ' . $d->cantidad, 0, 1);
        }

        $pdf->Output('I', 'Etiquetas_' . $plc->cod_presupuesto . '.pdf');
    }

    public function ReporteXlsx() {
        require_once 'vendor/autoload.php';

        $filtros = $this->Filtros();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($filtros['estado'] === 'autorizadas') {
            $vndCod = $this->VndCodDeSesion();
            $items = $this->presupuestoModel->ListarParaCoordinador($vndCod, $filtros, 5000, 0);

            $sheet->setTitle('Autorizadas');
            $headers = ['N° Pto', 'Paciente', 'Médico', 'Institución', 'Coordinador', 'Subestado', 'Fecha Autorización', 'Fecha CX', 'Hora CX'];
            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:I1')->getFont()->setBold(true);

            $row = 2;
            foreach ($items as $r) {
                $sheet->fromArray([
                    $r->cod_presupuesto,
                    $r->PresupuestoPaciente,
                    $r->mediconombre,
                    $r->HospDesc,
                    $r->VndNom,
                    $r->SueDes,
                    $r->PresupFecAut,
                    ($r->PresupFecSeg && $r->PresupFecSeg != '0000-00-00') ? $r->PresupFecSeg : '',
                    $r->PresupHorSeg,
                ], null, 'A' . $row);
                $row++;
            }
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        } else {
            $items = $this->model->Listar($filtros, 5000, 0);

            $sheet->setTitle('Panel de Cirugias');
            $headers = ['N° Pto', 'Paciente', 'Médico', 'Institución', 'Coordinador', 'Fecha CX', 'Hora CX', 'Materiales a necesitar', 'Estado'];
            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:I1')->getFont()->setBold(true);

            $row = 2;
            foreach ($items as $r) {
                $sheet->fromArray([
                    $r->cod_presupuesto,
                    $r->PlcPac,
                    $r->mediconombre,
                    $r->HospDesc,
                    $r->VndNom,
                    ($r->PlcFec && $r->PlcFec != '0000-00-00') ? $r->PlcFec : '',
                    $r->PlcHor,
                    $r->PlcMat,
                    ($r->PlcCxRea == 'S') ? 'Realizada' : 'Aceptada',
                ], null, 'A' . $row);
                $row++;
            }
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PanelDeCirugias_' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
