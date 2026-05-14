<h1 class="page-header">Categorías Presupuesto</h1>

<div class="well well-sm text-right">
    <form action="?c=categoriapresupuesto" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=categoriapresupuesto&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>CprCod</th>
            <th>CprDes</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->CprCod); ?></td>
            <td><?php echo htmlspecialchars($r->CprDes); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=categoriapresupuesto&a=Crud&CprCod=<?php echo $r->CprCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=categoriapresupuesto&a=Eliminar&CprCod=<?php echo $r->CprCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=categoriapresupuesto&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
