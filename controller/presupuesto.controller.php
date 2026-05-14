<?php
require_once 'model/presupuesto.php';

class PresupuestoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new Presupuesto();
    }
    
    public function Index(){
            
        require_once 'view/header.php';        
        require_once 'view/presupuesto/Presupuesto.php';
        require_once 'view/footer.php';
    }
    
    public function Crud(){
        $alm = new Presupuesto();
        
        if(isset($_REQUEST['id'])){
            $alm = $this->model->Obtener($_REQUEST['id']);
        } else {
            $alm->cod_presupuesto = $this->model->ProximoNro();
            $alm->fecha = date('d/m/Y');
        }
        
        require_once 'view/header.php';
        require_once 'view/presupuesto/presupuesto-editar.php';
        require_once 'view/footer.php';
    }

    public function Guardar(){
        $alm = new Presupuesto();
        
        $alm->cod_presupuesto = $_REQUEST['cod_presupuesto'];
        $alm->cod_cliente = $_REQUEST['cod_cliente'];
        $alm->cod_medico = $_REQUEST['cod_medico'];
        $alm->fecha = $_REQUEST['fecha'];
        $alm->fecha_validez = $_REQUEST['fecha_validez'];
        $alm->f_pago = $_REQUEST['f_pago'];
        $alm->plazo = $_REQUEST['plazo'];
        $alm->Licitacion_Nro = isset($_REQUEST['Licitacion_Nro']) ? 1 : 0;
        $alm->PresupuestoPaciente = $_REQUEST['PresupuestoPaciente'];
        $alm->PresupDisAlt = $_REQUEST['PresupDisAlt'];
        $alm->CprCod = $_REQUEST['CprCod'];
        $alm->PresupVndCom = $_REQUEST['PresupVndCom'];
        $alm->PresupFecSeg = $_REQUEST['PresupFecSeg'];
        $alm->PresupHorSeg = $_REQUEST['PresupHorSeg'];
        $alm->PresupRel = $_REQUEST['PresupRel'];
        $alm->Expendiente_nro = $_REQUEST['Expendiente_nro'];
        $alm->PresupEnviadoMail = isset($_REQUEST['PresupEnviadoMail']) ? 1 : 0;

        $detalles = [];
        if(isset($_REQUEST['det_cod_producto'])) {
            foreach($_REQUEST['det_cod_producto'] as $key => $val) {
                if(empty($val)) continue;
                $detalles[] = [
                    'cod_producto' => $val,
                    'detalle' => $_REQUEST['det_detalle'][$key],
                    'cantidad' => $_REQUEST['det_cantidad'][$key],
                    'importe' => $_REQUEST['det_importe'][$key],
                    'alt' => isset($_REQUEST['det_alt'][$key]) ? 'S' : 'N'
                ];
            }
        }

        $this->model->Guardar($alm, $detalles);
        
        header('Location: index.php?c=presupuesto');
    }

    public function PreviewPDF() {
        require_once 'fpdf/fpdf.php';
        
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        
        // Background
        $background_path = 'assets/sheet/Presupuesto_page-0001.jpg';
        if(file_exists($background_path)) {
            $pdf->Image($background_path, 0, 0, 210, 297);
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Top Right: NRO & FECHA
        $nro = isset($_REQUEST['cod_presupuesto']) && $_REQUEST['cod_presupuesto'] ? $_REQUEST['cod_presupuesto'] : 'BORRADOR';
        $pdf->SetXY(145, 15);
        $pdf->Cell(50, 5, $nro, 0, 0, 'L');
        
        $pdf->SetXY(145, 20);
        $pdf->Cell(50, 5, $_REQUEST['fecha'], 0, 0, 'L');

        // Fetch Cliente Data
        $cliente_nombre = 'Sin Datos';
        $domicilio = '';
        $localidad = '';
        $cuit = '';
        $condicion_iva = '';
        if(!empty($_REQUEST['cod_cliente'])) {
            require_once 'model/clientes.php';
            $clienteModel = new Clientes();
            $cliente = $clienteModel->Obtener($_REQUEST['cod_cliente']);
            if($cliente) {
                $cliente_nombre = $cliente->nombre;
                $domicilio = isset($cliente->direccion) ? $cliente->direccion : '';
                $localidad = isset($cliente->localidad) ? $cliente->localidad : '';
                $cuit = isset($cliente->cuit) ? $cliente->cuit : '';
                $condicion_iva = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }

        // Middle Left: Cliente
        $pdf->SetXY(45, 59);
        $pdf->Cell(80, 5, utf8_decode($cliente_nombre), 0, 0, 'L');
        $pdf->SetXY(45, 64);
        $pdf->Cell(80, 5, utf8_decode($domicilio), 0, 0, 'L');
        $pdf->SetXY(45, 69);
        $pdf->Cell(80, 5, utf8_decode($condicion_iva), 0, 0, 'L');
        
        // Middle Right: Localidad & CUIT
        $pdf->SetXY(145, 59);
        $pdf->Cell(50, 5, utf8_decode($localidad), 0, 0, 'L');
        $pdf->SetXY(145, 64);
        $pdf->Cell(50, 5, utf8_decode($cuit), 0, 0, 'L');

        // Fetch Medico
        $medico_nombre = 'Sin Datos';
        if(!empty($_REQUEST['cod_medico'])) {
            require_once 'model/medicos.php';
            $medicoModel = new Medicos();
            $medico = $medicoModel->Obtener($_REQUEST['cod_medico']);
            if($medico) {
                $medico_nombre = $medico->mediconombre;
            }
        }

        // Lower Middle Left: Paciente & Fecha/Hora Ap.
        $pdf->SetXY(45, 80);
        $pdf->Cell(80, 5, utf8_decode($_REQUEST['PresupuestoPaciente']), 0, 0, 'L');
        $pdf->SetXY(45, 85);
        $fecha_hora_ap = $_REQUEST['PresupFecSeg'] . ' ' . $_REQUEST['PresupHorSeg'];
        $pdf->Cell(80, 5, utf8_decode($fecha_hora_ap), 0, 0, 'L');
        
        // Lower Middle Right: Doctor & Institucion
        $pdf->SetXY(145, 80);
        $pdf->Cell(60, 5, utf8_decode($medico_nombre), 0, 0, 'L');
        $pdf->SetXY(145, 85);
        $pdf->Cell(60, 5, utf8_decode('Hospital / Clinica'), 0, 0, 'L'); // Placeholder until we link hospital properly

        // Grid Details
        $pdf->SetXY(10, 102);
        $y = 102;
        $pdf->SetFont('Arial', '', 9);
        if(isset($_REQUEST['det_cod_producto'])) {
            foreach($_REQUEST['det_cod_producto'] as $key => $val) {
                if(empty($val)) continue;
                
                $pdf->SetXY(12, $y);
                $pdf->Cell(20, 5, $val, 0, 0, 'C'); // ITEM (Product ID)
                
                $pdf->SetXY(43, $y);
                $pdf->Cell(15, 5, $_REQUEST['det_cantidad'][$key], 0, 0, 'C'); // CANT
                
                $pdf->SetXY(60, $y);
                $pdf->Cell(95, 5, utf8_decode($_REQUEST['det_detalle'][$key]), 0, 0, 'L'); // DESCRIPCION
                
                $pdf->SetXY(156, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($_REQUEST['det_importe'][$key], 2), 0, 0, 'R'); // UNITARIO
                
                $pdf->SetXY(178, $y);
                $total = $_REQUEST['det_cantidad'][$key] * $_REQUEST['det_importe'][$key];
                $pdf->Cell(20, 5, '$ ' . number_format($total, 2), 0, 0, 'R'); // TOTAL
                
                $y += 6;
            }
        }

        // Bottom Left: Validez, Forma Pago, Plazo
        $pdf->SetXY(45, 260);
        $pdf->Cell(60, 5, $_REQUEST['fecha_validez'], 0, 0, 'L');
        
        // Forma de pago name
        $fpago_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscapagos() as $p) {
            if($p->FfaCod == $_REQUEST['f_pago']) {
                $fpago_name = $p->FfaDesc;
                break;
            }
        }
        
        $pdf->SetXY(45, 265);
        $pdf->Cell(60, 5, utf8_decode($fpago_name), 0, 0, 'L');
        
        $pdf->SetXY(45, 270);
        $pdf->Cell(60, 5, utf8_decode($_REQUEST['plazo']), 0, 0, 'L');

        // Bottom Center: Observaciones
        $pdf->SetXY(105, 260);
        $pdf->MultiCell(90, 4, utf8_decode($_REQUEST['PresupVndCom']), 0, 'L');

        $filename = 'scratch/temp_presupuesto_' . time() . '.pdf';
        $pdf->Output('F', $filename);

        header('Content-Type: application/json');
        echo json_encode(['url' => $filename]);
        exit;
    }
    public function VerPDF() {
        if(!isset($_REQUEST['id'])) {
            die("ID no especificado");
        }
        
        $alm = $this->model->Obtener($_REQUEST['id']);
        if(!$alm) {
            die("Presupuesto no encontrado");
        }

        require_once 'fpdf/fpdf.php';
        
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        
        // Background
        $background_path = 'assets/sheet/Presupuesto_page-0001.jpg';
        if(file_exists($background_path)) {
            $pdf->Image($background_path, 0, 0, 210, 297);
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Top Right: NRO & FECHA
        $pdf->SetXY(145, 15);
        $pdf->Cell(50, 5, $alm->cod_presupuesto, 0, 0, 'L');
        
        $pdf->SetXY(145, 20);
        $pdf->Cell(50, 5, date('d/m/Y', strtotime($alm->fecha)), 0, 0, 'L');

        // Fetch Cliente Data
        $cliente_nombre = 'Sin Datos';
        $domicilio = '';
        $localidad = '';
        $cuit = '';
        $condicion_iva = '';
        if(!empty($alm->cod_cliente)) {
            require_once 'model/clientes.php';
            $clienteModel = new Clientes();
            $cliente = $clienteModel->Obtener($alm->cod_cliente);
            if($cliente) {
                $cliente_nombre = $cliente->nombre;
                $domicilio = isset($cliente->direccion) ? $cliente->direccion : '';
                $localidad = isset($cliente->localidad) ? $cliente->localidad : '';
                $cuit = isset($cliente->cuit) ? $cliente->cuit : '';
                $condicion_iva = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }

        // Middle Left: Cliente
        $pdf->SetXY(45, 59);
        $pdf->Cell(80, 5, utf8_decode($cliente_nombre), 0, 0, 'L');
        $pdf->SetXY(45, 64);
        $pdf->Cell(80, 5, utf8_decode($domicilio), 0, 0, 'L');
        $pdf->SetXY(45, 69);
        $pdf->Cell(80, 5, utf8_decode($condicion_iva), 0, 0, 'L');
        
        // Middle Right: Localidad & CUIT
        $pdf->SetXY(145, 59);
        $pdf->Cell(50, 5, utf8_decode($localidad), 0, 0, 'L');
        $pdf->SetXY(145, 64);
        $pdf->Cell(50, 5, utf8_decode($cuit), 0, 0, 'L');

        // Fetch Medico
        $medico_nombre = 'Sin Datos';
        if(!empty($alm->cod_medico)) {
            require_once 'model/medicos.php';
            $medicoModel = new Medicos();
            $medico = $medicoModel->Obtener($alm->cod_medico);
            if($medico) {
                $medico_nombre = $medico->mediconombre;
            }
        }

        // Lower Middle Left: Paciente & Fecha/Hora Ap.
        $pdf->SetXY(45, 80);
        $pdf->Cell(80, 5, utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
        $pdf->SetXY(45, 85);
        $fecha_hora_ap = $alm->PresupFecSeg . ' ' . $alm->PresupHorSeg;
        $pdf->Cell(80, 5, utf8_decode($fecha_hora_ap), 0, 0, 'L');
        
        // Lower Middle Right: Doctor & Institucion
        $pdf->SetXY(145, 80);
        $pdf->Cell(60, 5, utf8_decode($medico_nombre), 0, 0, 'L');
        $pdf->SetXY(145, 85);
        $pdf->Cell(60, 5, utf8_decode('Hospital / Clinica'), 0, 0, 'L');

        // Grid Details
        $pdf->SetXY(10, 102);
        $y = 102;
        $pdf->SetFont('Arial', '', 9);
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $pdf->SetXY(12, $y);
                $pdf->Cell(20, 5, $d->cod_producto, 0, 0, 'C'); 
                
                $pdf->SetXY(43, $y);
                $pdf->Cell(15, 5, $d->cantidad, 0, 0, 'C'); 
                
                $pdf->SetXY(60, $y);
                $pdf->Cell(95, 5, utf8_decode($d->detalle_ag), 0, 0, 'L'); 
                
                $pdf->SetXY(156, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->p_unitario, 2), 0, 0, 'R'); 
                
                $pdf->SetXY(178, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->importe, 2), 0, 0, 'R'); 
                
                $y += 6;
            }
        }

        // Bottom Left: Validez, Forma Pago, Plazo
        $pdf->SetXY(45, 260);
        $pdf->Cell(60, 5, date('d/m/Y', strtotime($alm->fecha_validez)), 0, 0, 'L');
        
        // Forma de pago name
        $fpago_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscapagos() as $p) {
            if($p->FfaCod == $alm->f_pago) {
                $fpago_name = $p->FfaDesc;
                break;
            }
        }
        
        $pdf->SetXY(45, 265);
        $pdf->Cell(60, 5, utf8_decode($fpago_name), 0, 0, 'L');
        
        $pdf->SetXY(45, 270);
        $pdf->Cell(60, 5, utf8_decode($alm->plazo), 0, 0, 'L');

        // Bottom Center: Observaciones
        $pdf->SetXY(105, 260);
        $pdf->MultiCell(90, 4, utf8_decode($alm->PresupVndCom), 0, 'L');

        $pdf->Output('I', 'Presupuesto_' . $alm->cod_presupuesto . '.pdf');
    }
}
?>