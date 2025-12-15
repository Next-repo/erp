$(function () {
   $(document).on("click", ".loadBtnDeux", function () {
      const $btn = $(this);

      if ($btn.prop("disabled")) return;

      // Sauvegarde le contenu original du bouton
      const originalContent = $btn.html();

      // Désactive le bouton
      $btn.prop("disabled", true);

      // Remplace le contenu par un spinner
      $btn.html(`
         <div class="spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true">
            <span class="visually-hidden"></span>
         </div>
      `);

      // Après 5 secondes, restaure l'état initial
      setTimeout(() => {
         $btn.html(originalContent);
         $btn.prop("disabled", false);
      }, 9000);
   });
});
