const formsId = [
    'formLogin',
    'formCreateUser',
]

formsId.forEach(id => {
    let form = document.getElementById(id);
    if (form != null) {
        initializeCheckLoginSignup(form);
    }
});

function initializeCheckLoginSignup(form) {
    /* Input di Credenziali */
    const credentials = form.querySelectorAll(".input-credential");
    /* Bottone di Invio */
    const buttonSubmitCredentials = form.querySelectorAll(".button-submit");
    /* Input of type Password */
    const passwordInputsList = form.querySelectorAll("input[type=password]");
    /* Show Password Button */
    const passwordToggle = form.querySelectorAll(".password-toggle");
    /* Container degli Errori */
    const errorContainers = form.querySelectorAll(".errors-container");
    
    /* Regex di Validazione */
    const regexList = {
        username: /^[a-zA-Z_]{6,32}$/, // Username
        password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, // Password
    };
    
    /* Message Errors */
    const errorMessages = {
        "username-errors": 'Username must contain only letters or underscore ("_") and be between 6 and 32 characters',
        "password-errors": 'Password must contain at least 1 Lowercase and 1 Uppercase Letter, 1 Digit and 1 Special Character (@$!%*?&) and be between 8 and 32 characters',
        "repeat-password-errors": "Passwords do not match",
    };
    
    /* Disabilitazione iniziale del Pulsante di Invio */
    buttonSubmitCredentials.forEach(button => {
        button.setAttribute('disabled', 'disabled');
    });
    
    /* Error Messages to Container Association */
    errorContainers.forEach(container => {
        const errorKey = container.classList[1]; // Assuming the class follows `errors-container error-key`
        container.firstElementChild.textContent = errorMessages[errorKey] || '';
        container.style.visibility = 'hidden';
    });
    
    /* Cambio Stile Validazione di Input */
    credentials.forEach(input => {
        input.classList.remove("invalid");
        input.addEventListener("input", function (e) {
            const currentValue = e.target.value;
            const regex = regexList[this.dataset.type]; // Use data attributes to determine type
            const errContainer = form.querySelector(`.errors-container.${this.dataset.type}-errors`);
    
            if (this.dataset.type !== "repeat-password" && !regex.test(currentValue)) {
                this.classList.add("invalid");
                this.classList.remove("valid");
                errContainer.style.visibility = "visible";
            } else {
                this.classList.remove("invalid");
                this.classList.add("valid");
                errContainer.style.visibility = "hidden";
            }
    
            updateSubmitButton(form);
        });
    });
    
    /* Ripeti Password Validation */
    passwordInputsList.forEach(input => {
        input.addEventListener("input", function () {
            checkPasswordMatch(form);
        });
    });
    
    function checkPasswordMatch(form) {
        const inputPassword = form.querySelector("input[data-type='password']");
        const inputRepeatPassword = form.querySelector("input[data-type='repeat-password']");
        const errorRepeatPassword = form.querySelector(".errors-container.repeat-password-errors");
    
        if (inputPassword.value !== inputRepeatPassword.value) {
            inputRepeatPassword.classList.add("invalid");
            inputRepeatPassword.classList.remove("valid");
            errorRepeatPassword.style.visibility = "visible";
        } else {
            inputRepeatPassword.classList.remove("invalid");
            inputRepeatPassword.classList.add("valid");
            errorRepeatPassword.style.visibility = "hidden";
        }
    
        updateSubmitButton(form);
    }
    
    /* Funzione di Aggiornamento del Pulsante di Invio */
    function updateSubmitButton(form) {
        const errorPresent = Array.from(form.querySelectorAll(".input-credential")).some(input => input.classList.contains("invalid") || input.value === "");
        const submitButton = form.querySelector(".button-submit");
    
        if (errorPresent) {
            submitButton.setAttribute("disabled", "disabled");
        } else {
            submitButton.removeAttribute("disabled");
        }
    }
    
    /* Pulsante "Mostra Password" */
    passwordToggle.forEach(toggle => {
        const password = toggle.closest(".password-container").querySelector("input[type='password']");
        toggle.addEventListener("click", function () {
            if (this.classList.contains("show")) {
                password.type = "text";
                this.classList.remove("show");
                this.classList.add("hide");
            } else {
                password.type = "password";
                this.classList.remove("hide");
                this.classList.add("show");
            }
        });
    });
}