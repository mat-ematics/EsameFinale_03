/* Input di Credenziali */
const credentials = document.querySelectorAll(".login-credential");
const buttonLogIn = document.getElementById("buttonLogIn");
buttonLogIn.setAttribute('disabled', 'disabled');

credentials.forEach(input => {
    input.addEventListener("input", function () {
         /* Variabili delle Credenziali */
        const message = this.value; // Testo scritto in tempo reale
        const minLength = parseInt(this.getAttribute('minlength')); // Lunghezza minima del messaggio
        const maxLength = parseInt(this.getAttribute('maxlength')); // Lunghezza massima del messaggio
        
        /* Invalidità del Testo */
        if (message.length < minLength || message.length > maxLength) {
            this.setCustomValidity(`Message must be between ${minLength} and ${maxLength} characters.`);
            this.classList.add('invalid');
            this.classList.remove('valid');

        /* Validità del testo */
        } else {
            this.setCustomValidity('');
            this.classList.remove('invalid');
            this.classList.add('valid');
        }

        updateSubmitButton();
    })
});

function updateSubmitButton() {
    const errorPresent = Array.from(credentials).some(input => input.className.includes('invalid') || input.value == "");
    if (errorPresent) {
        buttonLogIn.setAttribute('disabled', 'disabled');
    } else {
        buttonLogIn.removeAttribute('disabled');
    }
}
