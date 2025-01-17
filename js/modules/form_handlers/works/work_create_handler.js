//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/** 
 * List of Regexes for Work Creation.
 */
const regexList = {
    "work-name": /^[a-zA-Z\s]{3,50}$/,
    "work-description": /^[a-zA-Z0-9.,'"\s-]{10,500}$/,
};
/**
 * List of Error Messages for Work Creation
 */
const errorMessages = {
    "work-name": "Invalid Work Name",
    "work-date": "Insert Work Date",
    "work-image": "Insert an Image",
    "work-languages": "Insert at least one language",
    "work-description": "Invalid Work Description",
};

/**
 * Intializes Work Creation Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationWorkCreate(form) {
    /* Variable Declaration */
    const inputs = form.querySelectorAll(".input-work"); //All inputs
    const submitButton = form.querySelector(".button-submit");

    DOMUtils.initializeErrorMessages(form, inputs, errorMessages); //Assign all error messages 
    DOMUtils.disableButton(submitButton, true); //Disable initially the submit button

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, {regexList: regexList});
            DOMUtils.updateButtonState(inputs, submitButton);
        });
    });

    form.querySelectorAll(".password-toggle").forEach(toggle => {
        const passwordInput = toggle.closest(".password-container").querySelector("input[type=password]");
        toggle.addEventListener("click", () => DOMUtils.togglePasswordVisibility(toggle, passwordInput));
    });
}
