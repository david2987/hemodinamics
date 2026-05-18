<h1 class="page-header">Instituciones</h1>

<div class="well well-sm text-right">
    <form action="?c=hospitales" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=hospitales&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Email</th>
            <th>CUIT</th>
            <th>Teléfono</th>
            <th>Domicilio</th>
            <th>CUFE</th>
            <th>Localidad</th>
            <th>Descripción 2</th>
            <th>Código Localidad</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->HospCod); ?></td>
            <td><?php echo htmlspecialchars($r->HospDesc); ?></td>
            <td><?php echo htmlspecialchars($r->HospMail); ?></td>
            <td><?php echo htmlspecialchars($r->HospCUIT); ?></td>
            <td><?php echo htmlspecialchars($r->HospTel); ?></td>
            <td><?php echo htmlspecialchars($r->HospDom); ?></td>
            <td><?php echo htmlspecialchars($r->HospCUFE); ?></td>
            <td><?php echo htmlspecialchars($r->HospLoc); ?></td>
            <td><?php echo htmlspecialchars($r->HospDesc2); ?></td>
            <td><?php echo htmlspecialchars($r->HospLocCod); ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="?c=hospitales&a=Crud&HospCod=<?php echo $r->HospCod; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=hospitales&a=Eliminar&HospCod=<?php echo $r->HospCod; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=hospitales&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
