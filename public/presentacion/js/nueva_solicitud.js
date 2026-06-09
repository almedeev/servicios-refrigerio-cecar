// ============================================================
// PRESENTACIÓN - JS: Nueva Solicitud (AJAX + validaciones)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // Cargar dependencias dinámicamente desde la BD
    cargarDependencias();

    const form    = document.getElementById('formSolicitud');
    const mensaje = document.getElementById('mensaje');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validaciones frontend
        if (!validarFormulario()) return;

        const formData = new FormData(form);
        formData.append('accion', 'crear');

        const btnSubmit = form.querySelector('button[type="submit"]');
        btnSubmit.innerHTML = '⏳ Enviando...';
        btnSubmit.disabled  = true;

        fetch('../negocio/api.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'ok') {
                mostrarMensaje(`✅ ${data.mensaje} — Radicado: <strong>${data.radicado}</strong>`, 'green');
                form.reset();
                // Volver al paso 1
                document.querySelectorAll('.step').forEach((s, i) => {
                    s.classList.toggle('active', i === 0);
                });
            } else {
                mostrarMensaje(`❌ ${data.mensaje}`, 'red');
            }
        })
        .catch(() => mostrarMensaje('❌ Error de conexión con el servidor.', 'red'))
        .finally(() => {
            btnSubmit.innerHTML = '<img src="../assets/application-resources/form-icons/next.svg"> Enviar solicitud';
            btnSubmit.disabled  = false;
        });
    });

    // Validar archivo PDF en frontend
    const inputArchivo = document.getElementById('archivo');
    if (inputArchivo) {
        inputArchivo.addEventListener('change', function () {
            const archivo = this.files[0];
            if (!archivo) return;

            if (archivo.type !== 'application/pdf') {
                mostrarMensaje('❌ Solo se permiten archivos PDF.', 'red');
                this.value = '';
                return;
            }

            if (archivo.size > 5 * 1024 * 1024) {
                mostrarMensaje('❌ El archivo no puede superar 5MB.', 'red');
                this.value = '';
                return;
            }

            mostrarMensaje('✅ Archivo PDF listo para enviar.', 'green');
        });
    }
});

// Cargar dependencias via AJAX
function cargarDependencias() {
    fetch('../negocio/api.php?tipo=dependencias')
        .then(r => r.json())
        .then(data => {
            if (data.status !== 'ok') return;

            const select = document.querySelector('select[name="dependencia_id"]');
            if (!select) return;

            data.data.forEach(dep => {
                const option = document.createElement('option');
                option.value       = dep.id;
                option.textContent = dep.nombre;
                select.appendChild(option);
            });
        })
        .catch(err => console.error('Error cargando dependencias:', err));
}

// Validaciones frontend básicas
function validarFormulario() {
    const fecha = document.querySelector('input[name="fecha_solicitud"]')?.value;
    const dep   = document.querySelector('select[name="dependencia_id"]')?.value;
    const valor = document.getElementById('valor_total')?.value;

    if (!fecha) {
        mostrarMensaje('❌ La fecha de solicitud es obligatoria.', 'red');
        return false;
    }

    if (!dep) {
        mostrarMensaje('❌ Debes seleccionar una dependencia.', 'red');
        return false;
    }

    if (valor && isNaN(parseFloat(valor.replace(/,/g, '')))) {
        mostrarMensaje('❌ El valor presupuestal debe ser un número.', 'red');
        return false;
    }

    return true;
}

function mostrarMensaje(html, color) {
    const div = document.getElementById('mensaje');
    if (!div) return;
    div.innerHTML = html;
    div.style.color      = color === 'green' ? '#065f46' : '#991b1b';
    div.style.background = color === 'green' ? '#d1fae5' : '#fee2e2';
    div.style.padding    = '0.75rem 1rem';
    div.style.borderRadius = '8px';
    div.style.marginTop  = '1rem';
}
