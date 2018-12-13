
/// Abre una ventana nueva con un nombre especifico, esto con el fin de que no se creen varias ventanas iguales.
function abrirVentana3(url, Nombre, Alto, Ancho) {
    var randomnumber = Math.floor((Math.random()*100)+1);
    // windowObjectReference2 = window.open(url, Nombre, "toolbar=0, width=" + Ancho + ", height=" + Alto + ",location=0,status=1,menubar=0,scrollbars=1,resizable=0, PopUp"+randomnumber);
    window.open(url,Nombre + "-" +randomnumber, 'width=' + Ancho + ', height=' + Alto +',scrollbars=1,menubar=0,resizable=1');
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

/**
 * Función para convertir los combobox en select2.
 */
$(function () {
    $(".to-select-2").select2({
        width: '100%',
        language: 'es'
    });
    /**
     * Solucionar el problema del tab index que tiene el select 2.
     */
    $(".to-select-2").on("select2:close", function (e) {
        e.target.focus();
    });
});