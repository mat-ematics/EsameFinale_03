//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/**
 * List of Error Messages for User/Admin Creation
 */
const errorMessages = {
    "category-select": "No User Selected or Present",
};

/**
 * Intializes Category Deletion Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationCategoryDelete(form) {
    const select = form.querySelector(".select-category");
    const submitButton = form.querySelector(".button-submit");
    let allowEnable = true;

    DOMUtils.initializeError(form, select, errorMessages); //Assign all error messages

    /* Check if there is at least one option */
    if (select.options.length == 0) {
        /* No options found, button disabled */
        DOMUtils.disableButton(submitButton, true);
        DOMUtils.displayError(form, select);
        allowEnable = false;
    }

    select.addEventListener("click", () => {
        DOMUtils.validateInput(form, select, regexList);
        if (allowEnable) {
            DOMUtils.updateButtonState(selectElements, submitButton);
        }
    });
}