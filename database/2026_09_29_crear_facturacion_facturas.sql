-- ============================================================================
-- Migración: Crear tabla `facturacion_facturas`
-- Fecha: 2026-09-29
-- Motivo: El módulo "Panel de CX, Consumo y Facturación" (model/facturacion.php,
--         controller/cirugia.controller.php, view/cirugia/index.php),
--         agregado en el commit 4600402 ("Agreado Panel de CX, Consumo y
--         Facturacion"), lee/escribe en una tabla `facturacion_facturas` que
--         NO existe en la base de datos (se verificó tanto contra el volcado
--         de esquema como contra la base local `hemodinamics`).
--
--         Sin esta tabla, las siguientes funciones de
--         controller/cirugia.controller.php fallan con
--         "Table 'hemodinamics.facturacion_facturas' doesn't exist":
--           - ListarFacturasJson()  -> Facturacion::ListarFacturas()
--           - GuardarFactura()      -> Facturacion::AgregarFactura()
--           - AnularFactura()       -> Facturacion::AnularFactura()
--
-- Alcance: Esta es la ÚNICA tabla/columna faltante detectada al auditar
--          todos los modelos (model/*.php) y controladores (controller/*.php)
--          contra el esquema actual. El resto de las tablas (categoriaclientes,
--          categoriapresupuesto, categoriaproductos, clientes, detalles_presupuesto,
--          historicopresupuesto, hospitales, localidades, medicos, planillacirugia,
--          presupuestodocumento, presupuestos, presupuestosautprecio,
--          presupuestosusrseg, productos, sisesp, sisespsub, sisffa, sisgru,
--          sispar, usuarios, vtavnd) y todas sus columnas usadas en el código
--          ya existen en producción.
--
-- Nota aparte (NO incluida en esta migración, requiere decisión de negocio):
--   controller/presupuesto.controller.php referencia `$cliente->condicion_iva`
--   (con guarda isset(), en 4 puntos, para los PDF de Presupuesto/Remito/
--   Expediente). La tabla `clientes` no tiene esa columna, pero SÍ tiene una
--   columna `iva` que ya almacena ese mismo dato (valores como
--   "Responsable Inscripto", "Exento", "Consumidor Final"). Agregar una
--   columna `condicion_iva` vacía no resolvería nada porque nada la
--   completaría: lo correcto parece ser corregir el código para que use
--   `$cliente->iva` en vez de `$cliente->condicion_iva`. Se deja fuera de
--   este script para no crear una columna muerta; avisar para decidir el fix.
--
-- Seguro para producción: usa CREATE TABLE IF NOT EXISTS, no borra ni
-- modifica ninguna tabla existente. Ejecutar dentro de una transacción /
-- con backup previo como buena práctica estándar.
-- ============================================================================

CREATE TABLE IF NOT EXISTS `facturacion_facturas` (
  `FacCod` bigint(20) NOT NULL AUTO_INCREMENT,
  `PlcCod` bigint(20) NOT NULL COMMENT 'FK a planillacirugia.PlcCod',
  `NroFac` varchar(20) DEFAULT NULL,
  `TipoFac` varchar(2) DEFAULT NULL COMMENT 'Letra de comprobante: A, B, C...',
  `FechaFac` date DEFAULT NULL,
  `ImporteFac` decimal(17,2) DEFAULT NULL,
  `ConsumoValorizado` decimal(17,2) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL,
  `FacUsuario` varchar(15) DEFAULT NULL COMMENT 'UsrCod de quien cargó la factura',
  `Anulada` tinyint(1) NOT NULL DEFAULT 0,
  `FechaAnulacion` date DEFAULT NULL,
  `UsuarioAnulacion` varchar(15) DEFAULT NULL COMMENT 'UsrCod de quien anuló la factura',
  PRIMARY KEY (`FacCod`) USING BTREE,
  KEY `IFACTURACION_FACTURAS1` (`PlcCod`) USING BTREE,
  CONSTRAINT `IFACTURACION_FACTURAS1` FOREIGN KEY (`PlcCod`) REFERENCES `planillacirugia` (`PlcCod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPACT;
