document.getElementById("formSolicitud").addEventListener("submit", function(e) {

    let nombre = document.getElementById("nombre").value.trim();
    let correo = document.getElementById("correo").value.trim();
    let tipo = document.getElementById("tipo").value;
    let descripcion = document.getElementById("descripcion").value.trim();
    let fecha = document.getElementById("fecha").value;

    // Validar campos vacíos
    if (!nombre || !correo || !tipo || !descripcion || !fecha) {
        alert("Todos los campos son obligatorios");
        e.preventDefault();
        return;
    }

    // Validar correo
    let regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regexCorreo.test(correo)) {
        alert("Correo inválido");
        e.preventDefault();
        return;
    }

    // Validar longitud descripción
    if (descripcion.length < 10) {
        alert("La descripción debe tener al menos 10 caracteres");
        e.preventDefault();
        return;
    }

    let archivo = document.getElementById("archivo").files[0];

    if (!archivo) {
        alert("Debes adjuntar un archivo PDF");
        e.preventDefault();
        return;
    }

    // Validar tipo
    if (archivo.type !== "application/pdf") {
        alert("Solo se permiten archivos PDF");
        e.preventDefault();
        return;
    }

    // Validar tamaño (2MB)
    let maxSize = 2 * 1024 * 1024;

    if (archivo.size > maxSize) {
        alert("El archivo supera el tamaño permitido (2MB)");
        e.preventDefault();
        return;
    }

    document.getElementById("formSolicitud").addEventListener("submit", function(e) {
    e.preventDefault(); // 🚨 evita recarga

    let form = document.getElementById("formSolicitud");
    let formData = new FormData(form);

    let mensaje = document.getElementById("mensaje");

    // 🔎 VALIDACIONES (las tuyas + archivo)
    let nombre = form.nombre.value.trim();
    let correo = form.correo.value.trim();
    let tipo = form.tipo.value;
    let descripcion = form.descripcion.value.trim();
    let fecha = form.fecha.value;
    let archivo = document.getElementById("archivo").files[0];

    if (!nombre || !correo || !tipo || !descripcion || !fecha || !archivo) {
        mensaje.innerHTML = "❌ Todos los campos son obligatorios";
        return;
    }

    if (archivo.type !== "application/pdf") {
        mensaje.innerHTML = "❌ Solo PDF permitido";
        return;
    }

    if (archivo.size > 2 * 1024 * 1024) {
        mensaje.innerHTML = "❌ Máximo 2MB";
        return;
    }

    // 🚀 AJAX con fetch
    fetch("php/procesar_solicitud.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "ok") {
            mensaje.innerHTML = "✅ " + data.mensaje;
            mensaje.style.color = "green";

            form.reset(); // limpiar formulario
        } else {
            mensaje.innerHTML = "❌ Error al enviar";
        }

    })
    .catch(error => {
        mensaje.innerHTML = "❌ Error de conexión";
        console.error(error);
    });

});

});