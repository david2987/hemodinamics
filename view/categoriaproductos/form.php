<h1 class="page-header">
    <?php echo $alm->CptId != null ? 'Editar Registro' : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=categoriaproductos">Categorías Productos</a></li>
  <li class="active"><?php echo $alm->CptId != null ? 'Editar' : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=categoriaproductos&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="CptId" value="<?php echo htmlspecialchars($alm->CptId); ?>" />
    <?php if ($alm->CptId != null): ?>
    <div class="form-group">
        <label>ID</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->CptId); ?>" readonly />
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="CptDes" value="<?php echo htmlspecialchars($alm->CptDes); ?>" class="form-control" placeholder="Ingrese descripción (Ej. Catéteres)" required />
    </div>
    <div class="form-group">
        <label>Abreviación (Máx 4 caracteres)</label>
        <input type="text" name="CptAbv" value="<?php echo htmlspecialchars($alm->CptAbv); ?>" class="form-control" placeholder="Ingrese abreviación (Ej. CATE)" maxlength="4" required />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
