<?	session_start();

	include("../funciones/index.php");
	
	$op=recibe_POST('op','');

	$conecta= new conector();

	$conecta->consulta("SET NAMES utf8");

	switch ($op) 
	{
		case "usuarios":
			$nombre=recibe_POST('txt-nombre','');
			$apaterno=recibe_POST('txt-Apaterno','');
			$amaterno=recibe_POST('txt-Amaterno',"");
			$email=recibe_POST('txt-correo','');
			$telefono=recibe_POST('txt-tel','');
			$id=recibe_POST('txt-id','');
			$activo=recibe_POST('chk-activo',"");

			if($activo == "")
				$activo=0;
			else
				$activo=1;

			$nombre_completo=$nombre . " " . $apaterno . " " . $amaterno;

			$bd=$conecta->consulta("select * from usuarios where id_usuario = '$id'");
			
            if ($bd->num_rows == 0)
            {	
            	$rj=$conecta->consulta("select concat_ws(' ', nombre, apaterno, amaterno) from usuarios where concat_ws(' ', nombre, apaterno, amaterno) like '%$nombre_completo%'");

                if ($rj->num_rows != 0) 
                {
                	echo "El usuario ingresado ya existe en el sistema.";
                }
                else
               	{
               		$query="insert into usuarios (nombre, apaterno, amaterno, email, telefono, activo) values ('$nombre', '$apaterno', '$amaterno', '$email', '$telefono', '$activo')";
                
	                $conecta->consulta($query, 'I', 'I_usuarios');
	                echo "Datos de usuario ingresados al sistema correctamente";
               	}
            }
            else
            {
            	$query="Update usuarios 
						set nombre='$nombre',apaterno='$apaterno',amaterno='$amaterno',email='$email',telefono='$telefono', activo=$activo
						where id_usuario='$id'";

				$conecta->consulta($query, 'U', 'U_usuarios');
				echo "Datos actualizados con éxito";
            }
		break;
		case "delete":
			$id=recibe_POST('id',0);

			$conecta->consulta("delete from usuarios where id_usuario = '$id'");
			echo "El usuario se elimino correctamente";
		break;
	}
?>