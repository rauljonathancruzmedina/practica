<?
	include("funciones/index.php");
	$conecta = new conector();
	$conecta->consulta("SET NAMES utf8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Raúl Jonathan</title>
	<link rel="stylesheet" href="css/index.css">
	<script src="js/index.js"></script>
</head>
<body>
	<div class="principal">
		<div class="head-nombre text-flex-center">
			<h1>Raúl Jonathan Cruz Medina</h1>
		</div>
		<div class="area-trabajo grid-at-2">
			<form id="frm-buscar" action="index.php" method="post">				  
				<div class="row row-1-2 line-bottom">
			    	<div class="col col-1-6 responsive-usu autocompletar">
			    		<input type='hidden' id="nuevo" name="nuevo" value=1>
						<label for="txt-buscar" class="etiqueta">Empleado</label>
						<input type="text" id="txt-buscar" name="txt-buscar" placeholder="Nombre de ususario" value="">
					</div>
					<div class="col col-6-7 responsive-search btn-button pt-30" name="btn-search" id="btn-search">
						<img src="img/lupa.png" height="25px">
					</div>
					<div class="col col-12-14 btn-button responsive-btn-nuevo pt-30" name="btn-nuevo" id="btn-nuevo">
						Nuevo
					</div>
				</div>
			</form>
<? 
			if(count($_POST) != 0)
			{
				$nombre_completo=recibe_POST('txt-buscar','');
				$nuevo=recibe_POST('nuevo',0);
				$nombre=$apaterno=$amaterno=$email=$telefono=$id="";
				$activo=1;
				if($nuevo==1)
				{
					$query = "select * from usuarios where concat_ws(' ', nombre, apaterno, amaterno) like '%$nombre_completo%'";

					$bd=$conecta->consulta($query);
					if ($bd->num_rows != 0)
					{
						$tb=mysqli_fetch_array($bd);
						$id=$tb["id_usuario"];
						$nombre=$tb["nombre"];
						$apaterno=$tb["apaterno"];
						$amaterno=$tb["amaterno"];
						$email=$tb["email"];
						$telefono=$tb["telefono"];
						$activo=$tb["activo"];
					}
				}
				$chk_activo="";
				if($activo==1)
					$chk_activo="CHECKED";	
?>	
			<form id="frm-datos" class="form-group grid-at-6" method="post">
				<div class="row row-1-2">
					<div class="col col-14-15 mt-30">
						<label class="etiqueta">Activo
						  	<input type="checkbox" id="chk-activo" name='chk-activo' <?= $chk_activo; ?>>
						</label>
					</div>
				</div>
				<div class="row row-2-3">
					<input type="hidden" id="txt-id" name="txt-id" value="<?=$id; ?>">
					<div class="col col-2-7">
						<label for="txt-nombre" class="etiqueta">Nombre</label>
						<input type="text" id="txt-nombre" name='txt-nombre' placeholder='Nombre' value="<?=$nombre; ?>" data-men='El Nombre es obligatorio' onkeyup="javascript:this.value=this.value.toUpperCase();">
					</div>
					<div class="col col-7-11">
						<label for="txt-Apaterno" class="etiqueta">Apellido paterno</label>
						<input type="text" id="txt-Apaterno" name='txt-Apaterno' placeholder='Apellido paterno' value="<?=$apaterno ?>" data-men='El Apellido paterno es obligatorio' onkeyup="javascript:this.value=this.value.toUpperCase();">
					</div>
					<div class="col col-11-15">
						<label for="txt-Amaterno" class="etiqueta">Apellido materno</label>
						<input type="text" id="txt-Amaterno" name='txt-Amaterno' placeholder='Apellido materno' value="<?=$amaterno ?>" data-men='El Apellido materno es obligatorio' onkeyup="javascript:this.value=this.value.toUpperCase();">
					</div>
				</div>
				<div class="row row-3-4">
					<div class="col col-4-8">
						<label for="txt-correo" class="etiqueta">Correo electronico</label>
						<input type="text" id="txt-correo" name='txt-correo' placeholder='Correo electronico' value="<?=$email ?>" data-men='El correo electronico es obligatorio' data-e="1" onkeyup="javascript:this.value=this.value.toUpperCase();">
					</div>
					<div class="col col-8-11">
						<label for="txt-tel" class="etiqueta">Teléfono</label>
						<input type="number" id="txt-tel" name='txt-tel' placeholder='Teléfono' value="<?=$telefono ?>" data-men='El teléfono es obligatorio' maxlength="10" data-l=10 data-menl='El Telefono debe tener 10 caracteres' onkeyup="javascript:this.value=this.value.toUpperCase();">
					</div>
				</div>
				<div class="row row-5-6">
					<div class="col col-2-5 btn-danger" name="btn-eliminar" id="btn-eliminar">
						Eliminar
					</div>
					<div class="col col-12-15 btn-button" name="btn-guardar" id="btn-guardar">
						Guardar
					</div>
				</div>
			</form>
<script type="text/javascript">
	const form_D = document.getElementById("frm-datos");
	let btn_G = document.getElementById("btn-guardar");
	let btn_eliminar = document.getElementById("btn-eliminar");
	btn_G.onclick = () =>
	{
		validarForm (form_D)
	  		.then(() =>
	  		{
	  			esperar();
	  			const data = new FormData(form_D);
	  			data.append('op', 'usuarios');
	  			envioAjax(data,"./ajax/actualizar.php")
	  				.then((txt) => 
		  				{
		  					esperar_hide();
		  					alerta(txt)
								.then(() =>
								{
									window.location="index.php";
								});
		  				});
	  		})
	  		.catch((res) =>
	  			{
	  				d=res.split("|"); 
	  				alerta(d[1])
	  					.then (() =>
	  						{
				  				form_D[d[0]].focus();
				  			});
	  			});
	}

	btn_eliminar.onclick = () => 
  	{
  		confirma("¿Estas seguro de eliminar?").then (() =>
		{	
			if (res_confirma == 'S') 
			{
				const data = new FormData();
	  			data.append('op', "delete");
	  			data.append('id', document.getElementById('txt-id').value);
	  			envioAjax(data,"./ajax/actualizar.php")
	  				.then((txt) => 
		  				{
		  					alerta(txt).then (() =>
							{
								window.location="index.php";
			  				});
		  				});	
			}
		});
  	}
  	if (document.getElementById("txt-id").value == "") 
  	{
  		document.getElementById("btn-eliminar").style.display = "none";
  	}
</script>
<?
			}
?>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	
	const form_B = document.getElementById("frm-buscar");
	let btn_buscar = document.getElementById("btn-search");
	let btn_nuevo = document.getElementById("btn-nuevo");

	btn_buscar.onclick = () => 
  	{

  		if (document.getElementById("txt-buscar").value == 0) 
  		{
  			alerta("No se ha seleccionado el nombre a buscar")
  				.then(() =>
		  		{
					document.getElementById("txt-buscar").focus();
				});
  		}
  		else
  		{
  			validarForm(form_B)
			  .then(() =>
		  		{
		  			form_B.submit();
		  		})
		  		.catch((res) =>
		  		{ 
	  				d=res.split("|");
	  				alerta(d[1]);
	  			});	
  		}	
	}

	btn_nuevo.onclick = () =>
  	{
 		let nuevo = document.getElementById("nuevo");
  		nuevo.value=0;
 		form_B.submit();
  	}
</script>