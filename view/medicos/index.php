<h1 class="page-header">Médicos</h1>

<div class="well well-sm text-right">
    <form action="?c=medicos" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=medicos&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Localidad</th>
            <th>Médico</th>
            <th>Domicilio</th>
            <th>Cod. Postal</th>
            <th>Teléfono</th>
            <th>Celular</th>
            <th>Email</th>
            <th>Código Localidad</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->cod_medico); ?></td>
            <td><?php echo htmlspecialchars($r->localidad); ?></td>
            <td><?php echo htmlspecialchars($r->mediconombre); ?></td>
            <td><?php echo htmlspecialchars($r->medicodomicilio); ?></td>
            <td><?php echo htmlspecialchars($r->medicocod_postal); ?></td>
            <td><?php echo htmlspecialchars($r->medicotelefono); ?></td>
            <td><?php echo htmlspecialchars($r->medicocelular); ?></td>
            <td><?php echo htmlspecialchars($r->medicoemail); ?></td>
            <td><?php echo htmlspecialchars($r->MelLocCod); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=medicos&a=Crud&cod_medico=<?php echo $r->cod_medico; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=medicos&a=Eliminar&cod_medico=<?php echo $r->cod_medico; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=medicos&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
