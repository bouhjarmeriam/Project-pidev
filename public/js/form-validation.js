document.addEventListener('DOMContentLoaded', function() {
    // Initialize all forms
    const forms = document.querySelectorAll('form.form-container');
    forms.forEach(form => {
        initializeFormValidation(form);
    });
});

function initializeFormValidation(form) {
    // Add real-time validation for all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('change', function() {
            validateField(this);
        });
        field.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Add validation for number fields
    const numberFields = form.querySelectorAll('input[type="number"]');
    numberFields.forEach(field => {
        field.addEventListener('input', function() {
            validateNumberField(this);
        });
    });

    // Add validation for textareas with minlength
    const textareas = form.querySelectorAll('textarea[minlength]');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            validateTextLength(this);
        });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm(this)) {
            showSuccessMessage('Formulaire validé avec succès');
            this.submit();
        }
    });
}

function validateField(field) {
    const inputField = field.closest('.input-field');
    const errorMessage = field.getAttribute('data-error') || 'Ce champ est requis';
    
    if (field.hasAttribute('required') && !field.value.trim()) {
        showFieldError(inputField, errorMessage);
        return false;
    }

    hideFieldError(inputField);
    return true;
}

function validateNumberField(field) {
    const inputField = field.closest('.input-field');
    const value = parseFloat(field.value);
    const min = parseFloat(field.getAttribute('min'));
    
    if (isNaN(value) || (min !== null && value < min)) {
        showFieldError(inputField, 'Veuillez entrer un nombre valide et positif');
        return false;
    }

    hideFieldError(inputField);
    return true;
}

function validateTextLength(field) {
    const inputField = field.closest('.input-field');
    const minLength = parseInt(field.getAttribute('minlength'));
    
    if (field.value.trim().length < minLength) {
        showFieldError(inputField, `Le texte doit contenir au moins ${minLength} caractères`);
        return false;
    }

    hideFieldError(inputField);
    return true;
}

function validateDateRange(form) {
    const dateEntree = form.querySelector('input[id$="_dateEntree"]');
    const dateSortie = form.querySelector('input[id$="_dateSortie"]');
    
    if (dateEntree && dateSortie && dateEntree.value && dateSortie.value) {
        const entreeDate = new Date(dateEntree.value);
        const sortieDate = new Date(dateSortie.value);
        
        if (sortieDate <= entreeDate) {
            showFieldError(dateSortie.closest('.input-field'), 
                'La date de sortie doit être postérieure à la date d\'entrée');
            return false;
        }
    }
    return true;
}

function validateForm(form) {
    let isValid = true;
    
    // Validate all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    // Validate number fields
    const numberFields = form.querySelectorAll('input[type="number"]');
    numberFields.forEach(field => {
        if (!validateNumberField(field)) {
            isValid = false;
        }
    });

    // Validate textareas with minlength
    const textareas = form.querySelectorAll('textarea[minlength]');
    textareas.forEach(field => {
        if (!validateTextLength(field)) {
            isValid = false;
        }
    });

    // Validate date range for sejour form
    if (form.querySelector('input[id$="_dateEntree"]')) {
        if (!validateDateRange(form)) {
            isValid = false;
        }
    }

    if (!isValid) {
        showErrorMessage('Veuillez corriger les erreurs dans le formulaire');
    }

    return isValid;
}

function showFieldError(inputField, message) {
    inputField.classList.add('error');
    let errorDiv = inputField.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        inputField.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

function hideFieldError(inputField) {
    inputField.classList.remove('error');
    const errorDiv = inputField.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function showErrorMessage(message) {
    if (typeof M !== 'undefined') {
        M.toast({
            html: `<div class="error-toast">
                    <i class="material-icons">error</i>
                    <span>${message}</span>
                   </div>`,
            classes: 'red lighten-1',
            displayLength: 4000
        });
    } else {
        alert(message);
    }
}

function showSuccessMessage(message) {
    if (typeof M !== 'undefined') {
        M.toast({
            html: `<div class="success-toast">
                    <i class="material-icons">check_circle</i>
                    <span>${message}</span>
                   </div>`,
            classes: 'green lighten-1',
            displayLength: 3000
        });
    } else {
        alert(message);
    }
}
