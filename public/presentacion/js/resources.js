document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formSolicitud");
    const steps = document.querySelectorAll(".step");
    let currentStep = 0;

    function showStep(newStep) {

        const current = steps[currentStep];
        const next = steps[newStep];

        const forward = newStep > currentStep;

        // Reset clases
        steps.forEach(step => {
            step.classList.remove("active", "exit-left", "exit-right");
        });

        // Animación salida
        if (forward) {
            current.classList.add("exit-left");
        } else {
            current.classList.add("exit-right");
        }

        setTimeout(() => {
            current.classList.remove("active");

            next.classList.add("active");

            currentStep = newStep;
        }, 200);
    }
    // SIGUIENTE
    document.querySelectorAll(".next").forEach(btn => {
        btn.addEventListener("click", () => {

            const inputs = steps[currentStep].querySelectorAll("input, textarea, select");
            let valid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    valid = false;
                    input.reportValidity();
                }
            });

            if (valid && currentStep < steps.length - 1) {
                showStep(currentStep + 1);
            }
        });
    });

    // ATRÁS
    document.querySelectorAll(".prev").forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        });
    });

    // ENVÍO
    form.addEventListener("submit", function () {
        const btn = form.querySelector('button[type="submit"]');

        btn.innerHTML = "⏳ Enviando...";
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = "✈ Enviar solicitud";
            btn.disabled = false;
        }, 2000);
    });

});