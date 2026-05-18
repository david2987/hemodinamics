<h1 class="page-header">
    <?php echo $alm->GruCod != null ? 'Editar Grupo' : 'Nuevo Grupo'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=sisgru">Grupos de Usuarios</a></li>
  <li class="active"><?php echo $alm->GruCod != null ? 'Editar' : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=sisgru&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="GruCod" value="<?php echo htmlspecialchars($alm->GruCod); ?>" />
    <?php if ($alm->GruCod != null): ?>
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->GruCod); ?>" readonly />
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="GruDsc" value="<?php echo htmlspecialchars($alm->GruDsc); ?>" class="form-control" placeholder="Ingrese descripción (Ej. Administradores)" required />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
