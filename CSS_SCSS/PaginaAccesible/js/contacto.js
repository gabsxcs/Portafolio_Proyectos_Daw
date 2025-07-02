document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const errorSummary = document.getElementById('error-summary');
    
    function validateForm(e) {
      e.preventDefault();
      
      let hasErrors = false;
      let errorMessages = [];
      const errorList = document.getElementById('error-list');
      errorList.innerHTML = '';
      
      const errorElements = form.querySelectorAll('.form-error');
      errorElements.forEach(el => {
        el.classList.remove('error-visible');
      });
      
      form.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('error');
      });
      
      const requiredFields = form.querySelectorAll('[required]');
      requiredFields.forEach(field => {
        const errorElement = document.getElementById(`${field.id}-error`);
        
        if (!field.value.trim()) {
          field.classList.add('error');
          errorElement.classList.add('error-visible');
          hasErrors = true;
          
          const fieldLabel = form.querySelector(`label[for="${field.id}"]`).textContent.replace(' *', '');
          errorMessages.push(`El campo "${fieldLabel}" es obligatorio.`);
        }
      });
      
      const emailField = document.getElementById('email');
      const emailError = document.getElementById('email-error');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      if (emailField.value && !emailRegex.test(emailField.value)) {
        emailField.classList.add('error');
        emailError.classList.add('error-visible');
        hasErrors = true;
        errorMessages.push('El formato del correo electrónico no es válido.');
      }
      
      if (hasErrors) {
        errorSummary.style.display = 'block';
        errorMessages.forEach(message => {
          const li = document.createElement('li');
          li.textContent = message;
          errorList.appendChild(li);
        });
        
        errorSummary.focus();
      } else {
        errorSummary.style.display = 'none';

        document.getElementById('success-message').style.display = 'block';
        form.reset();
      }
    }
    
    form.addEventListener('submit', validateForm);
  });