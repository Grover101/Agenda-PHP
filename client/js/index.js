$(function () {
  var l = new Login();
  console.log("hola");
  validarSession();
});

/*Verificar que no exista una sesión iniciada*/
function validarSession() {
  $.ajax({
    url: "../server/session.php",
    type: "post",
    data: {},
    dataType: "json",
    success: function (data) {
      if (data.msg != "") {
        //Si la respuesta del servidor no es vacía
        alert("Ya existe una sesión iniciada. Redireccionando"); //Mostrar mensaje de sesión iniciada
        window.location.href = "./main.html"; //Redireccionar a la página main.html
      }
    },
    error: function (data) {
      $(".row.align-center").fadeOut("fast"); //En caso de ocurrir un error
      alert("Error inesperado. " + data); //Mostrar mensaje con la respuesta de la consulta
    },
  });
}

class Login {
  constructor() {
    this.submitEvent();
  }

  submitEvent() {
    $("form").submit((event) => {
      event.preventDefault();
      this.sendForm();
    });
  }

  sendForm() {
    let form_data = new FormData();
    form_data.append("username", $("#user").val());
    form_data.append("password", $("#password").val());
    $.ajax({
      url: "../server/check_login.php",
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: "POST",
      success: function (php_response) {
        if (php_response.msg == "OK") {
          window.location.href = "main.html";
        } else {
          alert(php_response.msg);
        }
      },
      error: function () {
        alert("error en la comunicación con el servidor");
      },
    });
  }
}
