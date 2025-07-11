// utils.js

// Retorna el nombre del mes desde un string de fecha "YYYY-MM-DD"
function getNombreMes(fechaStr) {
  const fecha = new Date(fechaStr);
  return fecha.toLocaleDateString("es-ES", { month: "long" });
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
  const mes = fecha.toLocaleDateString("es-ES", { month: "long" });
  return `${dia} de ${mes}`;
}

function formatearFechaDMY(fechaISO) {
  if (!fechaISO) return "";
  const [año, mes, dia] = fechaISO.split("-");
  return `${dia}/${mes}/${año}`;
}

// Función para formatear fecha
function formatFechaCorta(fechaStr) {
  const meses = [
    "Ene",
    "Feb",
    "Mar",
    "Abr",
    "May",
    "Jun",
    "Jul",
    "Ago",
    "Sep",
    "Oct",
    "Nov",
    "Dic",
  ];
  const [anio, mes, dia] = fechaStr.split("-");
  return `${parseInt(dia)} ${meses[parseInt(mes) - 1]}`;
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
    { id: 12, nombre: "DICIEMBRE" },
  ];
}

function getFechaActual() {
  // Obtener la fecha y hora actual en Lima
  const fechaLima = new Date().toLocaleString("en-US", {
    timeZone: "America/Lima",
  });
  const date = new Date(fechaLima); // Convierte a objeto Date para extraer componentes

  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`; // Ej: "2025-07-09"
}

function obtenerDiasDelMes(mes, anio = new Date().getFullYear()) {
  const diasSemana = {
    0: { abrev: "D", nombre: "Domingo" },
    1: { abrev: "L", nombre: "Lunes" },
    2: { abrev: "M", nombre: "Martes" },
    3: { abrev: "M", nombre: "Miércoles" },
    4: { abrev: "J", nombre: "Jueves" },
    5: { abrev: "V", nombre: "Viernes" },
    6: { abrev: "S", nombre: "Sábado" },
  };

  const dias = [];
  const diasEnMes = new Date(anio, mes, 0).getDate();

  for (let d = 1; d <= diasEnMes; d++) {
    const fecha = new Date(anio, mes - 1, d);
    const dia = diasSemana[fecha.getDay()];
    dias.push({
      fecha: fecha.toISOString().split("T")[0],
      diaNombre: dia.nombre,
      diaAbrev: dia.abrev,
      diaNumero: d < 10 ? `0${d}` : `${d}`,
    });
  }

  return dias;
}

function formatearFechaLegible(fechaISO) {
  if (!fechaISO) return "Fecha no disponible";

  const opciones = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };

  // Asegura compatibilidad reemplazando espacio por 'T'
  const fecha = new Date(fechaISO.replace(" ", "T"));

  if (isNaN(fecha.getTime())) return "Fecha inválida";

  return fecha.toLocaleString("es-PE", opciones);
}

function getHoraMinuto(fecha = null) {
  let date;

  if (!fecha) {
    return null;
  } else if (
    typeof fecha === "string" &&
    /^\d{2}:\d{2}(:\d{2})?$/.test(fecha)
  ) {
    // Si solo es una hora, convertirla a formato completo ISO
    date = new Date(`1970-01-01T${fecha}`);
  } else {
    date = new Date(fecha);
  }

  // Validar que sea una fecha válida
  if (isNaN(date.getTime())) return "--";

  const horas = date.getHours().toString().padStart(2, "0");
  const minutos = date.getMinutes().toString().padStart(2, "0");
  return `${horas}:${minutos}`;
}

function iniciarReloj(idHora, idFecha = null) {
  const dias = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
  const meses = [
    "Ene",
    "Feb",
    "Mar",
    "Abr",
    "May",
    "Jun",
    "Jul",
    "Ago",
    "Sep",
    "Oct",
    "Nov",
    "Dic",
  ];

  function actualizar() {
    const now = new Date();
    const horas = now.getHours().toString().padStart(2, "0");
    const minutos = now.getMinutes().toString().padStart(2, "0");
    const segundos = now.getSeconds();

    const separador = segundos % 2 === 0 ? ":" : " ";
    $(`#${idHora}`).text(`${horas}${separador}${minutos}`);

    if (idFecha) {
      const dia = dias[now.getDay()];
      const fecha = now.getDate().toString().padStart(2, "0");
      const mes = meses[now.getMonth()];
      const año = now.getFullYear();
      $(`#${idFecha}`).text(`${dia}, ${fecha} ${mes} ${año}`);
    }
  }

  actualizar(); // Ejecutar una vez
  setInterval(actualizar, 1000); // Luego cada segundo
}

function formatearHoraAmPm(horaStr) {
  if (!horaStr || typeof horaStr !== "string") return null;

  const partes = horaStr.split(":");
  if (partes.length < 2) return "Hora inválida";

  let horas = parseInt(partes[0], 10);
  const minutos = partes[1];
  const ampm = horas >= 12 ? "p.m." : "a.m.";

  horas = horas % 12 || 12;

  return `${horas}:${minutos} ${ampm}`;
}

function aplicarValidacionesPersonalizadas() {
  $(document).on("input", "input[data-validate]", function () {
    const tipos = $(this).data("validate").toString().split(/\s+/);
    let value = this.value;

    tipos.forEach((tipo) => {
      switch (tipo) {
        case "numeric":
          value = value.replace(/[^0-9]/g, "");
          break;
        case "alpha":
          value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
          break;
        case "alphanumeric":
          value = value.replace(/[^a-zA-Z0-9]/g, "");
          break;
        case "uppercase":
          value = value.toUpperCase();
          break;
        case "no-space":
          value = value.replace(/\s+/g, "");
          break;
        // puedes seguir agregando más validaciones aquí
      }
    });

    this.value = value;
  });
}

$(document).ready(function () {
  aplicarValidacionesPersonalizadas();
});
