//Imports
import DOMUtils from "../../_shared/_dom_utils"; 

/**
 * List of Error Messages for Work Creation
 */
const errorMessages = {
    "work-select": "No Work Selected or Present",
};

/**
 * Intializes Work Deletion Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationWorkDelete(form) {
    /* Variable Declaration */
    const select = form.querySelector(".select-work"); //The work select
    const submitButton = form.querySelector(".button-submit");

    let allowEnable = true; //Flag for Work presence

    /* Assign all error messages */
    DOMUtils.initializeError(form, select, errorMessages); 

    /* Check if there is at least one option */
    if (select.options.length == 0) {
        /* No options found, button disabled */
        DOMUtils.disableButton(submitButton, true);
        DOMUtils.displayError(form, select);
        allowEnable = false; //Update flag
    }

    select.addEventListener("click", () => {
        const flagValid = DOMUtils.validateInput(form, select);
        /* Check Flag */
        if (allowEnable && flagValid) {
            DOMUtils.disableButton(submitButton, false);
        }
    });
}