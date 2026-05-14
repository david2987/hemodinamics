<?php
class presupuesto
{
	private $pdo;
    
    public $cod_presupuesto;
    public $cod_cliente;
    public $cod_medico;
    public $fecha;
    public $fecha_validez;
    public $f_pago;
    public $plazo;
    public $Licitacion_Nro;
    public $PresupuestoPaciente;
    public $PresupDisAlt;
    public $CprCod;
    public $PresupVndCom;
    public $PresupFecSeg;
    public $PresupHorSeg;
    public $PresupRel;
    public $Expendiente_nro;
    public $PresupEnviadoMail;
    public $PresupExpress;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::StartUp();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function buscaruta()
	{
		try
		{
			$result = array();
			$sql = "SELECT ParCod,ParVarChr From sispar where  Parcod >= 48 ";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(); 	
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function Listar($where='',$limit=30)
	{		
		try
		{
			$result = array();

			$sql = "SELECT presupuestos.cod_presupuesto,fecha ,PresupEnviadoMail,PresupFecAut,nombre,PresupuestoPaciente,mediconombre,CprDes,PresupVndCom,presupuestos.EspCod,EspDes,SueDes,PresupFecVnd,VndNom,PresupUsrCre,Licitacion_Nro,PresupRecPre,PresupDisAlt,PresupPrc,PresupUsuSeg FROM presupuestos";  
			$sql .=" LEFT JOIN  clientes ON presupuestos.cod_cliente = clientes.cod_cliente ";  
			//$sql .=" RIGHT JOIN  detalles_presupuesto ON presupuestos.cod_presupuesto =  detalles_presupuesto.cod_presupuesto  ";  
			$sql .=" LEFT JOIN  medicos ON presupuestos.cod_medico = medicos.cod_medico  ";
			$sql .=' LEFT JOIN  sisesp ON presupuestos.EspCod = sisesp.EspCod ';
			$sql .=" LEFT JOIN  vtavnd ON presupuestos.VndCod = vtavnd.VndCod ";
			$sql .=" LEFT JOIN  sisespsub  as Tabla1 ON Presupuestos.SueCod = Tabla1.SueCod ";
			$sql .=" LEFT JOIN  categoriapresupuesto ON Presupuestos.CprCod = categoriapresupuesto.CprCod ";
			$sql .= "where PresupuestoPaciente <> '' ".$where;			
			$sql .= ' ORDER BY presupuestos.cod_presupuesto desc  LIMIT '.$limit ;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			
			echo  "<script>console.log(".'"'. $sql .'"'.")</script>";
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage().$sql);
		}
	}

	public function Eliminar($id)
	{
		try 
		{	
			// ELIMINA LA CIRUGIA ASOCIADA
			$stm = $this->pdo->prepare("DELETE from planillacirugia WHERE cod_presupuesto = {$id} ");		
			$stm->execute();

			// ELIMINA EL HISTORICO DE PRECIOS
			$stm = $this->pdo->prepare("DELETE from historicopresupuesto WHERE cod_presupuesto = {$id} ");		
			$stm->execute();

			// ELIMINA EL DETALLE DEL PRESUPUESTO
			$stm = $this->pdo->prepare("DELETE from detalles_presupuesto WHERE cod_presupuesto = {$id} ");		
			$stm->execute();

			// ELIMINA EL  PRESUPUESTO
			$stm = $this->pdo->prepare("DELETE from presupuestos WHERE cod_presupuesto = {$id} ");		
			$stm->execute();

			return "<b>EL PRESUPUESTO {$id} <br> SE HA ELIMINADO CORRECTAMENTE</b>";

		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}


	public function ListarcontTot($where='',$limit = 20)
	{
		try
		{
			$result = array();

			$sql = "SELECT count(*) as CantRegistro FROM presupuestos";  
			//$sql .=" LEFT JOIN  detalles_presupuesto ON presupuestos.cod_presupuesto =  detalles_presupuesto.cod_presupuesto  ";  
			$sql .=" LEFT JOIN  clientes ON presupuestos.cod_cliente = clientes.cod_cliente "; 
			$sql .=" LEFT JOIN medicos ON presupuestos.cod_medico = medicos.cod_medico  ";
			$sql .=' LEFT JOIN sisesp ON presupuestos.EspCod = sisesp.EspCod ';
			$sql .=" LEFT JOIN vtavnd ON presupuestos.VndCod = vtavnd.VndCod ";
			$sql .=" LEFT JOIN sisespsub ON Presupuestos.SueCod = sisespsub.SueCod ";
			$sql .=" LEFT JOIN categoriapresupuesto ON Presupuestos.CprCod = categoriapresupuesto.CprCod ";
			$sql .= "where PresupuestoPaciente <> '' ".$where.' LIMIT '.$limit;

			//$sql .= ' ORDER BY presupuestos.cod_presupuesto desc ' ;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			
			//echo  "<script>console.log(".'"'. $sql .'"'.")</script>";
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e)
		{
			die($e->getMessage().$sql);
		}
	}


	public function buscaestado()
	{
		try
		{
			$sql = 'select * from sisesp';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}

	public function buscadescripestado($EspCod)
	{
		try
		{
			$sql = 'select EspDes from sisesp where EspCod = '.$EspCod;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}

	public function buscacategoria()
	{
		try
		{
			$sql = 'select * from categoriapresupuesto';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}


	public function buscatotal($cod_presupuesto = 0 )
	{
		try
		{
			
			$sql = 'select sum(importe) as total from detalles_presupuesto where cod_presupuesto='.$cod_presupuesto;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}
	public function buscaproducto($cod_presupuesto = 0 )
	{
		try
		{
			
			$sql = 'select producto_titulo,cantidad,p_unitario from detalles_presupuesto INNER JOIN productos on productos.cod_producto = detalles_presupuesto.det_producto  where cod_presupuesto='.$cod_presupuesto;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}

	public function buscamedico($cod_medico)
	{
		$sql = 'select mediconombre from medicos where cod_medico = '.$cod_medico;
		$stm = $this->pdo->prepare($sql);
		$stm->execute();
		foreach($stm->fetchAll(PDO::FETCH_OBJ) as $s):
			return  $s->mediconombre;
		endforeach;

		//return $stm->fetchAll(PDO::FETCH_OBJ);
	}

	public function ListarTotImporte($where='', $limit = 20)
	{
		try
		{
			$result = array();
			$sql = "SELECT sum(importe) totimporte FROM presupuestos";  
			//$sql .=" LEFT JOIN  detalles_presupuesto ON presupuestos.cod_presupuesto =  detalles_presupuesto.cod_presupuesto  ";  
			$sql .=" LEFT JOIN  clientes ON presupuestos.cod_cliente = clientes.cod_cliente "; 
			$sql .=" LEFT JOIN medicos ON presupuestos.cod_medico = medicos.cod_medico  ";
			$sql .=' LEFT JOIN sisesp ON presupuestos.EspCod = sisesp.EspCod ';
			$sql .=" LEFT JOIN vtavnd ON presupuestos.VndCod = vtavnd.VndCod ";
			$sql .=" LEFT JOIN sisespsub ON Presupuestos.SueCod = sisespsub.SueCod ";
			$sql .=" LEFT JOIN categoriapresupuesto ON Presupuestos.CprCod = categoriapresupuesto.CprCod ";
			$sql .= "LEFT JOIN detalles_presupuesto on presupuestos.cod_presupuesto = detalles_presupuesto.cod_presupuesto ";
			$sql .= "where PresupuestoPaciente <> '' ".$where.' LIMIT '.$limit;									
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			
			echo  "<script>console.log(".'"'. $sql .'"'.")</script>";
			return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e){
			die($e->getMessage());
				
		}

	
	}

	public function buscarusuario()
	{
		try
		{
			$sql = "SELECT * FROM usuarios WHERE UsrCod IN (SELECT DISTINCT PresupUsrCre from presupuestos WHERE fecha >= '2019-01-01'  GROUP BY PresupUsrCre) and Usrcod <> 'admin' and Usrcod <> 'ROEV' ";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}


	
	public function buscarUsuarioAutPrecio()
	{
		try
		{
			$sql = "SELECT presupuestosautprecio.PapCod,presupuestosautprecio.PapUsr,usuarios.UsrInf  FROM presupuestosautprecio INNER JOIN usuarios ON presupuestosautprecio.PapUsr = usuarios.UsrCod ";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}

	public function buscarUsuarioSegPresupuesto()
	{
		try
		{
			$sql = "SELECT presupuestosusrseg.PasCod,presupuestosusrseg.PasUsr,usuarios.UsrInf,usuarios.UsrCod FROM presupuestosusrseg INNER JOIN usuarios ON presupuestosusrseg.PasUsr = usuarios.UsrCod";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}

	public function BuscarArchivos($cod_presupuesto)
	{
		try
		{
			$sql = "SELECT * FROM presupuestodocumento WHERE cod_presupuesto =  ".$cod_presupuesto;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);	
		}
		catch(Exception $e)
			{
				die($e->getMessage());
			}
		

	}


	


    public function Obtener($id)
    {
        try 
        {
            $stm = $this->pdo->prepare("SELECT * FROM presupuestos WHERE cod_presupuesto = ?");
            $stm->execute(array($id));
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if($r) {
                // Fetch details
                $stm = $this->pdo->prepare("SELECT * FROM detalles_presupuesto WHERE cod_presupuesto = ? ORDER BY item");
                $stm->execute(array($id));
                $r->detalles = $stm->fetchAll(PDO::FETCH_OBJ);
            }

            return $r;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function buscapagos()
    {
        try
        {
            $sql = 'select * from sisffa';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);	
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    public function ProximoNro()
    {
        try
        {
            $sql = 'SELECT MAX(cod_presupuesto) FROM presupuestos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchColumn() + 1;
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    public function Guardar($data, $detalles)
    {
        try 
        {
            if(!empty($data->cod_presupuesto))
            {
                $sql = "UPDATE presupuestos SET 
                            cod_cliente = ?, 
                            cod_medico = ?, 
                            fecha_validez = ?, 
                            f_pago = ?, 
                            plazo = ?, 
                            Licitacion_Nro = ?, 
                            PresupuestoPaciente = ?, 
                            PresupDisAlt = ?, 
                            CprCod = ?, 
                            PresupVndCom = ?, 
                            PresupFecSeg = ?, 
                            PresupHorSeg = ?, 
                            PresupRel = ?, 
                            Expendiente_nro = ?,
                            PresupEnviadoMail = ?
                        WHERE cod_presupuesto = ?";

                $this->pdo->prepare($sql)
                     ->execute(
                        array(
                            $data->cod_cliente, 
                            $data->cod_medico, 
                            $data->fecha_validez, 
                            $data->f_pago, 
                            $data->plazo, 
                            $data->Licitacion_Nro, 
                            $data->PresupuestoPaciente, 
                            $data->PresupDisAlt, 
                            $data->CprCod, 
                            $data->PresupVndCom, 
                            $data->PresupFecSeg, 
                            $data->PresupHorSeg, 
                            $data->PresupRel, 
                            $data->Expendiente_nro,
                            $data->PresupEnviadoMail,
                            $data->cod_presupuesto
                        )
                    );
                $cod_presupuesto = $data->cod_presupuesto;
            }
            else
            {
                $sql = "INSERT INTO presupuestos (cod_cliente, cod_medico, fecha, fecha_validez, f_pago, plazo, Licitacion_Nro, PresupuestoPaciente, PresupDisAlt, CprCod, PresupVndCom, PresupFecSeg, PresupHorSeg, PresupRel, Expendiente_nro, PresupEnviadoMail) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $this->pdo->prepare($sql)
                     ->execute(
                        array(
                            $data->cod_cliente, 
                            $data->cod_medico, 
                            date('Y-m-d'),
                            $data->fecha_validez, 
                            $data->f_pago, 
                            $data->plazo, 
                            $data->Licitacion_Nro, 
                            $data->PresupuestoPaciente, 
                            $data->PresupDisAlt, 
                            $data->CprCod, 
                            $data->PresupVndCom, 
                            $data->PresupFecSeg, 
                            $data->PresupHorSeg, 
                            $data->PresupRel, 
                            $data->Expendiente_nro,
                            $data->PresupEnviadoMail
                        )
                    );
                $cod_presupuesto = $this->pdo->lastInsertId();
            }

            // Handle Details
            $this->pdo->prepare("DELETE FROM detalles_presupuesto WHERE cod_presupuesto = ?")
                 ->execute(array($cod_presupuesto));

            $item = 1;
            foreach($detalles as $d) {
                $sql = "INSERT INTO detalles_presupuesto (cod_presupuesto, item, cod_producto, det_producto, detalle_ag, cantidad, p_unitario, importe, itemAlt) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $this->pdo->prepare($sql)
                     ->execute(array(
                        $cod_presupuesto,
                        $item++,
                        $d['cod_producto'],
                        $d['cod_producto'],
                        $d['detalle'],
                        $d['cantidad'],
                        $d['importe'],
                        $d['cantidad'] * $d['importe'],
                        $d['alt']
                     ));
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }
}