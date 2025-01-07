
/**
 * Utility Methods for DOM manipulation
 */

export default class DOMUtils {
    
    /** 
     * Toggle Password Visibility through button
     * 
     * @param {Element} toggle The element which clicked toggles the password visibility
     * @param {Element} passwordInput The input element of the password  
     */
    static togglePasswordVisibility (toggle, passwordInput) {
        if (toggle.classList.contains("show")) {
            /* Show Password */
            passwordInput.type = "text";
            toggle.classList.replace("show", "hide");
        } else {
            /* Hide Password */
            passwordInput.type = "password";
            toggle.classList.replace("hide", "show");
        }
    };
    
    /** 
     * Disable and Enable a Button
     * 
     * @param {Element} button The button element to toggle
     * @param {boolean} disable Disables the button when true, enables it when false
     */
    static disableButton (button, disable) {
        if (disable) button.setAttribute("disabled", "disabled"); //Disable
        else button.removeAttribute("disabled"); //Enable
    };

    /**
     * Validates a given Input element with a Regular Expression.
     * Works if regexList and errorContainer both exist.
     * 
     * @param {HTMLFormElement} form The Form containing the Input
     * @param {HTMLInputElement} input The Input element to validate
     * @param {Object} [regexList] The Regular Expression List (input type - regex pairs) of the Inputs
     */
    static validateInput (form, input, regexList) {
        /* Error Container and Regex Null Checking */
        const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`); //Error Container 
        const regex = regexList[input.dataset.type] || /.*/; //Regex, any input if not present
        const value = input.value; // The value of the Input
    
        /* Input Validity Check */
        if (!regex.test(value)) {
            /* Invalid Input */
            input.classList.add("invalid");

            /* Update Error Message Container Visibility */
            errorContainer.style.visibility = "visible";
        } else {
            /* Valid Input */
            input.classList.remove("invalid");

            /* Remove Error Message Container Visibility */
            errorContainer.style.visibility = "hidden";
        }
    }

    /**
     * Updates a Button State based on invalidity of Inputs
     * 
     * @param {NodeList} inputs List of Inputs to Verifiy
     * @param {HTMLButtonElement} button The Button element to update
     * @param {boolean} [checkSomeEmpty] If true (default), checks if ANY ONE INPUT IS EMPTY, otherwise CHECKS IF ALL INPUTS ARE EMPTY
     */
    static updateButtonState(inputs, button, checkSomeEmpty = true) {
        let hasErrors = false; // Variable that checks whether there are errors or not

        /* Check for some empty */
        if (checkSomeEmpty) {
            /* hasErrors is true when at least one input is invalid or empty */
            hasErrors = Array.from(inputs).some(input => input.classList.contains("invalid") || !input.value);
        } 
        else {
            /* hasErrors is true when at least one input is invalid or all inputs are empty */
            const hasInvalidInputs = Array.from(inputs).some(input => input.classList.contains("invalid"));
            const allInputsEmpty = Array.from(inputs).every(input => !input.value);
            hasErrors = hasInvalidInputs || allInputsEmpty;
        } 

        DOMUtils.disableButton(button, hasErrors);
    }

    /**
     * Initializes all Error Messages, assigning the texts to the correct containers
     * 
     * @param {HTMLFormElement} form The Form containing the Inputs
     * @param {Object} [errorMessages] The Error Messages List (input type - error pairs) of the Inputs
     */
    static initializeErrorMessages(form, errorMessages) {
        const inputs = form.querySelectorAll(".input-credential");
        inputs.forEach(input => {
            /* Error Message Assigning */
            const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`); //Nearest Error Container 
            const errorMessage = errorMessages[input.dataset.type] || "Invalid input."; //Generic error Message (if not present)
            errorContainer.textContent = errorMessage; //Assign the error message to the container

            /* Hide the Error Message */
            errorContainer.style.visibility = 'hidden';
        });
    }

    /**
     * Checks for a match between the Password Input and its corresponding Repeat Password
     * 
     * @param {HTMLFormElement} form The Form containing the Inputs
     */
    static repeatPasswordCheck(form) {
        /* Passwords' value retrieval */
        //The input-credential class search filters any non-input elements with the same data-type
        const password = form.querySelector('.input-credential[data-type="password"]');
        const repeatPassword = form.querySelector('.input-credential[data-type="repeat-password"]');
        //The container of Repeat-Password Error Message
        const errorContainer = form.querySelector(`.errors-container.repeat-password-errors`); 

        /* Check for Match between passwords */
        if (password.value === repeatPassword.value) {
            /* Mark as Valid */
            repeatPassword.classList.remove('invalid');

            /* Hide the Error Message */
            errorContainer.style.visibility = 'hidden';
        } else {
            /* Mark as Invalid */
            repeatPassword.classList.add('invalid');

            /* Show the Error Message */
            errorContainer.style.visibility = 'visible';
        }
    }

    /**
        * Switches the Area/Slide/View shown based on a Item List/NavBar of the same length and in the same order
        * 
        * @param {NodeListOf<Element>} slides The Node List of the possible Views/Slides to change to current
        * @param {NodeListOf<Element>} itemList The Node List of the Items inside the NavBar of the slides
        * @param {Element} newItem The new Element to Show
        * @param {Element=} currentItem The currently Shown Element
        * 
        * @returns {Element} The New Current Element (New Item)
        */
    static changeArea(slides, itemList, newItem, currentItem) {
        
        const zipped = Array.from(itemList).map((item, area) => [item.id, slides[area]]);
        const areaMap = Object.fromEntries(zipped);
        
        if (!currentItem) {
            currentItem = itemList[0];
        }

        currentItem.classList.remove("current");
        areaMap[currentItem.id].classList.remove("current");

        newItem.classList.add("current");
        areaMap[newItem.id].classList.add("current");

        return newItem;
    }
}

