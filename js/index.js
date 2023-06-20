
let res_confirma="";

function validarEmail(email) {
	  var patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	  return patron.test(email);
	}

function validarTelefono(telefono) {

  telefono = telefono.replace(/\s/g, "").replace(/-/g, "");

  if (!/^\d+$/.test(telefono)) {
    return false;
  }

  for (var i = 0; i < telefono.length - 1; i++) {
    var currentDigit = parseInt(telefono[i]);
    var nextDigit = parseInt(telefono[i + 1]);

    if (currentDigit + 1 === nextDigit || currentDigit - 1 === nextDigit) {
      return false;
    }
  }

  return true;
}

//------------------------------------------------------------------------------------------------------- Validar formulario
validarForm = (f) =>
{
	let res="";
	return new Promise(function(resolve,reject)
			   {
			   		let men="";
					for (i=0; i < f.length; i++) 
					{
						if(f[i].value == "" || f[i].value == 0)
						{

					    	men=f[i].dataset.men;
					    	if(men != undefined)
					    	{
								res=i + "|" + men;
								break;
							}
						}
						else
						{
							if(f[i].dataset.l != undefined)
							{
								if(f[i].value.length != f[i].dataset.l)
								{
									men=f[i].dataset.menl;
									res=i + "|" + men;
									break;
								}
								else
								{
									var telefono = f[i].value;
									if (!validarTelefono(telefono)) {
									 	men="El número de teléfono no debe tener numeros consecutivos.";
									  	res=i + "|" + men;
										break;
									} 
								}
							}
							if(f[i].dataset.e != undefined)
							{
								var email = f[i].value;
								if (!validarEmail(email)) 
								{
									men = "El correo electrónico no es válido.";
									res=i + "|" + men;
									break;
								}
							}
						}
					}
					if(res=="")
			   			resolve();
			   		else
			   			reject(res);
			   });
}
//--------------------------------------------------------------------------------------------------------------- Alertas
alerta= (men) =>
{
	return new Promise(function(resolve)
			   {
					const alertaDiv = document.createElement("div");
					alertaDiv.id="alert-container";
					document.body.appendChild(alertaDiv);

					const alertaBox=document.createElement("div");
					alertaBox.className = "alerta-box";
					alertaDiv.appendChild(alertaBox);

					const alertaImg=document.createElement("div");
					alertaImg.className = "alerta-img";
					const cs=document.createElement("img");
					cs.src="./img/Imagen_alert.png";
					alertaImg.appendChild(cs);
					alertaBox.appendChild(alertaImg);

					const alertaMensaje=document.createElement("div");
					alertaMensaje.className = "alerta-mensaje";
					alertaSpan = document.createElement("span");
					alertaSpan.innerText = men;
					alertaMensaje.appendChild(alertaSpan);
					alertaBox.appendChild(alertaMensaje);

					const alertaBtn=document.createElement("div");
					alertaBtn.className = "alerta-btn";
					btn = document.createElement("input");
					btn.className = "btn-button";
					btn.id="btn-alerta";
					btn.type="button";
					btn.value="Aceptar";
					alertaBtn.appendChild(btn);
					alertaBox.appendChild(alertaBtn);

					btn.addEventListener("click",() =>
					{
						document.body.removeChild(alertaDiv);
						resolve();
					});
				});
}

confirma= (men,btnNombre1="Aceptar",btnNombre2="Cancelar",activaBtn2=0) =>
{
	return new Promise(function(resolve)
			   {
					const alertaDiv = document.createElement("div");
					alertaDiv.id="alert-container";
					document.body.appendChild(alertaDiv);

					const alertaBox=document.createElement("div");
					alertaBox.className = "alerta-box";
					alertaDiv.appendChild(alertaBox);

					const alertaImg=document.createElement("div");
					alertaImg.className = "alerta-img";
					const cs=document.createElement("img");
					cs.src="./img/Imagen_alert.png";
					alertaImg.appendChild(cs);
					alertaBox.appendChild(alertaImg);

					const alertaMensaje=document.createElement("div");
					alertaMensaje.className = "alerta-mensaje";
					alertaSpan = document.createElement("span");
					alertaSpan.innerText = men;
					alertaMensaje.appendChild(alertaSpan);
					alertaBox.appendChild(alertaMensaje);

					const alertaBtn=document.createElement("div");
					alertaBtn.className = "alerta-btn";

						btn = document.createElement("input");
						btn.className = "btn-button";
						btn.id="btn-alerta";
						btn.type="button";
						btn.value=btnNombre1;
						alertaBtn.appendChild(btn);

						btncancel = document.createElement("input");
						btncancel.className = "btn-danger";
						btncancel.id="btn-cancela";
						btncancel.type="button";
						btncancel.value=btnNombre2;
						btncancel.style.marginLeft = "50px";
						alertaBtn.appendChild(btncancel);

					alertaBox.appendChild(alertaBtn);

					btn.addEventListener("click",() =>
					{
						res_confirma='S';
						document.body.removeChild(alertaDiv);
						resolve();
					});

					btncancel.addEventListener("click",() =>
					{
						res_confirma='N';
						document.body.removeChild(alertaDiv);
						if(activaBtn2==1)
							resolve();
					});

				});
}
//-------------------------------------------------------------------------------------------------------- envio de formalarios
envioAjax = (data,url) =>
{
	return new Promise(function(resolve)
			   {
					fetch(url, 
					{
						method: 'POST',
					   	body: data
					})
					.then(response =>  
						{
						   	if(response.ok)
						    	return response.text();
						    else
						    	alerta("Existe un error en la conexión o en la página. \nVuelve a intentarlo más tarde por favor");
						})
						.then(txt => 
							{
								resolve(txt);
							});
				});
}

cerrarBox = (nobj) =>
{
	let box=document.querySelector("#" + nobj);

	box.style.visibility="hidden";
}

esperar = () =>
{
	const divespera = document.createElement("div");
	let html="<div><img src='./img/cargando.gif' height='100%'></div>";
	divespera.id="cargar";
	document.body.appendChild(divespera);
	divespera.innerHTML=html;
}

esperar_hide = () =>
{
	let div = document.getElementById("cargar");

	document.body.removeChild(div);
}