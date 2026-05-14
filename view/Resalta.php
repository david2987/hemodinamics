<?php
			 $id = $_GET['id'];

			 require_once __DIR__ . '/../model/database.php';
$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);

 
			 $sql = 'SELECT PresupRecPre FROM  presupuestos  where cod_presupuesto ='.$id ;	
			 foreach ($conn->query($sql) as $row) 
			  {
				 $PresupRecPre = $row['PresupRecPre'];		
			 }
				 
			 IF ($PresupRecPre == 0) 
			 {
				 $sql = 'UPDATE presupuestos set PresupRecPre = 1 where cod_presupuesto ='.$id ;
				 $conn->query($sql);
			 }else
			 {
				 $sql = 'UPDATE presupuestos set PresupRecPre = 0 where cod_presupuesto ='.$id ;
				 $conn->query($sql);
 
			 }
			 echo $PresupRecPre;
		 
?>