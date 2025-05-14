//NOTA: estas validaciones funciona con las clases de bootstrap
function initFormValidation() {
  const forms = document.querySelectorAll("form");

  console.log("ESTA CORRIENDO /(¨>)>/");

  forms.forEach((form) => {
    const inputs = form.querySelectorAll("input, select");

    inputs.forEach((input) => {
      input.addEventListener("input", function () {
        enforceMaxLength(input);
        validateInput(input);
        if (input.hasAttribute("data-letters")) {
          enforceLettersOnly(input);
        }
        if (input.hasAttribute("data-capitalize")) {
          capitalizeWords(input);
        }
        if (input.hasAttribute("data-currency")) {
          formatCurrency(input);
        }
        if (input.hasAttribute("data-uppercase")) {
          enforceUpperCase(input);
        }
      });

      input.addEventListener("change", function () {
        validateInput(input);
      });

      if (input.hasAttribute("data-numeric")) {
        input.addEventListener("keypress", function (event) {
          restrictNumericInput(event);
        });
      }

      if (input.hasAttribute("data-letters")) {
        input.addEventListener("keypress", function (event) {
          restrictLettersOnly(event);
        });
      }

      if (input.hasAttribute("data-currency")) {
        input.addEventListener("keypress", function (event) {
          restrictCurrencyInput(event);
        });
      }
    });

    form.addEventListener("submit", function (event) {
      if (!validateForm(form)) {
        event.preventDefault();
      }
    });
  });

  function validateForm(form) {
    let isValid = true;
    let firstInvalidInput = null;
    const inputs = form.querySelectorAll("input, select");

    inputs.forEach((input) => {
      if (!validateInput(input)) {
        if (isValid) {
          firstInvalidInput = input;
        }
        isValid = false;
      }
    });

    if (firstInvalidInput) {
      firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
      firstInvalidInput.focus();
    }

    return isValid;
  }

  function validateInput(input) {
    let isValid = true;

    if (input.hasAttribute("data-required") && input.value.trim() === "") {
      showError(input, "Este campo es obligatorio.");
      isValid = false;
    } else {
      hideError(input);
    }

    if (input.hasAttribute("data-numeric") && !/^\d*$/.test(input.value)) {
      showError(input, "Este campo solo debe contener números.");
      isValid = false;
    } else if (input.hasAttribute("data-numeric")) {
      hideError(input);
    }

    if (
      input.hasAttribute("data-email") &&
      !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)
    ) {
      showError(input, "Por favor, ingrese un correo electrónico válido.");
      isValid = false;
    } else if (input.hasAttribute("data-email")) {
      hideError(input);
    }

    if (input.hasAttribute("data-maxlength")) {
      const maxLength = input.getAttribute("data-maxlength");
      if (input.value.length > maxLength) {
        showError(
          input,
          `Este campo no debe exceder los ${maxLength} caracteres.`
        );
        isValid = false;
      } else {
        hideError(input);
      }
    }

    if (input.hasAttribute("data-minlength")) {
      const minLength = input.getAttribute("data-minlength");
      if (input.value.length < minLength) {
        showError(
          input,
          `Este campo debe tener al menos ${minLength} caracteres.`
        );
        isValid = false;
      } else {
        hideError(input);
      }
    }

    if (input.hasAttribute("data-exactlength")) {
      const exactLength = input.getAttribute("data-exactlength");
      if (input.value.length !== parseInt(exactLength)) {
        showError(
          input,
          `Este campo debe tener exactamente ${exactLength} caracteres.`
        );
        isValid = false;
      } else {
        hideError(input);
      }
    }

    if (input.hasAttribute("data-currency")) {
      const currencyPattern = /^\d{1,4}(\.\d{0,2})?$/;
      if (!currencyPattern.test(input.value)) {
        showError(
          input,
          "Ingrese una cantidad válida con un máximo de 4 dígitos y 2 decimales."
        );
        isValid = false;
      } else {
        hideError(input);
      }
    }

    if (isValid) {
      showValid(input);
    }

    return isValid;
  }

  function enforceMaxLength(input) {
    if (input.hasAttribute("data-maxlength")) {
      const maxLength = input.getAttribute("data-maxlength");
      if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
      }
    }
  }

  function restrictNumericInput(event) {
    const keyCode = event.which ? event.which : event.keyCode;
    if (keyCode < 48 || keyCode > 57) {
      event.preventDefault();
    }
  }

  function restrictLettersOnly(event) {
    const keyCode = event.which ? event.which : event.keyCode;
    const key = String.fromCharCode(keyCode);
    const allowedPattern = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]$/;
  
    if (!allowedPattern.test(key)) {
      event.preventDefault();
    }
  }

  function enforceLettersOnly(input) {
    input.value = input.value.replace(/[^a-zA-ZñÑáéíóúÁÉÍÓÚ\s]/g, "");
  }

  function capitalizeWords(input) {
    input.value = input.value
      .replace(/\b\w/g, function (char) {
        return char.toUpperCase();
      })
      .replace(/\B\w/g, function (char) {
        return char.toLowerCase();
      });
  }

  function enforceUpperCase(input) {
    input.value = input.value.toUpperCase();
  }

  function restrictCurrencyInput(event) {
    const keyCode = event.which ? event.which : event.keyCode;
    const value = event.target.value;
    const decimalSeparator = ".";
    const parts = value.split(decimalSeparator);

    // Allow digits and decimal separator
    if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
      event.preventDefault();
    } else if (keyCode === 46 && parts.length > 1) {
      event.preventDefault();
    } else if (keyCode === 46 && value.length === 0) {
      event.preventDefault();
    }

    // Restrict to 4 digits before decimal and 2 digits after decimal
    if (
      parts[0].length >= 4 &&
      keyCode !== 46 &&
      !value.includes(decimalSeparator)
    ) {
      event.preventDefault();
    }

    if (parts.length > 1 && parts[1].length >= 2) {
      event.preventDefault();
    }
  }

  function formatCurrency(input) {
    const value = input.value.replace(/[^\d.]/g, "");
    const parts = value.split(".");

    // Limit to 4 digits before the decimal point
    parts[0] = parts[0].slice(0, 4);

    // Limit to 2 digits after the decimal point
    if (parts.length > 1) {
      parts[1] = parts[1].slice(0, 2);
    }

    input.value = parts.join(".");
  }

  function showError(input, message) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains("invalid-feedback")) {
      errorDiv.textContent = message;
      input.classList.add("is-invalid");
      input.classList.remove("is-valid");
    }
  }

  function hideError(input) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains("invalid-feedback")) {
      errorDiv.textContent = "";
      input.classList.remove("is-invalid");
    }
  }

  function showValid(input) {
    input.classList.add("is-valid");
    input.classList.remove("is-invalid");
  }

  // Exponer la función validateForm globalmente
  window.validateForm = validateForm;
}
