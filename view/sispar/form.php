<h1 class="page-header">
    <?php echo $alm->ParCod != null ? 'Editar Parámetro' : 'Nuevo Parámetro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=sispar">Parámetros del Sistema</a></li>
  <li class="active"><?php echo $alm->ParCod != null ? 'Editar' : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=sispar&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="ParCod" value="<?php echo htmlspecialchars($alm->ParCod); ?>" />
    <?php if ($alm->ParCod != null): ?>
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->ParCod); ?>" readonly />
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="ParDsc" value="<?php echo htmlspecialchars($alm->ParDsc); ?>" class="form-control" placeholder="Ingrese descripción del parámetro" required />
    </div>
    <div class="form-group">
        <label>Valor Numérico</label>
        <input type="number" name="ParVarNum" value="<?php echo htmlspecialchars($alm->ParVarNum); ?>" class="form-control" placeholder="0" />
    </div>
    <div class="form-group">
        <label>Valor Texto</label>
        <input type="text" name="ParVarChr" value="<?php echo htmlspecialchars($alm->ParVarChr); ?>" class="form-control" placeholder="Ingrese valor texto del parámetro" required />
    </div>
    <div class="form-group">
        <label>Fecha</label>
        <input type="date" name="ParFec" value="<?php echo !empty($alm->ParFec) && $alm->ParFec != '0000-00-00' ? $alm->ParFec : date('Y-m-d'); ?>" class="form-control" />
    </div>
    <div class="form-group">
        <label>Usuario</label>
        <input type="text" name="ParUsr" value="<?php echo htmlspecialchars($alm->ParUsr); ?>" class="form-control" placeholder="Código de usuario" required />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
