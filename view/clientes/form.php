<h1 class="page-header">
    <?php echo $alm->cod_cliente != null ? $alm->cod_cliente : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=clientes">Clientes</a></li>
  <li class="active"><?php echo $alm->cod_cliente != null ? $alm->cod_cliente : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=clientes&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_cliente" value="<?php echo htmlspecialchars($alm->cod_cliente); ?>" />
    <div class="form-group">
        <label>Código Cliente</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->cod_cliente); ?>" readonly />
    </div>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($alm->nombre); ?>" class="form-control" placeholder="Ingrese nombre" />
    </div>
    <div class="form-group">
        <label>Domicilio</label>
        <input type="text" name="domicilio" value="<?php echo htmlspecialchars($alm->domicilio); ?>" class="form-control" placeholder="Ingrese domicilio" />
    </div>
    <div class="form-group">
        <label>Localidad</label>
        <input type="text" id="localidad_suggest" name="localidad" value="<?php echo htmlspecialchars(!empty($alm->localidad_nombre) ? $alm->localidad_nombre : $alm->localidad); ?>" class="form-control" placeholder="Ingrese localidad" />
    </div>
    <div class="form-group">
        <label>Cod. Postal</label>
        <input type="text" name="cod_postal" value="<?php echo htmlspecialchars($alm->cod_postal); ?>" class="form-control" placeholder="Ingrese cod_postal" />
    </div>
    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($alm->telefono); ?>" class="form-control" placeholder="Ingrese telefono" />
    </div>
    <div class="form-group">
        <label>celular</label>
        <input type="text" name="celular" value="<?php echo htmlspecialchars($alm->celular); ?>" class="form-control" placeholder="Ingrese celular" />
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($alm->email); ?>" class="form-control" placeholder="Ingrese email" />
    </div>
    <div class="form-group">
        <label>CUIT</label>
        <input type="text" name="cuit" value="<?php echo htmlspecialchars($alm->cuit); ?>" class="form-control" placeholder="Ingrese cuit" />
    </div>
    <div class="form-group">
        <label>IVA</label>
        <input type="text" name="iva" value="<?php echo htmlspecialchars($alm->iva); ?>" class="form-control" placeholder="Ingrese iva" />
    </div>
    <div class="form-group">
        <label>Contacto 1</label>
        <input type="text" name="CliNomCon1" value="<?php echo htmlspecialchars($alm->CliNomCon1); ?>" class="form-control" placeholder="Ingrese CliNomCon1" />
    </div>
    <div class="form-group">
        <label>Telefono 1</label>
        <input type="text" name="CliTelCon1" value="<?php echo htmlspecialchars($alm->CliTelCon1); ?>" class="form-control" placeholder="Ingrese CliTelCon1" />
    </div>
    <div class="form-group">
        <label>Email 1</label>
        <input type="text" name="CliMaiCon1" value="<?php echo htmlspecialchars($alm->CliMaiCon1); ?>" class="form-control" placeholder="Ingrese CliMaiCon1" />
    </div>
    <div class="form-group">
        <label>Contacto 2</label>
        <input type="text" name="CliNomCon2" value="<?php echo htmlspecialchars($alm->CliNomCon2); ?>" class="form-control" placeholder="Ingrese CliNomCon2" />
    </div>
    <div class="form-group">
        <label>Telefono 2</label>
        <input type="text" name="CliTelCon2" value="<?php echo htmlspecialchars($alm->CliTelCon2); ?>" class="form-control" placeholder="Ingrese CliTelCon2" />
    </div>
    <div class="form-group">
        <label>Email 2</label>
        <input type="text" name="CliMaiCon2" value="<?php echo htmlspecialchars($alm->CliMaiCon2); ?>" class="form-control" placeholder="Ingrese CliMaiCon2" />
    </div>
    <div class="form-group">
        <label>Contacto 3</label>
        <input type="text" name="CliNomCon3" value="<?php echo htmlspecialchars($alm->CliNomCon3); ?>" class="form-control" placeholder="Ingrese CliNomCon3" />
    </div>
    <div class="form-group">
        <label>Telefono 3</label>
        <input type="text" name="CliTelCon3" value="<?php echo htmlspecialchars($alm->CliTelCon3); ?>" class="form-control" placeholder="Ingrese CliTelCon3" />
    </div>
    <div class="form-group">
        <label>Email 3</label>
        <input type="text" name="CliMaiCon3" value="<?php echo htmlspecialchars($alm->CliMaiCon3); ?>" class="form-control" placeholder="Ingrese CliMaiCon3" />
    </div>
    <div class="form-group">
        <label>CliNomCon4</label>
        <input type="text" name="CliNomCon4" value="<?php echo htmlspecialchars($alm->CliNomCon4); ?>" class="form-control" placeholder="Ingrese CliNomCon4" />
    </div>
    <div class="form-group">
        <label>CliTelCon4</label>
        <input type="text" name="CliTelCon4" value="<?php echo htmlspecialchars($alm->CliTelCon4); ?>" class="form-control" placeholder="Ingrese CliTelCon4" />
    </div>
    <div class="form-group">
        <label>CliMaiCon4</label>
        <input type="text" name="CliMaiCon4" value="<?php echo htmlspecialchars($alm->CliMaiCon4); ?>" class="form-control" placeholder="Ingrese CliMaiCon4" />
    </div>
    <div class="form-group">
        <label>Código Localidad</label>
        <input type="text" id="CliLocCod" name="CliLocCod" value="<?php echo htmlspecialchars($alm->CliLocCod); ?>" class="form-control" readonly placeholder="Código Localidad (Se autocompleta)" />
    </div>
    <div class="form-group">
        <label>Categoría Cliente</label>
        <input type="text" name="CcliCod" value="<?php echo htmlspecialchars($alm->CcliCod); ?>" class="form-control" placeholder="Ingrese CcliCod" />
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
            return "view/buscalocalidad.php?phrase=" + phrase + "&format=json";
        },
        getValue: function(element) {
            return element.name;
        },
        list: {
            maxNumberOfElements: 12,
            onSelectItemEvent: function() {
                var code = $("#localidad_suggest").getSelectedItemData().code;
                $("#CliLocCod").val(code).trigger("change");
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
    $("#localidad_suggest").easyAutocomplete(options);
});
</script>
