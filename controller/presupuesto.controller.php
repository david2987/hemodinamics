<?php
require_once 'model/presupuesto.php';

class PresupuestoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new Presupuesto();
    }
    
    public function Index(){
            
        require_once 'view/header.php';        
        require_once 'view/presupuesto/presupuesto.php';
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
        $alm->PresupuestoPaciente = $_REQUEST['PresupuestoPaciente'];
        $alm->CprCod = $_REQUEST['CprCod'];
        $alm->HospCod = !empty($_REQUEST['HospCod']) ? $_REQUEST['HospCod'] : null;
        $alm->PresupVndCom = $_REQUEST['PresupVndCom'];

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
        
        // If AJAX request, return JSON with the saved ID
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'id' => (int)$alm->cod_presupuesto]);
            exit;
        }
        
        header('Location: index.php?c=presupuesto');
    }

    private function ObtenerTituloProducto($cod_producto = 0 ) {     
        require_once 'model/productos.php';
        $pModel = new Productos();
        $producto = $pModel->Obtener($cod_producto);
        return $producto ? $producto->titulo : '';
    }

    public function PreviewPDF() {
        require_once 'fpdf/fpdf.php';
        
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false, 0);
        
        // === Preparar datos ===
        $background_path = 'assets/sheet/Presupuesto_page-0001.jpg';

        $nro = isset($_REQUEST['cod_presupuesto']) && $_REQUEST['cod_presupuesto'] ? $_REQUEST['cod_presupuesto'] : 'BORRADOR';

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
                $domicilio = isset($cliente->domicilio) ? $cliente->domicilio : '';
                $localidad = isset($cliente->localidad) ? $cliente->localidad : '';
                $cuit = isset($cliente->cuit) ? $cliente->cuit : '';
                $condicion_iva = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }

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

        // Pre-computar datos del footer
        $fpago_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscapagos() as $p) {
            if($p->FfaCod == $_REQUEST['f_pago']) {
                $fpago_name = $p->FfaDesc;
                break;
            }
        }

        $total_general = 0;
        if(isset($_REQUEST['det_cantidad']) && isset($_REQUEST['det_importe'])) {
            foreach($_REQUEST['det_cantidad'] as $key => $cantidad) {
                if(!empty($_REQUEST['det_importe'][$key])) {
                    $total_general += (float)$_REQUEST['det_importe'][$key] * (int)$cantidad;
                }
            }
        }

        // Pre-computar hospital
        $preview_hosp_name = '';
        $hospCodPreview = !empty($_REQUEST['HospCod']) ? (int)$_REQUEST['HospCod'] : 0;
        if ($hospCodPreview) {
            require_once 'model/presupuesto.php';
            $tmpModel = new Presupuesto();
            foreach($tmpModel->buscainstitucion() as $p) {
                if($p->HospCod == $hospCodPreview) {
                    $preview_hosp_name = $p->HospDesc;
                    break;
                }
            }
        }

        // === Closure: Renderizar encabezado (background + header) ===
        $renderHeader = function() use ($pdf, $background_path, $nro, $cliente_nombre, $domicilio, $condicion_iva, $localidad, $cuit, $medico_nombre, $preview_hosp_name) {
            // Background
            if(file_exists($background_path)) {
                $pdf->Image($background_path, 0, 0, 210, 297);
            }

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);

            // Top Right: NRO & FECHA
            $pdf->SetXY(155, 9.5);
            $pdf->Cell(50, 5, $nro, 0, 0, 'L');
            
            $pdf->SetXY(158, 13.5);
            $pdf->Cell(50, 5, $_REQUEST['fecha'], 0, 0, 'L');

            // Switch to regular font for client data values
            $pdf->SetFont('Arial', '', 9);

            // Middle Left: Cliente
            $pdf->SetXY(22, 45.5);
            $pdf->Cell(80, 5, utf8_decode($cliente_nombre), 0, 0, 'L');
            $pdf->SetXY(22, 50);
            $pdf->Cell(80, 5, utf8_decode($domicilio), 0, 0, 'L');
            $pdf->SetXY(25, 55);
            $pdf->Cell(80, 5, utf8_decode($condicion_iva), 0, 0, 'L');
            
            // Middle Right: Localidad & CUIT
            $pdf->SetXY(89, 50);
            $pdf->Cell(50, 5, utf8_decode($localidad), 0, 0, 'L');
            $pdf->SetXY(82, 55);
            $pdf->Cell(50, 5, utf8_decode($cuit), 0, 0, 'L');

            // Patient / Doctor section - regular font
            $pdf->SetFont('Arial', '', 9);

            // Lower Middle Left: Paciente
            $pdf->SetXY(20, 67);
            $pdf->Cell(80, 5, utf8_decode($_REQUEST['PresupuestoPaciente']), 0, 0, 'L');
            
            // Lower Middle Right: Doctor & Servicio
            $pdf->SetXY(84, 66.5);
            $pdf->Cell(85, 5, utf8_decode($medico_nombre), 0, 0, 'L');
            $pdf->SetXY(89, 72);
            $pdf->Cell(85, 5, utf8_decode($preview_hosp_name), 0, 0, 'L');
        };

        // === Closure: Renderizar footer ===
        $renderFooter = function() use ($pdf, $fpago_name, $total_general) {
            $pdf->SetFont('Arial', '', 9);

            // Bottom Left: Validez, Forma Pago, Plazo
            $pdf->SetXY(45, 260);
            $pdf->Cell(60, 5, $_REQUEST['fecha_validez'], 0, 0, 'L');
            
            $pdf->SetXY(45, 265);
            $pdf->Cell(60, 5, utf8_decode($fpago_name), 0, 0, 'L');
            
            $pdf->SetXY(45, 270);
            $pdf->Cell(60, 5, utf8_decode($_REQUEST['plazo']), 0, 0, 'L');
            
            // Bottom Right: Total
            $pdf->SetXY(145, 270);
            $pdf->Cell(50, 5, 'TOTAL: $ ' . number_format($total_general, 2), 0, 0, 'R');

            // Bottom Center: Observaciones
            $pdf->SetXY(109, 261.5);
            $pdf->MultiCell(90, 4, utf8_decode($_REQUEST['PresupVndCom']), 0, 'L');
        };

        // === Primera pagina ===
        $pdf->AddPage();
        $renderHeader();

        // === Grid Details ===
        $pdf->SetXY(10, 102);
        $y = 90;
        $pdf->SetFont('Arial', '', 7);
        if(isset($_REQUEST['det_cod_producto'])) {
            foreach($_REQUEST['det_cod_producto'] as $key => $val) {
                if(empty($val)) continue;
                
                // Estimar altura del item
                $text_check = utf8_decode($_REQUEST['det_detalle'][$key]);
                $pdf->SetFont('Arial', '', 7);
                $textWidth_check = $pdf->GetStringWidth($text_check);
                $numLines_check = max(1, ceil($textWidth_check / 80));
                $itemHeight_check = ($numLines_check * 5) + 2 + 6;

                // Verificar si necesitamos nueva pagina
                if ($y + $itemHeight_check > 249) {
                    $renderFooter();
                    $pdf->AddPage();
                    $renderHeader();
                    $y = 90;
                    $pdf->SetFont('Arial', '', 7);
                }

                $pdf->SetXY(2, $y);
                $pdf->Cell(20, 5, $key == 0 ? '1' : $key + 1, 0, 0, 'C'); 
                
                $pdf->SetXY(19, $y);
                $pdf->Cell(15, 5, $_REQUEST['det_cantidad'][$key], 0, 0, 'C'); 
                            
                $pdf->SetXY(152, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($_REQUEST['det_importe'][$key], 2), 0, 0, 'R'); 
                
                $pdf->SetXY(180, $y);
                $total = $_REQUEST['det_cantidad'][$key] * $_REQUEST['det_importe'][$key];
                $pdf->Cell(20, 5, '$ ' . number_format($total, 2), 0, 0, 'R'); 
                

                $pdf->SetXY(40, $y);
                $pdf->MultiCell(80, 5, utf8_decode($_REQUEST['det_detalle'][$key]), 0, 'L');             
                $y = $pdf->GetY() + 2;

                $y += 6;
                
            }
        }

        // Footer en la ultima pagina
        $renderFooter();

        $filename = 'scratch/Presupuesto_' . $_REQUEST['PresupuestoPaciente'] . '.pdf';
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
        $pdf->SetAutoPageBreak(false, 0);

        // === Preparar datos ===
        $background_path = 'assets/sheet/Presupuesto_page-0001.jpg';
        
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
                $domicilio = isset($cliente->domicilio) ? $cliente->domicilio : '';
                $localidad = isset($cliente->localidad) ? $cliente->localidad : '';
                $cuit = isset($cliente->cuit) ? $cliente->cuit : '';
                $condicion_iva = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }
        
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
        
        // Pre-computar datos del footer
        $fpago_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscapagos() as $p) {
            if($p->FfaCod == $alm->f_pago) {
                $fpago_name = $p->FfaDesc;
                break;
            }
        }
        
        $total_general = 0;
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $total_general += (float)$d->importe;
            }
        }

        // Pre-computar hospital
        $ver_hosp_name = '';
        if (!empty($alm->HospCod)) {
            require_once 'model/presupuesto.php';
            $tmpModel = new Presupuesto();
            foreach($tmpModel->buscainstitucion() as $p) {
                if($p->HospCod == $alm->HospCod) {
                    $ver_hosp_name = $p->HospDesc;
                    break;
                }
            }
        }

        // === Closure: Renderizar encabezado (background + header) ===
        $renderHeader = function() use ($pdf, $background_path, $alm, $cliente_nombre, $domicilio, $condicion_iva, $localidad, $cuit, $medico_nombre, $ver_hosp_name) {
            // Background
            if(file_exists($background_path)) {
                $pdf->Image($background_path, 0, 0, 210, 297);
            }
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);
            
            // Top Right: NRO & FECHA
            $pdf->SetXY(155, 9.5);
            $pdf->Cell(50, 5, $alm->cod_presupuesto, 0, 0, 'L');
            
            $pdf->SetXY(158, 13.5);
            $pdf->Cell(50, 5, date('d/m/Y', strtotime($alm->fecha)), 0, 0, 'L');
            
            // Switch to regular font for client data values
            $pdf->SetFont('Arial', '', 9);
            
            // Middle Left: Cliente
            $pdf->SetXY(22, 45.5);
            $pdf->Cell(80, 5, utf8_decode($cliente_nombre), 0, 0, 'L');
            $pdf->SetXY(22, 50);
            $pdf->Cell(80, 5, utf8_decode($domicilio), 0, 0, 'L');
            $pdf->SetXY(25, 55);
            $pdf->Cell(80, 5, utf8_decode($condicion_iva), 0, 0, 'L');
            
            // Middle Right: Localidad & CUIT
            $pdf->SetXY(89, 50);
            $pdf->Cell(50, 5, utf8_decode($localidad), 0, 0, 'L');
            $pdf->SetXY(82, 55);
            $pdf->Cell(50, 5, utf8_decode($cuit), 0, 0, 'L');
            
            // Patient / Doctor section - regular font
            $pdf->SetFont('Arial', '', 9);
            
            // Lower Middle Left: Paciente & Fecha/Hora Ap.
            $pdf->SetXY(20, 67);
            $pdf->Cell(80, 5, utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
            $pdf->SetXY(29, 72);
            $fecha_hora_ap = $alm->PresupFecSeg . ' ' . $alm->PresupHorSeg;
            $pdf->Cell(80, 5, utf8_decode($fecha_hora_ap), 0, 0, 'L');
            
            // Lower Middle Right: Doctor & Servicio
            $pdf->SetXY(84, 66.5);
            $pdf->Cell(85, 5, utf8_decode($medico_nombre), 0, 0, 'L');
            $pdf->SetXY(89, 72);
            $pdf->Cell(85, 5, utf8_decode($ver_hosp_name), 0, 0, 'L');
        };

        // === Closure: Renderizar footer ===
        $renderFooter = function() use ($pdf, $alm, $fpago_name, $total_general) {
            $pdf->SetFont('Arial', '', 9);

            // Bottom Left: Validez, Forma Pago, Plazo
            $pdf->SetXY(45, 260);
            $pdf->Cell(60, 5, date('d/m/Y', strtotime($alm->fecha_validez)), 0, 0, 'L');
            
            $pdf->SetXY(45, 265);
            $pdf->Cell(60, 5, utf8_decode($fpago_name), 0, 0, 'L');
            
            $pdf->SetXY(45, 270);
            $pdf->Cell(60, 5, utf8_decode($alm->plazo), 0, 0, 'L');
            
            // Bottom Right: Total
            $pdf->SetXY(145, 270);
            $pdf->Cell(50, 5, 'TOTAL: $ ' . number_format($total_general, 2), 0, 0, 'R');
            
            // Bottom Center: Observaciones
            $pdf->SetXY(105, 260);
            $pdf->MultiCell(90, 4, utf8_decode($alm->PresupVndCom), 0, 'L');
        };
        
        // === Primera pagina ===
        $pdf->AddPage();
        $renderHeader();
        
        // === Grid Details ===
        $pdf->SetXY(10, 102);
        $y = 90;
        $pdf->SetFont('Arial', '', 9);
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                // Estimar altura del item
                $text_check = utf8_decode($d->detalle_ag);
                $pdf->SetFont('Arial', '', 9);
                $textWidth_check = $pdf->GetStringWidth($text_check);
                $numLines_check = max(1, ceil($textWidth_check / 95));
                $itemHeight_check = ($numLines_check * 5) + 2 + 6;

                // Verificar si necesitamos nueva pagina
                if ($y + $itemHeight_check > 249) {
                    $renderFooter();
                    $pdf->AddPage();
                    $renderHeader();
                    $y = 90;
                    $pdf->SetFont('Arial', '', 9);
                }

                $pdf->SetXY(2, $y);
                $pdf->Cell(20, 5, $d->item, 0, 0, 'C'); 
                
                $pdf->SetXY(19, $y);
                $pdf->Cell(15, 5, $d->cantidad, 0, 0, 'C'); 
                                
                $pdf->SetXY(158, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->p_unitario, 2), 0, 0, 'R'); 
                
                $pdf->SetXY(181, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->importe, 2), 0, 0, 'R'); 

                $pdf->SetXY(40, $y);
                $pdf->MultiCell(95, 5, utf8_decode($d->detalle_ag), 0, 'L');
                //$pdf->Cell(95, 5, utf8_decode($d->detalle_ag), 0, 0, 'L'); 
                $y = $pdf->GetY() + 2;
               
                
                $y += 6;
            }
        }
        
        // Footer en la ultima pagina
        $renderFooter();
        
        // If download=1 parameter present, force file download; otherwise show inline
        $disposition = (isset($_REQUEST['download']) && $_REQUEST['download'] == '1') ? 'D' : 'I';
        $pdf->Output($disposition, 'Presupuesto_' . $alm->PresupuestoPaciente . '.pdf');
    }
    
    public function RemitoPDF() {
        if(!isset($_REQUEST['id'])) {
            die("ID no especificado");
        }
        
        // Verify that the budget is authorized (EspCod = 3)
        $alm = $this->model->Obtener($_REQUEST['id']);
        if(!$alm) {
            die("Presupuesto no encontrado");
        }
        
        // Only allow remito for authorized budgets
        if($alm->EspCod != 3) {
            die("Solo se puede generar remito para presupuestos autorizados");
        }

        // Use hospCod from modal if provided, otherwise fallback to existing
        $hospCod = !empty($_REQUEST['hospCod']) ? (int)$_REQUEST['hospCod'] : $alm->HospCod;

        // Save the service only when a different one was selected
        if ($hospCod && $hospCod != $alm->HospCod) {
            $this->model->UpdateHospCod($_REQUEST['id'], $hospCod);
        }

        // Use fecha_remito from modal if provided, otherwise fallback to original
        $fecha_remito = !empty($_REQUEST['fecha_remito']) ? $_REQUEST['fecha_remito'] : $alm->fecha;
        
        require_once 'fpdf/fpdf.php';
        
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false, 0);

        // === Preparar datos ===
        
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
                $domicilio = isset($cliente->domicilio) ? $cliente->domicilio : '';
                $localidad = isset($cliente->localidad) ? $cliente->localidad : '';
                $cuit = isset($cliente->cuit) ? $cliente->cuit : '';
                $condicion_iva = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }
        
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

        // Pre-computar datos del footer
        $fpago_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscapagos() as $p) {
            if($p->FfaCod == $alm->f_pago) {
                $fpago_name = $p->FfaDesc;
                break;
            }
        }

         // Pre-computar datos del footer
        $hosp_name = '';
        require_once 'model/presupuesto.php';
        $pModel = new Presupuesto();
        foreach($pModel->buscainstitucion() as $p) {
            if($p->HospCod == $hospCod) {
                $hosp_name = $p->HospDesc;
                break;
            }
        }
              
        $total_general = 0;
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $total_general += (float)$d->importe;
            }
        }

        // === Closure: Renderizar encabezado (sin background para remito) ===
        $renderHeader = function() use ($pdf, $alm, $cliente_nombre,$fpago_name, $domicilio, $condicion_iva, $localidad, $cuit, $medico_nombre, $fecha_remito) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            // Top Right: NRO & FECHA (same positioning as VerPDF)
            // $pdf->SetXY(165, 10.5);
            // $pdf->Cell(50, 5, $alm->cod_presupuesto, 0, 0, 'L');
            
            $pdf->SetXY(159, 30);
            $pdf->Cell(50, 5, date('d    m    Y', strtotime($fecha_remito)), 0, 0, 'L');
            
            // Switch to regular font for client data values
            $pdf->SetFont('Arial', '', 9);
            
            // Middle Left: Cliente
            $pdf->SetXY(40, 61);
            $pdf->Cell(80, 5, utf8_decode($cliente_nombre), 0, 0, 'L');
            $pdf->SetXY(40, 68);
            $pdf->Cell(80, 5, utf8_decode($domicilio), 0, 0, 'L');
            $pdf->SetXY(60, 84);
            $pdf->Cell(80, 5, utf8_decode($fpago_name), 0, 0, 'L');
            
            // Middle Right: Localidad & CUIT
            $pdf->SetXY(160, 68);
            $pdf->Cell(50, 5, utf8_decode($localidad), 0, 0, 'L');
            $pdf->SetXY(160, 74);
            $pdf->Cell(50, 5, utf8_decode($cuit), 0, 0, 'L');
            
            // Patient / Doctor section - regular font
            $pdf->SetFont('Arial', '', 9);
            
            // Lower Middle Left: Paciente & Fecha/Hora Ap.
            // $pdf->SetXY(20, 67);
            // $pdf->Cell(80, 5, utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
            // $pdf->SetXY(29, 72);
            // $fecha_hora_ap = $alm->PresupFecSeg . ' ' . $alm->PresupHorSeg;
            // $pdf->Cell(80, 5, utf8_decode($fecha_hora_ap), 0, 0, 'L');
            
            // Lower Middle Right: Doctor & Institucion
            // $pdf->SetXY(84, 66.5);
            // $pdf->Cell(85, 5, utf8_decode($medico_nombre), 0, 0, 'L');
            // $pdf->SetXY(89, 72);
            // $pdf->Cell(85, 5, utf8_decode('Hospital / Clinica'), 0, 0, 'L');
        };

        // === Closure: Renderizar footer ===
        $renderFooter = function() use ($pdf, $alm, $fpago_name, $total_general,$medico_nombre,$hosp_name) {
            $pdf->SetFont('Arial', '', 9);

            // Bottom Left: Validez, Forma Pago, Plazo
            $pdf->SetXY(57, 250);
            $pdf->Cell(60, 5,"PACIENTE: " . utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
            
            $pdf->SetXY(57, 255);
            $pdf->Cell(60, 5, "MEDICO: " . utf8_decode($medico_nombre), 0, 0, 'L');
            
            $pdf->SetXY(57, 260);
            $pdf->Cell(60, 5, "SERVICIO: " . utf8_decode($hosp_name), 0, 0, 'L');
            
            // Bottom Right: Total
            // $pdf->SetXY(145, 270);
            // $pdf->Cell(50, 5, 'TOTAL: $ ' . number_format($total_general, 2), 0, 0, 'R');
            
            // Bottom Center: Observaciones
            // $pdf->SetXY(105, 260);
            // $pdf->MultiCell(90, 4, utf8_decode($alm->PresupVndCom), 0, 'L');
        };
        
        // === Primera pagina ===
        $pdf->AddPage();
        $renderHeader();
        
        // === Grid Details ===
        $pdf->SetXY(10, 102);
        $y = 102;
        $pdf->SetFont('Arial', '', 9);
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                // Estimar altura del item
                $text_check = utf8_decode($d->detalle_ag);
                $pdf->SetFont('Arial', '', 9);
                $textWidth_check = $pdf->GetStringWidth($text_check);
                $numLines_check = max(1, ceil($textWidth_check / 95));
                $itemHeight_check = ($numLines_check * 5) + 2 + 6;

                // Verificar si necesitamos nueva pagina
                if ($y + $itemHeight_check > 249) {
                    $renderFooter();
                    $pdf->AddPage();
                    $renderHeader();
                    $y = 90;
                    $pdf->SetFont('Arial', '', 9);
                }

                $pdf->SetXY(19, $y);
                $pdf->Cell(20, 5, $d->item, 0, 0, 'C'); 
                
                $pdf->SetXY(36, $y);
                $pdf->Cell(15, 5, $d->cantidad, 0, 0, 'C'); 
                                
                // $pdf->SetXY(160, $y);
                // $pdf->Cell(20, 5, '$ ' . number_format($d->p_unitario, 2), 0, 0, 'R'); 
                
                // $pdf->SetXY(181, $y);
                // $pdf->Cell(20, 5, '$ ' . number_format($d->importe, 2), 0, 0, 'R'); 

                $pdf->SetXY(57, $y);
                $pdf->MultiCell(95, 5, utf8_decode($d->detalle_ag), 0, 'L');
                //$pdf->Cell(95, 5, utf8_decode($d->detalle_ag), 0, 0, 'L'); 
                $y = $pdf->GetY() + 2;
               
                
                $y += 6;
            }
        }
        
        // Footer en la ultima pagina
        $renderFooter();
        
        // If download=1 parameter present, force file download; otherwise show inline
        $disposition = (isset($_REQUEST['download']) && $_REQUEST['download'] == '1') ? 'D' : 'I';
        $pdf->Output($disposition, 'Remito_' . $alm->PresupuestoPaciente . '.pdf');
    }
    
    public function CaratulaPDF() {
        if(!isset($_REQUEST['id'])) {
            die("ID no especificado");
        }
        
        $alm = $this->model->Obtener($_REQUEST['id']);
        if(!$alm) {
            die("Presupuesto no encontrado");
        }
        
        require_once 'fpdf/fpdf.php';

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false, 0);
        $datos = $this->prepararDatosExpediente($alm);
        $this->dibujarCaratulaExpediente($pdf, $alm, $datos);

        $disposition = (isset($_REQUEST['download']) && $_REQUEST['download'] == '1') ? 'D' : 'I';
        $pdf->Output($disposition, 'Caratula_Presupuesto_' . $alm->cod_presupuesto . '.pdf');
    }

    private function prepararDatosExpediente($alm, $hospCod = null) {
        $datos = [];
        $HospCod = $hospCod !== null ? (int)$hospCod : (!empty($alm->HospCod) ? (int)$alm->HospCod : null);
        $datos['HospCod'] = $HospCod;
        $datos['cliente_nombre'] = 'Sin Datos';
        $datos['domicilio'] = '';
        $datos['localidad'] = '';
        $datos['cuit'] = '';
        $datos['condicion_iva'] = '';
        if(!empty($alm->cod_cliente)) {
            require_once 'model/clientes.php';
            $clienteModel = new Clientes();
            $cliente = $clienteModel->Obtener($alm->cod_cliente);
            if($cliente) {
                $datos['cliente_nombre'] = $cliente->nombre;
                $datos['domicilio'] = isset($cliente->domicilio) ? $cliente->domicilio : '';
                $datos['localidad'] = isset($cliente->localidad) ? $cliente->localidad : '';
                $datos['cuit'] = isset($cliente->cuit) ? $cliente->cuit : '';
                $datos['condicion_iva'] = isset($cliente->condicion_iva) ? $cliente->condicion_iva : 'RESPONSABLE INSCRIPTO';
            }
        }
        require_once 'model/medicos.php';
        $datos['medico_nombre'] = 'Sin Datos';
        if(!empty($alm->cod_medico)) {
            $medicoModel = new Medicos();
            $medico = $medicoModel->Obtener($alm->cod_medico);
            if($medico) {
                $datos['medico_nombre'] = $medico->mediconombre;
            }
        }
        $datos['fpago_name'] = '';
        foreach($this->model->buscapagos() as $p) {
            if($p->FfaCod == $alm->f_pago) {
                $datos['fpago_name'] = $p->FfaDesc;
                break;
            }
        }
        $datos['hosp_name'] = '';
        if(!empty($HospCod)) {
            foreach($this->model->buscainstitucion() as $p) {
                if($p->HospCod == $HospCod) {
                    $datos['hosp_name'] = $p->HospDesc;
                    break;
                }
            }
        }
        $datos['total_general'] = 0;
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $datos['total_general'] += (float)$d->importe;
            }
        }
        $datos['coordinador_nombre'] = '';
        if(!empty($alm->VndCod)) {
            require_once 'model/vtavnd.php';
            $vtModel = new Vtavnd();
            $coord = $vtModel->Obtener($alm->VndCod);
            if($coord && !empty($coord->VndNom)) {
                $datos['coordinador_nombre'] = $coord->VndNom;
            }
        }
        return $datos;
    }

    private function dibujarCaratulaExpediente($pdf, $alm, $datos) {
        $pdf->AddPage();
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetTextColor(0, 0, 0);

        $x = 15;
        $y = 55;
        $lineH = 10;

        $campos = [
            'Paciente:' => (string)$alm->PresupuestoPaciente,
            'Médico:' => $datos['medico_nombre'],
            'Obra Social:' => $datos['cliente_nombre'],
            'Servicio:' => $datos['hosp_name'],
            'Fecha de Cx:' => '',
            'Horario de Cx:' => '',
            'Asistencia Técnica:' => '',
            'Coordinador:' => $datos['coordinador_nombre'],
        ];

        foreach($campos as $label => $valor) {
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, 8, utf8_decode($label . ' ' . $valor), 0, 0, 'L');
            $y += $lineH;
        }

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(15, 248);
        $pdf->Cell(0, 6, utf8_decode('Fecha de impresión: ' . date('d/m/Y H:i:s')), 0, 0, 'L');

        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetXY(15, 258);
        $pdf->Cell(0, 10, 'Ppto: ' . $alm->cod_presupuesto, 0, 0, 'L');

        $logo_path = 'assets/image/Logo.jpg';
        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, 135, 256, 62);
        }
    }

    private function dibujarHeaderPresupuesto($pdf, $background_path, $alm, $datos) {
        if(file_exists($background_path)) {
            $pdf->Image($background_path, 0, 0, 210, 297);
        }
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(155, 9.5);
        $pdf->Cell(50, 5, $alm->cod_presupuesto, 0, 0, 'L');
        $pdf->SetXY(158, 13.5);
        $pdf->Cell(50, 5, date('d/m/Y', strtotime($alm->fecha)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(22, 45.5);
        $pdf->Cell(80, 5, utf8_decode($datos['cliente_nombre']), 0, 0, 'L');
        $pdf->SetXY(22, 50);
        $pdf->Cell(80, 5, utf8_decode($datos['domicilio']), 0, 0, 'L');
        $pdf->SetXY(25, 55);
        $pdf->Cell(80, 5, utf8_decode($datos['condicion_iva']), 0, 0, 'L');
        $pdf->SetXY(89, 50);
        $pdf->Cell(50, 5, utf8_decode($datos['localidad']), 0, 0, 'L');
        $pdf->SetXY(82, 55);
        $pdf->Cell(50, 5, utf8_decode($datos['cuit']), 0, 0, 'L');
        $pdf->SetXY(20, 67);
        $pdf->Cell(80, 5, utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
        $pdf->SetXY(29, 72);
        $fecha_hora_ap = $alm->PresupFecSeg . ' ' . $alm->PresupHorSeg;
        $pdf->Cell(80, 5, utf8_decode($fecha_hora_ap), 0, 0, 'L');
        $pdf->SetXY(84, 66.5);
        $pdf->Cell(85, 5, utf8_decode($datos['medico_nombre']), 0, 0, 'L');
        $pdf->SetXY(89, 72);
        $pdf->Cell(85, 5, utf8_decode($datos['hosp_name']), 0, 0, 'L');
    }

    private function dibujarFooterPresupuesto($pdf, $alm, $datos) {
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(45, 260);
        $pdf->Cell(60, 5, date('d/m/Y', strtotime($alm->fecha_validez)), 0, 0, 'L');
        $pdf->SetXY(45, 265);
        $pdf->Cell(60, 5, utf8_decode($datos['fpago_name']), 0, 0, 'L');
        $pdf->SetXY(45, 270);
        $pdf->Cell(60, 5, utf8_decode($alm->plazo), 0, 0, 'L');
        $pdf->SetXY(145, 270);
        $pdf->Cell(50, 5, 'TOTAL: $ ' . number_format($datos['total_general'], 2), 0, 0, 'R');
        $pdf->SetXY(105, 260);
        $pdf->MultiCell(90, 4, utf8_decode($alm->PresupVndCom), 0, 'L');
    }

    private function dibujarPaginasPresupuestoExpediente($pdf, $alm, $datos) {
        $background_path = 'assets/sheet/Presupuesto_page-0001.jpg';
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $this->dibujarHeaderPresupuesto($pdf, $background_path, $alm, $datos);
        $y = 90;
        $pdf->SetFont('Arial', '', 9);
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $text_check = utf8_decode($d->detalle_ag);
                $pdf->SetFont('Arial', '', 9);
                $textWidth_check = $pdf->GetStringWidth($text_check);
                $numLines_check = max(1, ceil($textWidth_check / 95));
                $itemHeight_check = ($numLines_check * 5) + 2 + 6;
                if ($y + $itemHeight_check > 249) {
                    $this->dibujarFooterPresupuesto($pdf, $alm, $datos);
                    $pdf->AddPage();
                    $this->dibujarHeaderPresupuesto($pdf, $background_path, $alm, $datos);
                    $y = 90;
                    $pdf->SetFont('Arial', '', 9);
                }
                $pdf->SetXY(2, $y);
                $pdf->Cell(20, 5, $d->item, 0, 0, 'C');
                $pdf->SetXY(19, $y);
                $pdf->Cell(15, 5, $d->cantidad, 0, 0, 'C');
                $pdf->SetXY(158, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->p_unitario, 2), 0, 0, 'R');
                $pdf->SetXY(181, $y);
                $pdf->Cell(20, 5, '$ ' . number_format($d->importe, 2), 0, 0, 'R');
                $pdf->SetXY(40, $y);
                $pdf->MultiCell(95, 5, utf8_decode($d->detalle_ag), 0, 'L');
                $y = $pdf->GetY() + 2;
                $y += 6;
            }
        }
        $this->dibujarFooterPresupuesto($pdf, $alm, $datos);
    }

    private function dibujarHeaderRemito($pdf, $alm, $datos, $fecha_remito) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(159, 30);
        $pdf->Cell(50, 5, date('d    m    Y', strtotime($fecha_remito)), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(40, 61);
        $pdf->Cell(80, 5, utf8_decode($datos['cliente_nombre']), 0, 0, 'L');
        $pdf->SetXY(40, 68);
        $pdf->Cell(80, 5, utf8_decode($datos['domicilio']), 0, 0, 'L');
        $pdf->SetXY(60, 84);
        $pdf->Cell(80, 5, utf8_decode($datos['fpago_name']), 0, 0, 'L');
        $pdf->SetXY(160, 68);
        $pdf->Cell(50, 5, utf8_decode($datos['localidad']), 0, 0, 'L');
        $pdf->SetXY(160, 74);
        $pdf->Cell(50, 5, utf8_decode($datos['cuit']), 0, 0, 'L');
    }

    private function dibujarFooterRemito($pdf, $alm, $datos) {
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(57, 250);
        $pdf->Cell(60, 5, "PACIENTE: " . utf8_decode($alm->PresupuestoPaciente), 0, 0, 'L');
        $pdf->SetXY(57, 255);
        $pdf->Cell(60, 5, "MEDICO: " . utf8_decode($datos['medico_nombre']), 0, 0, 'L');
        $pdf->SetXY(57, 260);
        $pdf->Cell(60, 5, "SERVICIO: " . utf8_decode($datos['hosp_name']), 0, 0, 'L');
    }

    private function dibujarPaginasRemitoExpediente($pdf, $alm, $datos, $fecha_remito) {
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $this->dibujarHeaderRemito($pdf, $alm, $datos, $fecha_remito);
        $y = 102;
        $pdf->SetFont('Arial', '', 9);
        if(isset($alm->detalles)) {
            foreach($alm->detalles as $d) {
                $text_check = utf8_decode($d->detalle_ag);
                $pdf->SetFont('Arial', '', 9);
                $textWidth_check = $pdf->GetStringWidth($text_check);
                $numLines_check = max(1, ceil($textWidth_check / 95));
                $itemHeight_check = ($numLines_check * 5) + 2 + 6;
                if ($y + $itemHeight_check > 249) {
                    $this->dibujarFooterRemito($pdf, $alm, $datos);
                    $pdf->AddPage();
                    $this->dibujarHeaderRemito($pdf, $alm, $datos, $fecha_remito);
                    $y = 90;
                    $pdf->SetFont('Arial', '', 9);
                }
                $pdf->SetXY(19, $y);
                $pdf->Cell(20, 5, $d->item, 0, 0, 'C');
                $pdf->SetXY(36, $y);
                $pdf->Cell(15, 5, $d->cantidad, 0, 0, 'C');
                $pdf->SetXY(57, $y);
                $pdf->MultiCell(95, 5, utf8_decode($d->detalle_ag), 0, 'L');
                $y = $pdf->GetY() + 2;
                $y += 6;
            }
        }
        $this->dibujarFooterRemito($pdf, $alm, $datos);
    }

    public function ExpedientePDF() {
        if(!isset($_REQUEST['id'])) {
            die("ID no especificado");
        }
        $alm = $this->model->Obtener($_REQUEST['id']);
        if(!$alm) {
            die("Presupuesto no encontrado");
        }
        if($alm->EspCod != 3) {
            die("Solo se puede generar expediente para presupuestos autorizados");
        }
        require_once 'fpdf/fpdf.php';
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false, 0);
        $datos = $this->prepararDatosExpediente($alm);
        $this->dibujarCaratulaExpediente($pdf, $alm, $datos);
        $this->dibujarPaginasPresupuestoExpediente($pdf, $alm, $datos);
        $this->dibujarPaginasRemitoExpediente($pdf, $alm, $datos, date('Y-m-d'));
        $disposition = (isset($_REQUEST['download']) && $_REQUEST['download'] == '1') ? 'D' : 'I';
        $pdf->Output($disposition, 'Expediente_' . $alm->cod_presupuesto . '.pdf');
    }

    public function ObtenerDetallesJson() {
        header('Content-Type: application/json');
        try {
            $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
            $data = $this->model->ObtenerCompletoParaAutorizar($id);
            $coordinadores = $this->model->ListarCoordinadores();
            
            echo json_encode([
                'success' => true,
                'data' => $data,
                'coordinadores' => $coordinadores
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function Autorizar() {
        header('Content-Type: application/json');
        try {
            $id = (int)$_REQUEST['cod_presupuesto'];
            $paciente = $_REQUEST['PresupuestoPaciente'];
            $cod_medico = (int)$_REQUEST['cod_medico'];
            $vndCod = (int)$_REQUEST['VndCod'];
            $comentario = $_REQUEST['PresupVndCom'];
            $hospCod = !empty($_REQUEST['HospCod']) ? (int)$_REQUEST['HospCod'] : null;
            $items_a_eliminar = isset($_REQUEST['items_a_eliminar']) ? $_REQUEST['items_a_eliminar'] : [];

            $this->model->AutorizarPresupuesto($id, $paciente, $cod_medico, $vndCod, $comentario, $hospCod, $items_a_eliminar);

            echo json_encode([
                'success' => true,
                'message' => 'Presupuesto autorizado correctamente.'
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function ObtenerMotivosAnulacionJson() {
        header('Content-Type: application/json');
        try {
            $motivos = $this->model->ListarMotivosAnulacion();
            echo json_encode([
                'success' => true,
                'motivos' => $motivos
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function Anular() {
        header('Content-Type: application/json');
        try {
            $id = (int)$_REQUEST['cod_presupuesto'];
            $sueCod = (int)$_REQUEST['SueCod'];
            $comentario = $_REQUEST['PresupVndCom'];

            $this->model->AnularPresupuesto($id, $sueCod, $comentario);

            echo json_encode([
                'success' => true,
                'message' => 'Presupuesto anulado correctamente.'
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function EnviarEmailAutorizados() {
        header('Content-Type: application/json');
        try {
            $fecha_reporte = isset($_POST['fecha_reporte']) && !empty($_POST['fecha_reporte']) ? $_POST['fecha_reporte'] : date('Y-m-d');
            $pdo = Database::StartUp();

            $query = "SELECT p.cod_presupuesto, p.fecha, p.PresupFecAut, p.PresupUsrCre, p.PresupEnviadoMail, p.PresupUsuSeg,
                             c.nombre AS cliente_nombre, p.Licitacion_Nro, p.PresupuestoPaciente, m.mediconombre,
                             p.PresupPrc, p.CprCod, cat.CprDes, v.VndNom, p.PresupVndCom, p.EspCod, s.EspDes, p.SueCod,
                             sub.SueDes, p.PresupFecVnd
                      FROM presupuestos p
                      LEFT JOIN clientes c ON p.cod_cliente = c.cod_cliente
                      LEFT JOIN medicos m ON p.cod_medico = m.cod_medico
                      LEFT JOIN sisesp s ON p.EspCod = s.EspCod
                      LEFT JOIN vtavnd v ON p.VndCod = v.VndCod
                      LEFT JOIN sisespsub sub ON p.SueCod = sub.SueCod
                      LEFT JOIN categoriapresupuesto cat ON p.CprCod = cat.CprCod
                      WHERE (p.fecha = ? OR p.PresupFecAut = ? OR p.PresupFecSegVnd = ?) AND (p.EspCod = 3 OR p.EspCod = 4)
                      ORDER BY p.cod_presupuesto DESC";

            $stm = $pdo->prepare($query);
            $stm->execute([$fecha_reporte, $fecha_reporte, $fecha_reporte]);
            $budgets = $stm->fetchAll(PDO::FETCH_OBJ);

            $autorizados = [];
            $rechazados = [];
            foreach ($budgets as $b) {
                if ($b->EspCod == 3) {
                    $autorizados[] = $b;
                } elseif ($b->EspCod == 4) {
                    $rechazados[] = $b;
                }
            }

            // --- Generate Excel XML (SpreadsheetML) ---
            $xls = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Hemodinamics</Author>
  <Created>' . date('Y-m-d\TH:i:s\Z') . '</Created>
 </DocumentProperties>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="Header">
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>
   <Interior ss:Color="#206773" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="CellText">
   <Alignment ss:Vertical="Center" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="CellNumber">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
   <NumberFormat ss:Format="$#,##0.00"/>
  </Style>
  <Style ss:ID="CellDate">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
 </Styles>';

            $generateSheet = function($title, $list) use ($pdo) {
                $sheet = ' <Worksheet ss:Name="' . $title . '">
  <Table>
   <Row ss:Height="22" ss:StyleID="Header">
    <Cell><Data ss:Type="String">N°</Data></Cell>
    <Cell><Data ss:Type="String">Usr.</Data></Cell>
    <Cell><Data ss:Type="String">Fecha Ppto.</Data></Cell>
    <Cell><Data ss:Type="String">Seg.</Data></Cell>
    <Cell><Data ss:Type="String">Usr. Seg.</Data></Cell>
    <Cell><Data ss:Type="String">Fecha Aut.</Data></Cell>
    <Cell><Data ss:Type="String">Cliente</Data></Cell>
    <Cell><Data ss:Type="String">Lic.</Data></Cell>
    <Cell><Data ss:Type="String">Paciente</Data></Cell>
    <Cell><Data ss:Type="String">Medico</Data></Cell>
    <Cell><Data ss:Type="String">Producto</Data></Cell>
    <Cell><Data ss:Type="String">Aut. Precio</Data></Cell>
    <Cell><Data ss:Type="String">Total</Data></Cell>
    <Cell><Data ss:Type="String">Categoria</Data></Cell>
    <Cell><Data ss:Type="String">Coordinador</Data></Cell>
    <Cell><Data ss:Type="String">Comentarios</Data></Cell>
    <Cell><Data ss:Type="String">Estado</Data></Cell>
    <Cell><Data ss:Type="String">Motivo Per./Rech.</Data></Cell>
    <Cell><Data ss:Type="String">Fecha Cx</Data></Cell>
   </Row>';

                foreach ($list as $r) {
                    // Fetch details
                    $sql_det = "SELECT prd.producto_titulo, d.cantidad, d.p_unitario, d.importe 
                                FROM detalles_presupuesto d 
                                INNER JOIN productos prd ON d.det_producto = prd.cod_producto 
                                WHERE d.cod_presupuesto = ? 
                                ORDER BY d.item";
                    $stm_det = $pdo->prepare($sql_det);
                    $stm_det->execute([$r->cod_presupuesto]);
                    $details = $stm_det->fetchAll(PDO::FETCH_OBJ);

                    $prod_str = '';
                    $total_general = 0;
                    foreach ($details as $d) {
                        $prod_str .= '* ' . $d->producto_titulo . ' (' . $d->cantidad . ') - $' . number_format($d->p_unitario, 2, ',', '.') . "\n";
                        $total_general += (float)$d->importe;
                    }
                    $prod_str = rtrim($prod_str, "\n");

                    // Format dates
                    $fecha_ppto = !empty($r->fecha) ? date('d/m/Y', strtotime($r->fecha)) : '';
                    $fecha_aut = (!empty($r->PresupFecAut) && $r->PresupFecAut !== '1000-01-01' && $r->PresupFecAut !== '0000-00-00') ? date('d/m/Y', strtotime($r->PresupFecAut)) : '';
                    $fecha_cx = (!empty($r->PresupFecVnd) && $r->PresupFecVnd !== '1000-01-01' && $r->PresupFecVnd !== '0000-00-00') ? date('d/m/Y', strtotime($r->PresupFecVnd)) : '';

                    // Seg & Lic
                    $seg = ($r->PresupEnviadoMail == 'S') ? 'SI' : 'NO';
                    $lic = '';
                    if ($r->Licitacion_Nro == 1) {
                        $lic = 'LIC';
                    } elseif ($r->Licitacion_Nro == 2 || $r->Licitacion_Nro == 0) {
                        $lic = 'NOL';
                    }

                    // Clean comments
                    $comentarios = $r->PresupVndCom;
                    $comentarios = str_replace(['<BR>', '<br>', '</br>', "\r", "\n"], ' ', $comentarios);

                    $sheet .= '
   <Row ss:Height="30" ss:StyleID="CellText">
    <Cell><Data ss:Type="Number">' . $r->cod_presupuesto . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars(substr(strtoupper($r->PresupUsrCre), 0, 3), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellDate"><Data ss:Type="String">' . htmlspecialchars($fecha_ppto, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellDate"><Data ss:Type="String">' . htmlspecialchars($seg, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars(substr(strtoupper($r->PresupUsuSeg), 0, 3), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellDate"><Data ss:Type="String">' . htmlspecialchars($fecha_aut, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->cliente_nombre, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellDate"><Data ss:Type="String">' . htmlspecialchars($lic, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->PresupuestoPaciente, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->mediconombre, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($prod_str, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars(substr($r->PresupPrc, 0, 3), ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellNumber"><Data ss:Type="Number">' . $total_general . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->CprDes, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->VndNom, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($comentarios, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->EspDes, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell><Data ss:Type="String">' . htmlspecialchars($r->SueDes, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
    <Cell ss:StyleID="CellDate"><Data ss:Type="String">' . htmlspecialchars($fecha_cx, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Data></Cell>
   </Row>';
                }

                $sheet .= '
  </Table>
 </Worksheet>';
                return $sheet;
            };

            $xls .= $generateSheet('Autorizados', $autorizados);
            $xls .= $generateSheet('Rechazados', $rechazados);
            $xls .= '</Workbook>';

            // ==========================================
            // CONFIGURACIÓN SMTP (PHPMailer)
            // ==========================================
            $smtp_config = [
                'host'       => 'mail.hemodinamics.com',         // Cambia por tu servidor SMTP (ej: smtp.gmail.com)
                'username'   => 'presupuestos@hemodinamics.com',    // Cambia por tu usuario SMTP (ej: ventas@hemodinamics.com)
                'password'   => 'Presu514',          // Cambia por tu contraseña o token de app SMTP
                'port'       => 587,                      // Puerto (587 para TLS, 465 para SSL)
                'encryption' => 'tls',                    // 'tls', 'ssl' o vacío ''
                'from_email' => 'presupuestos@hemodinamics.com',
                'from_name'  => 'Sistema Hemodinamics'
            ];
            // ==========================================

            $fecha_formatted = date('d/m/Y', strtotime($fecha_reporte));
            $to = 'ventas@hemodinamics.com';
            $subject = 'Reporte de Presupuestos - ' . $fecha_formatted;
            $filename_attachment = 'Reporte-Presupuestos-' . $fecha_reporte . '.xls';

            require_once 'vendor/autoload.php';

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host       = $smtp_config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp_config['username'];
            $mail->Password   = $smtp_config['password'];
            $mail->Port       = $smtp_config['port'];
            
            if ($smtp_config['encryption'] === 'tls') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($smtp_config['encryption'] === 'ssl') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = '';
                $mail->SMTPAutoTLS = false;
            }

            // Character set
            $mail->CharSet = 'UTF-8';

            // Recipients
            $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;

            // HTML Body
            $html_body = "<p>Hola,</p>";
            $html_body .= "<p>Se adjunta el reporte de presupuestos de la fecha <strong>" . $fecha_formatted . "</strong>.</p>";
            $html_body .= "<p>El archivo adjunto contiene dos pestañas:</p>";
            $html_body .= "<ul>";
            $html_body .= "<li><strong>Autorizados:</strong> " . count($autorizados) . " presupuestos autorizados.</li>";
            $html_body .= "<li><strong>Rechazados:</strong> " . count($rechazados) . " presupuestos rechazados.</li>";
            $html_body .= "</ul>";
            $html_body .= "<p>Saludos cordiales,<br>Sistema de Gestión Hemodinamics</p>";
            $mail->Body = $html_body;

            // Attach spreadsheet from string in memory
            $mail->addStringAttachment($xls, $filename_attachment, 'base64', 'application/vnd.ms-excel');

            $sent = $mail->send();

            if ($sent) {
                echo json_encode([
                    'success' => true,
                    'message' => 'El correo ha sido enviado exitosamente a ' . $to
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se pudo enviar el correo. Verifique los datos de configuración SMTP.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}
?>