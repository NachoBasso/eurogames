console.log("ESTOY ENTRANDO A APP.JS");

//Perfil de Usuario - ESTO LO VOY A MODIFICAR ANTES DE LA ENTREGA
document.addEventListener("DOMContentLoaded", function () {
  const btnModificar = document.getElementById("btn-modificar");
  const btnCancelar = document.getElementById("btn-cancelar");
  const datosPersonales = document.getElementById("datos-personales");
  const formModificar = document.getElementById("form-modificar");

  console.log("esto es una prueba para ver si funciona app.js");

  btnModificar.addEventListener("click", function () {
    datosPersonales.style.display = "none";
    formModificar.style.display = "block";
  });

  btnCancelar.addEventListener("click", function () {
    datosPersonales.style.display = "block";
    formModificar.style.display = "none";
  });
});

console.log("ESTOY ENTRANDO A APP.JS");

//Para el eliminar el juego del carrito
function confirmarEliminar() {
  return confirm("¿Estás seguro que deseas eliminar este juego del carrito?");
}
//Se utiliza en gestionPedido.php - Reformulada con CHATGPT

function rellenarDatos(checkbox) {
  if (checkbox.checked) {
    var nombreUsuario = checkbox.getAttribute("data-nombre");
    var apellidosUsuario = checkbox.getAttribute("data-apellidos");
    var emailUsuario = checkbox.getAttribute("data-email");
    var telefonoUsuario = checkbox.getAttribute("data-telefono");

    document.getElementById("nombre").value = nombreUsuario;
    document.getElementById("apellidos").value = apellidosUsuario;
    document.getElementById("email").value = emailUsuario;
    document.getElementById("telefono").value = telefonoUsuario;
  } else {
    document.getElementById("nombre").value = "";
    document.getElementById("apellidos").value = "";
    document.getElementById("email").value = "";
    document.getElementById("telefono").value = "";
  }
}
//Se utiliza en gestionPedido.php
function copiarDatosPersonales() {
  var checkbox = document.getElementById("usarInfoPersonal");
  if (checkbox.checked) {
    document.getElementById("nombreDestinatario").value =
      document.getElementById("nombre").value;
    document.getElementById("apellidosDestinatario").value =
      document.getElementById("apellidos").value;
    document.getElementById("telefonoDestinatario").value =
      document.getElementById("telefono").value;
    document.getElementById("domicilioDestinatario").value =
      document.getElementById("domicilio").value;
    document.getElementById("localidadDestinatario").value =
      document.getElementById("localidad").value;
    document.getElementById("provinciaDestinatario").value =
      document.getElementById("provincia").value;
    document.getElementById("codigoPostalDestinatario").value =
      document.getElementById("codigoPostal").value;
    document.getElementById("nombreDestinatario").disabled = true;
    document.getElementById("apellidosDestinatario").disabled = true;
    document.getElementById("telefonoDestinatario").disabled = true;
    document.getElementById("domicilioDestinatario").disabled = true;
    document.getElementById("localidadDestinatario").disabled = true;
    document.getElementById("provinciaDestinatario").disabled = true;
    document.getElementById("codigoPostalDestinatario").disabled = true;
  } else {
    document.getElementById("nombreDestinatario").value = "";
    document.getElementById("apellidosDestinatario").value = "";
    document.getElementById("telefonoDestinatario").value = "";
    document.getElementById("domicilioDestinatario").value = "";
    document.getElementById("localidadDestinatario").value = "";
    document.getElementById("provinciaDestinatario").value = "";
    document.getElementById("codigoPostalDestinatario").value = "";
    document.getElementById("nombreDestinatario").disabled = false;
    document.getElementById("apellidosDestinatario").disabled = false;
    document.getElementById("telefonoDestinatario").disabled = false;
    document.getElementById("domicilioDestinatario").disabled = false;
    document.getElementById("localidadDestinatario").disabled = false;
    document.getElementById("provinciaDestinatario").disabled = false;
    document.getElementById("codigoPostalDestinatario").disabled = false;
  }
}

//Muestra u oculta la información de transferencia bancaria
document.addEventListener("DOMContentLoaded", function () {
  const pagoTransferencia = document.getElementById("pagoTransferencia");
  const pagoEfectivo = document.getElementById("pagoEfectivo");
  const infoTransferencia = document.getElementById("infoTransferencia");

  function cambiarVisibilidadTransferencia() {
    if (pagoTransferencia.checked) {
      infoTransferencia.style.display = "block";
    } else {
      infoTransferencia.style.display = "none";
    }
  }

  function cambiarVisibilidadEfectivo() {
    if (pagoEfectivo.checked) {
      infoTransferencia.style.display = "none";
    }
  }

  pagoTransferencia.addEventListener("click", cambiarVisibilidadTransferencia);
  pagoEfectivo.addEventListener("click", cambiarVisibilidadEfectivo);

  cambiarVisibilidadTransferencia();
});


//redireccionamiento a login para finalizar la compra 

const duration = 7000;

const countdownElement = document.createElement("div");
countdownElement.classList.add("text-center");

const spinnerContainer = document.createElement("div");
spinnerContainer.classList.add("spinner-container");

const spinner = document.createElement("i");
spinner.classList.add("fas", "fa-spinner", "fa-spin", "fa-3x");

spinnerContainer.appendChild(spinner);
countdownElement.appendChild(spinnerContainer);

document.getElementById("error-message").appendChild(countdownElement);

setTimeout(() => {
  window.location.href = "login.php";
}, duration);


// $(document).ready(function() {
//   // Mostrar el modal con el mensaje de error
//   $('#errorModal').modal('show');

//   // Iniciar el contador para redireccionar después de 7 segundos
//   const duration = 7000;
//   setTimeout(function() {
//       window.location.href = "login.php";
//   }, duration);
// });