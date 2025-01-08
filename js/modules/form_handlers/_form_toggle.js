/* Imports */
import initializeValidationLogin from "./login_handler.js";
import initializeValidationSignup from "./users/signup_handler.js";
import initializeValidationUserEdit from "./users/edit_user_handler.js";
import initializeValidationCategoryCreate from "./categories/create_category_handler.js";

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
        case "userEdit": 
            initializeValidationUserEdit(formElement);
            break;
        case "categoryCreate":
            initializeValidationCategoryCreate(formElement);
            break;
        default:
            throw new Error(`Unknown form type: ${type}`);
    }
}