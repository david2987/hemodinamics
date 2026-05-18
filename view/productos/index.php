<h1 class="page-header">Productos</h1>

<div class="well well-sm text-right">
    <form action="?c=productos" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=productos&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Detalle</th>
            <th>Título</th>
            <th>Precio</th>
            <th>Precio Diferenciado</th>
            <th>Categoría</th>
            <th>Fecha Aviso</th>
            <th>Aviso</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->cod_producto); ?></td>
            <td><?php echo htmlspecialchars($r->detalle); ?></td>
            <td><?php echo htmlspecialchars($r->producto_titulo); ?></td>
            <td><?php echo htmlspecialchars($r->producto_precio); ?></td>
            <td><?php echo htmlspecialchars($r->productoPrecDis); ?></td>
            <td><?php echo htmlspecialchars(!empty($r->categoria_nombre) ? $r->categoria_nombre : $r->CptId); ?></td>
            <td><?php echo htmlspecialchars($r->producto_fechaaviso); ?></td>
            <td><?php echo htmlspecialchars($r->producto_aviso); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=productos&a=Crud&cod_producto=<?php echo $r->cod_producto; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=productos&a=Eliminar&cod_producto=<?php echo $r->cod_producto; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=productos&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
