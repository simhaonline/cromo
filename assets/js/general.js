
/// Abre una ventana nueva con un nombre especifico, esto con el fin de que no se creen varias ventanas iguales.
function abrirVentana3(url, Nombre, Alto, Ancho) {
    windowObjectReference2 = window.open(url, Nombre, "toolbar=0, width=" + Ancho + ", height=" + Alto + ",location=0,status=1,menubar=0,scrollbars=1,resizable=0");
    windowObjectReference2.focus();
}

var validarCaracteres = function (event) {
    var tecla = event.keyCode;
    var valorIngresado = String.fromCharCode(tecla);
    var regEx = new RegExp("((?![a-zA-Z\\-\\_]).)+", "g");
    var match = valorIngresado.match(regEx);
    if (match && match.length > 0) {
        event.preventDefault();
        return false;
    }
}

function ChequearTodosTabla(source, nombre) {
    checkboxes = document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    for (i = 0; i < checkboxes.length; i++) { //recoremos todos los controles
        if (checkboxes[i].type == "checkbox") {//solo si es un checkbox entramos
            if (checkboxes[i].name == nombre) {
                checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)   
            }
        }
    }
}

$(document).ready(function() {
    $( "select" ).addClass("js-example-basic-multiple");
    $('.js-example-basic-multiple').select2();
});