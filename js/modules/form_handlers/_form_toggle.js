/* Imports */
import initializeValidationLogin from "./login_handler.js";
import initializeValidationSignup from "./signup_handler.js";
import initializeValidationUserEdit from "./edit_user_handler.js";

/**
 * Initialize the Validation of a Form based on its type
 * 
 * @param {String} type The Type of Form to Initialize Validation
 * @param {HTMLFormElement} formElement The Form to Intialize
 */
export default function initializeForm(type, formElement) {
    switch (type) {
        case "login":
            initializeValidationLogin(formElement);
            break;
        case "signup":
            initializeValidationSignup(formElement);
            break;
        case "editUser": 
            initializeValidationUserEdit(formElement);
            break;
        default:
            throw new Error(`Unknown form type: ${type}`);
    }
}