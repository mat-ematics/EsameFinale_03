//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/**
 * List of Error Messages for User/Admin Deletion
 */
const errorMessages = {
    "user-select": "No User Selected or Present",
};

/**
 * Intializes User Deletion Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationUserDelete(form) {
    const select = form.querySelector(".select-user");
    const submitButton = form.querySelector(".button-submit");
    let allowEnable = true;

    DOMUtils.initializeError(form, select, errorMessages); //Assign all error messages

    /* Check if there is at least one option */
    if (select.options.length == 0 || !select.value) {
        /* No options found, button disabled */
        DOMUtils.disableButton(submitButton, true);
        DOMUtils.displayError(form, select);
        allowEnable = false;
    }

    select.addEventListener("click", () => {
        DOMUtils.validateInput(form, select);
        if (allowEnable) {
            DOMUtils.updateButtonState(selectElements, submitButton, false);
        }
    });
}