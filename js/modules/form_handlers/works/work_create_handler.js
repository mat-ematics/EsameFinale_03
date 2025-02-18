//Imports
import DOMUtils from "../../_shared/_dom_utils.js"; 

/** 
 * List of Regexes for Work Creation.
 */
const regexList = {
    "work-name": /^[a-zA-Z\s]{3,50}$/,
    "work-description": /^[a-zA-Z0-9.,!'"-\s]{10,500}$/,
};
/**
 * List of Error Messages for Work Creation
 */
const errorMessages = {
    "work-category": "No Category Selected or Present",
    "work-name": "Work Name must be between 3 and 50 alphabetic characters",
    "work-date": "Work Date must be valid and non-empty",
    "work-image": "Insert a Valid Image Type (PNG/JPEG/GIF)",
    "work-languages": "Insert at least one language",
    "work-description": "Work Description must be between 10 and 500 alphabetic and special (.,!-'\") characters",
};

/**
 * Intializes Work Creation Validation on a given Form
 * 
 * @param {HTMLFormElement} form The Form to initialize validation
 */
export default function initializeValidationWorkCreate(form) {
    /* Variable Declaration */
    const inputs = form.querySelectorAll(".input-work"); //All inputs
    const select = form.querySelector(".select-category"); //The category select
    const selectTagsContainer = form.querySelector('.global-multitag-dropdown-container').querySelector(".tags-container");

    const submitButton = form.querySelector(".button-submit");
    let allowEnable = true; //Flag for Category presence

    /* Assign all error messages */
    DOMUtils.initializeErrorMessages(form, inputs, errorMessages);
    DOMUtils.initializeError(form, select, errorMessages); 

    DOMUtils.disableButton(submitButton, true); //Disable initially the submit button

    /* Check if there is at least one option */
    if (select.options.length == 0) {
        /* No options found, button disabled */
        DOMUtils.disableButton(submitButton, true);
        DOMUtils.displayError(form, select);
        allowEnable = false; //Update flag
    }

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            DOMUtils.validateInput(form, input, {regexList: regexList});
            /* Check Flag */
            if (allowEnable) {
                const pushInputs = [...Array.from(inputs), select];
                DOMUtils.updateButtonState(pushInputs, submitButton);
            }
        });
    });

    const observer = new MutationObserver(() => {
        if (allowEnable) {
            const pushInputs = [...Array.from(inputs), select];
            DOMUtils.updateButtonState(pushInputs, submitButton);
        }
    });

    observer.observe(selectTagsContainer, {childList: true});
}