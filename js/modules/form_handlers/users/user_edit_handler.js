//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

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
    "user-select": "No User Selected or Present",
    username: 'Username must be alphabetic with eventual underscores and 6-32 characters long.',
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
    const select = form.querySelector(".select-user");
    const submitButton = form.querySelector(".button-submit");
    let allowEnable = true;

    //Assign all error messages 
    DOMUtils.initializeErrorMessages(form, inputs, errorMessages);
    DOMUtils.initializeError(form, select, errorMessages);

    /* Check if there is at least one option */
    if (select.options.length == 0) {
        /* No options found, button disabled */
        DOMUtils.displayError(form, select);
        allowEnable = false;
    }
    
    //Disable initially the submit button
    DOMUtils.disableButton(submitButton, true); 

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, regexList);
            DOMUtils.repeatPasswordCheck(form);
            if (allowEnable) {
                DOMUtils.updateButtonState(inputs, submitButton, false);
            }
        });
    });

    form.querySelectorAll(".password-toggle").forEach(toggle => {
        const passwordInput = toggle.closest(".password-container").querySelector("input[type=password]");
        toggle.addEventListener("click", () => DOMUtils.togglePasswordVisibility(toggle, passwordInput));
    });
}