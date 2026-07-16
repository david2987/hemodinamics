<h1 class="page-header">Formas de Pago</h1>

<div class="well well-sm text-right">
    <form action="?c=sisffa" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=sisffa&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Forma de Pago</th>
            <th>Días de Validez</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->FfaCod); ?></td>
            <td><?php echo htmlspecialchars($r->FfaDesc); ?></td>
            <td><?php echo htmlspecialchars($r->FfaFec); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=sisffa&a=Crud&FfaCod=<?php echo $r->FfaCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=sisffa&a=Eliminar&FfaCod=<?php echo $r->FfaCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=sisffa&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
