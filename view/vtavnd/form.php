<h1 class="page-header">
    <?php echo $alm->VndCod != null ? $alm->VndCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=vtavnd">Coordinadores</a></li>
  <li class="active"><?php echo $alm->VndCod != null ? $alm->VndCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=vtavnd&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="VndCod" value="<?php echo htmlspecialchars($alm->VndCod); ?>" />
    <div class="form-group">
        <label>VndCod</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->VndCod); ?>" readonly />
    </div>
    <div class="form-group">
        <label>VndNom</label>
        <input type="text" name="VndNom" value="<?php echo htmlspecialchars($alm->VndNom); ?>" class="form-control" placeholder="Ingrese VndNom" />
    </div>
    <div class="form-group">
        <label>VndDir</label>
        <input type="text" name="VndDir" value="<?php echo htmlspecialchars($alm->VndDir); ?>" class="form-control" placeholder="Ingrese VndDir" />
    </div>
    <div class="form-group">
        <label>VndTel</label>
        <input type="text" name="VndTel" value="<?php echo htmlspecialchars($alm->VndTel); ?>" class="form-control" placeholder="Ingrese VndTel" />
    </div>
    <div class="form-group">
        <label>VndCel</label>
        <input type="text" name="VndCel" value="<?php echo htmlspecialchars($alm->VndCel); ?>" class="form-control" placeholder="Ingrese VndCel" />
    </div>
    <div class="form-group">
        <label>VndCom</label>
        <input type="text" name="VndCom" value="<?php echo htmlspecialchars($alm->VndCom); ?>" class="form-control" placeholder="Ingrese VndCom" />
    </div>
    <div class="form-group">
        <label>VndMai</label>
        <input type="text" name="VndMai" value="<?php echo htmlspecialchars($alm->VndMai); ?>" class="form-control" placeholder="Ingrese VndMai" />
    </div>
    <div class="form-group">
        <label>VndUsr</label>
        <input type="text" name="VndUsr" value="<?php echo htmlspecialchars($alm->VndUsr); ?>" class="form-control" placeholder="Ingrese VndUsr" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
