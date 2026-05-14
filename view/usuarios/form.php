<h1 class="page-header">
    <?php echo $alm->UsrCod != null ? $alm->UsrCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=usuarios">Usuarios</a></li>
  <li class="active"><?php echo $alm->UsrCod != null ? $alm->UsrCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=usuarios&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="UsrCod" value="<?php echo htmlspecialchars($alm->UsrCod); ?>" />
    <div class="form-group">
        <label>UsrCod</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->UsrCod); ?>" readonly />
    </div>
    <div class="form-group">
        <label>UsrPas</label>
        <input type="text" name="UsrPas" value="<?php echo htmlspecialchars($alm->UsrPas); ?>" class="form-control" placeholder="Ingrese UsrPas" />
    </div>
    <div class="form-group">
        <label>UsrInf</label>
        <input type="text" name="UsrInf" value="<?php echo htmlspecialchars($alm->UsrInf); ?>" class="form-control" placeholder="Ingrese UsrInf" />
    </div>
    <div class="form-group">
        <label>GruCod</label>
        <input type="text" name="GruCod" value="<?php echo htmlspecialchars($alm->GruCod); ?>" class="form-control" placeholder="Ingrese GruCod" />
    </div>
    <div class="form-group">
        <label>UsrAdm</label>
        <input type="text" name="UsrAdm" value="<?php echo htmlspecialchars($alm->UsrAdm); ?>" class="form-control" placeholder="Ingrese UsrAdm" />
    </div>
    <div class="form-group">
        <label>SucCod</label>
        <input type="text" name="SucCod" value="<?php echo htmlspecialchars($alm->SucCod); ?>" class="form-control" placeholder="Ingrese SucCod" />
    </div>
    <div class="form-group">
        <label>UsrAct</label>
        <input type="text" name="UsrAct" value="<?php echo htmlspecialchars($alm->UsrAct); ?>" class="form-control" placeholder="Ingrese UsrAct" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
