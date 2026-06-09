// ============================================================
// PRESENTACIÓN - script.js (index.php)
// Carga solicitudes desde BD via AJAX
// ============================================================

const tabData = {
    all:      { title: 'Todas',        desc: 'Aquí puedes ver todas las solicitudes registradas.' },
    review:   { title: 'En revisión',  desc: 'Solicitudes que están siendo evaluadas.' },
    approved: { title: 'Aprobadas',    desc: 'Solicitudes aprobadas correctamente.' },
    pending:  { title: 'Pendientes',   desc: 'Solicitudes en espera de atención.' }
};

document.querySelectorAll('.status').forEach(btn => {
    btn.addEventListener('click', function () {
        const tipo = this.dataset.tab;
        document.querySelectorAll('.status').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const box = document.getElementById('status-box');
        box.innerHTML = `<h3>${tabData[tipo].title}</h3><p>${tabData[tipo].desc}</p>`;
        box.style.animation = 'none';
        box.offsetHeight;
        box.style.animation = 'fadeStatus 0.4s ease';
        box.className = 'status-dynamic ' + tipo;
    });
});

function cargarActividades() {
    fetch('negocio/api.php?tipo=solicitudes')
        .then(r => r.json())
        .then(resp => {
            const contenedor = document.getElementById('actividades-recientes');
            contenedor.innerHTML = '';
            if (!resp.data || resp.data.length === 0) {
                contenedor.innerHTML = '<p style="font-size:0.85rem;color:#999;">Sin actividad reciente.</p>';
                return;
            }
            resp.data.slice(0, 10).forEach(s => {
                const div = document.createElement('div');
                div.classList.add('activity');
                let claseEstado = 'pending';
                const estado = (s.estado || '').toLowerCase();
                if (estado.includes('aprobad'))  claseEstado = 'approved';
                if (estado.includes('revisi'))   claseEstado = 'review';
                div.innerHTML = `<p>${s.numero_radicado}</p><span class="status ${claseEstado}">${s.estado}</span>`;
                contenedor.appendChild(div);
            });
        })
        .catch(err => console.error('Error cargando actividades:', err));
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('status-title').textContent       = tabData['all'].title;
    document.getElementById('status-description').textContent = tabData['all'].desc;
    cargarActividades();
});
