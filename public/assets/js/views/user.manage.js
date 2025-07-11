$(document).ready(function () {
  cargarRoles();
  cargarPermisos();
  //Funciones de carga
  function cargarRoles() {
    $.get(base_url + "/role/getAll", function (response) {
      const $grado = $("#role_id_user");
      $grado.empty().append('<option value="">-- Seleccione --</option>');

      if (Array.isArray(response.data)) {
        response.data.forEach((g) => {
          $grado.append(`<option value="${g.role_id}">${g.nombre}</option>`);
        });
      }
    });
  }
  function cargarPermisos(permisosSeleccionados = [], callback = null) {
    $.get(base_url + "/permission/getAll", function (response) {
      const permisos = response.data;
      const $container = $("#permisosContainer");
      $container.empty();
  
      const grupos = {};
      const selectedSet = new Set(permisosSeleccionados.map(String));
  
      // Agrupar por grupo
      permisos.forEach((p) => {
        if (!grupos[p.grupo]) {
          grupos[p.grupo] = [];
        }
        grupos[p.grupo].push(p);
      });
  
      const $row = $('<div class="row"></div>');
  
      Object.keys(grupos).forEach((grupo) => {
        const $col = $('<div class="col-6"></div>');
        const $grupoLabel = $(`
          <small class="font-weight-bold d-block mb-1 mt-3">${grupo}</small>
        `);
        $col.append($grupoLabel);
  
        grupos[grupo].forEach((p) => {
          const isChecked = selectedSet.has(p.permiso_id.toString());
          const checkedAttr = isChecked ? "checked" : "";
  
          const $checkbox = $(`
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permisos[]" value="${p.permiso_id}" id="perm_${p.nombre}" ${checkedAttr}>
              <label class="form-check-label" for="perm_${p.nombre}">${p.nombre}</label>
            </div>
          `);
          $col.append($checkbox);
        });
  
        $row.append($col);
      });
  
      $container.append($row);
  
      // 🧠 Ejecutar callback si fue proporcionado
      if (typeof callback === "function") {
        callback();
      }
    });
  }
  

  $("#table_users").DataTable({
    ajax: {
      url: base_url + "/user/getAll", // Ajusta según tu ruta
      dataSrc: "data",
    },
    columns: [
      { data: "user_id" },
      { data: "nombre" },
      {
        data: null,
        render: function (data, type, row) {
          return "************";
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          if (row.nombre_rol) {
            return `<span class="badge ${row.color_rol}">${row.nombre_rol}</span>`;
          }
          return '<span class="badge badge-secondary">Sin rol</span>';
        },
      },
      {
        data: "estatus",
        render: function (data) {
          let badge = data === "Activo" ? "badge-success" : "badge-danger";
          return `<span class="badge ${badge}">${data}</span>`;
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data, type, row) {
          
          let disabled = row.protegido == 1 ? "disabled" : "";
          return `
            <button
              class="btn btn-sm btn-warning btn-edit-user ${disabled}"
              data-id="${row.user_id}"
              data-nombre="${row.nombre}"
              data-email="${row.email}"
              data-role="${row.role_id}"
              data-estado="${row.estado == 1 ? 1 : 0}"
            >
              <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-delete-user ${disabled}" data-id="${
              row.user_id
            }">
              <i class="fas fa-trash-alt"></i>
            </button>

          `;
        },
      },
    ],
    responsive: true,

    autoWidth: false,
    language: {
      url: url_plugins_datable_esp,
    },
  });

  $("#table_roles").DataTable({
    ajax: {
      url: base_url + "/role/getAll", // Ajusta el path si es diferente
      dataSrc: "data",
    },
    columns: [
      { data: "role_id" },
      { data: "nombre" },
      { data: "descripcion" },
      {
        data: "color_badge",
        render: function (data, type, row) {
          return `<span class="badge ${data}">Color de Rol</span>`;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          let disabled = row.protegido == 1 ? "disabled" : "";
          return `
                <button class="btn btn-sm btn-info btn-edit-role" data-id="${row.role_id}" ${disabled}><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger btn-delete-role" data-id="${row.role_id}"  ${disabled}><i class="fas fa-trash-alt"></i></button>
              `;
        },
      },
    ],
    responsive: true,
    autoWidth: false,
    language: {
      url: url_plugins_datable_esp,
    },
  });

  //------------------------------------------
  // GESTION DE USUARIO
  //------------------------------------------
  $(document).on("click", ".btn-new-user", function () {
    $("#formUser")[0].reset(); // Limpiar formulario
    $("#user_id_user").val(""); // Limpiar el ID oculto
    configureUserModal("create");
    $("#modalUserForm").modal("show");
  });

  $(document).on("click", ".btn-edit-user", function () {
    const $btn = $(this);
    const $icon = $btn.find("i");
    const originalIconClass = $icon.attr("class");
    const userId = $btn.data("id");

    // Mostrar spinner
    $icon.removeClass().addClass("fas fa-spinner fa-spin");

    $.ajax({
      url: base_url + "/user/get",
      method: "POST",
      data: { user_id: userId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          const user = response.data;

          $("#user_id_user").val(user.user_id);
          $("#nombre_user").val(user.nombre);
          $("#email_user").val(user.email);
          $("#role_id_user").val(user.role_id);
          $("#estado_user").val(user.estatus);

          configureUserModal("edit");
          $("#modalUserForm").modal("show");
        } else {
          Swal.fire("Error", "No se pudo obtener el usuario.", "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Error del servidor.", "error");
      },
      complete: function () {
        // Restaurar ícono original
        $icon.removeClass().addClass(originalIconClass);
      },
    });
  });

  $(document).on("click", ".btn-delete-user", function () {
    const userId = $(this).data("id");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción eliminará al usuario permanentemente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/user/delete",
          method: "POST",
          data: { user_id: userId },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Swal.fire("Eliminado", response.message, "success");
              // Actualiza la tabla o recarga los datos
              loadUsers(); // Asegúrate de tener esta función para recargar usuarios
            } else {
              Swal.fire(
                "Error",
                response.message || "No se pudo eliminar el usuario.",
                "error"
              );
            }
          },
          error: function () {
            Swal.fire(
              "Error",
              "Error del servidor al intentar eliminar el usuario.",
              "error"
            );
          },
        });
      }
    });
  });

  $("#btnOpenRoleModal").on("click", function () {
    // Limpiar formulario
    $("#formRole")[0].reset();
    // Limpiar campo oculto
    $("#role_id").val("");
    // Actualizar el título del modal
    $("#roleModalTitle").text("Nuevo Rol");
    // Mostrar el modal
    $("#modalRoleForm").modal("show");
  });

  function configureUserModal(mode) {
    const $header = $("#modalUserForm .modal-header");
    const $title = $("#userModalTitle");
    const $btn = $("#formUser button[type='submit']");

    // Limpiar clases de color previas
    $header.removeClass("bg-primary bg-warning");
    $btn.removeClass("btn-success btn-warning text-white");

    if (mode === "create") {
      $header.addClass("bg-primary");
      $title.html('<i class="fas fa-user-plus mr-2"></i> Nuevo Usuario');
      $btn
        .addClass("btn-success")
        .html('<i class="fas fa-save mr-1"></i> Guardar');
    } else if (mode === "edit") {
      $header.addClass("bg-warning");
      $title.html('<i class="fas fa-user-edit mr-2"></i> Editar Usuario');
      $btn
        .addClass("btn-warning text-white")
        .html('<i class="fas fa-save mr-1"></i> Actualizar');
    }
  }

  $("#formUser").on("submit", function (e) {
    e.preventDefault();
    const form = this;
    const formData = $(form).serialize();
    const userId = $("#user_id_user").val();
    const isNew = !userId;
    const url = base_url + (isNew ? "/user/store" : "/user/update");

    Swal.fire({
      title: isNew ? "¿Registrar usuario?" : "¿Actualizar usuario?",
      text: isNew
        ? "Se guardará un nuevo usuario en el sistema."
        : "Se actualizarán los datos del usuario.",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: isNew ? "Sí, registrar" : "Sí, actualizar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(
          url,
          formData,
          function (response) {
            if (response.status === "success") {
              $("#modalUserForm").modal("hide");
              form.reset();
              $("#user_id_user").val(""); // Limpiar el ID por si es edición

              Swal.fire({
                icon: "success",
                title: "Éxito",
                text: response.message,
                timer: 2000,
                showConfirmButton: false,
              });

              $("#table_users").DataTable().ajax.reload(); // Recargar tabla si usas DataTables
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message,
              });
            }
          },
          "json"
        ).fail(function () {
          Swal.fire({
            icon: "error",
            title: "Error del servidor",
            text: "No se pudo procesar la solicitud.",
          });
        });
      }
    });
  });

  //------------------------------------------
  // GESTION DE ROLES
  //------------------------------------------

  $(document).on("click", ".btn-new-role", function () {
    $("#formRole")[0].reset(); // Limpiar campos del formulario
    $("#role_id").val(""); // Limpiar el input hidden
    $("#roleModalTitle").text("Nuevo Rol"); // Cambiar el título del modal
    $("#color_rol").val(""); // Limpiar color seleccionado
    $("#permisosContainer").html(
      '<div class="text-muted">Cargando permisos...</div>'
    );

    configureRoleModal("create"); // 🔹 Aquí la llamada

    // Cargar los permisos dinámicamente
    cargarPermisos();

    // Mostrar el modal de rol
    $("#modalRoleForm").modal("show");
  });

  function configureRoleModal(mode) {
    const $header = $("#modalRoleForm .modal-header");
    const $title = $("#roleModalTitle");
    const $btn = $("#formRole button[type='submit']");

    // Limpiar clases de color previas
    $header.removeClass("bg-secondary bg-warning");
    $btn.removeClass("btn-success btn-warning text-white");

    if (mode === "create") {
      $header.addClass("bg-secondary");
      $title.html('<i class="fas fa-user-tag mr-2"></i> Nuevo Rol');
      $btn
        .addClass("btn-success")
        .html('<i class="fas fa-save mr-1"></i> Guardar');
    } else if (mode === "edit") {
      $header.addClass("bg-warning");
      $title.html('<i class="fas fa-user-tag mr-2"></i> Editar Rol');
      $btn
        .addClass("btn-warning text-white")
        .html('<i class="fas fa-save mr-1"></i> Actualizar');
    }
  }

  $(document).on("click", ".btn-edit-role", function () {
    const $btn = $(this);
    const $icon = $btn.find("i");
    const originalIconClass = $icon.attr("class");
    const roleId = $btn.data("id");
  
    $icon.removeClass().addClass("fas fa-spinner fa-spin");
  
    $.ajax({
      url: base_url + "/role/get",
      method: "POST",
      data: { role_id: roleId },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          const rol = response.data.role;
          const permisosAsignados = response.data.permissions;
  
          $("#role_id").val(rol.role_id);
          $("#nombre_rol").val(rol.nombre);
          $("#descripcion_rol").val(rol.descripcion);
          $("#color_rol").val(rol.color_badge);
  
          configureRoleModal("edit");
  
          // Cargar permisos y solo luego mostrar modal + restaurar ícono
          cargarPermisos(permisosAsignados.map((p) => p.permiso_id), function () {
            $("#modalRoleForm").modal("show");
            $icon.removeClass().addClass(originalIconClass); // Restaurar ícono después
          });
  
        } else {
          Swal.fire("Error", "No se pudo obtener el rol.", "error");
          $icon.removeClass().addClass(originalIconClass); // Restaurar en error también
        }
      },
      error: function () {
        Swal.fire("Error", "Error del servidor.", "error");
        $icon.removeClass().addClass(originalIconClass);
      }
    });
  });
  

  $("#formRole").on("submit", function (e) {
    e.preventDefault();

    const form = this;
    const formData = $(form).serialize(); // Incluye permisos[] y todos los inputs

    const roleId = $("#role_id").val();
    const isNew = !roleId;
    const url = base_url + (isNew ? "/role/store" : "/role/update");

    Swal.fire({
      title: isNew ? "¿Registrar rol?" : "¿Actualizar rol?",
      text: isNew
        ? "Se guardará un nuevo rol con los permisos seleccionados."
        : "Se actualizará el rol y sus permisos.",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: isNew ? "Sí, registrar" : "Sí, actualizar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.post(
          url,
          formData,
          function (response) {
            if (response.status === "success") {
              $("#modalRoleForm").modal("hide");
              form.reset();
              $("#role_id").val(""); // limpiar ID para modo nuevo

              Swal.fire({
                icon: "success",
                title: "Éxito",
                text: response.message,
                timer: 2000,
                showConfirmButton: false,
              });
              cargarRoles();
              // Si usas DataTables para listar roles
              if ($("#table_roles").length) {
                $("#table_roles").DataTable().ajax.reload();
              }
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message || "Ocurrió un error al guardar el rol.",
              });
            }
          },
          "json"
        ).fail(function () {
          Swal.fire({
            icon: "error",
            title: "Error del servidor",
            text: "No se pudo procesar la solicitud.",
          });
        });
      }
    });
  });

  $(document).on("click", ".btn-delete-role", function () {
    const roleId = $(this).data("id");
  
    Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción eliminará el rol permanentemente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: base_url + "/role/delete",
          method: "POST",
          data: { role_id: roleId },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Swal.fire("Eliminado", response.message, "success");
              // Recarga los roles (asegúrate de tener esta función)
              loadRoles();
            } else {
              Swal.fire(
                "Error",
                response.message || "No se pudo eliminar el rol.",
                "error"
              );
            }
          },
          error: function () {
            Swal.fire(
              "Error",
              "Error del servidor al intentar eliminar el rol.",
              "error"
            );
          },
        });
      }
    });
  });
  
});
