<h1 class="page-header">
    <?php echo $alm->FfaCod != null ? 'Editar Forma de Pago' : 'Nueva Forma de Pago'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=sisffa">Formas de Pago</a></li>
  <li class="active"><?php echo $alm->FfaCod != null ? 'Editar' : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=sisffa&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="FfaCod" value="<?php echo htmlspecialchars($alm->FfaCod); ?>" />
    <?php if ($alm->FfaCod != null): ?>
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->FfaCod); ?>" readonly />
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Forma de Pago</label>
        <input type="text" name="FfaDesc" value="<?php echo htmlspecialchars($alm->FfaDesc); ?>" class="form-control" placeholder="Ingrese la forma de pago" required />
    </div>
    <div class="form-group">
        <label>Días de Validez</label>
        <input type="number" name="FfaFec" value="<?php echo htmlspecialchars($alm->FfaFec); ?>" class="form-control" placeholder="Cantidad de días" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
