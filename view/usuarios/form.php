<h1 class="page-header">
    <?php echo $alm->UsrCod != null ? $alm->UsrCod : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=usuarios">Usuarios</a></li>
  <li class="active"><?php echo $alm->UsrCod != null ? $alm->UsrCod : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=usuarios&a=Guardar" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Código de Usuario</label>
        <input type="text" name="UsrCod" class="form-control" value="<?php echo htmlspecialchars($alm->UsrCod); ?>" <?php echo $alm->UsrCod != null ? 'readonly' : 'required'; ?> placeholder="Ingrese Código de Usuario (Ej. admin)" />
    </div>
    <div class="form-group">
        <label>Contraseña</label>
        <input type="text" name="UsrPas" value="<?php echo htmlspecialchars($alm->UsrPas); ?>" class="form-control" placeholder="Ingrese UsrPas" required />
    </div>
    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="UsrInf" value="<?php echo htmlspecialchars($alm->UsrInf); ?>" class="form-control" placeholder="Ingrese UsrInf" />
    </div>
    <div class="form-group">
        <label>Grupo</label>
        <select name="GruCod" class="form-control" required>
            <option value="">-- Seleccionar Grupo --</option>
            <?php foreach ($grupos as $g): ?>
                <option value="<?php echo $g->GruCod; ?>" <?php echo $alm->GruCod == $g->GruCod ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($g->GruDsc); ?> (Código: <?php echo $g->GruCod; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Administrador</label>
        <input type="text" name="UsrAdm" value="<?php echo htmlspecialchars($alm->UsrAdm); ?>" class="form-control" placeholder="Ingrese UsrAdm" />
    </div>
    <div class="form-group">
        <label>Sucursal</label>
        <input type="text" name="SucCod" value="<?php echo htmlspecialchars($alm->SucCod); ?>" class="form-control" placeholder="Ingrese SucCod" />
    </div>
    <div class="form-group">
        <label>Estado</label>
        <input type="text" name="UsrAct" value="<?php echo htmlspecialchars($alm->UsrAct); ?>" class="form-control" placeholder="Ingrese UsrAct" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
