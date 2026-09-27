# Relevamiento de Sistema de Gestión de Ortopedia

Este documento reúne el relevamiento funcional de los módulos nuevos solicitados para `consultapto`, tal como los definió el usuario, más los hallazgos de arquitectura que determinan cómo se van a implementar sobre el sistema existente.

## Estado general

| Módulo | Estado |
|---|---|
| 1. Coordinadores | **Implementado** (reutiliza ABM ya existente de `vtavnd`, agrega panel "Mis Cirugías" con programación de fecha CX y materiales) |
| 2. Panel de Cirugías | **Implementado** (estados Aceptadas/Realizadas, filtros, impresión de Presupuesto/Remito/Etiquetas, export a Excel) |
| 3. Gestión de Stock Completa | **Implementado** (catálogo de artículos propio, lotes con GTIN/vencimiento, Kardex con carga inicial/ajuste manual, alertas de vencimiento y stock bajo mínimo, export a Excel) |
| 4. Consumo | **Implementado** (carga de consumo por lote con valorización, SIN CONSUMO, descuento de stock y Kardex automático, export a Excel) |
| 5. Órdenes de Compra | **Implementado** (Proveedores, OC con líneas, recepciones parciales/completas que alimentan Stock y Kardex automáticamente, anulación, reportes) |
| 6. Facturación | **Implementado** (checklist de documentos RM/CI/PQ/RX/HM/OT, múltiples facturas por CX con Consumo Valorizado, export a Excel) |

---

## Módulos (relevamiento original)

### 1. Coordinadores

**Detalle:** Al Autorizar un Presupuesto, se debe asignar a un Coordinador que lleve la CX. Este Coordinador va a tener su propio panel para ver las posibles cirugías que tiene que gestionar. Las mismas deben colocar cuándo se va a realizar y un campo para completar los materiales que va a necesitar (un texto libre para que pongan lo que necesiten, puede ser cosas que haya o que no).

**Alcance:** Panel de Coordinadores, que tenga todos los presupuestos "Autorizados" a él. Gestión de presupuestos asignados, deben poderse seleccionar la fecha que se va a realizar la cirugía (Aceptadas), o bien poder cambiar la fecha de CX. Todos los presupuestos que se colocaron fecha, se van a pasar a la Planilla de Cirugías siguiente.

### 2. Panel de Cirugías

**Detalle:** Este panel se visualiza que CX fueron aprobadas anteriormente por el médico para que se realicen. Va a tener toda la información relevante del presupuesto y también material a entregar, técnicos que va a tener la cx, etc. (todo lo que vaya para un panel de cirugía en un sistema de gestión de ortopedias). También van a tener estados:
- **Aceptadas**: son las que están por hacerse.
- **Realizadas**: son las que están realizadas.

Las filas de las mismas se tienen que poder imprimir: el presupuesto, el remito (de la pantalla de presupuesto, es la misma) y también poder armar etiquetas para las cajas.

### 3. Gestión de Stock Completa

**Detalle:** Acá se va a gestionar todo lo relacionado a Stock. Básicamente es un panel de gestión de Stock (todo lo relacionado a un material de cirugía, lote, GTIN, fecha de vencimiento, etc.). También puede verse el historial de uso y compras que tuvieron sobre ese material (tipo ficha Kardex).

**Alcance:** Tiene todos los artículos utilizables que tiene la empresa (ejemplo: stents, parches de duramadre, etc.). También los precios de cada uno de los artículos.

### 4. Consumo

**Detalle:** Este panel permite cargar qué artículos se utilizaron efectivamente en la cirugía una vez que se pasaron a estado de realizadas. También puede ser que una CX no lleve consumo y debería poderse cargar SIN CONSUMO. Esto obviamente va a restar de stock los materiales previamente cargados.

**Alcance:** Este panel tiene que mostrar todo lo que se usó para posteriormente facturarse con las demás cosas, como los honorarios médicos, la logística, honorarios técnicos, etc. Tienen 2 estados:
- Sin Consumo Cargado: cuando apenas se pasa a Realizada la CX.
- (Con Consumo Cargado, una vez completado).

### 5. Órdenes de Compra

**Detalle:** En este panel se tienen todas las compras realizadas por la empresa, de materiales, por proveedor, recepción de artículos que llegaron de la orden de compra que se realizaron. Cada recepción tiene un remito y una factura asociada.

**Alcance:** Acá se van a realizar las compras que alimentan todo el stock de materiales, y solamente va a alimentar el stock cuando se reciba, hasta completar en su totalidad la orden de compra. Estados de la compra:
- **Pendiente**: cuando se crea la orden de compra.
- **Anulada**: cuando se anula la orden de compras.
- **Completa Parcial**: cuando llegaron algunos artículos pero no se terminó de completar la orden de compras.
- **Completa**: cuando se recibieron todos los artículos de la orden de compras.

**Aclaración:** Los precios se colocan únicamente cuando llega la orden de compra, o previamente es opcional. Debe mostrar reporte tanto de cada una de las recepciones como de las totalizadas.

### 6. Facturación

**Detalle:** Este panel va a mostrar todas las CX con Consumo o Sin Consumo (las que no lleven consumo) y va a permitir cargar si las mismas llegaron los siguientes documentos:
- RM: Remito de la CX
- CI: Documento de Consumo
- PQ: Protocolo Quirúrgico
- RX: Foto de Radiografía
- HM: Honorarios Médicos
- OT: Otros Documentos

También debe permitir la carga de la factura, pero de forma simple:
- Nro Fac
- Tipo Fac
- Fecha Fac
- Importe de Fac
- Consumo Valorizado
- Observaciones

(Aclaración: puede tener más de una factura para la misma CX)

---

## Contemplaciones generales (todos los paneles)

- Todos los paneles deben permitir de forma sencilla filtrar toda la información y poder realizar reportes por los filtros que se vayan aplicando. Puntualmente en el Panel de CX, que tenga filtros de todos los campos involucrados.
- Es posible que cuando se vaya cambiando de estados no solamente se muestre, sino también que se pinte toda la fila del registro.

---

## Hallazgos de arquitectura (investigación previa a la implementación)

`consultapto` es un MVC casero en PHP plano (sin framework), con PDO puro, front controller único en `index.php` (`?c=controlador&a=accion`). Se investigó la BD (`hemodinamics-final.fixed.sql`) y el código para determinar qué se puede reutilizar antes de diseñar los módulos nuevos.

- **Estado del Presupuesto**: vive en `presupuestos.EspCod`/`SueCod` (catálogos `sisesp`/`sisespsub`), no en la columna `estado` (muerta, sin uso). Bajo `EspCod=3` (AUTORIZADO), `sisespsub` ya tiene la progresión exacta que necesita el circuito de Cirugía:
  - `SueCod=6` Esperando Aceptación Médica
  - `SueCod=7` Aceptado por Médico (lo setea `AutorizarPresupuesto()` hoy)
  - `SueCod=8` Rechazado por el Médico
  - `SueCod=9` Aceptado Obligado por O.S. Auditoría
  - `SueCod=10` Fecha Probable de CX
  - `SueCod=11` **Fecha Confirmada de CX**
  - `SueCod=12` **Cx Realizada**
  - `SueCod=13` PTO Desactualizado
- **`vtavnd`** ya es el maestro de "Coordinadores" (el menú lateral ya lo etiqueta así) y ya tiene ABM completo (`model/vtavnd.php`, `controller/vtavnd.controller.php`, `view/vtavnd/*`). `vtavnd.VndUsr` permite vincular un coordinador con un login de `usuarios`. `presupuestos.VndCod` ya se asigna al autorizar.
- **`planillacirugia`**: tabla legada (PK `PlcCod`, índice por `cod_presupuesto`) con columnas ya pensadas para Cirugía/Consumo/Facturación: `PlcFec`/`PlcHor` (fecha/hora CX), `PlcCor` (coordinador), `PlcTec` (técnico), `PlcPac`/`PlcMed`/`PlcSer`/`PlcCli`, `PlcMat` (materiales a necesitar, texto libre), `PlcMatCx` (materiales realmente consumidos), `PlcCxRea` (flag realizada), y campos de factura (`PlcNroFac`, `PlcImp`, `PlcSubTot`, `PlcIva`, `PlcFecFac`, etc.). Hoy está huérfana (solo referenciada para un `DELETE` en cascada). Es la base natural de los módulos 2, 4 y 6.
- **`sisgru`** (roles) ya tiene `Administrador`, `Operador`, `Coordinador`, `Operador/Coord`, `Deposito`, `Consumos`, `Facturacion` — anticipando exactamente estos módulos. El login no guarda `GruCod` en sesión todavía.
- No existe nada de Stock/Lote/GTIN/Compras/Proveedores en el modelo de datos actual — el módulo 3 y 5 se diseñan desde cero.
- Patrones de UI a reutilizar: filtros tipo `.filters-card`/`.filters-grid`, paginación page/offset (patrón de `clientes`/`vtavnd`), impresión de PDF vía FPDF con `target="_blank"`, coloreado de fila por estado (a migrar de `style=""` inline a clases CSS).

## Decisiones de diseño ya acordadas

- **Reutilizar y extender infraestructura legada** (`vtavnd`, `sisgru`, `planillacirugia`, `EspCod`/`SueCod`) en vez de construir todo desde cero.
- Documentos de Facturación (módulo 6): solo checkbox + fecha, sin archivo adjunto.
- Reportes: generar **Excel real con PhpSpreadsheet** (nueva dependencia Composer), no el truco de HTML disfrazado de `.xls` que usa el resto del proyecto hoy.
- Se planifica e implementa un módulo a la vez, empezando por Coordinadores + Panel de Cirugías (módulos 1 y 2), por ser los que enganchan directo con el Presupuesto ya autorizado.
