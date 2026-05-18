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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <?php  } ?>                        
        <style  type="text/css">
            /* Google Fonts Link (Inter & Outfit) */
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap');

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
            
            /* Modern Premium Custom Styles */
            body {
                background-color: #f1f5f9 !important;
                font-family: 'Inter', sans-serif !important;
            }

            #sidebar {
                background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
                box-shadow: 4px 0 25px rgba(15, 23, 42, 0.15);
                border-right: 1px solid rgba(255, 255, 255, 0.05);
            }

            .logo-container {
                background: rgba(255, 255, 255, 0.02) !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                padding: 25px 20px !important;
            }

            .logo-container img {
                background: transparent !important;
                padding: 0 !important;
                border-radius: 0 !important;
                max-width: 90% !important;
                filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
                transition: all 0.3s ease;
            }

            .logo-container img:hover {
                transform: scale(1.03);
            }

            .sidebar-menu {
                list-style: none;
                padding: 20px 14px !important;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .sidebar-menu-item a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                color: #94a3b8 !important;
                text-decoration: none !important;
                border-radius: 12px;
                font-family: 'Inter', sans-serif;
                font-size: 14px;
                font-weight: 500;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid transparent;
            }

            .sidebar-menu-item a i {
                font-size: 18px;
                width: 24px;
                text-align: center;
                transition: transform 0.25s ease;
            }

            .sidebar-menu-item a:hover {
                color: #f8fafc !important;
                background: rgba(255, 255, 255, 0.04);
                transform: translateX(4px);
            }

            .sidebar-menu-item a:hover i {
                transform: scale(1.15);
            }

            .sidebar-menu-item.active a {
                color: #ffffff !important;
                background: linear-gradient(135deg, #206773 0%, #15454e 100%) !important;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 20px -5px rgba(32, 103, 115, 0.4);
                font-weight: 600;
            }

            .sidebar-menu-item.active a i {
                color: #38bdf8 !important;
            }

            th {
                color: white !important;
                background-color: #206773 !important;
                border: none !important;
                font-family: 'Outfit', sans-serif;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 11px;
                letter-spacing: 0.5px;
                padding: 14px 16px !important;
                vertical-align: middle !important;
            }

            .table {
                background: white !important;
                border-radius: 16px !important;
                overflow: hidden !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
                border: 1px solid #e2e8f0 !important;
            }

            .table td {
                padding: 14px 16px !important;
                vertical-align: middle !important;
                border-bottom: 1px solid #f1f5f9 !important;
                font-size: 13.5px;
                color: #334155;
            }

            .table tr:hover td {
                background-color: #f8fafc !important;
            }

            .panel, .well {
                background: white !important;
                border-radius: 16px !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
                padding: 24px !important;
            }

            h1, h2, h3, h4, h5 {
                font-family: 'Outfit', sans-serif !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }

            .btn {
                border-radius: 10px !important;
                padding: 8px 16px !important;
                font-weight: 500 !important;
                font-family: 'Outfit', sans-serif !important;
                transition: all 0.2s ease !important;
            }

            .btn-primary {
                background-color: #206773 !important;
                border-color: #206773 !important;
            }

            .btn-primary:hover {
                background-color: #174d56 !important;
                border-color: #174d56 !important;
                transform: translateY(-1px);
            }

            /* Premium Filter Card & Grid */
            .filters-card {
                background: white !important;
                border-radius: 16px !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
                padding: 24px !important;
                margin-bottom: 24px !important;
                width: 100% !important;
            }

            .filters-title {
                font-family: 'Outfit', sans-serif;
                font-size: 16px;
                font-weight: 600;
                color: #0f172a;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
                border-bottom: 1px solid #f1f5f9;
                padding-bottom: 12px;
            }

            .filters-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px 20px;
                margin-bottom: 20px;
            }

            @media (max-width: 1200px) {
                .filters-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }

            @media (max-width: 992px) {
                .filters-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 576px) {
                .filters-grid {
                    grid-template-columns: 1fr;
                }
            }

            .filter-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .filter-group label {
                font-family: 'Inter', sans-serif;
                font-size: 12px;
                font-weight: 600;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 0;
            }

            .filter-group input[type="text"],
            .filter-group input[type="date"],
            .filter-group select {
                width: 100% !important;
                height: 38px !important;
                padding: 8px 12px !important;
                font-family: 'Inter', sans-serif !important;
                font-size: 13.5px !important;
                color: #334155 !important;
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 10px !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                transition: all 0.2s ease !important;
            }

            .filter-group input[type="text"]:focus,
            .filter-group input[type="date"]:focus,
            .filter-group select:focus {
                border-color: #206773 !important;
                outline: none !important;
                box-shadow: 0 0 0 3px rgba(32, 103, 115, 0.15) !important;
            }

            .filter-group .multiselect {
                width: 100% !important;
            }

            .filter-group .multiselect select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
                background-position: right 8px center !important;
                background-repeat: no-repeat !important;
                background-size: 18px !important;
                padding-right: 28px !important;
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                appearance: none !important;
            }

            .filters-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 12px;
                border-top: 1px solid #f1f5f9;
                padding-top: 20px;
                margin-top: 10px;
            }

            /* Custom styled search/action buttons */
            .btn-filter-search {
                background-color: #206773 !important;
                color: white !important;
                border: 1px solid #206773 !important;
            }

            .btn-filter-search:hover {
                background-color: #174d56 !important;
                border-color: #174d56 !important;
                transform: translateY(-1px);
            }

            .btn-filter-clear {
                background-color: #f1f5f9 !important;
                color: #475569 !important;
                border: 1px solid #e2e8f0 !important;
                text-decoration: none !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .btn-filter-clear:hover {
                background-color: #e2e8f0 !important;
                color: #1e293b !important;
                transform: translateY(-1px);
            }

            .btn-filter-action {
                background-color: #f8fafc !important;
                color: #0f172a !important;
                border: 1px solid #cbd5e1 !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            .btn-filter-action:hover {
                background-color: #f1f5f9 !important;
                border-color: #94a3b8 !important;
                transform: translateY(-1px);
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
            <?php
            // Get active controller
            $activeController = isset($_REQUEST['c']) ? strtolower($_REQUEST['c']) : 'presupuesto';
            ?>
            <!-- Sidebar -->
            <div id="sidebar" style="width: 260px; display: flex; flex-direction: column; flex-shrink: 0;">
                <div class="logo-container" style="text-align: center;">
                    <img src="assets/image/logo.png" alt="Hemodinamics srl">
                </div>
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item <?php echo ($activeController === 'presupuesto' && (!isset($_GET['a']) || $_GET['a'] !== 'Crud')) ? 'active' : ''; ?>">
                        <a href="index.php"><i class="fa-solid fa-file-invoice-dollar"></i><span>Presupuestos</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo ($activeController === 'presupuesto' && isset($_GET['a']) && $_GET['a'] === 'Crud') ? 'active' : ''; ?>">
                        <a href="index.php?c=presupuesto&a=Crud"><i class="fa-solid fa-file-circle-plus"></i><span>Crear Presupuesto</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'clientes' ? 'active' : ''; ?>">
                        <a href="index.php?c=clientes"><i class="fa-solid fa-users"></i><span>Clientes</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'hospitales' ? 'active' : ''; ?>">
                        <a href="index.php?c=hospitales"><i class="fa-solid fa-hospital"></i><span>Instituciones</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'medicos' ? 'active' : ''; ?>">
                        <a href="index.php?c=medicos"><i class="fa-solid fa-user-doctor"></i><span>Médicos</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'productos' ? 'active' : ''; ?>">
                        <a href="index.php?c=productos"><i class="fa-solid fa-box-open"></i><span>Productos</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'categoriaclientes' ? 'active' : ''; ?>">
                        <a href="index.php?c=categoriaclientes"><i class="fa-solid fa-tags"></i><span>Cat. Clientes</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'categoriaproductos' ? 'active' : ''; ?>">
                        <a href="index.php?c=categoriaproductos"><i class="fa-solid fa-tags"></i><span>Cat. Productos</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'categoriapresupuesto' ? 'active' : ''; ?>">
                        <a href="index.php?c=categoriapresupuesto"><i class="fa-solid fa-folder-tree"></i><span>Cat. Presupuesto</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'sisgru' ? 'active' : ''; ?>">
                        <a href="index.php?c=sisgru"><i class="fa-solid fa-users-gear"></i><span>Grupos Usuarios</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'usuarios' ? 'active' : ''; ?>">
                        <a href="index.php?c=usuarios"><i class="fa-solid fa-user-gear"></i><span>Usuarios</span></a>
                    </li>
                    <li class="sidebar-menu-item <?php echo $activeController === 'vtavnd' ? 'active' : ''; ?>">
                        <a href="index.php?c=vtavnd"><i class="fa-solid fa-user-tie"></i><span>Coordinadores</span></a>
                    </li>
                </ul>
            </div>
            
            <!-- Page Content -->
            <div id="page-content-wrapper" style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                <div id="header-bar" style="background-color: #f8f8f8; padding: 15px 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; color: #206773; font-weight: bold;">Sistema de Gestión Hemodinamics</h3>
                    <div>
                        <span style="font-size: 14px; color: #475569; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-regular fa-circle-user" style="color: #206773; font-size: 18px;"></i>
                            <span>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['user']['UsrInf'] ?? 'Administrador'); ?></strong></span>
                            <span style="color: #cbd5e1;">|</span>
                            <a href="index.php?c=auth&a=Logout" style="color: #ef4444; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; background-color: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1); transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(239, 68, 68, 0.1)'; this.style.transform='translateY(-1px)';" onmouseout="this.style.backgroundColor='rgba(239, 68, 68, 0.05)'; this.style.transform='none';">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Salir</span>
                            </a>
                        </span>
                    </div>
                </div>
                <div  style="padding: 20px; overflow-y: auto; flex: 1;">
