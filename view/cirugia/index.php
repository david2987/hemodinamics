<?php $estado = $filtros['estado']; ?>
<h1 class="page-header">Panel de Cirugías</h1>

<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <li class="<?php echo $estado === 'autorizadas' ? 'active' : ''; ?>">
        <a href="?c=cirugia&estado=autorizadas">Autorizadas</a>
    </li>
    <li class="<?php echo $estado === 'aceptadas' ? 'active' : ''; ?>">
        <a href="?c=cirugia&estado=aceptadas">Aceptadas</a>
    </li>
    <li class="<?php echo $estado === 'realizadas' ? 'active' : ''; ?>">
        <a href="?c=cirugia&estado=realizadas">Realizadas</a>
    </li>
</ul>

<form method="get" action="index.php">
    <input type="hidden" name="c" value="cirugia">
    <input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado); ?>">
    <div class="filters-card">
        <div class="filters-title">
            <i class="fa-solid fa-filter" style="color: #206773;"></i>
            <span>Filtros de Búsqueda</span>
        </div>
        <div class="filters-grid">
            <div class="filter-group">
                <label>Paciente</label>
                <input type="text" name="paciente" value="<?php echo htmlspecialchars($filtros['paciente']); ?>">
            </div>
            <div class="filter-group">
                <label>Médico</label>
                <input type="text" name="medico" value="<?php echo htmlspecialchars($filtros['medico']); ?>">
            </div>
            <div class="filter-group">
                <label>Institución</label>
                <select name="hospCod">
                    <option value="">Todas</option>
                    <?php foreach($hospitales as $h): ?>
                        <option value="<?php echo $h->HospCod; ?>" <?php echo ($filtros['hospCod'] == $h->HospCod) ? 'selected' : ''; ?>><?php echo htmlspecialchars($h->HospDesc); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($estado === 'autorizadas'): ?>
                <div class="filter-group">
                    <label>Subestado</label>
                    <select name="sueCod">
                        <option value="">Todos</option>
                        <?php foreach($subestados as $s): ?>
                            <option value="<?php echo $s->SueCod; ?>" <?php echo ($filtros['sueCod'] == $s->SueCod) ? 'selected' : ''; ?>><?php echo htmlspecialchars($s->SueDes); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Autorizado Desde</label>
                    <input type="date" name="fechaDesde" value="<?php echo htmlspecialchars($filtros['fechaDesde']); ?>">
                </div>
                <div class="filter-group">
                    <label>Autorizado Hasta</label>
                    <input type="date" name="fechaHasta" value="<?php echo htmlspecialchars($filtros['fechaHasta']); ?>">
                </div>
            <?php else: ?>
                <div class="filter-group">
                    <label>Coordinador</label>
                    <select name="vndCod">
                        <option value="">Todos</option>
                        <?php foreach($coordinadores as $c): ?>
                            <option value="<?php echo $c->VndCod; ?>" <?php echo ($filtros['vndCod'] == $c->VndCod) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c->VndNom); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Fecha CX Desde</label>
                    <input type="date" name="fechaDesde" value="<?php echo htmlspecialchars($filtros['fechaDesde']); ?>">
                </div>
                <div class="filter-group">
                    <label>Fecha CX Hasta</label>
                    <input type="date" name="fechaHasta" value="<?php echo htmlspecialchars($filtros['fechaHasta']); ?>">
                </div>
            <?php endif; ?>
        </div>
        <div class="filters-actions">
            <button type="submit" class="btn btn-filter-search"><i class="fa-solid fa-search"></i> Buscar</button>
            <a href="index.php?c=cirugia&estado=<?php echo htmlspecialchars($estado); ?>" class="btn btn-filter-clear"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            <a href="index.php?c=cirugia&a=ReporteXlsx&<?php echo http_build_query($filtros); ?>" class="btn btn-filter-action"><i class="fa-solid fa-file-excel"></i> Exportar Excel</a>
        </div>
    </div>
</form>

<?php if ($estado === 'autorizadas'): ?>

<table id="Grilla" class="table table-striped" style="margin-top: 15px;">
    <thead>
        <tr>
            <th>N° Pto</th>
            <th>Paciente</th>
            <th>Médico</th>
            <th>Institución</th>
            <th>Fecha Autorización</th>
            <th>Subestado</th>
            <th style="width:150px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr class="estado-sue-<?php echo (int)$r->SueCod; ?>">
            <td><?php echo $r->cod_presupuesto; ?></td>
            <td><?php echo htmlspecialchars($r->PresupuestoPaciente); ?></td>
            <td><?php echo htmlspecialchars($r->mediconombre); ?></td>
            <td><?php echo htmlspecialchars($r->HospDesc); ?></td>
            <td><?php echo !empty($r->PresupFecAut) ? date('d/m/Y', strtotime($r->PresupFecAut)) : ''; ?></td>
            <td><?php echo htmlspecialchars($r->SueDes); ?></td>
            <td>
                <div style="display:flex; gap:8px; align-items:center;">
                    <a href="#" class="btn-aceptar-cx" style="color:#2e7d32;"
                       data-id="<?php echo $r->cod_presupuesto; ?>"
                       data-fecha="<?php echo (!empty($r->PresupFecSeg) && $r->PresupFecSeg != '0000-00-00') ? $r->PresupFecSeg : ''; ?>"
                       data-hora="<?php echo htmlspecialchars($r->PresupHorSeg); ?>"
                       data-materiales=""
                       title="Aceptar y programar CX">
                        <i class="fa-solid fa-calendar-check"></i> Aceptar
                    </a>
                    <a href="#" class="btn-rechazar" data-id="<?php echo $r->cod_presupuesto; ?>" style="color:#c62828;" title="Rechazar">
                        <i class="fa-solid fa-ban"></i> Rechazar
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php else: ?>

<table id="Grilla" class="table table-striped" style="margin-top: 15px;">
    <thead>
        <tr>
            <th>N° Pto</th>
            <th>Paciente</th>
            <th>Médico</th>
            <th>Institución</th>
            <th>Coordinador</th>
            <th>Fecha / Hora CX</th>
            <th>Materiales a necesitar</th>
            <th style="width:190px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr class="<?php echo $r->PlcCxRea == 'S' ? 'estado-sue-12' : 'estado-sue-11'; ?>">
            <td><?php echo $r->cod_presupuesto; ?></td>
            <td><?php echo htmlspecialchars($r->PlcPac); ?></td>
            <td><?php echo htmlspecialchars($r->mediconombre); ?></td>
            <td><?php echo htmlspecialchars($r->HospDesc); ?></td>
            <td><?php echo htmlspecialchars($r->VndNom); ?></td>
            <td><?php
                if (!empty($r->PlcFec) && $r->PlcFec != '0000-00-00') {
                    echo date('d/m/Y', strtotime($r->PlcFec)) . ' ' . $r->PlcHor;
                }
            ?></td>
            <td><?php echo nl2br(htmlspecialchars((string)$r->PlcMat)); ?></td>
            <td>
                <div style="display:flex; gap:8px; align-items:center; flex-wrap: wrap;">
                    <a title="Ver Presupuesto PDF" href="?c=presupuesto&a=VerPDF&id=<?php echo $r->cod_presupuesto; ?>" target="_blank"><i class="fa-solid fa-file-invoice" style="color:#206773;"></i></a>
                    <a title="Ver Remito PDF" href="?c=presupuesto&a=RemitoPDF&id=<?php echo $r->cod_presupuesto; ?>" target="_blank"><i class="fa-solid fa-file-pen" style="color:#206773;"></i></a>
                    <a title="Imprimir Etiquetas" href="?c=cirugia&a=EtiquetasPDF&id=<?php echo $r->PlcCod; ?>" target="_blank"><i class="fa-solid fa-tags" style="color:#206773;"></i></a>
                    <a href="#" class="btn-aceptar-cx" title="Reprogramar CX"
                       data-id="<?php echo $r->cod_presupuesto; ?>"
                       data-fecha="<?php echo (!empty($r->PlcFec) && $r->PlcFec != '0000-00-00') ? $r->PlcFec : ''; ?>"
                       data-hora="<?php echo htmlspecialchars($r->PlcHor); ?>"
                       data-materiales="<?php echo htmlspecialchars((string)$r->PlcMat); ?>">
                        <i class="fa-solid fa-calendar-days" style="color:#206773;"></i>
                    </a>
                    <a href="#" class="btn-consumo" title="Cargar Consumo"
                       data-plccod="<?php echo $r->PlcCod; ?>"
                       data-consumo="<?php echo htmlspecialchars((string)$r->PlcMatCx); ?>">
                        <i class="fa-solid fa-boxes-packing" style="color:#206773;"></i>
                    </a>
                    <a href="#" class="btn-facturas" title="Gestionar Facturas" data-plccod="<?php echo $r->PlcCod; ?>">
                        <i class="fa-solid fa-file-invoice-dollar" style="color:#206773;"></i>
                    </a>
                    <?php if ($r->PlcCxRea != 'S'): ?>
                        <a href="#" class="btn-marcar-realizada" data-plccod="<?php echo $r->PlcCod; ?>" data-codpresupuesto="<?php echo $r->cod_presupuesto; ?>" title="Marcar como Realizada">
                            <i class="fa-solid fa-circle-check" style="color:#2e7d32;"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=cirugia&page=<?php echo $i; ?>&<?php echo http_build_query($filtros); ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>

<!-- Modal Aceptar / Programar CX -->
<div class="modal fade" id="modalAceptarCx" tabindex="-1" role="dialog" aria-labelledby="modalAceptarCxLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #206773 0%, #174b54 100%); color: white; padding: 20px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalAceptarCxLabel" style="font-weight: bold;">
          <i class="fa-solid fa-calendar-days"></i> Programar Cirugía
        </h4>
      </div>
      <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
        <form id="frm-aceptar-cx">
            <input type="hidden" name="cod_presupuesto" id="acx_cod_presupuesto" />
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Fecha de la Cirugía</label>
                <input type="date" name="fecha" id="acx_fecha" class="form-control" required>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Hora</label>
                <input type="time" name="hora" id="acx_hora" class="form-control">
            </div>
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Materiales a necesitar</label>
                <textarea name="materiales" id="acx_materiales" class="form-control" rows="4" placeholder="Detalle libre de los materiales necesarios para la cirugía..."></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btn-guardar-aceptar-cx" class="btn btn-primary" style="background-color: #206773; border: none;">
          <i class="fa-solid fa-check"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Rechazar -->
<div class="modal fade" id="modalRechazar" tabindex="-1" role="dialog" aria-labelledby="modalRechazarLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #c62828 0%, #8e0000 100%); color: white; padding: 20px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalRechazarLabel" style="font-weight: bold;">
          <i class="fa-solid fa-ban"></i> Rechazar Presupuesto
        </h4>
      </div>
      <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
        <form id="frm-rechazar">
            <input type="hidden" name="cod_presupuesto" id="rch_cod_presupuesto" />
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Motivo</label>
                <select name="SueCod" id="rch_motivo" class="form-control" required>
                    <option value="">Seleccione un motivo...</option>
                    <?php foreach($motivosAnulacion as $m): ?>
                        <option value="<?php echo $m->SueCod; ?>"><?php echo htmlspecialchars($m->SueDes); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Comentario</label>
                <textarea name="PresupVndCom" id="rch_comentario" class="form-control" rows="3"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btn-guardar-rechazar" class="btn btn-danger">
          <i class="fa-solid fa-ban"></i> Rechazar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Consumo -->
<div class="modal fade" id="modalConsumo" tabindex="-1" role="dialog" aria-labelledby="modalConsumoLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #206773 0%, #174b54 100%); color: white; padding: 20px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalConsumoLabel" style="font-weight: bold;">
          <i class="fa-solid fa-boxes-packing"></i> Consumo de la Cirugía
        </h4>
      </div>
      <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
        <form id="frm-consumo">
            <input type="hidden" name="PlcCod" id="con_plccod" />
            <div class="form-group">
                <label style="font-weight: 600; color: #475569;">Consumo (texto libre)</label>
                <textarea name="Consumo" id="con_texto" class="form-control" rows="6" placeholder="Detalle de los materiales efectivamente consumidos..."></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btn-guardar-consumo" class="btn btn-primary" style="background-color: #206773; border: none;">
          <i class="fa-solid fa-check"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Facturas -->
<div class="modal fade" id="modalFacturas" tabindex="-1" role="dialog" aria-labelledby="modalFacturasLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #206773 0%, #174b54 100%); color: white; padding: 20px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalFacturasLabel" style="font-weight: bold;">
          <i class="fa-solid fa-file-invoice-dollar"></i> Facturas de la Cirugía
        </h4>
      </div>
      <div class="modal-body" style="padding: 25px; background-color: #f8fafc;">
        <input type="hidden" id="fac_plccod" />
        <table class="table table-striped">
            <thead>
                <tr><th>N° Fac</th><th>Letra</th><th>Fecha</th><th>Importe</th><th></th></tr>
            </thead>
            <tbody id="tbody-facturas">
                <tr><td colspan="5">Cargando...</td></tr>
            </tbody>
        </table>
        <hr>
        <div class="filters-grid">
            <div class="filter-group">
                <label>N° Factura</label>
                <input type="text" id="fac_nro" class="form-control">
            </div>
            <div class="filter-group">
                <label>Letra</label>
                <input type="text" id="fac_letra" class="form-control" placeholder="A, B, C...">
            </div>
            <div class="filter-group">
                <label>Fecha</label>
                <input type="date" id="fac_fecha" class="form-control">
            </div>
            <div class="filter-group">
                <label>Importe</label>
                <input type="number" step="0.01" id="fac_importe" class="form-control">
            </div>
        </div>
        <button type="button" class="btn btn-filter-search" id="btn-agregar-factura" style="margin-top:10px;"><i class="fa-solid fa-plus"></i> Agregar Factura</button>
      </div>
      <div class="modal-footer" style="background-color: #f1f5f9; padding: 15px 25px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
function pintarFacturas(facturas) {
    var $tbody = $('#tbody-facturas');
    $tbody.empty();
    if (!facturas || facturas.length === 0) {
        $tbody.append('<tr><td colspan="5">Todavía no se cargaron facturas para esta CX.</td></tr>');
        return;
    }
    facturas.forEach(function(f) {
        var anulada = f.Anulada == 1;
        var estilo = anulada ? ' style="text-decoration:line-through;color:#999;"' : '';
        var accion = anulada
            ? '<span class="label label-default">Anulada</span>'
            : '<a href="#" class="btn-anular-factura" data-faccod="' + f.FacCod + '" title="Anular"><i class="fa-solid fa-ban" style="color:red;"></i></a>';
        $tbody.append(
            '<tr' + estilo + '>' +
            '<td>' + $('<div>').text(f.NroFac).html() + '</td>' +
            '<td>' + $('<div>').text(f.TipoFac).html() + '</td>' +
            '<td>' + f.FechaFac + '</td>' +
            '<td>$' + parseFloat(f.ImporteFac).toFixed(2) + '</td>' +
            '<td>' + accion + '</td>' +
            '</tr>'
        );
    });
}

function cargarFacturas(plcCod) {
    $.ajax({
        url: '?c=cirugia&a=ListarFacturasJson',
        method: 'GET',
        data: { PlcCod: plcCod },
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                pintarFacturas(resp.facturas);
            }
        }
    });
}

$(document).ready(function() {
    $('.btn-aceptar-cx').click(function(e) {
        e.preventDefault();
        $('#acx_cod_presupuesto').val($(this).data('id'));
        $('#acx_fecha').val($(this).data('fecha'));
        $('#acx_hora').val($(this).data('hora'));
        $('#acx_materiales').val($(this).data('materiales'));
        $('#modalAceptarCx').modal('show');
    });

    $('#btn-guardar-aceptar-cx').click(function() {
        var fecha = $('#acx_fecha').val();
        if (!fecha) {
            alert('Debe indicar la fecha de la cirugía.');
            return;
        }
        $.ajax({
            url: '?c=cirugia&a=Aceptar',
            method: 'POST',
            data: $('#frm-aceptar-cx').serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modalAceptarCx').modal('hide');
                    location.reload();
                } else {
                    alert(resp.message || 'No se pudo guardar.');
                }
            },
            error: function() { alert('Error de comunicación con el servidor.'); }
        });
    });

    $('.btn-rechazar').click(function(e) {
        e.preventDefault();
        $('#rch_cod_presupuesto').val($(this).data('id'));
        $('#rch_motivo').val('');
        $('#rch_comentario').val('');
        $('#modalRechazar').modal('show');
    });

    $('#btn-guardar-rechazar').click(function() {
        if (!$('#rch_motivo').val()) {
            alert('Debe seleccionar un motivo.');
            return;
        }
        if (!confirm('¿Confirma que desea rechazar este presupuesto?')) return;
        $.ajax({
            url: '?c=cirugia&a=Rechazar',
            method: 'POST',
            data: $('#frm-rechazar').serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modalRechazar').modal('hide');
                    location.reload();
                } else {
                    alert(resp.message || 'No se pudo rechazar.');
                }
            },
            error: function() { alert('Error de comunicación con el servidor.'); }
        });
    });

    $('.btn-marcar-realizada').click(function(e) {
        e.preventDefault();
        if (!confirm('¿Confirma que esta cirugía fue realizada?')) return;
        var plcCod = $(this).data('plccod');
        var codPresupuesto = $(this).data('codpresupuesto');
        $.ajax({
            url: '?c=cirugia&a=MarcarRealizada',
            method: 'POST',
            data: { PlcCod: plcCod, cod_presupuesto: codPresupuesto },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    location.reload();
                } else {
                    alert(resp.message || 'No se pudo actualizar.');
                }
            },
            error: function() { alert('Error de comunicación con el servidor.'); }
        });
    });

    $('.btn-consumo').click(function(e) {
        e.preventDefault();
        $('#con_plccod').val($(this).data('plccod'));
        $('#con_texto').val($(this).data('consumo'));
        $('#modalConsumo').modal('show');
    });

    $('#btn-guardar-consumo').click(function() {
        $.ajax({
            url: '?c=cirugia&a=GuardarConsumo',
            method: 'POST',
            data: $('#frm-consumo').serialize(),
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modalConsumo').modal('hide');
                    location.reload();
                } else {
                    alert(resp.message || 'No se pudo guardar.');
                }
            },
            error: function() { alert('Error de comunicación con el servidor.'); }
        });
    });

    $('.btn-facturas').click(function(e) {
        e.preventDefault();
        var plcCod = $(this).data('plccod');
        $('#fac_plccod').val(plcCod);
        $('#fac_nro, #fac_letra, #fac_importe').val('');
        $('#fac_fecha').val('<?php echo date('Y-m-d'); ?>');
        $('#tbody-facturas').html('<tr><td colspan="5">Cargando...</td></tr>');
        $('#modalFacturas').modal('show');
        cargarFacturas(plcCod);
    });

    $('#btn-agregar-factura').click(function() {
        var nro = $('#fac_nro').val();
        var fecha = $('#fac_fecha').val();
        if (!nro || !fecha) { alert('Debe indicar N° de factura y fecha.'); return; }

        var plcCod = $('#fac_plccod').val();
        $.ajax({
            url: '?c=cirugia&a=GuardarFactura',
            method: 'POST',
            data: {
                PlcCod: plcCod, NroFac: nro, TipoFac: $('#fac_letra').val(),
                FechaFac: fecha, ImporteFac: $('#fac_importe').val() || 0
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#fac_nro, #fac_letra, #fac_importe').val('');
                    cargarFacturas(plcCod);
                } else {
                    alert(resp.message || 'No se pudo guardar la factura.');
                }
            },
            error: function() { alert('Error de comunicación con el servidor.'); }
        });
    });

    $(document).on('click', '.btn-anular-factura', function(e) {
        e.preventDefault();
        if (!confirm('¿Anular esta factura?')) return;
        var facCod = $(this).data('faccod');
        var plcCod = $('#fac_plccod').val();
        $.ajax({
            url: '?c=cirugia&a=AnularFactura',
            method: 'POST',
            data: { FacCod: facCod },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) { cargarFacturas(plcCod); }
            }
        });
    });
});
</script>
