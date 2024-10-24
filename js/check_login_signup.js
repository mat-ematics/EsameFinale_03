/* Input di Credenziali */
const credentials = document.querySelectorAll(".input-credential");
/* Bottone di Invio */
const buttonSubmitCredentials = document.querySelectorAll(".button-submit-credentials");
/* Password e Pulsante "Mostra Password" */
const passwordList = document.querySelectorAll("input[type=password]");
const passwordToggle = document.querySelectorAll(".password-toggle");
/* Ripeti Password */
const repeatPasswordList = document.querySelectorAll("repeat-password");
/* Container degli Errori */
const errorContainers = document.querySelectorAll(".errors-container");

/* Regex di Validazione */
const regexList = {
    username: /^[a-zA-Z_]{6,32}$/, // Username
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, // Password
}

/* Message Errors */
const errorMessages = {
    usernameErrors: 'Username must contain only letters or underscore ("_") and be between 6 and 32 characters',
    passwordErrors: 'Password must contain at least 1 Lowercase and 1 Uppercase Letter, 1 Digit and 1 Special Character (@$!%*?&) and be between 8 and 32 characters',
    repeatPasswordErrors: "Password isn't the Same",
}

/* Disabilitazione iniziale del Pulsante di Invio */
buttonSubmitCredentials.forEach(button => {
    button.setAttribute('disabled', 'disabled');
})

/* Error Messages to Container Association */
errorContainers.forEach(container => {
    container.firstElementChild.textContent = errorMessages[container.id];
    container.style.visibility = 'hidden';
});

/* Cambio Stile Validazione di Input  */
credentials.forEach(input => {
    input.classList.remove("invalid");
    input.addEventListener("input", function (e) {
         /* Variabili delle Credenziali */
        const currentValue = e.target.value; // Testo scritto in tempo reale
        console.log(currentValue)
        let regex = regexList[this.id] ; // Controllo degli Errori
        // Container of the Error Message
        let errContainer = Array.from(errorContainers).find(container => container.id == this.id + "Errors");

        if (this.id != 'repeatPassword' && !regex.test(currentValue)) {  /* Controllo di Validità dell'Input */
            /* Caso di Invalidità  */
            this.classList.add('invalid'); // Aggiunta Stile di Errore
            this.classList.remove('valid'); // Rimozione Stile di Validità
            // Messaggio di Errore Visibile
            errContainer.style.visibility = 'visible';
        } else {
            /* Caso di Validità */
            this.classList.remove('invalid'); // Rimozione Stile di Errore
            this.classList.add('valid'); // Aggiunta Stile di Validità
            // Messaggio di Errore Nascosto
            errContainer.style.visibility = 'hidden';
        }

        /* Aggiornamento Bottone di Invio */
        updateSubmitButton();
    })
});

repeatPasswordList.forEach(input => {
    input.addEventListener('input', checkPasswordMatch);
})
passwordList.forEach(input => {
    input.addEventListener('input', checkPasswordMatch);
})

function checkPasswordMatch () {
    const parentForm = this.parentElement.parentElement;
    // console.log(parentForm);

    const inputPassword = parentForm.querySelector("input#password");
    console.log(inputPassword.value);

    const inputRepeatPassword = parentForm.querySelector("input#repeatPassword");
    console.log(inputRepeatPassword.value);

    const errorRepeatPassword = parentForm.querySelector('#repeatPasswordErrors');

    /* Controllo Validità Ripeti Password */
    if (inputPassword.value !== inputRepeatPassword.value) {
        /* Caso di Invalidità  */
        inputRepeatPassword.classList.add('invalid'); // Aggiunta Stile di Errore
        inputRepeatPassword.classList.remove('valid'); // Rimozione Stile di Validità
        // Messaggio di Errore Visibile
        errorRepeatPassword.style.visibility = 'visible';
    } else {
        /* Caso di Validità */
        inputRepeatPassword.classList.remove('invalid'); // Rimozione Stile di Errore
        inputRepeatPassword.classList.add('valid'); // Aggiunta Stile di Validità
        // Messaggio di Errore Nascosto
        errorRepeatPassword.style.visibility = 'hidden';
    }

    updateSubmitButton();
}

/* Funzione di Aggiornamento del Pulsante di Invio */
function updateSubmitButton() {
    /* Ricerca di Invalidità di un Input */
    const errorPresent = Array.from(credentials).some(input => input.className.includes('invalid') || input.value == "");
    /* Controllo Validità */
    if (errorPresent) {
        /* Disabilitazione del Pulsante in Presenza di Errori */
        buttonSubmitCredentials.forEach(button => button.setAttribute('disabled', 'disabled'));
    } else {
        /* Abilitazione del Pulsante in Assenza di Errori */
        buttonSubmitCredentials.forEach(button => button.removeAttribute('disabled'));
    }
}

/* Pulsante "Mostra Password" */
passwordToggle.forEach(toggle => {
    const password = toggle.parentElement.parentElement.querySelector("input[type=password]");
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