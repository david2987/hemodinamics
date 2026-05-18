<h1 class="page-header">Categorías Productos</h1>

<div class="well well-sm text-right">
    <form action="?c=categoriaproductos" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=categoriaproductos&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Abreviación</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->CptId); ?></td>
            <td><?php echo htmlspecialchars($r->CptDes); ?></td>
            <td><?php echo htmlspecialchars($r->CptAbv); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=categoriaproductos&a=Crud&CptId=<?php echo $r->CptId; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=categoriaproductos&a=Eliminar&CptId=<?php echo $r->CptId; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=categoriaproductos&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
