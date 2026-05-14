<?php include_once 'view/header.php'; ?>

<h1 class="page-header">Panel de Presupuestos</h1>
<div style="width: 60% !important;">
<form name="formu" id='formu' method="post" action="index.php">

<?php 
// Definiciniciones Generales
$importeTotal = 0;
$cantidadTotalRegistro = 0;

if(empty($_GET['excel'])){ ?>

<table style=" border-spacing: 5px;
    border-collapse: separate;"  style="width: 90% !important;">
    <tr> 
        <td>N° Pto</td>
            <td><input type="text" name="cod_presupuesto" value="<?php if(isset($_POST['cod_presupuesto'])){echo $_POST['cod_presupuesto']; } ?>" style="border: 2px solid #888;border-radius: 10px;color: #888;font-family: inherit;font-weight: 400;margin: 0;min-width: 300px;"></td>
        <td>Cliente</td>
            <td><input type="text" name='nombre' id="cod_cliente" value="<?php if(isset($_POST['nombre'])){echo $_POST['nombre']; } ?>"  ></td>
        <td>Medico</td>
            <td><input type="text" name="mediconombre" id="cod_medico" value="<?php if(isset($_POST['mediconombre'])){echo $_POST['mediconombre']; } ?>"> </td>    
        <td>Paciente</td>
            <td><input type="text" name="presupuestopaciente" style="border: 2px solid #888;border-radius: 10px;color: #888;font-family: inherit;font-weight: 400;margin: 0;min-width: 300px;" value="<?php if(isset($_POST['presupuestopaciente'])){echo $_POST['presupuestopaciente']; } ?>"></td>
    </tr>
    <tr>
        <td>Descrip. Producto</td>
        <td> 
            <input type="hidden" name="CodProducto" id="codprod" size="30" value="<?php if(isset($_POST['CodProducto'])){  echo $_POST['CodProducto']; } ?>" >
            <input type="text" name="Producto" id="codprod1" size="30" value="<?php if(isset($_POST['Producto'])){  echo $_POST['Producto']; } ?>" >
        </td>  
        <td> Estado</td>
        <td>    
            <div class="multiselect" style="width: 250px;">
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
        </td>    
        <td> Coordinador</td>
            <td><input type="text" name="VndNom" id="VndNom" value="<?php if(isset($_POST['VndNom'])){echo $_POST['VndNom']; } ; ?>"></td>                    
           <td>Seguimiento</td>
           <td>          
                <select name="Seguimiento"  style="font-weight: bold;">
                    <option value="">Seleccionar Opción</option>
                    <option value="S" > SI</option>
                    <option value="N"  >NO</option>
                </select>
           </td>          
    </tr>
    <tr>
    <td>Desde Fecha Ppto.</td>
    
    <td><input type="date" name="fechaDD" value="<?php if(isset($_POST['fechaDD'])){echo $_POST['fechaDD']; } ?>"></td>	
    
    <td>Hasta fecha Ppto.</td>
        <td><input type="date"  name="fechaHH" value="<?php if(isset($_POST['fechaHH'])){echo $_POST['fechaHH']; } ?>"></td>	
    <td> Usuario Carga </td>
    <td>
    <select name="Usrcod" id="Usrcod" style="width: 264px;">
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
    </td>
    <td> Licitacion</td>
    <td>
        <select name="Licitacion" style="font-weight: bolder;">
            <option value="">Seleccionar Opción</option>             
            <option value="2" <?php if((isset($_POST['Licitacion']))) { if($_POST['Licitacion'] == 2){ echo 'selected';} } ?>>NOL</option>     
            <option value="1" <?php if((isset($_POST['Licitacion']))){ if($_POST['Licitacion'] == 1){ echo 'selected';} } ?> >LIC</option>                 
        </select>
    </td>
    </tr>
    <tr>
        <td>Servicio</td>	
            <td><input type="text" name="Servicio" id="Hospcod" value="<?php if(isset($_POST['Hospcod'])){echo $_POST['Hospcod']; } ?>"></td>
        <td>Categoria</td>  
        <td>
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
        </td>              
        <td>
             <button name="buscar"  onclick="renovar('buscar')" class="botonFiltro">Buscar</button>
             <a class="btn btn-success" href="?c=presupuesto&a=Crud" style="margin-left: 10px;">Nuevo Presupuesto</a>
            </div>
        </td>
        <td>
            <a class="botonFiltro" href="<?php echo $Rutaapp; ?>consultapto/limpia.php"> Limpiar</a>

            <button class="botonFiltro" onclick="abre('<?php echo $RutaappTomcat; ?>hemodinamicsJavaEnviroment/servlet/mailautorizados',400,300)"> Enviar Email Autorizados</button></td>
            <td>
                <b>Mostrar Registro</b>  
                <?php $xregistro =  !isset($_POST['xregistro'])?20:$_POST['xregistro'];?>         
                <select name="xregistro">                
                    <option value="10" <?php if ($xregistro == 10) {echo 'selected' ;} ?>>10</option>
                    <option value="20" <?php if ($xregistro == 20) {echo 'selected' ;} ?>>20</option>
                    <option value="30" <?php if( $xregistro == 30) {echo 'selected' ;} ?>>30</option>
                    <option value="40" <?php if( $xregistro == 40) {echo 'selected' ;} ?>>40</option>
                    <option value="100" <?php if( $xregistro == 100) {echo 'selected' ;} ?>>100</option>
                    <option value="200" <?php if( $xregistro == 200) {echo 'selected' ;} ?>>200</option>
                </select>        
            </td>
            <td>
                <button  onclick="renovar('ImprimirReporte')"  class="botonFiltro" name="ImprimirExcel" >Reporte Xls</button>
            </td>            
    </tr>
    <tr>
        <td>Fecha de Aut. </td><td><input type="date" name="fechaautorizacion" value="<?php if(isset($_POST['fechaautorizacion'])){echo $_POST['fechaautorizacion']; } ?>">  </td>
        <td>Autorizó Precio </td>
        <td>
            <select name="PresupPrc">
                
                <option value="">Seleccione Usuario</option>
            <?php
                         
            foreach($this->model->buscarUsuarioAutPrecio() as $c):                 
                $chk = !empty($_POST['PresupPrc']) && $_POST['PresupPrc'] == $c->PapUsr?'selected':'';?>                
                <option value="<?php  echo trim($c->PapUsr); ?>" <?php echo $chk ; ?> ><?php echo $c->UsrInf; ?></option>                
            <?php endforeach;    ?>
            </select>
        </td>
        <td><b>Seguimiento Presupuesto</b></td>
        <td>
            <select name="PresupUsuSeg">
                <option value="">Seleccione Usuario</option>
            <?php                         
            foreach($this->model->buscarUsuarioSegPresupuesto() as $d): 
                $chk2 = !empty($_POST['PresupUsuSeg']) && $_POST['PresupUsuSeg'] == $d->PasUsr?'selected':'';
            ?>
                   <option value="<?php  echo $d->PasUsr ?>" <?php echo $chk2 ; ?> ><?php echo $d->UsrInf; ?></option>                
            <?php endforeach;    ?>
            </select>
        </td>        
    </tr>
    <tr>
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

    
     //$where= buscar(); foreach($this->model->ListarcontTot($where,$limit) as $c): ?>    
        <td>Cant. Registros</td> <td><b><div id="cantRegistroMostrar"> <div> <?php // echo $c->CantRegistro; ?></b></td>                   
    <?php // endforeach;    ?>
    </tr>
    <tr>
    <?php 
     $where= buscar();
        //foreach($this->model->ListarTotImporte($where,$limit) as $c): ?>
        <td>Total Importe</td> <td><b>$<?php //echo number_format( $c->totimporte, 0, ',', '.'); ?></b></td>                   
    <?php // endforeach;    ?>
    </tr>

</table>
<?php } else{

$where= buscar();
}?>
<table class="table table-striped" id="Grilla" style="width: 74% !important;">
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

                <!-- EDITAR (solo vencidos, perdidos o pendientes) -->
             <?php if($r->EspCod == 1 || $r->EspCod == 2 || $r->EspCod == 5){ ?> 
                <td style="width: 10px;padding:4px"><a href="?c=presupuesto&a=Crud&id=<?php echo $r->cod_presupuesto; ?>" title="Editar Presupuesto"><img src='assets/image/edit.png' style="filter: sepia(1);"></a></td>
            <?php  }else { ?>
                <td style="width: 10px;padding:4px;"><img src='assets/image/edit.png' title="NO ES POSIBLE EDITAR"></td>
            <?php  }?>
            
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
                if($r->PresupFecAut == '1000-01-01')
                {
                   echo '';     
                }else{                    
                    $myDateTime2 = DateTime::createFromFormat('Y-m-d', $r->PresupFecAut);
                    $fechaaut = $myDateTime2->format('d/m/Y');
                    echo $fechaaut;
      
                }
            //echo $r->PresupFecAut;
            
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