<h1 class="page-header">Usuarios</h1>

<div class="well well-sm text-right">
    <form action="?c=usuarios" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=usuarios&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Grupo</th>
            <th>Administrador</th>
            <th>Sucursal</th>
            <th>Estado</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->UsrCod); ?></td>
            <td><?php echo htmlspecialchars($r->UsrInf); ?></td>
            <td><?php echo htmlspecialchars(!empty($r->grupo_nombre) ? $r->grupo_nombre : $r->GruCod); ?></td>
            <td><?php echo htmlspecialchars($r->UsrAdm); ?></td>
            <td><?php echo htmlspecialchars($r->SucCod); ?></td>
            <td><?php echo htmlspecialchars($r->UsrAct); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=usuarios&a=Crud&UsrCod=<?php echo $r->UsrCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=usuarios&a=Eliminar&UsrCod=<?php echo $r->UsrCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=usuarios&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
