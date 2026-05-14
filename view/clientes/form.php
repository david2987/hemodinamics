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
        <label>cod_cliente</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->cod_cliente); ?>" readonly />
    </div>
    <div class="form-group">
        <label>nombre</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($alm->nombre); ?>" class="form-control" placeholder="Ingrese nombre" />
    </div>
    <div class="form-group">
        <label>domicilio</label>
        <input type="text" name="domicilio" value="<?php echo htmlspecialchars($alm->domicilio); ?>" class="form-control" placeholder="Ingrese domicilio" />
    </div>
    <div class="form-group">
        <label>localidad</label>
        <input type="text" name="localidad" value="<?php echo htmlspecialchars($alm->localidad); ?>" class="form-control" placeholder="Ingrese localidad" />
    </div>
    <div class="form-group">
        <label>cod_postal</label>
        <input type="text" name="cod_postal" value="<?php echo htmlspecialchars($alm->cod_postal); ?>" class="form-control" placeholder="Ingrese cod_postal" />
    </div>
    <div class="form-group">
        <label>telefono</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($alm->telefono); ?>" class="form-control" placeholder="Ingrese telefono" />
    </div>
    <div class="form-group">
        <label>celular</label>
        <input type="text" name="celular" value="<?php echo htmlspecialchars($alm->celular); ?>" class="form-control" placeholder="Ingrese celular" />
    </div>
    <div class="form-group">
        <label>email</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($alm->email); ?>" class="form-control" placeholder="Ingrese email" />
    </div>
    <div class="form-group">
        <label>cuit</label>
        <input type="text" name="cuit" value="<?php echo htmlspecialchars($alm->cuit); ?>" class="form-control" placeholder="Ingrese cuit" />
    </div>
    <div class="form-group">
        <label>iva</label>
        <input type="text" name="iva" value="<?php echo htmlspecialchars($alm->iva); ?>" class="form-control" placeholder="Ingrese iva" />
    </div>
    <div class="form-group">
        <label>CliNomCon1</label>
        <input type="text" name="CliNomCon1" value="<?php echo htmlspecialchars($alm->CliNomCon1); ?>" class="form-control" placeholder="Ingrese CliNomCon1" />
    </div>
    <div class="form-group">
        <label>CliTelCon1</label>
        <input type="text" name="CliTelCon1" value="<?php echo htmlspecialchars($alm->CliTelCon1); ?>" class="form-control" placeholder="Ingrese CliTelCon1" />
    </div>
    <div class="form-group">
        <label>CliMaiCon1</label>
        <input type="text" name="CliMaiCon1" value="<?php echo htmlspecialchars($alm->CliMaiCon1); ?>" class="form-control" placeholder="Ingrese CliMaiCon1" />
    </div>
    <div class="form-group">
        <label>CliNomCon2</label>
        <input type="text" name="CliNomCon2" value="<?php echo htmlspecialchars($alm->CliNomCon2); ?>" class="form-control" placeholder="Ingrese CliNomCon2" />
    </div>
    <div class="form-group">
        <label>CliTelCon2</label>
        <input type="text" name="CliTelCon2" value="<?php echo htmlspecialchars($alm->CliTelCon2); ?>" class="form-control" placeholder="Ingrese CliTelCon2" />
    </div>
    <div class="form-group">
        <label>CliMaiCon2</label>
        <input type="text" name="CliMaiCon2" value="<?php echo htmlspecialchars($alm->CliMaiCon2); ?>" class="form-control" placeholder="Ingrese CliMaiCon2" />
    </div>
    <div class="form-group">
        <label>CliNomCon3</label>
        <input type="text" name="CliNomCon3" value="<?php echo htmlspecialchars($alm->CliNomCon3); ?>" class="form-control" placeholder="Ingrese CliNomCon3" />
    </div>
    <div class="form-group">
        <label>CliTelCon3</label>
        <input type="text" name="CliTelCon3" value="<?php echo htmlspecialchars($alm->CliTelCon3); ?>" class="form-control" placeholder="Ingrese CliTelCon3" />
    </div>
    <div class="form-group">
        <label>CliMaiCon3</label>
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
        <label>CliLocCod</label>
        <input type="text" name="CliLocCod" value="<?php echo htmlspecialchars($alm->CliLocCod); ?>" class="form-control" placeholder="Ingrese CliLocCod" />
    </div>
    <div class="form-group">
        <label>CcliCod</label>
        <input type="text" name="CcliCod" value="<?php echo htmlspecialchars($alm->CcliCod); ?>" class="form-control" placeholder="Ingrese CcliCod" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
