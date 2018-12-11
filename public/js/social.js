
var host="http://localhost/soga/cesio/public/index.php/";

/**
 * Función devuelve la api requerida
 */
function api(nombre) {
    var api= {
        'conexion':'api/social/conexion/',
    };

    return api[nombre];
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

