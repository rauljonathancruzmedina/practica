<?
/*------------------ FUNCIONES PARA CONEXION A BASE DE DATOS*/
	class conector
	{
		private $servidor;
		private $usuario;
		private $psw;
		private $bd;
		var $conexion;
		private $tbc;
		
		function __construct($db="practica", $server="localhost", $user="root", $contra="") //local
		{
			$this->servidor=$server;
			$this->usuario=$user;
			$this->psw=$contra;
			$this->bd=$db;
			$this->conexion= new mysqli();
			$this->conexion->connect($this->servidor,$this->usuario,$this->psw,$this->bd);
			$this->conexion->set_charset("utf8");
		}
		
		function consulta($fil,$tipo='C',$nom_q='')
		{
			$mensaje="";
			if($this->tbc=$this->conexion->query($fil))
				return $this->tbc;
			else
			{
				switch($tipo)
				{
					case "C": $mensaje="Hubo un error en la consulta($nom_q)"; break;
					case "U": $mensaje="Hubo un error en la actualización($nom_q)"; break;
					case "I": $mensaje="Hubo un error en la inserción($nom_q)"; break;
					case "D": $mensaje="Hubo un error en el borrado($nom_q)"; break;
				}
				echo $mensaje;
			}
		}


		function multi_consulta($fil,$tipo='U',$nom_q='')
		{
			$mensaje="";
			if (!$this->conexion->multi_query($fil)) 
			{
				switch($tipo)
				{
					case "U": $mensaje="Hubo un error en la actualización($nom_q)"; break;
					case "I": $mensaje="Hubo un error en la inserción($nom_q)"; break;
				}
				echo $mensaje;
			}
		}

		function escape($f)
		{
			return $this->conexion->real_escape_string(file_get_contents($f));
		}

		function var_escape($f)
		{
			return $this->conexion->real_escape_string($f);
		}
	}
//------------------------------------------
// FUNCIONES DE PASO DE PARÁMETROS DEL FORM
//------------------------------------------
	function recibe_POST($var,$retorno='')
	{
		if(isset($_POST[$var]))
		{
			if(is_null($_POST[$var]) or $_POST[$var]=='')
				return $retorno;
			else
				return str_replace(",","",str_replace("$","",addslashes(trim($_POST[$var]))));
		}
		else
			return $retorno;
	}

	function recibe_GET($var,$retorno='')
	{
		if(isset($_GET[$var]))
		{
			if(is_null($_GET[$var]) or $_GET[$var]=='')
				return $retorno;
			else
				return str_replace(",","",str_replace("$","",addslashes(trim($_GET[$var]))));
		}
		else
			return $retorno;
	}
//------------------------------------------------

?>
