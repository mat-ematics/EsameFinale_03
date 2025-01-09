//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/** 
 * List of Regexes for User/Admin Creation.
 */
const regexList = {
    'category-name': /^$|^[a-zA-Z]{3,32}$/, // Uppercase and Lowercase letters, underscores, between 6 and 32 characters
};
/**
 * List of Error Messages for User/Admin Creation
 */
const errorMessages = {
    'category-name': 'Category Name must use only letters and be 3-32 characters long.',
};

/**
 * Intializes Category Creation Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationCategoryEdit(form) {
    const inputs = form.querySelectorAll(".input-category");
    const submitButton = form.querySelector(".button-submit");

    DOMUtils.initializeErrorMessages(form, inputs, errorMessages); //Assign all error messages 
    DOMUtils.disableButton(submitButton, true); //Disable initially the submit button

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, regexList);
            DOMUtils.updateButtonState(inputs, submitButton);
        });
    });
}
