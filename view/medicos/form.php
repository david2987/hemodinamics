<h1 class="page-header">
    <?php echo $alm->cod_medico != null ? $alm->cod_medico : 'Nuevo Registro'; ?>
</h1>

<ol class="breadcrumb">
  <li><a href="?c=medicos">Médicos</a></li>
  <li class="active"><?php echo $alm->cod_medico != null ? $alm->cod_medico : 'Nuevo Registro'; ?></li>
</ol>

<form action="?c=medicos&a=Guardar" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cod_medico" value="<?php echo htmlspecialchars($alm->cod_medico); ?>" />
    <div class="form-group">
        <label>cod_medico</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($alm->cod_medico); ?>" readonly />
    </div>
    <div class="form-group">
        <label>localidad</label>
        <input type="text" name="localidad" value="<?php echo htmlspecialchars($alm->localidad); ?>" class="form-control" placeholder="Ingrese localidad" />
    </div>
    <div class="form-group">
        <label>mediconombre</label>
        <input type="text" name="mediconombre" value="<?php echo htmlspecialchars($alm->mediconombre); ?>" class="form-control" placeholder="Ingrese mediconombre" />
    </div>
    <div class="form-group">
        <label>medicodomicilio</label>
        <input type="text" name="medicodomicilio" value="<?php echo htmlspecialchars($alm->medicodomicilio); ?>" class="form-control" placeholder="Ingrese medicodomicilio" />
    </div>
    <div class="form-group">
        <label>medicocod_postal</label>
        <input type="text" name="medicocod_postal" value="<?php echo htmlspecialchars($alm->medicocod_postal); ?>" class="form-control" placeholder="Ingrese medicocod_postal" />
    </div>
    <div class="form-group">
        <label>medicotelefono</label>
        <input type="text" name="medicotelefono" value="<?php echo htmlspecialchars($alm->medicotelefono); ?>" class="form-control" placeholder="Ingrese medicotelefono" />
    </div>
    <div class="form-group">
        <label>medicocelular</label>
        <input type="text" name="medicocelular" value="<?php echo htmlspecialchars($alm->medicocelular); ?>" class="form-control" placeholder="Ingrese medicocelular" />
    </div>
    <div class="form-group">
        <label>medicoemail</label>
        <input type="text" name="medicoemail" value="<?php echo htmlspecialchars($alm->medicoemail); ?>" class="form-control" placeholder="Ingrese medicoemail" />
    </div>
    <div class="form-group">
        <label>MelLocCod</label>
        <input type="text" name="MelLocCod" value="<?php echo htmlspecialchars($alm->MelLocCod); ?>" class="form-control" placeholder="Ingrese MelLocCod" />
    </div>
    <hr />
    <div class="text-right">
        <button class="btn btn-success">Guardar</button>
    </div>
</form>
