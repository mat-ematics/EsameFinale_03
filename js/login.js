import { initializeCheckLoginSignup } from "./modules/check_login_signup";

// Add validation to Login
const form = document.getElementById('formLogin');
initializeCheckLoginSignup(form);