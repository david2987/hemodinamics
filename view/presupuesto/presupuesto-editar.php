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
                    <div class="form-inline">
                        <label style="width: 150px;">Cliente (*)</label>
                        <input type="hidden" id="cod_cliente" name="cod_cliente" value="<?php echo $alm->cod_cliente; ?>" />
                        <input type="text" id="cliente_nombre" class="form-control" style="width: 300px;" placeholder="1 - Sin Datos" required value="<?php echo isset($alm->nombre) ? $alm->nombre : ''; ?>" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Cliente (*)</label>
                        <input type="hidden" id="cod_cliente" name="cod_cliente" value="<?php echo $alm->cod_cliente; ?>" />
                        <input type="text" id="cliente_nombre" class="form-control" placeholder="Buscar cliente..." required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Paciente (*)</label>
                        <input type="text" name="PresupuestoPaciente" class="form-control" value="<?php echo $alm->PresupuestoPaciente; ?>" placeholder="Nombre del paciente" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Médico (*)</label>
                        <input type="hidden" id="cod_medico" name="cod_medico" value="<?php echo $alm->cod_medico; ?>" />
                        <input type="text" id="medico_nombre" class="form-control" placeholder="Buscar médico..." required />
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
                <div class="col-md-2 text-center">
                    <div class="checkbox" style="margin-top: 30px;">
                        <label><input type="checkbox" name="Licitacion_Nro" <?php echo $alm->Licitacion_Nro == 1 ? 'checked' : ''; ?>> Licitación</label>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="checkbox" style="margin-top: 30px;">
                        <label><input type="checkbox" name="PresupEnviadoMail" <?php echo $alm->PresupEnviadoMail == 1 ? 'checked' : ''; ?>> Seguimiento</label>
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
                            <td><input type="checkbox" name="det_alt[]" value="S" <?php echo $d->itemAlt == 'S' ? 'checked' : ''; ?>></td>
                            <td>
                                <input type="hidden" name="det_cod_producto[]" value="<?php echo $d->cod_producto; ?>" />
                                <input type="text" class="form-control input-sm product-suggest" value="<?php echo $d->cod_producto; ?>" />
                            </td>
                            <td><input type="text" name="det_detalle[]" class="form-control input-sm" value="<?php echo $d->detalle_ag; ?>" /></td>
                            <td><input type="number" name="det_cantidad[]" class="form-control input-sm qty" value="<?php echo $d->cantidad; ?>" /></td>
                            <td><input type="number" step="0.01" name="det_importe[]" class="form-control input-sm price" value="<?php echo $d->p_unitario; ?>" /></td>
                            <td class="row-total"><?php echo number_format($d->importe, 2); ?></td>
                            <td><button type="button" class="btn btn-danger btn-xs btn-remove"><i class="glyphicon glyphicon-remove"></i></button></td>
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
                                <option value="<?php echo $p->FfaCod; ?>" <?php echo $alm->f_pago == $p->FfaCod ? 'selected' : ''; ?>><?php echo $p->FfaDesc; ?></option>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha Seguimiento</label>
                        <input type="date" name="PresupFecSeg" class="form-control" value="<?php echo $alm->PresupFecSeg; ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Hora Seguimiento</label>
                        <input type="time" name="PresupHorSeg" class="form-control" value="<?php echo $alm->PresupHorSeg; ?>" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Relevancia</label>
                        <select name="PresupRel" class="form-control">
                            <option value="1" <?php echo $alm->PresupRel == 1 ? 'selected' : ''; ?>>Baja</option>
                            <option value="2" <?php echo $alm->PresupRel == 2 ? 'selected' : ''; ?>>Media</option>
                            <option value="3" <?php echo $alm->PresupRel == 3 ? 'selected' : ''; ?>>Alta</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Expediente Nro</label>
                        <input type="text" name="Expendiente_nro" class="form-control" value="<?php echo $alm->Expendiente_nro; ?>" />
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
    // Form Preview Logic
    $("#btn-previsualizar").click(function(){
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
        $("#frm-presupuesto").submit();
    });

    // Autocomplete for Cliente
    $("#cliente_nombre").easyAutocomplete({

        url: function(phrase) { return "view/buscacliente.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var value = $("#cliente_nombre").getSelectedItemData().cod_producto; // Wait, buscacliente.php returns cod_producto as the ID
                $("#cod_cliente").val(value).trigger("change");
            }
        }
    });

    // Autocomplete for Medico
    $("#medico_nombre").easyAutocomplete({
        url: function(phrase) { return "view/buscamedico.php?phrase=" + phrase; },
        getValue: "name",
        list: {
            onSelectItemEvent: function() {
                var value = $("#medico_nombre").getSelectedItemData().cod_producto;
                $("#cod_medico").val(value).trigger("change");
            }
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
                    row.find('input[name="det_detalle[]"]').val(data.name);
                }
            }
        });
    }

    $(".product-suggest").each(function(){ initProductSuggest(this); });

    $("#btn-add-row").click(function(){
        var row = `<tr>
            <td><input type="checkbox" name="det_alt[]" value="S"></td>
            <td>
                <input type="hidden" name="det_cod_producto[]" />
                <input type="text" class="form-control input-sm product-suggest" />
            </td>
            <td><input type="text" name="det_detalle[]" class="form-control input-sm" /></td>
            <td><input type="number" name="det_cantidad[]" class="form-control input-sm qty" value="1" /></td>
            <td><input type="number" step="0.01" name="det_importe[]" class="form-control input-sm price" value="0" /></td>
            <td class="row-total">0.00</td>
            <td><button type="button" class="btn btn-danger btn-xs btn-remove"><i class="glyphicon glyphicon-remove"></i></button></td>
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
