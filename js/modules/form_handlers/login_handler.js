//Imports
import DOMUtils from "../_shared/_dom_utils.js";

/** 
 * List of Regexes for Login.
 */
export const regexList = {
    username: /^[a-zA-Z_]{6,32}$/, // Uppercase and Lowercase letters, underscores, between 6 and 32 characters
    // Default Password Validation, at least 8 characters
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, 
};
/** 
 * List of Error Messages for Login.
 */
export const errorMessages = {
    username: 'Username must be 6-32 characters long and can only contain letters and underscores.',
    password: 'Password must be at least 8 characters long, and include an uppercase letter, a lowercase letter, a number, and a special character ($!%*?&).',
};

/**
 * Intializes Login-like Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationLogin(form) {
    const inputs = form.querySelectorAll(".input-credential");
    const submitButton = form.querySelector(".button-submit");

    DOMUtils.initializeErrorMessages(form, inputs, errorMessages); //Assign all error messages 
    DOMUtils.disableButton(submitButton, true); //Disable initially the submit button

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, {'regexList': regexList});
            DOMUtils.updateButtonState(inputs, submitButton);
        });
    });

    form.querySelectorAll(".password-toggle").forEach(toggle => {
        const passwordInput = toggle.closest(".password-container").querySelector("input[type=password]");
        toggle.addEventListener("click", () => DOMUtils.togglePasswordVisibility(toggle, passwordInput));
    });
}