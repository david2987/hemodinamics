<?php
$cliente_nombre_val = '';
if(!empty($alm->cod_cliente)) {
    require_once 'model/clientes.php';
    $clienteModel = new Clientes();
    $cliente = $clienteModel->Obtener($alm->cod_cliente);
    if($cliente) {
        $cliente_nombre_val = $cliente->nombre;
    }
}

$medico_nombre_val = '';
if(!empty($alm->cod_medico)) {
    require_once 'model/medicos.php';
    $medicoModel = new Medicos();
    $medico = $medicoModel->Obtener($alm->cod_medico);
    if($medico) {
        $medico_nombre_val = $medico->mediconombre;
    }
}

$hospital_nombre_val = '';
if(!empty($alm->HospCod)) {
    require_once 'model/hospitales.php';
    $hospModel = new Hospitales();
    $hospital = $hospModel->Obtener($alm->HospCod);
    if($hospital) {
        $hospital_nombre_val = trim($hospital->HospDesc) . (trim($hospital->HospLoc) ? ' - ' . trim($hospital->HospLoc) : '');
    }
}
?>
<style>
.easy-autocomplete-container {
    z-index: 99999 !important;
    position: absolute !important;
}
.easy-autocomplete {
    width: 100% !important;
}
.panel.panel-info,
.panel.panel-info .panel-body,
#detalles-table {
    overflow: visible !important;
}
#detalles-table td {
    overflow: visible !important;
    position: relative;
}

@media (max-width: 768px) {
    /* 1. Forzamos a la tabla completa y a sus secciones a comportarse como bloques */
    #detalles-table,
    #detalles-table tbody,
    #detalles-table tr,
    #detalles-table td {
        display: block !important;
        width: 100% !important;
        box-sizing: border-box; /* Evita que los inputs se desborden */
    }

    /* 2. Ocultamos el encabezado original */
    #detalles-table thead {
        display: none !important;
    }

    /* 3. Cada fila se convierte en una tarjeta (Card) independiente */
    #detalles-table tbody tr {
        margin-bottom: 20px;
        padding: 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    /* 4. Estilo para cada celda dentro de la tarjeta */
    #detalles-table tbody td {
        padding: 8px 0 !important; /* Espacio vertical entre campos */
        border: none !important;
        background: transparent !important;
        text-align: left !important; /* Asegura alineación a la izquierda */
    }

    /* 5. Generamos las etiquetas superiores usando data-label */
    #detalles-table tbody td::before {
        content: attr(data-label);
        display: block;
        font-weight: 700;
        font-size: 11px;
        color: #206773;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    /* 6. Ajustes de controles para que ocupen todo el ancho disponible */
    #detalles-table tbody td input[type="text"],
    #detalles-table tbody td input[type="number"],
    #detalles-table tbody td textarea,
    #detalles-table tbody td select {
        width: 100% !important;
        max-width: 100% !important;
        display: block;
    }

    /* Nota: El checkbox de la columna "Alt." no debería medir 100% de ancho */
    #detalles-table tbody td input[type="checkbox"] {
        width: auto !important;
        display: inline-block;
    }

    #detalles-table tbody td textarea {
        height: 80px !important;
    }

    /* 7. Botón de eliminar más fácil de presionar en móviles */
    #detalles-table tbody td .btn-remove {
        width: 100%;
        padding: 12px 16px !important;
        font-size: 16px !important;
        border-radius: 8px !important;
        margin-top: 8px;
    }
}
</style>
<form id="frm-presupuesto" action="?c=presupuesto&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_presupuesto" value="<?php echo $alm->cod_presupuesto; ?>" />
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header" style="color: #206773; font-weight: bold;">Presupuestos</h1>
        </div>
        <div class="col-md-6 text-right" style="padding-top: 20px;">
            <button type="button" class="btn btn-info" style="background-color: #00acee; border: none; font-weight: bold; padding: 10px 20px;">Importar</button>
            <button type="button" class="btn btn-primary" onclick="$('#btn-previsualizar').click();" style="background-color: #00acee; border: none; font-weight: bold; padding: 10px 20px; margin-left: 10px;">Confirmar</button>
        </div>
    </div>

    <ul class="nav nav-tabs" style="margin-bottom: 20px;">
      <li class="active"><a href="#">General</a></li>
    </ul>
    
    <div class="panel panel-default" style="border: none; box-shadow: none;">
        <div class="panel-body" style="padding: 0;">
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-4">
                    <label style="width: 150px; display: inline-block;">N° Presupuesto</label>
                    <span style="font-weight: bold; font-size: 1.2em;"><?php echo $alm->cod_presupuesto; ?></span>
                </div>
            </div>

            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-inline">
                        <label style="width: 150px;">Fecha</label>
                        <input type="text" name="fecha" class="form-control" value="<?php echo $alm->fecha; ?>" readonly style="width: 300px; background-color: #fff;" />
                        <label style="margin-left: 15px;">
                            <img src="assets/image/Application_form.png" style="width: 20px; vertical-align: middle;"> Discrimina Alternativa 
                            <input type="checkbox" name="PresupDisAlt" value="S" <?php echo $alm->PresupDisAlt == 'S' ? 'checked' : ''; ?> style="width: 20px; height: 20px; vertical-align: middle; margin-left: 5px;">
                        </label>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cliente (*)</label>
                        <input type="hidden" id="cod_cliente" name="cod_cliente" value="<?php echo $alm->cod_cliente; ?>" />
                        <input type="text" id="cliente_nombre" class="form-control" placeholder="Buscar cliente..." value="<?php echo htmlspecialchars($cliente_nombre_val); ?>" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Paciente (*)</label>
                        <input type="text" name="PresupuestoPaciente" class="form-control" value="<?php echo htmlspecialchars($alm->PresupuestoPaciente); ?>" placeholder="Nombre del paciente" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Médico (*)</label>
                        <input type="hidden" id="cod_medico" name="cod_medico" value="<?php echo $alm->cod_medico; ?>" />
                        <input type="text" id="medico_nombre" class="form-control" placeholder="Buscar médico..." value="<?php echo htmlspecialchars($medico_nombre_val); ?>" required />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Categoría</label>
                        <select name="CprCod" class="form-control">
                            <option value="">Seleccione...</option>
                            <?php foreach($this->model->buscacategoria() as $c): ?>
                                <option value="<?php echo $c->CprCod; ?>" <?php echo $alm->CprCod == $c->CprCod ? 'selected' : ''; ?>><?php echo $c->CprDes; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Servicio</label>
                        <input type="hidden" id="HospCod" name="HospCod" value="<?php echo $alm->HospCod; ?>" />
                        <input type="text" id="hospital_nombre" class="form-control" placeholder="Buscar servicio..." value="<?php echo htmlspecialchars($hospital_nombre_val); ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">Detalle del Presupuesto</div>
        <div class="panel-body">
            <table id="detalles-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">Alt.</th>
                        <th>Producto</th>
                        <th>Detalle</th>
                        <th style="width: 80px;">Cant.</th>
                        <th style="width: 120px;">Importe</th>
                        <th style="width: 120px;">Total</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($alm->detalles)): ?>
                        <?php foreach($alm->detalles as $d): ?>                            
                        <tr>
                            <td data-label="Alt."><input type="checkbox" name="det_alt[]" value="S" <?php echo $d->itemAlt == 'S' ? 'checked' : ''; ?>></td>
                            <td data-label="Producto">
                                <input type="hidden" name="det_cod_producto[]" value="<?php echo $d->cod_producto; ?>" />
                                <input type="text" class="form-control input-sm product-suggest" value="<?php echo htmlspecialchars($d->producto_titulo); ?>" />
                            </td>
                            <td data-label="Detalle"><textarea name="det_detalle[]" class="form-control input-md" style="height: 134px;"><?php echo $d->detalle_ag; ?></textarea></td>
                            <td data-label="Cant."><input type="number" name="det_cantidad[]" class="form-control input-sm qty" value="<?php echo $d->cantidad; ?>" /></td>
                            <td data-label="Importe"><input type="number" step="0.01" name="det_importe[]" class="form-control input-sm price" value="<?php echo $d->p_unitario; ?>" /></td>
                            <td data-label="Total" class="row-total"><?php echo number_format($d->importe, 2); ?></td>
                            <td data-label="Acción"><button type="button" class="btn btn-danger btn-xs btn-remove"><i class="glyphicon glyphicon-remove"></i></button></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <button type="button" id="btn-add-row" class="btn btn-primary btn-sm">Agregar Fila</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Datos Anexos</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Forma de Pago</label>
                        <select name="f_pago" class="form-control">
                            <option value="">Seleccione...</option>
                            <?php foreach($this->model->buscapagos() as $p): ?>
                                <option value="<?php echo $p->FfaCod;   ?> " 
                                <?php 
                                if($p->FfaCod == 2 && $alm->f_pago == 0) {
                                    echo 'selected';
                                }
                                ?>
                                
                                <?php echo $alm->f_pago == $p->FfaCod ? 'selected' : ''; ?>><?php echo $p->FfaDesc; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Plazo de Entrega</label>
                        <select name="plazo" class="form-control">
                            <option value="Inmediato" <?php echo $alm->plazo == 'Inmediato' ? 'selected' : ''; ?>>Inmediato</option>
                            <option value="24 hs" <?php echo $alm->plazo == '24 hs' ? 'selected' : ''; ?>>24 hs</option>
                            <option value="A convenir" <?php echo $alm->plazo == 'A convenir' ? 'selected' : ''; ?>>A convenir</option>
                            <option value="S/ Cronog. Quirurgico" <?php echo $alm->plazo == 'S/ Cronog. Quirurgico' ? 'selected' : ''; ?>>S/ Cronog. Quirurgico</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Validez de Oferta</label>
                        <input type="date" name="fecha_validez" class="form-control" value="<?php echo $alm->fecha_validez ? $alm->fecha_validez : date('Y-m-d', strtotime('+1 month')); ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Anotaciones en Pto.</label>
                        <textarea name="PresupVndCom" class="form-control" rows="3"><?php echo $alm->PresupVndCom; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Observaciones Internas</label>
                        <textarea name="motivo" class="form-control" rows="3"><?php echo isset($alm->motivo) ? $alm->motivo : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-right" style="margin-bottom: 50px;">
        <button type="button" id="btn-previsualizar" class="btn btn-success btn-lg">Confirmar Guardado</button>
    </div>
</form>

<!-- Modal Previsualización -->
<div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPreviewLabel">Previsualización de Presupuesto</h4>
      </div>
      <div class="modal-body" style="height: 75vh;">
        <iframe id="pdf-frame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar y Editar</button>
        <button type="button" id="btn-guardar-definitivo" class="btn btn-primary">Guardar Definitivamente</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    var selectedClienteName = $("#cliente_nombre").val();
    var selectedMedicoName = $("#medico_nombre").val();

    function validarFormulario() {
        // Validate Cliente
        var codCliente = $("#cod_cliente").val();
        var clienteNombre = $.trim($("#cliente_nombre").val());
        if (!codCliente || !clienteNombre) {
            alert("Debe seleccionar un Cliente válido de la lista de sugerencias.");
            $("#cliente_nombre").focus();
            return false;
        }

        // Validate Paciente
        var paciente = $.trim($("input[name='PresupuestoPaciente']").val());
        if (!paciente) {
            alert("Debe ingresar el nombre del Paciente.");
            $("input[name='PresupuestoPaciente']").focus();
            return false;
        }

        // Validate Medico
        var codMedico = $("#cod_medico").val();
        var medicoNombre = $.trim($("#medico_nombre").val());
        if (!codMedico || !medicoNombre) {
            alert("Debe seleccionar un Médico válido de la lista de sugerencias.");
            $("#medico_nombre").focus();
            return false;
        }

        // Validate at least 1 detail line
        var detRows = $("#detalles-table tbody tr");
        if (detRows.length === 0) {
            alert("Debe cargar al menos 1 detalle en el presupuesto.");
            return false;
        }

        var validDetailExists = false;
        detRows.each(function() {
            var codProd = $(this).find('input[name="det_cod_producto[]"]').val();
            var qty = parseFloat($(this).find('.qty').val()) || 0;
            if (codProd && qty > 0) {
                validDetailExists = true;
            }
        });

        if (!validDetailExists) {
            alert("Debe cargar al menos 1 detalle con un Producto válido de la lista y cantidad mayor a 0.");
            return false;
        }

        return true;
    }

    $("#frm-presupuesto").submit(function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
            return false;
        }
    });

    // Form Preview Logic
    $("#btn-previsualizar").click(function(){
        if (!validarFormulario()) {
            return;
        }

        var formData = $("#frm-presupuesto").serialize();
        // Change button to loading state
        var btn = $(this);
        btn.prop('disabled', true).text('Generando...');
        
        $.ajax({
            url: '?c=presupuesto&a=PreviewPDF',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.url) {
                    // Force refresh iframe
                    $("#pdf-frame").attr('src', response.url + '?t=' + new Date().getTime());
                    $("#modalPreview").modal('show');
                } else {
                    alert("Error al generar la previsualización.");
                }
            },
            error: function() {
                alert("Error de comunicación con el servidor.");
            },
            complete: function() {
                btn.prop('disabled', false).text('Confirmar Guardado');
            }
        });
    });

    $("#btn-guardar-definitivo").click(function(){
        if (!validarFormulario()) {
            return;
        }
        var btn = $(this);
        btn.prop('disabled', true).text('Guardando...');

        $.ajax({
            url: '?c=presupuesto&a=Guardar',
            type: 'POST',
            data: $("#frm-presupuesto").serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Open PDF download in a new tab
                    window.open('?c=presupuesto&a=VerPDF&id=' + response.id + '&download=1', '_blank');
                    // Redirect main window to budget list
                    window.location.href = 'index.php?c=presupuesto';
                } else {
                    alert('Error al guardar el presupuesto.');
                    btn.prop('disabled', false).text('Guardar Definitivamente');
                }
            },
            error: function() {
                alert('Error de comunicaci\u00f3n con el servidor.');
                btn.prop('disabled', false).text('Guardar Definitivamente');
            }
        });
    });

    // Autocomplete for Cliente
    $("#cliente_nombre").easyAutocomplete({
        url: function(phrase) { return "view/buscacliente.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var data = $("#cliente_nombre").getSelectedItemData();
                $("#cod_cliente").val(data.cod_producto).trigger("change");
                selectedClienteName = data.name;
            }
        }
    });

    $("#cliente_nombre").on('input', function() {
        if ($(this).val() !== selectedClienteName) {
            $("#cod_cliente").val('');
        }
    });

    // Autocomplete for Medico
    $("#medico_nombre").easyAutocomplete({
        url: function(phrase) { return "view/buscamedico.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var data = $("#medico_nombre").getSelectedItemData();
                $("#cod_medico").val(data.cod_producto).trigger("change");
                selectedMedicoName = data.name;
            }
        }
    });

    $("#medico_nombre").on('input', function() {
        if ($(this).val() !== selectedMedicoName) {
            $("#cod_medico").val('');
        }
    });

    // Autocomplete for Servicio
    var selectedHospitalName = $("#hospital_nombre").val();
    $("#hospital_nombre").easyAutocomplete({
        url: function(phrase) { return "view/buscahospitales.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var data = $("#hospital_nombre").getSelectedItemData();
                $("#HospCod").val(data.cod_producto).trigger("change");
                selectedHospitalName = data.name;
            }
        }
    });

    $("#hospital_nombre").on('input', function() {
        if ($(this).val() !== selectedHospitalName) {
            $("#HospCod").val('');
        }
    });

    function initProductSuggest(el) {
        $(el).easyAutocomplete({
            url: function(phrase) { return "view/buscaproductos.php?phrase=" + phrase; },
            getValue: "name",
            list: {
                onSelectItemEvent: function() {
                    var data = $(el).getSelectedItemData();
                    var row = $(el).closest('tr');
                    row.find('input[name="det_cod_producto[]"]').val(data.cod_producto);
                    row.find('textarea[name="det_detalle[]"]').val(data.detalle);
                    $(el).data('selected', data.name);
                }
            }
        });
    }

    $(".product-suggest").each(function(){
        var row = $(this).closest('tr');
        var currentVal = row.find('input[name="det_cod_producto[]"]').val();
        if (currentVal) {
            $(this).data('selected', $(this).val());
        }
        initProductSuggest(this);
    });

    $(document).on('input', '.product-suggest', function() {
        var row = $(this).closest('tr');
        if ($(this).val() !== $(this).data('selected')) {
            row.find('input[name="det_cod_producto[]"]').val('');
        }
    });

    $("#btn-add-row").click(function(){
        var row = `<tr>
            <td data-label="Alt."><input type="checkbox" name="det_alt[]" value="S"></td>
            <td data-label="Producto">
                <input type="hidden" name="det_cod_producto[]" />
                <input type="text" class="form-control input-sm product-suggest" />
            </td>
            <td data-label="Detalle"><textarea name="det_detalle[]" class="form-control input-sm" style="height: 134px;" /></td>
            <td data-label="Cant."><input type="number" name="det_cantidad[]" class="form-control input-sm qty" value="1" /></td>
            <td data-label="Importe"><input type="number" step="0.01" name="det_importe[]" class="form-control input-sm price" value="0" /></td>
            <td data-label="Total" class="row-total">0.00</td>
            <td data-label="Acción"><button type="button" class="btn btn-danger btn-xs btn-remove"><i class="glyphicon glyphicon-remove"></i></button></td>
        </tr>`;
        $("#detalles-table tbody").append(row);
        initProductSuggest($("#detalles-table tbody tr:last .product-suggest"));
    });

    $(document).on('click', '.btn-remove', function(){
        $(this).closest('tr').remove();
    });

    $(document).on('input', '.qty, .price', function(){
        var row = $(this).closest('tr');
        var qty = parseFloat(row.find('.qty').val()) || 0;
        var price = parseFloat(row.find('.price').val()) || 0;
        row.find('.row-total').text((qty * price).toFixed(2));
    });
});
</script>
