/* Login Form Validation Import */
import initializeValidationLogin from "./login_handler.js";
/* User Forms Validation Imports */
import initializeValidationUserCreate from "./users/user_create_handler.js";
import initializeValidationUserEdit from "./users/user_edit_handler.js";
/* Category Forms Validation Imports */
import initializeValidationCategoryCreate from "./categories/category_create_handler.js";
import initializeValidationCategoryEdit from "./categories/category_edit_handler.js";

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
        case "userCreate":
            initializeValidationUserCreate(formElement);
            break;
        case "userEdit": 
            initializeValidationUserEdit(formElement);
            break;
        case "categoryCreate":
            initializeValidationCategoryCreate(formElement);
            break;
        case "categoryEdit":
            initializeValidationCategoryEdit(formElement);
            break;
        default:
            throw new Error(`Unknown form type: ${type}`);
    }
}