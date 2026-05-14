<?php
require_once __DIR__ . '/../model/database.php';
try {
    $pdoHeader = Database::StartUp();
    $stm = $pdoHeader->query("SELECT ParCod, ParVarChr From sispar where Parcod >= 48");
    $rutas = $stm->fetchAll(PDO::FETCH_OBJ);
    foreach($rutas as $e) {
        if($e->ParCod == 48) {        
            $Rutaapp = $e->ParVarChr;
        }
        if($e->ParCod == 49) {        
            $RutaappTomcat = $e->ParVarChr;
        }
    }
} catch(Exception $e) {}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    <meta charset="utf-8" />		
        <?php 
        
        if(!empty($_GET['excel']))
        {
            header("Pragma: public");
            header("Expires: 0");
            $filename = "Reporte-Presupuestos-".date("d-m-y-H:i:s").".xls";
            header("Content-type: application/x-msdownload");
            header("Content-Disposition: attachment; filename=$filename");
            header("Pragma: no-cache");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            
        }
        else
        {                                        
        ?>

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

            <?php  } ?>                        
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
            th{
                color: white;
                background-color: #206773;
                border: 1px solid #000;

            }
        </style>

   

        <script language="JavaScript">
            function renovar(valor){               
                if(valor =='buscar'){    
                    var form =   document.formu ;//self.document;
                    form.action = 'index.php';                                                     
                }                
                if(valor =='ImprimirReporte')
                {                  
                    var form =   document.formu ;//self.document;
                    form.action = 'index.php?p=presupuesto2&excel=1';               
                    
                }
                form.submit();

            }
            function abre(url,width,height){
            newwindow=window.open(url,'','width='+width+ ',height='+height+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no');
            if (!window.focus) {newwindow.focus();}
            return false;}    
         
            

        </script>

        <script language="JavaScript">
                  
            $( document ).ready(function() 
            {

                $("#cantRegistroMostrar").append( $("#importeTotal").val());
            
                $("img.estrella").click(function(){

                       
                    var id = $(this).attr('value');                  
                    var jqxhr = $.ajax(  "<?php echo $Rutaapp; ?>consultapto/view/Resalta.php?id=" +  id )
                    .done(function(data) {
                       
                        if(data == 0)
                        {                        
                            $("#Fila" + id ).css("background-color","#fad000");                
                        }
                        else
                        {
                            $("#Fila" + id ).css("background-color","transparent");                
                        }
                    })
                    .fail(function() {
                         alert( "error" );
                     })
                  
                });

                $("img.eliminar").click(function(){

                       
                    var id = $(this).attr('value');
                    let width= '400';
                    let height='200';                  
                    newwindow=window.open('view/eliminar.php?id='+ id,'','width='+width+ ',height='+height+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no');
                    if (!window.focus) {newwindow.focus();}
                    return false;  
                    // url('eliminar.php?id=' + id,400,600)
                  
                });
                

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
                    var name = $("#codprod1").getSelectedItemData().name;
                    var CodProducto = $("#codprod1").getSelectedItemData().cod_producto;
                   // $("#codprod1").val(CodProducto).trigger("change");
                   $("#Producto").val(name).trigger("change");
                   $("#codprod").val(CodProducto).trigger("change");
                }, 
            onShowListEvent: function() 
            {
                var ObjetoSel = $("#codprod1").getItems() ;
                var cant = $("#codprod1").getItems().length;

            
                //console.log(cant);
                /*if(cant==1)
                {             
                    ObjetoSel.forEach(function(elemento, indice, array)
                    {   
                        $("#codprod").val(elemento.cod_producto);                                             
                        $("#codprod1").val(elemento.name);                            
                        $("#formu").submit();
                    }) 
                } */ 

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
                onShowListEvent: function() 
                {
                    var ObjetoSel = $("#cod_medico").getItems() ;
                    var cant = $("#cod_medico").getItems().length
                    console.log(cant);
                    if(cant==1)
                    {             
                        ObjetoSel.forEach(function(elemento, indice, array)
                        {
                            $("#cod_medico").val(elemento.name);                            
                            $("#formu").submit();
                        })
                    }     

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
                onShowListEvent: function() 
                {
                    var ObjetoSel = $("#VndNom").getItems() ;
                    var cant = $("#VndNom").getItems().length
                    console.log(cant);
                    if(cant==1)
                    {             
                        ObjetoSel.forEach(function(elemento, indice, array)
                        {
                            $("#VndNom").val(elemento.name);                            
                            $("#formu").submit();
                        })
                    }     

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
                onShowListEvent: function() 
                {
                    var ObjetoSel = $("#cod_cliente").getItems() ;
                    var cant = $("#cod_cliente").getItems().length
                    console.log(cant);
                    if(cant==1)
                    {             
                        ObjetoSel.forEach(function(elemento, indice, array)
                        {
                            $("#cod_cliente").val(elemento.name);                            
                            $("#formu").submit();
                        })
                    }     

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
                onShowListEvent: function() 
                {
                    var ObjetoSel = $("#Hospcod").getItems() ;
                    var cant = $("#Hospcod").getItems().length
                    console.log(cant);
                    if(cant==1)
                    {             
                        ObjetoSel.forEach(function(elemento, indice, array)
                        {
                            $("#Hospcod").val(elemento.name);                            
                            $("#formu").submit();
                        })
                    }     

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
        "embeddedInput":true,
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


        function ImprimeExcel(document) {
                
                // Create a form synamically
                var form = document.getElementById("formu");
                form.setAttribute("method", "post");
                form.setAttribute("action", "");

        }

          

        </script>

    
    </head>
    <body style="font-size: 14.0pt;font-weight: normal;font-style: normal;font-family:Arial">
        
        <div id="wrapper" style="display: flex; min-height: 100vh;">
            <!-- Sidebar -->
            <div id="sidebar" style="width: 250px; background-color: #206773; color: white; display: flex; flex-direction: column; flex-shrink: 0;">
                <div class="logo-container" style="padding: 20px; text-align: center; background-color: #1a545e;">
                    <img src="assets/image/logo.png" alt="Hemodinamics srl" style="max-width: 100%; height: auto; background: white; padding: 10px; border-radius: 5px;">
                </div>
                <ul class="nav nav-pills nav-stacked" style="padding-top: 20px;">
                    <li role="presentation"><a href="index.php" style="color: white;">Presupuestos</a></li>
                    <li role="presentation"><a href="index.php?c=clientes" style="color: white;">Clientes</a></li>
                    <li role="presentation"><a href="index.php?c=hospitales" style="color: white;">Instituciones</a></li>
                    <li role="presentation"><a href="index.php?c=medicos" style="color: white;">Médicos</a></li>
                    <li role="presentation"><a href="index.php?c=productos" style="color: white;">Productos</a></li>
                    <li role="presentation"><a href="index.php?c=categoriaclientes" style="color: white;">Cat. Clientes</a></li>
                    <li role="presentation"><a href="index.php?c=categoriapresupuesto" style="color: white;">Cat. Presupuesto</a></li>
                    <li role="presentation"><a href="index.php?c=usuarios" style="color: white;">Usuarios</a></li>
                    <li role="presentation"><a href="index.php?c=vtavnd" style="color: white;">Coordinadores</a></li>
                </ul>
            </div>
            
            <!-- Page Content -->
            <div id="page-content-wrapper" style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                <div id="header-bar" style="background-color: #f8f8f8; padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; color: #206773; font-weight: bold;">Sistema de Gestión Hemodinamics</h3>
                    <div>
                        <span>Administración</span>
                    </div>
                </div>
                <div  style="padding: 20px; overflow-y: auto; flex: 1;">
