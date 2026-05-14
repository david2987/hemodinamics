<h1 class="page-header">Coordinadores</h1>

<div class="well well-sm text-right">
    <form action="?c=vtavnd" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=vtavnd&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>VndCod</th>
            <th>VndNom</th>
            <th>VndDir</th>
            <th>VndTel</th>
            <th>VndCel</th>
            <th>VndCom</th>
            <th>VndMai</th>
            <th>VndUsr</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->VndCod); ?></td>
            <td><?php echo htmlspecialchars($r->VndNom); ?></td>
            <td><?php echo htmlspecialchars($r->VndDir); ?></td>
            <td><?php echo htmlspecialchars($r->VndTel); ?></td>
            <td><?php echo htmlspecialchars($r->VndCel); ?></td>
            <td><?php echo htmlspecialchars($r->VndCom); ?></td>
            <td><?php echo htmlspecialchars($r->VndMai); ?></td>
            <td><?php echo htmlspecialchars($r->VndUsr); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=vtavnd&a=Crud&VndCod=<?php echo $r->VndCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=vtavnd&a=Eliminar&VndCod=<?php echo $r->VndCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=vtavnd&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
