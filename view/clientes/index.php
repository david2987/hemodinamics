<h1 class="page-header">Clientes</h1>

<div class="well well-sm text-right">
    <form action="?c=clientes" method="post" class="form-inline">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Buscar...">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a class="btn btn-success" href="?c=clientes&a=Crud">Nuevo</a>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Domicilio</th>
            <th>Localidad</th>
            <th>Cod Postal</th>
            <th>Teléfono</th>
            <th>Celular</th>
            <th>Email</th>
            <th>CUIT</th>
            <th>IVA</th>
            <th>Cat. Cliente</th>
            <th style="width:120px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($items as $r): ?>
        <tr>
            <td><?php echo htmlspecialchars($r->cod_cliente); ?></td>
            <td><?php echo htmlspecialchars($r->nombre); ?></td>
            <td><?php echo htmlspecialchars($r->domicilio); ?></td>
            <td><?php echo htmlspecialchars(!empty($r->localidad_nombre) ? $r->localidad_nombre : $r->localidad); ?></td>
            <td><?php echo htmlspecialchars($r->cod_postal); ?></td>
            <td><?php echo htmlspecialchars($r->telefono); ?></td>
            <td><?php echo htmlspecialchars($r->celular); ?></td>
            <td><?php echo htmlspecialchars($r->email); ?></td>
            <td><?php echo htmlspecialchars($r->cuit); ?></td>
            <td><?php echo htmlspecialchars($r->iva); ?></td>
            <td><?php echo htmlspecialchars($r->CcliCod); ?></td>            
            <td>
                <a class="btn btn-warning btn-sm" href="?c=clientes&a=Crud&cod_cliente=<?php echo $r->cod_cliente; ?>">Editar</a>
                <a class="btn btn-danger btn-sm" onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=clientes&a=Eliminar&cod_cliente=<?php echo $r->cod_cliente; ?>">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<ul class="pagination">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="<?php echo $page == $i ? 'active' : ''; ?>"><a href="?c=clientes&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
