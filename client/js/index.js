function showMessage(message) {
  // mensajes de error
  $("#message")
    .html("<p>" + message + "</p>")
    .fadeIn("slow")
    .delay(5000);
  $(".overlay").fadeOut("slow");
}

function verificarConexion() {
  $.ajax({
    url: "../server/verificar_database.php",
    type: "post",
    data: {},
    dataType: "json",
    success: function (data) {
      if (data.phpmyadmin != "OK") {
        $(".row.align-center").fadeOut("fast");
        showMessage(data.msg);
      } else {
        crearDB();
      }
    },
    error: function (data) {
      showMessage(data);
    },
  });
}

function crearDB() {
  self = $(".loader-container h3");
  self.text("Verificando conexion a base de datos");
  $.ajax({
    url: "../server/crearDB.php",
    type: "post",
    data: {},
    dataType: "json",
    success: function (data) {
      if (data.database == "OK") {
        $(".loader-container h3").html(data.msg);
        crearTabla();
      } else {
        $(".row.align-center").fadeOut("fast");
        showMessage(data.msg);
      }
    },
    error: function (data) {
      $(".row.align-center").fadeOut("fast");
      showMessage(data.msg);
    },
  });
}

function crearTabla() {
  self = $(".loader-container h3");
  self.text("Verificando Tablas");
  $.ajax({
    url: "../server/verificar_tablas.php",
    type: "post",
    data: {},
    dataType: "json",
    success: function (data) {
      $(".overlay").delay(750).fadeOut("slow");
      if (data.msg == "OK") {
        var l = new Login();
        l.generarUsuarios();
        self.html("<p>Sistema inicializado</p>");
      } else {
        $(".row.align-center").fadeOut("fast");
        showMessage(data.msg).fadeIn();
      }
    },
    error: function (data) {
      $("#message").text(JSON.stringify(data.responseText));
    },
  });
}

$(function () {
  verificarConexion();
  var l = new Login();
  $("#generarUsuarios").on("click", function (e) {
    crearTabla();
  });
  validarSession();
});

function validarSession() {
  $.ajax({
    url: "../server/session.php",
    type: "post",
    data: {},
    dataType: "json",
    success: function (data) {
      if (data.msg != "") {
        alert("Ya existe una sesión iniciada. Redireccionando");
        window.location.href = "./main.html";
      }
    },
    error: function (data) {
      $(".row.align-center").fadeOut("fast");
      alert("Error inesperado. " + data);
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
        if (php_response.conexion == "OK") {
          if (php_response.acceso == "Usuario Autorizado") {
            $("#message").css({ background: "rgba(3, 147, 3, 0.6)" });
            window.location.href = "./main.html";
          }
          if (php_response.acceso == "Acceso rechazado") {
            $("#message").css({ background: "rgba(164, 11, 11, 0.7)" });
          }

          if (php_response.acceso == "No existen usuarios registrados") {
            $("#message").css({ background: "rgba(164, 11, 11, 0.7)" });
            $("#generarUsuarios").fadeIn("slow");
          }
          showMessage(php_response.acceso + ". " + php_response.msg);
        }
      },
    });
  }

  generarUsuarios() {
    $.ajax({
      url: "../server/create_user.php",
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      type: "POST",
      success: function (php_response) {
        if (php_response.conexion == "OK") {
          $(".row.align-center").fadeIn("fast");
          if (php_response.resultado == "1") {
            $("#message").css({ background: "#03930399" });
          } else {
            $("#message").css({ background: "#a40b0bb3" });
          }
          showMessage(php_response.msg);
        }
      },
      error: function () {
        $("#message").css({ background: "#a40b0bb3" });
        showMessage("Ha ocurrido un error al generar los usuarios");
      },
    });
  }
}
