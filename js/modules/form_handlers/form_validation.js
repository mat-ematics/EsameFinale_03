/* Import Error Messages, Regexes and Utils */
import { regexList, errorMessages } from "../validators/signup_validator.js";
import { togglePasswordVisibility, disableButton } from "../_shared/dom_utils.js";

export function initializeFormValidation(form) {
    const inputs = form.querySelectorAll(".input-credential");
    const submitButton = form.querySelector(".button-submit");

    inputs.forEach(input => {
        input.addEventListener("input", () => {
            validateInput(input, form);
            updateSubmitButtonState(inputs, submitButton);
        });
    });

    form.querySelectorAll(".password-toggle").forEach(toggle => {
        const passwordInput = toggle.closest(".password-container").querySelector("input[type=password]");
        toggle.addEventListener("click", () => togglePasswordVisibility(toggle, passwordInput));
    });
}

function validateInput(input, form) {
    const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`);
    const regex = regexList[input.dataset.type];
    const value = input.value;

    if (!regex.test(value)) {
        input.classList.add("invalid");
        errorContainer.textContent = errorMessages[input.dataset.type];
        errorContainer.style.visibility = "visible";
    } else {
        input.classList.remove("invalid");
        errorContainer.style.visibility = "hidden";
    }
}

function updateSubmitButtonState(inputs, button) {
    const hasErrors = Array.from(inputs).some(input => input.classList.contains("invalid") || !input.value);
    disableButton(button, hasErrors);
}