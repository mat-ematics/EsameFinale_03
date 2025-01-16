//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/** 
 * List of Regexes for Category Editing.
 */
const regexList = {
    'category-name': /^[a-zA-Z]{3,32}$/, // Uppercase and Lowercase letters, underscores, between 6 and 32 characters
};
/**
 * List of Error Messages for Category Editing
 */
const errorMessages = {
    "category-select": "No User Selected or Present",
    'category-name': 'Category Name must use only letters and be 3-32 characters long.',
};

/**
 * Intializes Category Editing Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationCategoryEdit(form) {
    const inputs = form.querySelectorAll(".input-category");
    const select = form.querySelector(".select-category");
    const submitButton = form.querySelector(".button-submit");
    let allowEnable = true;

    //Assign all error messages
    DOMUtils.initializeErrorMessages(form, inputs, errorMessages);  
    DOMUtils.initializeError(form, select, errorMessages); //Assign all error messages
    
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
            DOMUtils.validateInput(form, input, {regexList: regexList, allowEmpty: true});
            if (allowEnable) {
                DOMUtils.updateButtonState(inputs, submitButton);
            }
        });
    });
}
