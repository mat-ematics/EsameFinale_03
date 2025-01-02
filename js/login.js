/* Imports */
import initializeForm from "./modules/form_handlers/_form_toggle";

// Add validation to Login
const form = document.getElementById('formLogin');
initializeForm("login", form);