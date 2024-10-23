/* Input di Credenziali */
const credentials = document.querySelectorAll(".input-credential");
/* Bottone di Invio */
const buttonSubmitCredentials = document.querySelectorAll(".button-submit-credentials");
/* Password e Pulsante "Mostra Password" */
const password = document.querySelectorAll(".input-password");
const passwordToggle = document.querySelectorAll(".password-toggle");

/* Disabilitazione iniziale del Pulsante di Invio */
buttonSubmitCredentials.forEach(button => {
    button.setAttribute('disabled', 'disabled');
})

/* Regex di Validazione */
const regexUsername = /^[a-zA-Z_]{6,32}$/; // Username
const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/; // Password

/* Container degli Errori */
const errorContainers = document.querySelectorAll(".errors-container");
errorContainers.forEach(container => {
    container.firstElementChild.textContent = container.id == 'usernameErrors' ? 
                            'Username must contain only letters or underscore ("_") and be between 6 and 32 characters' :
                            'Password must contain at least 1 Lowercase and 1 Uppercase Letter, 1 Digit and 1 Special Character (@$!%*?&) and be between 8 and 32 characters'
                            ;
    container.style.visibility = 'hidden';
});

/* Cambio Stile Validazione di Input  */
credentials.forEach(input => {
    input.classList.remove("invalid");
    input.addEventListener("input", function (e) {
         /* Variabili delle Credenziali */
        const currentValue = e.target.value; // Testo scritto in tempo reale
        let regex = this.id == 'username' ? regexUsername : regexPassword; // Controllo degli Errori
        // Container of the Error Message
        const errContainer = this.id == 'username' ? document.getElementById('usernameErrors') : document.getElementById('passwordErrors');

        /* Controllo di Validità dell'Input */
         if (!regex.test(currentValue)) {
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

/* Funzione di Aggiornamento del Pulsante di Invio */
function updateSubmitButton() {
    /* Ricerca di Invalidità di un Input */
    const errorPresent = Array.from(credentials).some(input => input.className.includes('invalid') || input.value == "");
    /* Controllo Validità */
    if (errorPresent) {
        /* Disabilitazione del Pulsante in Presenza di Errori */
        buttonSubmitCredentials.setAttribute('disabled', 'disabled');
    } else {
        /* Abilitazione del Pulsante in Assenza di Errori */
        buttonSubmitCredentials.removeAttribute('disabled');
    }
}

/* Pulsante "Mostra Password" */
passwordToggle.addEventListener("click", function () {
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