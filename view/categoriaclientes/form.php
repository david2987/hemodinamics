<h1 class="page-header">
    <?php echo $alm->CcliCod != null ? $alm->CcliCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=categoriaclientes">Categorías Clientes</a></li>
  <li class="active"><?php echo $alm->CcliCod != null ? $alm->CcliCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=categoriaclientes&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="CcliCod" value="<?php echo htmlspecialchars($alm->CcliCod); ?>" />
    <div class="form-group">
        <label>Código</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->CcliCod); ?>" readonly />
    </div>
    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="CcliDes" value="<?php echo htmlspecialchars($alm->CcliDes); ?>" class="form-control" placeholder="Ingrese CcliDes" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
