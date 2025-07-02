(() => {
  'use strict';

  const forms = document.querySelectorAll('.form-personalizado');

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      const nameField = form.querySelector('#validationCustom01');
      const lastNameField = form.querySelector('#validationCustom02');
      const usernameField = form.querySelector('#validationCustomUsername');
      const emailField = form.querySelector('#validationCustomEmail');
      const passwordField = form.querySelector('#validationCustomPassword');
      const genderField = form.querySelector('#validationCustomGender');

      if (nameField && !validateName(nameField.value)) {
        event.preventDefault();
        event.stopPropagation();
        nameField.setCustomValidity('El nombre no debe contener números ni caracteres especiales.');
      } else {
        nameField.setCustomValidity('');
      }

      if (lastNameField && !validateName(lastNameField.value)) {
        event.preventDefault();
        event.stopPropagation();
        lastNameField.setCustomValidity('El apellido no debe contener números ni caracteres especiales.');
      } else {
        lastNameField.setCustomValidity('');
      }

      if (usernameField && !validateUsername(usernameField.value)) {
        event.preventDefault();
        event.stopPropagation();
        usernameField.setCustomValidity('El nombre de usuario debe tener al menos 5 caracteres.');
      } else {
        usernameField.setCustomValidity('');
      }

      if (emailField && !validateEmail(emailField.value)) {
        event.preventDefault();
        event.stopPropagation();
        emailField.setCustomValidity('Por favor escribe un email válido.');
      } else {
        emailField.setCustomValidity('');
      }

      if (passwordField && !validatePassword(passwordField.value)) {
        event.preventDefault();
        event.stopPropagation();
        passwordField.setCustomValidity('La contraseña debe tener 8-16 caracteres, una mayúscula, una minúscula y un número.');
      } else {
        passwordField.setCustomValidity('');
      }

      if (genderField && genderField.value === '') {
        event.preventDefault();
        event.stopPropagation();
        genderField.setCustomValidity('Por favor escoge tu género.');
      } else {
        genderField.setCustomValidity('');
      }

      form.classList.add('was-validated');
    }, false);
  });

  function validateName(name) {
    const re = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    return re.test(name);
  }

  function validateUsername(username) {
    return username.length >= 5;
  }

  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  function validatePassword(password) {
    const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/;
    return re.test(password);
  }
})();
