<h1 class="page-header">
    <?php echo !empty($alm->cod_producto) ? 'Editar Producto: ' . $alm->cod_producto : 'Nuevo Producto'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=productos">Productos</a></li>
  <li class="active"><?php echo !empty($alm->cod_producto) ? 'Editar Registro' : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=productos&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($alm->cod_producto); ?>" />

    <div class="panel panel-default">
        <div class="panel-heading">Información del Producto</div>
        <div class="panel-body">
            <div class="row">
                <?php if (!empty($alm->cod_producto)): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->cod_producto); ?>" readonly />
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Detalle</label>
                        <input type="text" name="detalle" value="<?php echo htmlspecialchars($alm->detalle); ?>" class="form-control" placeholder="Ingrese detalle" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="producto_titulo" value="<?php echo htmlspecialchars($alm->producto_titulo); ?>" class="form-control" placeholder="Ingrese título" required />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="producto_precio" value="<?php echo htmlspecialchars($alm->producto_precio); ?>" class="form-control" placeholder="0.00" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Precio Diferenciado</label>
                        <input type="number" step="0.01" name="productoPrecDis" value="<?php echo htmlspecialchars($alm->productoPrecDis); ?>" class="form-control" placeholder="0.00" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Fecha Aviso</label>
                        <input type="date" name="producto_fechaaviso" value="<?php echo !empty($alm->producto_fechaaviso) && $alm->producto_fechaaviso != '0000-00-00' ? $alm->producto_fechaaviso : ''; ?>" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Categoría</label>
                        <input type="text" id="categoria_suggest" name="categoria_nombre" value="<?php echo htmlspecialchars(!empty($alm->categoria_nombre) ? $alm->categoria_nombre : ''); ?>" class="form-control" placeholder="Buscar categoría..." />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Código Categoría</label>
                        <input type="text" id="CptId" name="CptId" value="<?php echo htmlspecialchars($alm->CptId); ?>" class="form-control" readonly placeholder="Se autocompleta" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Aviso</label>
                        <input type="text" name="producto_aviso" value="<?php echo htmlspecialchars($alm->producto_aviso); ?>" class="form-control" placeholder="Ingrese aviso" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />
    <div class="text-right">
        <a class="btn btn-default" href="?c=productos">Cancelar</a>
        <button class="btn btn-success">Guardar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    var selectedCategoryName = $("#categoria_suggest").val();
    var options = {
        url: function(phrase) {
            return "view/buscacategoriaproducto.php?phrase=" + phrase;
        },
        getValue: function(element) {
            return element.name;
        },
        list: {
            maxNumberOfElements: 12,
            onSelectItemEvent: function() {
                var data = $("#categoria_suggest").getSelectedItemData();
                $("#CptId").val(data.code).trigger("change");
                selectedCategoryName = data.name;
            },
            match: {
                enabled: true
            },
            sort: {
                enabled: true
            }
        },
        theme: "plate-dark"
    };
    $("#categoria_suggest").easyAutocomplete(options);

    $("#categoria_suggest").on('input', function() {
        if ($(this).val() !== selectedCategoryName) {
            $("#CptId").val('');
        }
    });
});
</script>
