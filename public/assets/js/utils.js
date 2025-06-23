// utils.js

// Retorna el nombre del mes desde un string de fecha "YYYY-MM-DD"
function getNombreMes(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleDateString('es-ES', { month: 'long' });
  }
  
  // Retorna solo el día (número) desde "YYYY-MM-DD"
  function getDia(fechaStr) {
    const fecha = new Date(fechaStr);
    const dia = fecha.getDate() + 1;
    return dia < 10 ? `0${dia}` : `${dia}`;
  }
  
  
  // Retorna "13 de mayo", por ejemplo
  function formatearFechaCompleta(fechaStr) {
    const fecha = new Date(fechaStr);
    const dia = fecha.getDate();
    const mes = fecha.toLocaleDateString('es-ES', { month: 'long' });
    return `${dia} de ${mes}`;
  }
  
  function obtenerMeses() {
    return [
      { id: 1, nombre: "ENERO" },
      { id: 2, nombre: "FEBRERO" },
      { id: 3, nombre: "MARZO" },
      { id: 4, nombre: "ABRIL" },
      { id: 5, nombre: "MAYO" },
      { id: 6, nombre: "JUNIO" },
      { id: 7, nombre: "JULIO" },
      { id: 8, nombre: "AGOSTO" },
      { id: 9, nombre: "SEPTIEMBRE" },
      { id: 10, nombre: "OCTUBRE" },
      { id: 11, nombre: "NOVIEMBRE" },
      { id: 12, nombre: "DICIEMBRE" }
    ];
  }
  
  
