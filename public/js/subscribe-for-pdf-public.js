(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  document.addEventListener("DOMContentLoaded", function () {
    const inputFields = document.querySelectorAll(".sutp-form-control");

    inputFields.forEach((inputField) => {
      inputField.addEventListener("input", function () {
        if (inputField.value !== "") {
          inputField.classList.add("filled");
        } else {
          inputField.classList.remove("filled");
        }
      });
    });
    var emailInput = document.getElementById("email");
    emailInput.addEventListener("input", function () {
      const email = emailInput.value;
      const dotCount = (email.match(/\./g) || []).length;
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

      if (!email.match(emailPattern)) {
        emailInput.setCustomValidity("Invalid email address.");
      } else if (dotCount > 2) {
        emailInput.setCustomValidity(
          "Email should contain at most one dot in the domain part."
        );
      } else {
        emailInput.setCustomValidity("");
      }
    });
  });
})(jQuery);
