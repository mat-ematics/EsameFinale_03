//Imports
import DOMUtils from "../_shared/_dom_utils"; 

/** 
 * List of Regexes for User/Admin Editing
 */
const regexList = {
    username: /^$|^[a-zA-Z_]{6,32}$/, // Uppercase and Lowercase letters, underscores, between 6 and 32 characters
    // Default Password Validation, at least 8 characters. Used indirectly for Repeat Password as well
    password: /^$|^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, 
};
/**
 * List of Error Messages for User/Admin Editing
 */
const errorMessages = {
    username: 'Username must be alphanumeric or underscore and 6-32 characters long.',
    password: 'Password must contain at least 1 lowercase, 1 uppercase, 1 digit, and 1 special character (@$!%*?&), and be 8-32 chars long.',
    "repeat-password": "Passwords do not match.",
};

/**
 * Intializes User Editing Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationUserEdit(form) {
    const inputs = form.querySelectorAll(".input-credential");
    const submitButton = form.querySelector(".button-submit");

    DOMUtils.initializeErrorMessages(form, errorMessages); //Assign all error messages 
    DOMUtils.disableButton(submitButton, true); //Disable initially the submit button

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, regexList);
            DOMUtils.repeatPasswordCheck(form);
            DOMUtils.updateButtonState(inputs, submitButton, false);
        });
    });

    form.querySelectorAll(".password-toggle").forEach(toggle => {
        const passwordInput = toggle.closest(".password-container").querySelector("input[type=password]");
        toggle.addEventListener("click", () => DOMUtils.togglePasswordVisibility(toggle, passwordInput));
    });
}