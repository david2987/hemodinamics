<h1 class="page-header">
    <?php echo $alm->HospCod != null ? $alm->HospCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=hospitales">Instituciones</a></li>
  <li class="active"><?php echo $alm->HospCod != null ? $alm->HospCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=hospitales&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="HospCod" value="<?php echo htmlspecialchars($alm->HospCod); ?>" />
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->HospCod); ?>" readonly />
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="HospDesc" value="<?php echo htmlspecialchars($alm->HospDesc); ?>" class="form-control" placeholder="Ingrese HospDesc" />
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="text" name="HospMail" value="<?php echo htmlspecialchars($alm->HospMail); ?>" class="form-control" placeholder="Ingrese HospMail" />
    </div>
    <div class="form-group">
        <label>CUIT</label>
        <input type="text" name="HospCUIT" value="<?php echo htmlspecialchars($alm->HospCUIT); ?>" class="form-control" placeholder="Ingrese HospCUIT" />
    </div>
    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="HospTel" value="<?php echo htmlspecialchars($alm->HospTel); ?>" class="form-control" placeholder="Ingrese HospTel" />
    </div>
    <div class="form-group">
        <label>Domicilio</label>
        <input type="text" name="HospDom" value="<?php echo htmlspecialchars($alm->HospDom); ?>" class="form-control" placeholder="Ingrese HospDom" />
    </div>
    <div class="form-group">
        <label>CUFE</label>
        <input type="text" name="HospCUFE" value="<?php echo htmlspecialchars($alm->HospCUFE); ?>" class="form-control" placeholder="Ingrese HospCUFE" />
    </div>
    <div class="form-group">
        <label>Localidad</label>
        <input type="text" name="HospLoc" value="<?php echo htmlspecialchars($alm->HospLoc); ?>" class="form-control" placeholder="Ingrese HospLoc" />
    </div>
    <div class="form-group">
        <label>Descripción 2</label>
        <input type="text" name="HospDesc2" value="<?php echo htmlspecialchars($alm->HospDesc2); ?>" class="form-control" placeholder="Ingrese HospDesc2" />
    </div>
    <div class="form-group">
        <label>Código Localidad</label>
        <input type="text" name="HospLocCod" value="<?php echo htmlspecialchars($alm->HospLocCod); ?>" class="form-control" placeholder="Ingrese HospLocCod" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
