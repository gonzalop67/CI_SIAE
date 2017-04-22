// Función para obtener la fecha actual parecida a "Lunes, 9 de Noviembre de 2009"
// Esta Función necesita cargar primero JQuery
function fecha_actual(obj) {
    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    var f = new Date();
    var cadena = diasSemana[f.getDay()]+", "+f.getDate()+" de "+meses[f.getMonth()]+" de "+f.getFullYear();
    $(obj).html(cadena);
}