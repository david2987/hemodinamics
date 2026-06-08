<h1 class="page-header">Parámetros del Sistema</h1>

<div class="well well-sm text-right">
    <form action="?c=sispar" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=sispar&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Valor Numérico</th>
            <th>Valor Texto</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->ParCod); ?></td>
            <td><?php echo htmlspecialchars($r->ParDsc); ?></td>
            <td><?php echo htmlspecialchars($r->ParVarNum); ?></td>
            <td><?php echo htmlspecialchars($r->ParVarChr); ?></td>
            <td><?php echo !empty($r->ParFec) && $r->ParFec != '0000-00-00' ? date('d/m/Y', strtotime($r->ParFec)) : ''; ?></td>
            <td><?php echo htmlspecialchars($r->ParUsr); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=sispar&a=Crud&ParCod=<?php echo $r->ParCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=sispar&a=Eliminar&ParCod=<?php echo $r->ParCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=sispar&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
