<?php include_once 'view/header.php'; ?>

<h1 class="page-header">Panel de Presupuestos</h1>
<div style="width: 100% !important;">
<form name="formu" id='formu' method="post" action="index.php">

<?php 
// Definiciniciones Generales
$importeTotal = 0;
$cantidadTotalRegistro = 0;

if(empty($_GET['excel'])){ ?>

<div class="filters-card">
    <div class="filters-title">
        <i class="fa-solid fa-filter" style="color: #206773;"></i>
        <span>Filtros de Búsqueda</span>
    </div>
    
    <div class="filters-grid">
        <!-- N° Pto -->
        <div class="filter-group">
            <label>N° Pto</label>
            <input type="text" name="cod_presupuesto" value="<?php if(isset($_POST['cod_presupuesto'])){echo $_POST['cod_presupuesto']; } ?>">
        </div>

        <!-- Cliente -->
        <div class="filter-group">
            <label>Cliente</label>
            <input type="text" name='nombre' id="cod_cliente" value="<?php if(isset($_POST['nombre'])){echo $_POST['nombre']; } ?>">
        </div>

        <!-- Medico -->
        <div class="filter-group">
            <label>Medico</label>
            <input type="text" name="mediconombre" id="cod_medico" value="<?php if(isset($_POST['mediconombre'])){echo $_POST['mediconombre']; } ?>">
        </div>

        <!-- Paciente -->
        <div class="filter-group">
            <label>Paciente</label>
            <input type="text" name="presupuestopaciente" value="<?php if(isset($_POST['presupuestopaciente'])){echo $_POST['presupuestopaciente']; } ?>">
        </div>

        <!-- Descrip. Producto -->
        <div class="filter-group">
            <label>Descrip. Producto</label>
            <input type="hidden" name="CodProducto" id="codprod" size="30" value="<?php if(isset($_POST['CodProducto'])){  echo $_POST['CodProducto']; } ?>" >
            <input type="text" name="Producto" id="codprod1" size="30" value="<?php if(isset($_POST['Producto'])){  echo $_POST['Producto']; } ?>" >
        </div>

        <!-- Estado -->
        <div class="filter-group">
            <label>Estado</label>
            <div class="multiselect">
                <div class="selectBox" onclick="showCheckboxes()">
                    <select>
                        <?php 
                        $CantEsp = 0;
                        for($i=1;$i<=10;$i++)
                        {
                            if(!empty($_POST['Esp'.$i]))
                            {
                                $CantEsp ++;
                            }       
                        }
                        if($CantEsp >= 1)
                        {                        
                            echo  "<option>";
                            for($i=1;$i<=10;$i++)
                            {
                                $EspFil = 0;
                                if(!empty($_POST['Esp'.$i]))
                                { 
                                    $EspFil .= $_POST['Esp'.$i];                     
                                    foreach($this->model->buscadescripestado($EspFil) as $d): ;                                                                           
                                        echo $d->EspDes.'-';
                                    endforeach;
                                }
                            }                            
                            echo "</option>" ;
                        }else{   
                        ?>
                        <option>Seleccione  Estado/s</option>
                        <?php } ?>
                    </select>
                    <div class="overSelect"></div>
                </div>
                <div id="checkboxes" style="z-index: 9000; position: absolute;width:inherit;background-color:white">
                    <?php $Cann=0; foreach($this->model->buscaestado() as $e): ?>                    
                        <label for="<?php echo $e->EspCod; $Cann ++;  ?>" onclick="  
                        if(document.getElementById('Esp<?php echo $Cann; ?>').checked == true)
                        {
                            document.getElementById('Esp<?php echo $Cann; ?>').checked = false;
                        }else
                        {
                            document.getElementById('Esp<?php echo $Cann; ?>').checked = true;
                        }
                        ">   
                        <input type="checkbox" id="Esp<?php echo $Cann; ?>" name="Esp<?php echo $Cann; ?>" value="<?php echo $e->EspCod; ?>"                     
                        <?php 
                            if(isset($_POST['Esp'.$Cann]))
                            { 
                                if($_POST['Esp'.$Cann] == $e->EspCod)
                                { 
                                    echo ' checked';
                                }                            
                            } 
                        ?> 
                        /> <?php echo $e->EspDes;  ?></label>
                    <?php endforeach; ?>  
                </div>
            </div>
        </div>

        <!-- Coordinador -->
        <div class="filter-group">
            <label>Coordinador</label>
            <input type="text" name="VndNom" id="VndNom" value="<?php if(isset($_POST['VndNom'])){echo $_POST['VndNom']; } ; ?>">
        </div>

        <!-- Seguimiento -->
        <div class="filter-group">
            <label>Seguimiento</label>
            <select name="Seguimiento">
                <option value="">Seleccionar Opción</option>
                <option value="S" <?php if(isset($_POST['Seguimiento']) && $_POST['Seguimiento'] == 'S') echo 'selected'; ?>>SI</option>
                <option value="N" <?php if(isset($_POST['Seguimiento']) && $_POST['Seguimiento'] == 'N') echo 'selected'; ?>>NO</option>
            </select>
        </div>

        <!-- Desde Fecha Ppto -->
        <div class="filter-group">
            <label>Desde Fecha Ppto.</label>
            <input type="date" name="fechaDD" value="<?php if(isset($_POST['fechaDD'])){echo $_POST['fechaDD']; } ?>">
        </div>

        <!-- Hasta Fecha Ppto -->
        <div class="filter-group">
            <label>Hasta fecha Ppto.</label>
            <input type="date"  name="fechaHH" value="<?php if(isset($_POST['fechaHH'])){echo $_POST['fechaHH']; } ?>">
        </div>

        <!-- Usuario Carga -->
        <div class="filter-group">
            <label>Usuario Carga</label>
            <select name="Usrcod" id="Usrcod">
                <option value="">Seleccione una opción</option>
                <?php foreach($this->model->buscarusuario() as $s): ?>
                    <option value="<?php echo $s->UsrCod; ?>" 
                    <?php 
                        if(isset($_POST['Usrcod']))
                        { 
                            if($_POST['Usrcod'] == $s->UsrCod)
                            { 
                                echo 'selected';
                            }
                        } 
                    ?> > <?php echo $s->UsrInf;   ?> </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Licitacion -->
        <div class="filter-group">
            <label>Licitacion</label>
            <select name="Licitacion">
                <option value="">Seleccionar Opción</option>             
                <option value="2" <?php if((isset($_POST['Licitacion']))) { if($_POST['Licitacion'] == 2){ echo 'selected';} } ?>>NOL</option>     
                <option value="1" <?php if((isset($_POST['Licitacion']))){ if($_POST['Licitacion'] == 1){ echo 'selected';} } ?> >LIC</option>                 
            </select>
        </div>

        <!-- Servicio -->
        <div class="filter-group">
            <label>Servicio</label>
            <input type="text" name="Servicio" id="Hospcod" value="<?php if(isset($_POST['Hospcod'])){echo $_POST['Hospcod']; } ?>">
        </div>

        <!-- Categoria -->
        <div class="filter-group">
            <label>Categoria</label>
            <select name="Categoria" id="CprCod">
                <option value="">Todas las Categorias</option>
                <?php foreach($this->model->buscacategoria() as $s): ?>
                    <option value="<?php echo $s->CprCod; ?>" 
                    <?php 
                        if(isset($_POST['Categoria']))
                        { 
                            if($_POST['Categoria'] == $s->CprCod)
                            { 
                                echo 'selected';
                            }
                        } 
                    ?> > <?php echo $s->CprDes;  ?> </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Fecha de Aut -->
        <div class="filter-group">
            <label>Fecha de Aut.</label>
            <input type="date" name="fechaautorizacion" value="<?php if(isset($_POST['fechaautorizacion'])){echo $_POST['fechaautorizacion']; } ?>">
        </div>

        <!-- Autorizó Precio -->
        <div class="filter-group">
            <label>Autorizó Precio</label>
            <select name="PresupPrc">
                <option value="">Seleccione Usuario</option>
                <?php foreach($this->model->buscarUsuarioAutPrecio() as $c):                 
                    $chk = !empty($_POST['PresupPrc']) && $_POST['PresupPrc'] == $c->PapUsr?'selected':'';?>                
                    <option value="<?php echo trim($c->PapUsr); ?>" <?php echo $chk ; ?> ><?php echo $c->UsrInf; ?></option>                
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Seguimiento Presupuesto -->
        <div class="filter-group">
            <label>Seguimiento Presupuesto</label>
            <select name="PresupUsuSeg">
                <option value="">Seleccione Usuario</option>
                <?php foreach($this->model->buscarUsuarioSegPresupuesto() as $d): 
                    $chk2 = !empty($_POST['PresupUsuSeg']) && $_POST['PresupUsuSeg'] == $d->PasUsr?'selected':'';
                ?>
                    <option value="<?php  echo $d->PasUsr ?>" <?php echo $chk2 ; ?> ><?php echo $d->UsrInf; ?></option>                
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Mostrar Registro -->
        <div class="filter-group">
            <label>Mostrar Registro</label>
            <?php $xregistro = !isset($_POST['xregistro'])?20:$_POST['xregistro'];?>         
            <select name="xregistro">                
                <option value="10" <?php if ($xregistro == 10) {echo 'selected' ;} ?>>10</option>
                <option value="20" <?php if ($xregistro == 20) {echo 'selected' ;} ?>>20</option>
                <option value="30" <?php if( $xregistro == 30) {echo 'selected' ;} ?>>30</option>
                <option value="40" <?php if( $xregistro == 40) {echo 'selected' ;} ?>>40</option>
                <option value="100" <?php if( $xregistro == 100) {echo 'selected' ;} ?>>100</option>
                <option value="200" <?php if( $xregistro == 200) {echo 'selected' ;} ?>>200</option>
            </select>
        </div>
    </div>

    <!-- Action Buttons Row -->
    <div class="filters-actions">
        <button type="submit" name="buscar" onclick="renovar('buscar')" class="btn btn-filter-search">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
        
        <a class="btn btn-filter-clear" href="<?php echo $Rutaapp; ?>consultapto/limpia.php">
            <i class="fa-solid fa-rotate-left"></i> Limpiar
        </a>
        
        <button type="button" class="btn btn-filter-action" onclick="abre('<?php echo $RutaappTomcat; ?>hemodinamicsJavaEnviroment/servlet/mailautorizados',400,300)">
            <i class="fa-solid fa-paper-plane"></i> Enviar Email Autorizados
        </button>
        
        <button type="submit" onclick="renovar('ImprimirReporte')" class="btn btn-filter-action" name="ImprimirExcel">
            <i class="fa-solid fa-file-excel" style="color: #107c41;"></i> Reporte Xls
        </button>

        <!-- Dynamic Metrics Badge -->
        <div style="margin-left: auto; display: flex; gap: 12px; align-items: start;">
            <span class="badge" style="background-color: #f1f5f9; color: #475569; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-weight: 500; font-size: 13px; text-align: left;">
                Importe Total: $<strong id="importeRegistroMostrar" style="color: #0f172a;margin-top: 5px;"></strong><br>
                Cantidad: <strong id="cantRegistroMostrar" style="color: #0f172a;margin-top: 5px;"></strong>
            </span>
        </div>
    </div>
</div>

<?php 
if(isset($_POST['xregistro'] ))
{ 
    $limit = $_POST['xregistro']; 
}else{
     $limit = 20;
}
if($limit == 1)
{
   $limit = 20;
}
if(!empty($_GET['excel'])) { $limit = 1000; } 

$where= buscar();
} else {
    $where= buscar();
}?>
<table class="table table-striped" id="Grilla" style="width: 100% !important;">
    <thead>
        <tr>
          <!--
            <th style="width:10px;">Rp</th>
            <th style="width:10px;">OC</th>
         -->   
         <?php if(empty($_GET['excel']))
                    { ?>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
             <th style="width:10px;"></th>
              <th style="width:10px;"></th>
            <?php } ?>
            <th style="width:60px;">N°</th>
            <th style="width:60px;">Usr.</th>
            <th style="width:60px;">Fecha Ppto.</th>
            <th style="width:30px;">Seg.</th>
            <th style="width:30px;">Usr. Seg.</th>
            <th style="width:60px;">Fecha Aut.</th>
            <th style="width:60px;">Cliente</th>
            <th style="width:60px;">Lic.</th>
            <th style="width:60px;">Paciente</th>
            <th style="width:60px;">Medico</th>
            <th style="width:200px;">Producto</th>
            <th style="width:200px;">Aut. Precio</th>
            <th style="width:60px;">Total</th>
            <th style="width:60px;">Categoria</th>
            <th style="width:60px;">Coordinador</th>
           <?php  if(empty($_GET['excel']))
            { ?> 
            <th style="width:10px;"></th>
            <?php } ?>
            <th style="width:60px;">Comentarios</th>
            <th style="width:60px;">Estado</th>
            <th style="width:60px;">Motivo Per./Rech.</th>
            <th style="width:60px;">Fecha Cx</th>
            <th style="width:60px;"></th>
            <th style="width:60px;">Elim.</th>
                          
        </tr>
    </thead>
    </form> 
    <tbody class="marcar">
    <?php     
    foreach($this->model->Listar($where,$limit) as $r): ?>
        
        <tr <?php if($r->PresupRecPre == 1){echo "style='background-color:#fad000'"; } ?> id="Fila<?php echo $r->cod_presupuesto; ?>" >
      
        
         <?php if(empty($_GET['excel'])) { ?>
            
                <!-- VISUALIZAR PRESUPUESTO PDF -->
                <td style="width: 10px;padding:4px"><a title='Visualizar Presupuesto PDF' href="?c=presupuesto&a=VerPDF&id=<?php echo $r->cod_presupuesto; ?>" target="_blank"><img src='assets/image/print.png'></a></td>
                
                <!-- VISUALIZAR REMITO PDF (solo para autorizados) -->
                <?php if($r->EspCod == 3) { ?>
                <td style="width: 10px;padding:4px"><a title='Visualizar Remito PDF' href="?c=presupuesto&a=RemitoPDF&id=<?php echo $r->cod_presupuesto; ?>" target="_blank"><img src='assets/image/print_remito.png'></a></td>
                <?php } else { ?>
                <td style="width: 10px;padding:4px;"><img src='assets/image/print_remito.png' title="Solo para presupuestos autorizados" style="opacity: 0.5;"></td>
                <?php } ?>
                
                <!-- VISUALIZAR CARÁTULA PDF -->
                <td style="width: 10px;padding:4px"><a title='Visualizar Carátula PDF' href="?c=presupuesto&a=CaratulaPDF&id=<?php echo $r->cod_presupuesto; ?>" target="_blank"><img src='assets/image/print.png' style="filter: sepia(0.5) hue-rotate(30deg);"></a></td>

                <!-- EDITAR (solo vencidos, perdidos o pendientes) -->
             <?php if($r->EspCod == 1 || $r->EspCod == 2 || $r->EspCod == 5){ ?> 
                <td style="width: 10px;padding:4px"><a href="?c=presupuesto&a=Crud&id=<?php echo $r->cod_presupuesto; ?>" title="Editar Presupuesto"><img src='assets/image/edit.png' style="filter: sepia(1);"></a></td>
            <?php  }else { ?>
                <td style="width: 10px;padding:4px;"><img src='assets/image/edit.png' title="NO ES POSIBLE EDITAR"></td>
            <?php  }?>

                <!-- ACCIONES DE AUTORIZACION / ANULACION -->
                <td style="width: 40px; padding: 4px; text-align: center; vertical-align: middle;">
                    <div style="display: flex; gap: 6px; justify-content: center; align-items: center;">
                        <?php if($r->EspCod != 3 && $r->PresupMedOk != 'S') { ?>
                            <a href="#" class="btn-autorizar" data-id="<?php echo $r->cod_presupuesto; ?>" title="Autorizar Presupuesto">
                                <i class="fa-solid fa-circle-check" style="color: #2e7d32; font-size: 18px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"></i>
                            </a>
                        <?php } ?>
                        <?php if($r->EspCod != 4) { ?>
                            <a href="#" class="btn-anular" data-id="<?php echo $r->cod_presupuesto; ?>" title="Anular Presupuesto">
                                <i class="fa-solid fa-circle-exclamation" style="color: #f59e0b; font-size: 18px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'"></i>
                            </a>
                        <?php } ?>
                    </div>
                </td>
            
            <?php  } // EXCEL?>
            <td><?php echo $r->cod_presupuesto; ?></td>
            <td><?php echo SUBSTR(strtoupper($r->PresupUsrCre),0,3); ?></td>
            <td style="width: 95px;padding:4px !important"><div style="width: 95px;"><?php
                $myDateTime = DateTime::createFromFormat('Y-m-d',  $r->fecha);
                $fecha = $myDateTime->format('d/m/Y');
                echo $fecha;                    
            ?></div>
            </td>
            <td align="center"><?php echo $r->PresupEnviadoMail == 'S' ? "<div style='background-color:red;color:white' align='center'>SI</div>" : 'NO'; ?></td>
            <td><?php echo SUBSTR(strtoupper($r->PresupUsuSeg),0,3); ?></td> <!-- AGREGADO 21-04-2022 -->
            <td style="width: 80px;padding:4px !important"><div style="width: 80px;"><?php 
                if(empty($r->PresupFecAut) || $r->PresupFecAut == '1000-01-01' || $r->PresupFecAut == '0000-00-00')
                {
                   echo '';     
                }else{                    
                    $myDateTime2 = DateTime::createFromFormat('Y-m-d', $r->PresupFecAut);
                    if ($myDateTime2) {
                        $fechaaut = $myDateTime2->format('d/m/Y');
                        echo $fechaaut;
                    } else {
                        echo '';
                    }
                }
            
            ?></div></td>
            <td><?php echo $r->nombre; ?></td>
            <td><?php if($r->Licitacion_Nro == 1){echo "LIC";}elseif($r->Licitacion_Nro == 2){echo "<div style='background-color:#0cea0c;color:white' align='center'>NOL</div>";}else{echo "<div align='center'></div>";} ; ?></td>
            <td><?php echo $r->PresupuestoPaciente; ?></td>
            <td><?php echo $r->mediconombre; ?></td>
            <td style="width: 120px;" ><?php
                foreach($this->model->buscaproducto($r->cod_presupuesto) as $t):
                    if(empty($_GET['excel']))
                    { 
                    echo  '* '.$t->producto_titulo.' ('.$t->cantidad.')'. "<b> - $".number_format($t->p_unitario,0,',','.')."</b><br>"; //$t->producto_titulo + $t->cantidad +  "<b>" ; //+  "<b>" +  $t->p_unitario +  "</b>"; //$t->producto_titulo + $t->cantidad +  "<b>" +  $t->p_unitario +  "</b>" + '<br>';
                    }else
                    {
                        echo  '* '.$t->producto_titulo.' ('.$t->cantidad.')'. "<b> - $".number_format($t->p_unitario,0,',','.')."</b>"; 
                    }   
                endforeach;                 
                  ?></td>
                <td> <?php echo !empty($r->PresupPrc)?substr($r->PresupPrc,0,3):''; ?></td> <!-- PRECIOS DE PRODUCTO -->

            
            <td><?php  
             
              foreach($this->model->buscatotal( $r->cod_presupuesto) as $s): 
				  echo '<b>$'.number_format($s->total, 0, ',', '.').'</b>'; 		
                  $importeTotal += $s->total;		                    
			  endforeach;

            
            
            ?></td>
            <td><?php echo $r->CprDes;  ?></td>
            <td><?php echo $r->VndNom; ?></td>
            <?php 
            if(empty($_GET['excel']))
            { ?> 
            <td style="width: 10px;padding:4px"><a href="#" title="Agregar Comentarios" onclick="abre('<?php echo $RutaappTomcat; ?>hemodinamicsJavaEnviroment/servlet/comentario?<?php echo $r->cod_presupuesto; ?>,admin,gxPopupLevel%3D0%3B',580,300)"  ><img src='assets/image/bubble.png'></a></td>
            <?php } ?>
            
            <td style="width: 200px;padding:4px !important"><div style="width: 350px;height100vh;"><?php 
            if(empty($_GET['excel']))
            { 
                echo trim($r->PresupVndCom); 
            }else
               { 
                $PresupVndCom = $r->PresupVndCom;
                $PresupVndCom = str_replace('<BR>','',$PresupVndCom);    
                $PresupVndCom = str_replace('<br>','',$PresupVndCom);    
                $PresupVndCom = str_replace('</br>','',$PresupVndCom); 
                $PresupVndCom = str_replace(chr(13),'',$PresupVndCom);
                $PresupVndCom = str_replace(chr(10),'',$PresupVndCom);
                echo   $PresupVndCom;
             }   
            
            ?></div></td>
            <td><?php   switch ($r->EspCod)
            {
                case 1:
                    echo "<div style='color:green'>".$r->EspDes."</div>"; 
                break;
                case 2:
                    echo "<div style='color:red'>".$r->EspDes."</div>"; 
                break;
                case 3:
                    echo "<div style='color:violet'>".$r->EspDes."</div>"; 
                break;
                case 4:
                    echo "<div style='color:blue'>".$r->EspDes."</div>"; 
                break;
                case 5:
                    echo "<div style='color:#CCC'>".$r->EspDes."</div>"; 
                break;
                case 6:
                    echo "<div style='color:blue'><b>".$r->EspDes."</b></div>"; 
                break;
                case 7:
                    echo "<div style='color:black'><b>".$r->EspDes."</b></div>"; 
                break;


            } 
            
            //$r->EspDes;
            
            ?></td>
            <td><?php  if($r->EspCod == 2 || $r->EspCod == 4 || $r->EspCod == 8) {echo $r->SueDes;} ?></td>
            <td style="width: 80px;padding:4px !important"><div style="width: 80px;"><?php
           
            if($r->PresupFecVnd == '1000-01-01' or empty($r->PresupFecVnd) )
            {
             echo '';
            }else{
               // echo  str_replace('-','/',$r->PresupFecVnd);
                $myDateTime3 = DateTime::createFromFormat('Y-m-d',$r->PresupFecVnd  );
                
                $fechaCx = $myDateTime3->format('d/m/Y');
                echo $fechaCx;
            }
            
            ?></div></td>

           <td><img src="assets/image/star.png" id="resaltar<?php echo $r->cod_presupuesto; ?>" class="estrella" value='<?php echo $r->cod_presupuesto; ?>' ></td>
           <td>
               <?php if( $r->EspCod != 5 and  $r->EspCod != 6  and  $r->EspCod != 7 ){ ?>
               <img src="assets/image/Deleteimg.png" id="Eliminar<?php echo $r->cod_presupuesto; ?>" class="eliminar" value='<?php echo $r->cod_presupuesto; ?>' >
               <?php } ?>
          </td>
            <!--<td>
                <a href="?c=Alumno&a=Crud&id=<?php //echo $r->id; ?>">Editar</a>
            </td>
            -->
           
        </tr>
         <?php $cantidadTotalRegistro++; ?>       
    
        <?php endforeach; ?>
    </tbody>
</table> 

<input type="hidden" value="<?php echo number_format($importeTotal,0,',','.'); ?>" id="importeTotal" >
<input type="hidden" value="<?php echo $cantidadTotalRegistro; ?>" id="Cantidadtotal" >

</div>
<?php

// ARREGLA LA BUSQUEDA POR COMILLAS
function FixComilla($valor){

    return str_replace("'","''",$valor);
}

// --- MEJORA EL MOTOR DE BUSQUEDA
function busquedacomodin($busqueda)
{

    /*if(stripos($busqueda,'|') > 1 )
    {
        $arrayBusqueda = explode(" ",$busqueda);
        
        $result = "= '";
        foreach($arrayBusqueda as $B)
        {
            $result .= $B." ";
        }
        $result =  substr($result,0,strlen($result)-1);
        $result .= "')  OR presupuestopaciente LIKE";
        /*foreach($arrayBusqueda as $B)
        {
            $result .= " ('%".$B."%') OR";                
        }
               // $result =  substr($result,0,strlen($result)-3);
        foreach($arrayBusqueda as $B)
        {
            $result .= " ('%".$B."%') OR";                
        }
        return  $result =  substr($result,0,strlen($result)-3); 
    }
    else{
        return "like '%".$busqueda."%')";
    }
    */
    return "like '%".$busqueda."%')";
    

}

// FUNCION DE BUSQUEDA
function buscar()
{
   
    $where = '';
   // $cod_presupuesto = isset($_POST['cod_presupuesto']);
    $fechaDD = isset($_POST['fechadd']);
    if(!empty($_POST['cod_presupuesto']))
    {
        if(is_numeric($_POST['cod_presupuesto']))
        {
            $where .= ' and presupuestos.cod_presupuesto = '.$_POST['cod_presupuesto'];
        }else{
            $where .= ' and presupuestos.cod_presupuesto = 0';
        }
    }
    if(!empty($_POST['fechaDD']))
    {
        $where .= " and fecha >= '".$_POST['fechaDD']."'";
    }
    if(!empty($_POST['fechaHH']))
    {
        $where .= " and fecha <= '".$_POST['fechaHH']."'";
    }
    if(!empty($_POST['mediconombre']))
    {
        $where .= ' and mediconombre like '."'%".FixComilla($_POST['mediconombre'])."%'";
    }
    
    if(!empty($_POST['nombre']))
    {
        $where .= "  and nombre like '%".FixComilla($_POST['nombre'])."%'";
    }
   
    if(!empty($_POST['presupuestopaciente']))
    {
        $where .= ' AND( presupuestopaciente '.busquedacomodin(FixComilla($_POST['presupuestopaciente']))." ";
    }
    if(!empty($_POST['Producto']))
    {    
        $where .= " and Presupuestos.PresupProductos like '%".$_POST['CodProducto']."%'";//."'%".FixComilla($_POST['Producto'])."%'";
        /*
        $_POST['Producto'] = substr($_POST['Producto'],0,strlen($_POST['Producto']) - 1);
        $producto = explode(';',$_POST['Producto']);
        
        $cont = 1;
        
        foreach($producto as $ItmProd)
        {
            if($cont ==1)
            {
                $where .= " and PresupProductos like '%".$ItmProd."%'";
                $cont++;
            }else
            {
                $where .= " or PresupProductos like '%".$ItmProd."%'";
            }
        }
        */
       // $where .= ' and PresupProductos like '."'%".FixComilla($_POST['Producto'])."%'";
    }
    if(!empty($_POST['Seguimiento']))
    {
        $where .= " and PresupEnviadoMail = '".$_POST['Seguimiento']."'";
    }

    if(!empty($_POST['Categoria']))
    {
        $where .= ' and presupuestos.CprCod = '.$_POST['Categoria'];
    }

    if(!empty($_POST['VndNom']))
    {
        $where .= " and VndNom like '%".FixComilla($_POST['VndNom'])."%'";
    }

    if(!empty($_POST['Licitacion']))
    {
            $where .= " and Licitacion_nro = ".$_POST['Licitacion'];
    }

    if(!empty($_POST['Usrcod']))
    {
            $where .= " and PresupUsrCre = '".$_POST['Usrcod']."'";
    }

    if(!empty($_POST['fechaautorizacion']))
    {
            $where .= " and PresupFecAut = '".$_POST['fechaautorizacion']."'";
    }

    if(!empty($_POST['PresupPrc']))
    {
            $where .= " and PresupPrc = '".$_POST['PresupPrc']."'";
    }

    if(!empty($_POST['PresupUsuSeg']))
    {
            $where .= " and PresupUsuSeg = '".$_POST['PresupUsuSeg']."'";
    }

    $CantEsp = 0;
    /// ANALIZA CUANTOS TIENE ESTADOS ELEGIDOS
    for($i=1;$i<=10;$i++)
    {
        if(!empty($_POST['Esp'.$i]))
        {
            $CantEsp ++;
        }       
    }   
    if($CantEsp > 1)
    {
        $where .= ' and (';  
    }
    if($CantEsp == 1)
    {
        $where .= ' and ';  
    }

    $a =0;
    for($i=1;$i<=10;$i++)
    {
        
        if(!empty($_POST['Esp'.$i]))
        {
            if($a == 0)
            {
                $where .= 'presupuestos.espcod = '.$_POST['Esp'.$i];   
                $a++;
            }else
            {
                $where .= ' or presupuestos.espcod = '.$_POST['Esp'.$i];   

            }
        }
    }
    if($CantEsp > 1)
    {
        $where .= ' )';  
    }

    return $where;
    
}

?>

<!-- Modal Anular -->
<div class="modal fade" id="modalAnular" tabindex="-1" role="dialog" aria-labelledby="modalAnularLabel">
  <div class="modal-dialog" role="document" style="max-width: 500px;">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <form id="frm-anular-presupuesto" method="post">
        <input type="hidden" name="cod_presupuesto" id="anu_cod_presupuesto" />
        
        <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalAnularLabel" style="font-weight: bold; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-exclamation"></i> Anular Presupuesto
          </h4>
        </div>
        
        <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
          <div class="form-group">
            <label style="font-weight: 600; color: #475569; margin-bottom: 8px;">Motivo de Anulación (*)</label>
            <select name="SueCod" id="anu_sel_motivo" class="form-control" required style="border-radius: 6px; border: 1px solid #cbd5e1; height: 42px;">
              <!-- Populated via AJAX -->
            </select>
          </div>
          
          <div class="form-group" style="margin-top: 20px;">
            <label style="font-weight: 600; color: #475569; margin-bottom: 8px;">Comentarios / Observaciones</label>
            <textarea name="PresupVndCom" id="anu_txt_comentarios" class="form-control" rows="4" placeholder="Ingrese comentarios sobre la anulación..." style="border-radius: 6px; border: 1px solid #cbd5e1; resize: vertical;"></textarea>
          </div>
        </div>
        
        <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: bold; padding: 8px 16px;">Cancelar</button>
          <button type="submit" class="btn btn-warning" style="border-radius: 6px; font-weight: bold; padding: 8px 20px; background-color: #f59e0b; border: none; color: white;">
            <i class="fa-solid fa-trash-can"></i> Anular Presupuesto
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Autorizar -->
<div class="modal fade" id="modalAutorizar" tabindex="-1" role="dialog" aria-labelledby="modalAutorizarLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <form id="frm-autorizar-presupuesto" method="post">
        <input type="hidden" name="cod_presupuesto" id="aut_cod_presupuesto" />
        
        <div class="modal-header" style="background: linear-gradient(135deg, #206773 0%, #174b54 100%); color: white; padding: 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalAutorizarLabel" style="font-weight: bold; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i> Autorizar Presupuesto
          </h4>
        </div>
        
        <div class="modal-body" style="padding: 25px; background-color: #f8fafc; max-height: 70vh; overflow-y: auto;">
          
          <!-- Seccion 1: Informacion del Presupuesto (Header) -->
          <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
            <div class="panel-heading" style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #334155; padding: 12px 15px;">
              <i class="fa-solid fa-file-invoice"></i> Información del Presupuesto
            </div>
            <div class="panel-body" style="padding: 15px;">
              <div class="row">
                <div class="col-md-4">
                  <p style="margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: bold;">NRO PRESUPUESTO</p>
                  <p id="aut_txt_nro" style="font-size: 16px; font-weight: bold; color: #0f172a; margin-bottom: 0;"></p>
                </div>
                <div class="col-md-4">
                  <p style="margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: bold;">CLIENTE</p>
                  <p id="aut_txt_cliente" style="font-size: 15px; color: #334155; margin-bottom: 0;"></p>
                </div>
                <div class="col-md-4">
                  <p style="margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: bold;">TOTAL</p>
                  <p id="aut_txt_total" style="font-size: 16px; font-weight: bold; color: #2e7d32; margin-bottom: 0;"></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Seccion 2: Informacion Editable -->
          <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
            <div class="panel-heading" style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #334155; padding: 12px 15px;">
              <i class="fa-solid fa-pen-to-square"></i> Información Editable
            </div>
            <div class="panel-body" style="padding: 15px;">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Nombre del Paciente</label>
                    <input type="text" name="PresupuestoPaciente" id="aut_inp_paciente" class="form-control" placeholder="Nombre completo" required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Médico</label>
                    <input type="hidden" name="cod_medico" id="aut_inp_cod_medico" />
                    <input type="text" id="aut_inp_medico_nombre" class="form-control" placeholder="Buscar médico..." required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Seccion 3: Coordinador y Comentarios -->
          <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px;">
            <div class="panel-heading" style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #334155; padding: 12px 15px;">
              <i class="fa-solid fa-user-tie"></i> Asignación y Comentarios
            </div>
            <div class="panel-body" style="padding: 15px;">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Coordinador Asignado (*)</label>
                    <select name="VndCod" id="aut_sel_coordinador" class="form-control" required style="border-radius: 6px; border: 1px solid #cbd5e1;">
                      <!-- Populated via JS -->
                    </select>
                  </div>
                  <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Fecha de Autorización</label>
                    <input type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" readonly style="background-color: #e2e8f0; border-radius: 6px; border: 1px solid #cbd5e1; cursor: not-allowed;" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label style="font-weight: 600; color: #475569;">Comentarios / Notas</label>
                    <textarea name="PresupVndCom" id="aut_txt_comentarios" class="form-control" rows="4" placeholder="Ingrese comentarios para guardar en el presupuesto..." style="border-radius: 6px; border: 1px solid #cbd5e1; resize: vertical;"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Seccion 4: Autorizar Items (Grilla editable) -->
          <div class="panel panel-default" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 0;">
            <div class="panel-heading" style="background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #334155; padding: 12px 15px;">
              <i class="fa-solid fa-list-check"></i> Autorizar Ítems (Eliminar si no corresponde)
            </div>
            <div class="panel-body" style="padding: 0;">
              <table class="table table-bordered table-striped" style="margin-bottom: 0; border: none;">
                <thead>
                  <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="width: 60px; text-align: center; border: none; color: #475569; font-weight: 600;">Elim.</th>
                    <th style="border: none; color: #475569; font-weight: 600;">Producto</th>
                    <th style="width: 80px; text-align: center; border: none; color: #475569; font-weight: 600;">Item</th>
                    <th style="width: 100px; text-align: center; border: none; color: #475569; font-weight: 600;">Cantidad</th>
                    <th style="width: 150px; text-align: right; border: none; color: #475569; font-weight: 600;">P. Unitario</th>
                  </tr>
                </thead>
                <tbody id="aut_tbl_items">
                  <!-- Populated via JS -->
                </tbody>
              </table>
            </div>
          </div>

        </div>
        
        <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: bold; padding: 8px 16px;">Cancelar</button>
          <button type="submit" class="btn btn-success" style="border-radius: 6px; font-weight: bold; padding: 8px 20px; background-color: #2e7d32; border: none;">
            <i class="fa-solid fa-check"></i> Autorizar Presupuesto
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // 1. Initialize easyAutocomplete on the modal's doctor field
    $("#aut_inp_medico_nombre").easyAutocomplete({
        url: function(phrase) { return "view/buscamedico.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var value = $("#aut_inp_medico_nombre").getSelectedItemData().cod_producto;
                $("#aut_inp_cod_medico").val(value).trigger("change");
            }
        }
    });

    // 2. Click handler for Autorizar button in grid
    $(document).on('click', '.btn-autorizar', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        $.ajax({
            url: '?c=presupuesto&a=ObtenerDetallesJson',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var p = res.data;
                    
                    // Fill header
                    $("#aut_cod_presupuesto").val(p.cod_presupuesto);
                    $("#aut_txt_nro").text(p.cod_presupuesto);
                    $("#aut_txt_cliente").text(p.cliente_nombre ? p.cliente_nombre : '1 - Sin Datos');
                    $("#aut_txt_total").text('$ ' + parseFloat(p.total || 0).toLocaleString('es-AR', {minimumFractionDigits: 2}));
                    
                    // Fill editable fields
                    $("#aut_inp_paciente").val(p.PresupuestoPaciente);
                    $("#aut_inp_cod_medico").val(p.cod_medico);
                    $("#aut_inp_medico_nombre").val(p.medico_nombre);
                    
                    // Fill coordinator select
                    var coordSel = $("#aut_sel_coordinador");
                    coordSel.empty();
                    coordSel.append('<option value="">-- Seleccionar Coordinador --</option>');
                    if (res.coordinadores) {
                        res.coordinadores.forEach(function(c) {
                            coordSel.append('<option value="' + c.VndCod + '">' + c.VndNom + '</option>');
                        });
                    }
                    
                    // Fill items grid
                    var tbl = $("#aut_tbl_items");
                    tbl.empty();
                    if (p.detalles && p.detalles.length > 0) {
                        p.detalles.forEach(function(d) {
                            var row = `<tr data-item="${d.item}">
                                <td style="text-align: center; vertical-align: middle;">
                                    <button type="button" class="btn btn-link btn-xs btn-remove-aut-item" style="color: #d32f2f;" title="Eliminar ítem">
                                        <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
                                    </button>
                                </td>
                                <td style="vertical-align: middle; font-weight: 500; color: #334155;">
                                    ${d.producto_nombre ? d.producto_nombre : 'Producto ' + d.det_producto}
                                </td>
                                <td style="text-align: center; vertical-align: middle; color: #64748b;">
                                    ${d.item}
                                </td>
                                <td style="text-align: center; vertical-align: middle; font-weight: bold; color: #0f172a;">
                                    ${d.cantidad}
                                </td>
                                <td style="text-align: right; vertical-align: middle; color: #475569;">
                                    $ ${parseFloat(d.p_unitario || 0).toLocaleString('es-AR', {minimumFractionDigits: 2})}
                                </td>
                            </tr>`;
                            tbl.append(row);
                        });
                    } else {
                        tbl.append('<tr><td colspan="5" class="text-center" style="padding: 20px; color: #64748b;">No hay productos asignados a este presupuesto.</td></tr>');
                    }
                    
                    // Show modal
                    $("#modalAutorizar").modal('show');
                } else {
                    alert("No se pudo obtener la información del presupuesto.");
                }
            },
            error: function() {
                alert("Error al comunicarse con el servidor.");
            }
        });
    });

    // 3. Remove item within modal
    $(document).on('click', '.btn-remove-aut-item', function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        var itemNum = row.data('item');
        
        if (confirm("¿Seguro que desea quitar este producto del presupuesto?")) {
            $("#frm-autorizar-presupuesto").append(`<input type="hidden" name="items_a_eliminar[]" class="del-item-input" value="${itemNum}" />`);
            row.fadeOut(300, function() {
                row.remove();
                if ($("#aut_tbl_items tr").length === 0) {
                    $("#aut_tbl_items").append('<tr><td colspan="5" class="text-center" style="padding: 20px; color: #64748b;">No hay productos asignados a este presupuesto.</td></tr>');
                }
            });
        }
    });

    // Reset deleted item inputs on modal close
    $('#modalAutorizar').on('hidden.bs.modal', function () {
        $(".del-item-input").remove();
    });

    // 4. Submit form via AJAX
    $("#frm-autorizar-presupuesto").submit(function(e) {
        e.preventDefault();
        
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Procesando...');
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '?c=presupuesto&a=Autorizar',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $("#modalAutorizar").modal('hide');
                    window.location.reload();
                } else {
                    alert("Error: " + res.message);
                    submitBtn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Autorizar Presupuesto');
                }
            },
            error: function() {
                alert("Error al guardar la autorización.");
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Autorizar Presupuesto');
            }
        });
    });

    // 5. Click handler for Anular button in grid
    $(document).on('click', '.btn-anular', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        $.ajax({
            url: '?c=presupuesto&a=ObtenerMotivosAnulacionJson',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $("#anu_cod_presupuesto").val(id);
                    $("#anu_txt_comentarios").val('');
                    
                    var motifSel = $("#anu_sel_motivo");
                    motifSel.empty();
                    motifSel.append('<option value="">-- Seleccionar Motivo --</option>');
                    if (res.motivos) {
                        res.motivos.forEach(function(m) {
                            motifSel.append('<option value="' + m.SueCod + '">' + m.SueDes + '</option>');
                        });
                    }
                    
                    $("#modalAnular").modal('show');
                } else {
                    alert("No se pudieron cargar los motivos de anulación.");
                }
            },
            error: function() {
                alert("Error al comunicarse con el servidor.");
            }
        });
    });

    // 6. Submit Anular form via AJAX
    $("#frm-anular-presupuesto").submit(function(e) {
        e.preventDefault();
        
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Procesando...');
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '?c=presupuesto&a=Anular',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    $("#modalAnular").modal('hide');
                    window.location.reload();
                } else {
                    alert("Error: " + res.message);
                    submitBtn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Anular Presupuesto');
                }
            },
            error: function() {
                alert("Error al guardar la anulación.");
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Anular Presupuesto');
            }
        });
    });
});
</script>