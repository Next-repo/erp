document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        // récupère tous les boutons de type "submit" liés au formulaire (à l'intérieur ou via form="")
        const submitButtons = Array.from(document.querySelectorAll("button, input")).filter(el =>
            el.type === "submit" && el.form === form
        );

        // Utilitaire : retourne true si le champ est à ignorer pour la validation automatique
        function shouldIgnoreField(field) {
            return field.disabled || field.type === "hidden";
        }

        function isFieldValid(field) {
            if (shouldIgnoreField(field)) return true; // on ignore ces champs

            // checkbox / radio => au moins un du groupe checked
            if (field.type === "checkbox" || field.type === "radio") {
                // si pas de name, on vérifie simplement ce champ
                if (!field.name) return field.checked;
                // utilisation de field.form pour limiter la recherche au même formulaire
                const group = Array.from(field.form.querySelectorAll(`[name="${CSS.escape(field.name)}"]`));
                return group.some(i => i.checked);
            }

            // file
            if (field.type === "file") {
                return field.files && field.files.length > 0;
            }

            // textarea
            if (field.tagName === "TEXTAREA") {
                return field.value.trim() !== "";
            }

            // select simple ou multiple
            if (field.tagName === "SELECT") {
                if (field.multiple) {
                    return Array.from(field.selectedOptions).some(opt => opt.value !== "");
                }
                return field.value !== "";
            }

            // nombres / range
            if (field.type === "number" || field.type === "range") {
                return field.value !== "" && !isNaN(field.value);
            }

            // email / url / others : on délègue à HTML5
            if (typeof field.checkValidity === "function") {
                return field.checkValidity();
            }

            // fallback : non vide
            return field.value == null ? true : String(field.value).trim() !== "";
        }

        function checkFormValidity() {
            // récupère dynamiquement les champs required (permet d'accepter les changements dynamiques)
            const requiredFields = Array.from(form.querySelectorAll("[required]")).filter(f => !shouldIgnoreField(f));
            const invalid = requiredFields.filter(f => !isFieldValid(f));

            const allValid = invalid.length === 0;

            submitButtons.forEach(btn => {
                btn.disabled = !allValid;
                // accessibilité
                if (!allValid) btn.setAttribute("aria-disabled", "true");
                else btn.removeAttribute("aria-disabled");
            });

            // pour debug : si le form a data-debug="true", afficher les champs invalides en console
            if (form.dataset.debug === "true") {
                if (!allValid) {
                    console.warn("Form invalid — champs requis non valides :", invalid);
                } else {
                    console.info("Form valide");
                }
            }
        }

        // délégation : on réévalue à chaque input/change et après reset
        form.addEventListener("input", checkFormValidity, {passive: true});
        form.addEventListener("change", checkFormValidity, {passive: true});
        form.addEventListener("reset", () => setTimeout(checkFormValidity, 0));

        // initial
        checkFormValidity();
    });
});
