<h1 class="page-header">
    <?php echo $alm->cod_producto != null ? $alm->cod_producto : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=productos">Productos</a></li>
  <li class="active"><?php echo $alm->cod_producto != null ? $alm->cod_producto : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=productos&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_producto" value="<?php echo htmlspecialchars($alm->cod_producto); ?>" />
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->cod_producto); ?>" readonly />
    </div>
    <div class="form-group">
        <label>Detalle</label>
        <input type="text" name="detalle" value="<?php echo htmlspecialchars($alm->detalle); ?>" class="form-control" placeholder="Ingrese detalle" />
    </div>
    <div class="form-group">
        <label>Título</label>
        <input type="text" name="producto_titulo" value="<?php echo htmlspecialchars($alm->producto_titulo); ?>" class="form-control" placeholder="Ingrese producto_titulo" />
    </div>
    <div class="form-group">
        <label>Precio</label>
        <input type="text" name="producto_precio" value="<?php echo htmlspecialchars($alm->producto_precio); ?>" class="form-control" placeholder="Ingrese producto_precio" />
    </div>
    <div class="form-group">
        <label>Precio Diferenciado</label>
        <input type="text" name="productoPrecDis" value="<?php echo htmlspecialchars($alm->productoPrecDis); ?>" class="form-control" placeholder="Ingrese productoPrecDis" />
    </div>
    <div class="form-group">
        <label>Categoría</label>
        <input type="text" id="categoria_suggest" name="categoria_nombre" value="<?php echo htmlspecialchars(!empty($alm->categoria_nombre) ? $alm->categoria_nombre : $alm->CptId); ?>" class="form-control" placeholder="Ingrese categoría" />
    </div>
    <div class="form-group">
        <label>Código Categoría</label>
        <input type="text" id="CptId" name="CptId" value="<?php echo htmlspecialchars($alm->CptId); ?>" class="form-control" readonly placeholder="Código Categoría (Se autocompleta)" />
    </div>
    <div class="form-group">
        <label>Fecha Aviso</label>
        <input type="text" name="producto_fechaaviso" value="<?php echo htmlspecialchars($alm->producto_fechaaviso); ?>" class="form-control" placeholder="Ingrese producto_fechaaviso" />
    </div>
    <div class="form-group">
        <label>producto_aviso</label>
        <input type="text" name="producto_aviso" value="<?php echo htmlspecialchars($alm->producto_aviso); ?>" class="form-control" placeholder="Ingrese producto_aviso" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>

<script>
$(document).ready(function() {
    var options = {
        url: function(phrase) {
            return "view/buscacategoriaproducto.php?phrase=" + phrase + "&format=json";
        },
        getValue: function(element) {
            return element.name;
        },
        list: {
            maxNumberOfElements: 12,
            onSelectItemEvent: function() {
                var code = $("#categoria_suggest").getSelectedItemData().code;
                $("#CptId").val(code).trigger("change");
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
});
</script>
