
/// Abre una ventana nueva con un nombre especifico, esto con el fin de que no se creen varias ventanas iguales.
function abrirVentana3(url, Nombre, Alto, Ancho) {
    var randomnumber = Math.floor((Math.random()*100)+1);
    // windowObjectReference2 = window.open(url, Nombre, "toolbar=0, width=" + Ancho + ", height=" + Alto + ",location=0,status=1,menubar=0,scrollbars=1,resizable=0, PopUp"+randomnumber);
    window.open(url,Nombre + "-" +randomnumber, 'width=' + Ancho + ', height=' + Alto +',scrollbars=1,menubar=0,resizable=1');
}

// function conexion(username, registrarse=false) {
//     alert(username);
//     $.ajax({
//         url: `${host}${api('conexion')}${username}`,
//         type: "POST",
//         dataType: "JSON",
//         success: function (respuestas) {
//             if(!respuestas.usuario){
//                 // var userName = "Shekhar Shete";
//                 // '<%Session["social_conexion"] = "' + false + '"; %>';
//                 // alert('<%=Session["social_conexion"] %>');
//             }
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }

