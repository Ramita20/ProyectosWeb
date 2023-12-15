//Confirmacion para eliminar el destinatario seleccionado.
document.getElementById("delete-destinatario").onsubmit = function (e) {
  e.preventDefault();
  if (
    confirm(
      "¿Estas seguro/a que quieres eliminar a este usuario de tus destinatarios?"
    )
  ) {
    document.getElementById("delete-destinatario").onsubmit = null;
    document.getElementById("delete-destinatario").submit();
  }
};

//Confirmacion para realizar la transferencia al destinatario seleccionado.
document.getElementById("form-transferencia").onsubmit = function (e) {
  e.preventDefault();
  if (
    confirm(
      "¿Estas seguro/a que quieres enviar el monto seleccionado a este destinatario?"
    )
  ) {
    document.getElementById("form-transferencia").onsubmit = null;
    document.getElementById("form-transferencia").submit();
  }
};
