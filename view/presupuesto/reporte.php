<?php
     foreach($this->model->buscaruta() as $e): 
        if($e->ParCod == 48)
        {        
            $Rutaapp = $e->ParVarChr;
        }
        if($e->ParCod == 49)
        {        
            $RutaappTomcat = $e->ParVarChr;
        }

    endforeach;
    ?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Presupuesto</title>
        
        <meta charset="utf-8" />
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="assets/js/jquery-ui/jquery-ui.min.css" />
        <script src="Multi-Select/js/m-select-d-box.min.js"></script> 
        <script src="easyautocomplete/jquery.easy-autocomplete.js"></script> 
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="easyautocomplete/easy-autocomplete.css">  <!-- easyautocomplete/easy-autocomplete.min.css-->
        <link rel="stylesheet" href="easyautocomplete/easy-autocomplete.themes.css">
        <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        
        <style  type="text/css">
            .multiselect 
            {
                width: 200px;
            }

            .selectBox {
            position: relative;
            }

            .selectBox select {
            width: 100%;
            font-weight: bold;
            }

            .overSelect {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            }

            #checkboxes {
            display: none;
            border: 1px #dadada solid;
            }

            #checkboxes label {
            display: block;
            z-index: 99999;
            position: relative;
           /* width: inherit;*/
            }

            #checkboxes label:hover {
            background-color: #1e90ff;
            }
        </style>

   

        <script language="JavaScript">
            function renovar(){self.document.forms[0].submit();}
            function abre(url,width,height){
            newwindow=window.open(url,'','width='+width+ ',height='+height+',left=100,top=50,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no');
            if (!window.focus) {newwindow.focus();}
            return false;}    
         
            

        </script>

        <script language="JavaScript">
            $( document ).ready(function() 
            {
                $('#Grilla tr').click(function(){
                    
                    if($( this ).attr('style') == 'background-color: lightblue;')
                    {
                        $( this ).css( "background-color" ,"transparent");
                    }else{
                        $( this ).css( "background-color" ,"lightblue");
                        
                    }  
                }); 
                
          // BUSCA PRODUCTOS      
         var options = 
        {
            url: function(phrase) 
            {
            return "<?php echo $Rutaapp; ?>consultapto/view/buscaproductos.php?phrase=" + phrase + "&format=json";
            },
            getValue: function(element) {
                return element.name;
            },

            list: {                
                maxNumberOfElements: 12,
                onSelectItemEvent: function() {                  
                    var CodProducto = $("#codprod1").getSelectedItemData().name;
                    $("#codprod1").val(CodProducto).trigger("change");
                },
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                theme: "plate-dark"            
                }
            }
    

        // BUSCA MEDICOS        
        var options2 = 
        {
            url: function(phrase) 
            {
            return "<?php echo $Rutaapp; ?>consultapto/view/buscamedico.php?phrase=" + phrase + "&format=json";
            },
            getValue: function(element) {
                return element.name;
            },

            list: {                
                maxNumberOfElements: 12,
                onSelectItemEvent: function() {                  
                    var medico = $("#cod_medico").getSelectedItemData().name;
                    $("#cod_medico").val(medico).trigger("change");
                },
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                theme: "plate-dark"            
                }
            }
            // BUSCA COORDINADORES
            var options3 = 
        {
            url: function(phrase) 
            {
            return "<?php echo $Rutaapp; ?>consultapto/view/buscacoordinadores.php?phrase=" + phrase + "&format=json";
            },
            getValue: function(element) {
                return element.name;
            },

            list: {                
                maxNumberOfElements: 12,
                onSelectItemEvent: function() {                  
                    var VndNom = $("#VndNom").getSelectedItemData().name;
                    $("#VndNom").val(VndNom).trigger("change");
                },
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                theme: "plate-dark"            
                }
            }
            // BUSCA CLIENTES
            var options4 = 
        {
            url: function(phrase) 
            {
            return "<?php echo $Rutaapp; ?>consultapto/view/buscacliente.php?phrase=" + phrase + "&format=json";
            },
            getValue: function(element) {
                return element.name;
            },

            list: {                
                maxNumberOfElements: 12,
                onSelectItemEvent: function() {                  
                    var cod_cliente = $("#cod_cliente").getSelectedItemData().name;
                    $("#cod_cliente").val(cod_cliente).trigger("change");
                },
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                theme: "plate-dark"            
                }
            }
            // BUSCA HOSPITALES
            var options5 = 
        {
            url: function(phrase) 
            {
            return "<?php echo $Rutaapp; ?>consultapto/view/buscahospitales.php?phrase=" + phrase + "&format=json";
            },
            getValue: function(element) {
                return element.name;
            },

            list: {                
                maxNumberOfElements: 12,
                onSelectItemEvent: function() {                  
                    var Hospcod = $("#Hospcod").getSelectedItemData().name;
                    $("#Hospcod").val(Hospcod).trigger("change");
                },
                match: {
                    enabled: true
                },
                sort: {
                    enabled: true
                },
                theme: "plate-dark"            
                }
            }
            

            $('#codprod1').easyAutocomplete(options);
            $('#cod_medico').easyAutocomplete(options2);
            $('#VndNom').easyAutocomplete(options3);
            $('#cod_cliente').easyAutocomplete(options4);
            $('#Hospcod').easyAutocomplete(options5);

            Math.rand = function(min,max){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	    };


        $("#msdb-b").mSelectDBox({
		"list": (function(){
			var lib = "<?php include_once 'buscaproductos2.php'; ?>".split('|') ;///"qwertyuiopasdfghjklzxcvbnm".split("");
			var arr = [], str;
			 for(var c=0; c<lib.length ; c++)
             {
				str = lib[c];
			// 	for(var v=0; v<8; v++)
            //     {
			// 		str += lib[Math.rand(0, lib.length-1)];
			// 	}
				arr.push(str);
			 }
		
		
        
        return arr;
        })(),
		"builtInInput": 0,
		"multiple": true,
		"autoComplete": true,
		"onkeydown": function(context, e){
			console.log(arguments);
		},
		"input:empty": function(){
			console.log(arguments);
		},
		"name": "b"
	});


        });
       

        var expanded = false;

        function showCheckboxes() 
        {
            var checkboxes = document.getElementById("checkboxes");
            if (!expanded) 
            {
                checkboxes.style.display = "block";
                expanded = true;
            } 
            else
                {
                    checkboxes.style.display = "none";
                    expanded = false;
                }
        }




        
          

        </script>

    
    </head>
    <body>

<h1 class="page-header">Presupuesto</h1>
<div style="width: 60% !important;">
<form name="formu" method="post">
<table style=" border-spacing: 5px;
    border-collapse: separate;"  style="width: 90% !important;">
    <tr> 
        <td>N° Pto</td>
            <td><input type="text" name="cod_presupuesto" value="<?php if(isset($_POST['cod_presupuesto'])){echo $_POST['cod_presupuesto']; } ?>" style="border: 2px solid #888;border-radius: 10px;
  color: #888;
  font-family: inherit;
  font-weight: 400;
  margin: 0;
  min-width: 300px;"></td>
        <td>Cliente</td>
            <td><input type="text" name='nombre' id="cod_cliente" value="<?php if(isset($_POST['nombre'])){echo $_POST['nombre']; } ?>"  ></td>
        <td>Medico</td>
            <td><input type="text" name="mediconombre" id="cod_medico" value="<?php if(isset($_POST['mediconombre'])){echo $_POST['mediconombre']; } ?>"> </td>    
        <td>Paciente</td>
            <td><input type="text" name="presupuestopaciente" style="border: 2px solid #888;border-radius: 10px;
  color: #888;
  font-family: inherit;
  font-weight: 400;
  margin: 0;
  min-width: 300px;" value="<?php if(isset($_POST['presupuestopaciente'])){echo $_POST['presupuestopaciente']; } ?>"></td>
    </tr>
    <tr>
        <td>Descrip. Producto</td>
            <td><label><input id="msdb-b" name="Producto" type="text" class="form-control" data-msdb-name="b" data-msdb-value="" style="    border: 2px solid #888;
    border-radius: 10px;
    color: #888;
    font-family: inherit;
    font-weight: 400;
    margin: 0;
    min-width: 300px;" value="<?php if(isset($_POST['Producto'])){echo $_POST['Producto']; } ?>"></label>
                <!-- <input type="text" name="Producto" id="codprod1" size="30" >-->
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
                                     //   echo $EspFil;
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
    <td>Desde fecha</td>
        <td><input type="date" name="fechaDD" value="<?php if(isset($_POST['fechaDD'])){echo $_POST['fechaDD']; } ?>"></td>	
    <td>Hasta fecha</td>
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
                   <option value="0">NOL</option>     
                   <option value="1" <?php if(!empty($_POST['Licitacion'])){echo 'selected'; } ?> >LIC</option>                 
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
                    ?> > <?php echo $s->CprDes;   ?> </option>
                <?php endforeach; ?>
            </select>
        </td>              
        <td><div style="margin-top: 7px;"><a style="    background-repeat: no-repeat;
    margin-bottom: 0;
    margin-left: 7px;
    margin-right: 7px;
    margin-top: 7px;
    padding-bottom: 5px;
    padding-left: 22px;
    padding-right: 22px;
    padding-top: 5px;
    background-color: #206773;
    
    border-style: none;
    font-family: Arial;
    font-size: 9pt;
    font-weight: bold;
    color: #fff;text-decoration: none;" href="<?php echo $Rutaapp; ?>consultapto/limpia.php"> Limpiar</a></div></td><td><button style="    background-repeat: no-repeat;
    margin-bottom: 0;
    margin-left: 7px;
    margin-right: 7px;
    margin-top: 7px;
    padding-bottom: 5px;
    padding-left: 22px;
    padding-right: 22px;
    padding-top: 5px;
    background-color: #206773;
    border-style: none;
    font-family: Arial;
    font-size: 9pt;
    font-weight: bold;
    color: #fff;"> Buscar</button><button style="    background-repeat: no-repeat;
    margin-bottom: 0;
    margin-left: 7px;
    margin-right: 7px;
    margin-top: 7px;
    padding-bottom: 5px;
    padding-left: 22px;
    padding-right: 22px;
    padding-top: 5px;
    background-color: #206773;
    border-style: none;
    font-family: Arial;
    font-size: 9pt;
    font-weight: bold;
    color: #fff;" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/mailautorizados',400,300)"> Enviar Email Autorizados</button></td>
        <td>
            <b>Mostrar Registro</b>  
            <?php
            if(!isset($_POST['xregistro']))
            {
                $xregistro = 20;
            }else{
                $xregistro = $_POST['xregistro'];
            }
            ?>         
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
        
    </tr>
    <tr>
    <?php 
     $where= buscar();
    foreach($this->model->ListarcontTot($where) as $c): ?>

        <td>Cant. Registros</td> <td><b><?php echo $c->CantRegistro; ?></b></td>               
    
    <?php endforeach;    ?>
    </tr>
    <tr>
    <?php 
     $where= buscar();
    foreach($this->model->ListarTotImporte($where) as $c): ?>

        <td>Total Importe</td> <td><b>$<?php echo number_format( $c->totimporte, 0, ',', '.'); ?></b></td>               
    
    <?php endforeach;    ?>
    </tr>

</table>
<table class="table table-striped" id="Grilla" style="width: 74% !important;">
    <thead>
        <tr>
          <!--
            <th style="width:10px;">Rp</th>
            <th style="width:10px;">OC</th>
         -->   
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:10px;"></th>
            <th style="width:60px;">N°</th>
            <th style="width:60px;">Usr.</th>
            <th style="width:60px;">Fecha</th>
            <th style="width:30px;">Seg.</th>
            <th style="width:60px;">Fecha Aut.</th>
            <th style="width:60px;">Cliente</th>
            <th style="width:60px;">Lic.</th>
            <th style="width:60px;">Paciente</th>
            <th style="width:60px;">Medico</th>
            <th style="width:200px;">Producto</th>
            <th style="width:60px;">Total</th>
            <th style="width:60px;">Categoria</th>
            <th style="width:60px;">Coordinador</th>
            <th style="width:10px;"></th>
            <th style="width:60px;">Comentarios</th>
            <th style="width:60px;">Estado</th>
            <th style="width:60px;">Motivo Per./Rech.</th>
            <th style="width:60px;">Fecha Cx</th>
                          
        </tr>
    </thead>
    </form> 
    <tbody class="marcar">
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

    ?>
    <?php foreach($this->model->Listar($where,$limit) as $r): ?>
        
        <tr>
        <!--
            <td style="width: 10px;padding:4px" ><a href="#" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/seleccionararchivo5?1,,,WWpresupuestos,<?php echo $r->cod_presupuesto; ?>,1,gxPopupLevel%3D0%3B ',400,500)"  ><img src='assets/image/Upload.png'></a></td>
            <td style="width: 10px;padding:4px"><a href="#" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/seleccionararchivo5?2,,,WWpresupuestos,<?php echo $r->cod_presupuesto; ?>,2,gxPopupLevel%3D0%3B',400,500)"  ><img src='assets/image/Upload.png'></a></td>
         -->   
            <td style="width: 10px;padding:4px"><a title='Visualizar Presupuesto' href="#" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/appre007_2?<?php echo $r->cod_presupuesto; ?>,S,gxPopupLevel%3D0%3B',400,500)"  ><img src='assets/image/print.png'></a></td>
            <td style="width: 10px;padding:4px"><a href="#" title="Editar Presupuesto" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/entitymanagerpresupuestos2?UPD,<?php echo $r->cod_presupuesto; ?>,0,',1800,900)"  ><img src='assets/image/edit.png'></a></td>
            
            <td style="width: 10px;padding:4px">
            <?php if($r->EspCod >= 3){ ?>  
            <a href="#" title="Imprimir Remito" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/mensajespopupremito?,imprimeremito,<?php echo $r->cod_presupuesto; ?>,,0,,gxPopupLevel%3D0%3B',700,700)"  ><img src='assets/image/print_remito.png'></a>
            <?php } ?>
            </td>
            
            <td style="width: 20px;padding:4px">
                <?php if($r->EspCod < 3){ ?>    
                <a href="#" title="Autorizar Presupuesto" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/autorizapresup2?<?php echo $r->cod_presupuesto; ?>,gxPopupLevel%3D0%3B ',555,600)"  ><img src='assets/image/tick.png'></a>
                <?php } ?>
            </td>            
            <td style="width: 20px;padding:4px"><a href="#" title="Revocar Presupuesto" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/anularpresup?<?php echo $r->cod_presupuesto; ?>,<?php if($r->EspCod < 3){echo '2';}else{echo '4';}?>,admin,gxPopupLevel%3D0%3B ',400,500)"  ><img src='assets/image/warning.png'></a></td>
            <td style="width: 20px;padding:4px"><a href="#" title="Cambiar de Estado" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/comentario6?<?php echo $r->cod_presupuesto; ?>,,gxPopupLevel%3D0%3B',400,500)"  ><img src='assets/image/Application_form.png'></a></td>            
            <td style="width: 20px;padding:4px"> 
            <?php if($r->EspCod >= 3){ ?>  
            <a href="#" title="Imprimir Caratula" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/appre007_5?<?php echo $r->cod_presupuesto; ?>,N,gxPopupLevel%3D0%3B',700,700)"  ><img src='assets/image/Lists.png'></a>
            <?php } ?>
            </td>
            <td><?php echo $r->cod_presupuesto; ?></td>
            <td><?php echo SUBSTR(strtoupper($r->PresupUsrCre),0,2); ?></td>
            <td style="width: 80px;padding:4px !important"><div style="width: 80px;"><?php
                $myDateTime = DateTime::createFromFormat('Y-m-d',  $r->fecha);
                $fecha = $myDateTime->format('d/m/Y');
                echo $fecha;                    
            ?></div>
            </td>
            <td><?php echo $r->PresupEnviadoMail == 'S' ? "<div style='background-color:red;color:white' align='center'>SI</div>" : 'NO'; ?></td>
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
            <td><?php if($r->Licitacion_Nro == 1){echo "LIC";}elseif($r->Licitacion_Nro == 0){echo "<div style='background-color:#0cea0c;color:white' align='center'>NOL</div>";}else{echo "<div align='center'></div>";} ; ?></td>
            <td><?php echo $r->PresupuestoPaciente; ?></td>
            <td><?php echo $r->mediconombre; ?></td>
            <td style="width: 120px;" ><?php
                foreach($this->model->buscaproducto($r->cod_presupuesto) as $t):
                 echo  '* '.$t->producto_titulo.' ('.$t->cantidad.')'. "<b> - $".number_format($t->p_unitario,0,',','.')."</b><br>"; //$t->producto_titulo + $t->cantidad +  "<b>" ; //+  "<b>" +  $t->p_unitario +  "</b>"; //$t->producto_titulo + $t->cantidad +  "<b>" +  $t->p_unitario +  "</b>" + '<br>';
                 
                endforeach;
                 
                 ?></td>
            
            
            <td><?php  
             
              foreach($this->model->buscatotal( $r->cod_presupuesto) as $s): 
				  echo '<b>$'.number_format($s->total, 0, ',', '.').'</b>'; 
				  
			endforeach;
            
            
            ?></td>
            <td><?php echo $r->CprDes;  ?></td>
            <td><?php echo $r->VndNom; ?></td>
            
            <td style="width: 10px;padding:4px"><a href="#" title="Agregar Comentarios" onclick="abre('<?php echo $RutaappTomcat; ?>bioprotJavaEnviroment/servlet/comentario?<?php echo $r->cod_presupuesto; ?>,admin,gxPopupLevel%3D0%3B',580,200)"  ><img src='assets/image/bubble.png'></a></td>
            <td style="width: 200px;padding:4px !important"><div style="width: 100px;height100vh;word-wrap: break-word;"><?php  echo trim($r->PresupVndCom); ?></div></td>
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
                    echo "<div style='color:black'><b>".$r->EspDes."</b></div>"; 
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
           
            <!--<td>
                <a href="?c=Alumno&a=Crud&id=<?php //echo $r->id; ?>">Editar</a>
            </td>
            -->
           
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 
</div>

<script> window.print()</script>
<?php
function buscar()
{
   
    $where = '';
   // $cod_presupuesto = isset($_POST['cod_presupuesto']);
    $fechaDD = isset($_POST['fechadd']);
    if(!empty($_POST['cod_presupuesto']))
    {
        $where .= ' and presupuestos.cod_presupuesto = '.$_POST['cod_presupuesto'];
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
        $where .= ' and mediconombre like '."'%".$_POST['mediconombre']."%'";
    }
    
    if(!empty($_POST['nombre']))
    {
        $where .= "  and nombre like '%".$_POST['nombre']."%'";
    }
   
    if(!empty($_POST['presupuestopaciente']))
    {
        $where .= ' and presupuestopaciente like '."'%".$_POST['presupuestopaciente']."%'";
    }
    if(!empty($_POST['Producto']))
    {    
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
        $where .= " and VndNom like '%".$_POST['VndNom']."%'";
    }

    if(!empty($_POST['Licitacion']))
    {
            $where .= " and Licitacion_nro = '".$_POST['Licitacion']."'";
    }

    if(!empty($_POST['Usrcod']))
    {
            $where .= " and PresupUsrCre = '".$_POST['Usrcod']."'";
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