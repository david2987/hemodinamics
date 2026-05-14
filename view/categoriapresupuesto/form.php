<h1 class="page-header">
    <?php echo $alm->CprCod != null ? $alm->CprCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=categoriapresupuesto">Categorías Presupuesto</a></li>
  <li class="active"><?php echo $alm->CprCod != null ? $alm->CprCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=categoriapresupuesto&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="CprCod" value="<?php echo htmlspecialchars($alm->CprCod); ?>" />
    <div class="form-group">
        <label>CprCod</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->CprCod); ?>" readonly />
    </div>
    <div class="form-group">
        <label>CprDes</label>
        <input type="text" name="CprDes" value="<?php echo htmlspecialchars($alm->CprDes); ?>" class="form-control" placeholder="Ingrese CprDes" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
