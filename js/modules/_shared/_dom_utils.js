
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
     * Toggles the visibility of the given Error Message(s) container
     * @param {Element} errorContainer - The container of the errors
     * @param {Boolean} [showError] - Optional flag to explicitly show or hide the error
     * @returns {String|false} The new State of the Error, false if failed to toggle
     */
    static toggleErrorMessage(errorContainer, showError = null) {
        if (!errorContainer) {
            console.error("Error container not provided");
            return false;
        }

        // Explicit control if `showError` is passed
        if (showError !== null) {
            errorContainer.style.visibility = showError ? 'visible' : 'hidden';
        } 
        // Automatic toggle if no `showError` value provided
        else if (errorContainer.style.visibility === 'visible') {
            errorContainer.style.visibility = 'hidden';
        } else {
            errorContainer.style.visibility = 'visible';
        }

        // Return the new State of the Error Container
        return errorContainer.style.visibility;
    }

    /**
     * Validates a text input using a given regex.
     * @param {string} text - The text to validate.
     * @param {RegExp} regex - The regular expression to validate against.
     * @param {boolean} [allowEmpty=false] [optional] Whether empty values are allowed.
     * @returns {boolean} `true` if the text passes validation or is allowed to be empty, otherwise `false`.
     */
    static validateText(text, regex, allowEmpty = false) {
        const value = text.trim();
        if (!value) return allowEmpty; // If empty, follow allowEmpty
        return regex.test(value);
    }

    /**
     * Validates a date input against optional max and min dates.
     * @param {string} date - The date to validate in ISO format (YYYY-MM-DD).
     * @param {Date|null} [maxDate=null] - The maximum allowed date.
     * @param {Date|null} [minDate=null] - The minimum allowed date.
     * @param {boolean} [allowEmpty=false] [optional] Whether empty values are allowed.
     * @returns {boolean} `true` if the date is valid or is allowed to be empty, otherwise `false`.
     */
    static validateDate(date, maxDate = null, minDate = null, allowEmpty = false) {
        if (!date.trim()) return allowEmpty; // If empty, follow allowEmpty

        const parsedDate = new Date(date);
        if (isNaN(parsedDate.getTime())) return false;

        if (maxDate && parsedDate > maxDate) return false;
        if (minDate && parsedDate < minDate) return false;

        return true;
    }

    /**
     * Validates a select input to ensure it is not empty.
     * @param {string|Array} value - The value of the select input (string for single select, array for multi-select).
     * @returns {boolean} `true` if the select input is not empty, otherwise `false`.
     */
    static validateSelect(value) {
        if (Array.isArray(value)) {
            return value.length > 0;
        }
        return !!value; // Non-empty string
    }

    /**
     * Validates an image input by checking the file extension.
     * @param {string} imagePath - The path or filename of the image to validate.
     * @param {boolean} [allowEmpty=false] [optional] Whether empty values are allowed.
     * @returns {boolean} `true` if the file is valid or is allowed to be empty, otherwise `false`.
     */
    static validateImage(imagePath, allowEmpty = false) {
        if (!imagePath.trim()) return allowEmpty; // If empty, follow allowEmpty
        return /\.(jpg|jpeg|png|gif)$/i.test(imagePath);
    }

    /**
     * Validates the multi-tag dropdown to ensure at least one tag is selected.
     * @param {Element} container - The container of the multi-tag dropdown.
     * @param {boolean} [allowEmpty=false] [optional] - Whether empty selections are allowed.
     * @returns {boolean} `true` if the validation passes, otherwise `false`.
     */
    static validateMultitagSelect(container, allowEmpty = false) {
        const tagsContainer = container.querySelector('.tags-container');
        if (!tagsContainer) {
            console.error("Tags container not found in the specified container.");
            return false;
        }

        // Check for tags
        const hasTags = tagsContainer.children.length > 0;

        // Return validation result based on allowEmpty
        return allowEmpty || hasTags;
    }

    /**
     * Validates a given Input element.
     * Works if regexList and errorContainer both exist.
     * 
     * @param {HTMLFormElement} form The Form containing the Input
     * @param {HTMLInputElement|HTMLSelectElement} input The Input (or Select) element to validate
     * @param {RegExp} [options.regex=/./] - Default regex for validation.
     * @param {Object} [options.regexList={}] - A map of regex patterns for different input types.
     * @param {Date|null} [options.maxDate=null] - The maximum allowed date (null if no limit).
     * @param {Date|null} [options.minDate=null] - The minimum allowed date (null if no limit).
     * @param {boolean} [options.allowEmpty=false] - Whether empty inputs are considered valid.
     * @returns {boolean} - Returns `true` if the input passes validation, otherwise `false`.
     */
    static validateInput(
        form, 
        input, 
        options = { 
            regex: /./, 
            regexList: {}, 
            maxDate: null, 
            minDate: null, 
            allowEmpty: false 
        }
    ) {
        /* Options Merging */
        const passedOptions = {
            ...{ 
                regex: /./, 
                regexList: {}, 
                maxDate: null, 
                minDate: null, 
                allowEmpty: false 
            },

            ...options
        }

        /* Flags */
        let flagValid = true; //Validity flag

        /* Input related variables */
        const type = input.dataset.type || 'input-type'; //Data-type of the input
        const inputType = input.dataset.inputType || 'text'; //Input type (text/select/multitag-select/date/image)

        
        const value = input.value; // The value of the Input
        
        const parent = input.parentElement;
        const errorContainer = form.querySelector(`.errors-container.${type}-errors`); //Error Container 
        let regex = passedOptions.regexList[type] || passedOptions.regex; //Regex, any input if not present
        if (typeof passedOptions.regexList[type] == 'undefined') {
            regex = passedOptions.regex;
            console.log(passedOptions);
        }

        console.log(regex);
        
        /* Control for multitag-select (already checked in its script) */
        if (inputType == 'multitag-select') {
            const container = input.closest(".global-multitag-dropdown-container");
            flagValid = this.validateMultitagSelect(container, passedOptions.allowEmpty);
        
            return flagValid;
        }
    
        /* Check the Correct type */
        switch (inputType) {
            case "text":
                flagValid = this.validateText(value, regex, passedOptions.allowEmpty); 
                break;
            case "date":
                flagValid = this.validateDate(value, passedOptions.maxDate, passedOptions.minDate, passedOptions.allowEmpty); // Date validation
                break;
            case "image":
                flagValid = this.validateImage(value, passedOptions.allowEmpty); // Image validation
                break;
            case "select":
                flagValid = this.validateSelect(value, passedOptions.allowEmpty); // Min length
                break;
            //Already integrated inside default integration
            case "multitag-select":
                // flagValid = this.validateMultitagSelect(container, passedOptions.allowEmpty); // Must select at least one tag
                break;
            default:
                break;
        }

        /* Update Validty Input */
        if (flagValid) {
            input.classList.remove("invalid");
            input.classList.add("valid");
        } else {
            input.classList.add("invalid");
            input.classList.remove("valid");
        }

        /* Remove Error Message Container Visibility */
        flagValid ? this.toggleErrorMessage(errorContainer, false) : this.toggleErrorMessage(errorContainer, true);

        return flagValid; //Return Value
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
            hasErrors = Array.from(inputs).some(input => {
                if (input.dataset.inputType === 'multitag-select') {
                    return input.parentElement.classList.contains("invalid") && !input.parentElement.classList.contains("valid");
                } else {
                    return input.classList.contains("invalid") || !input.value;
                }
            });
        } 
        else {
            /* hasErrors is true when at least one input is invalid or all inputs are empty */
            const hasInvalidInputs = Array.from(inputs).some(input => input.classList.contains("invalid"));
            /* Check if all inputs are empty */
            const allInputsEmpty = Array.from(inputs).every(input => {

                let result = false; //Flag to check for emptyness

                if (input.dataset.inputType === 'multitag-select') {
                    // Check if tags-container is empty for multitag-select
                    const tagsContainer = input.parentElement.querySelector('.tags-container');
                    /* Returns true (empty) if there are no tags */
                    result = tagsContainer && tagsContainer.children.length === 0;
                } else if (input.dataset.inputType === 'select') {
                    const value = input.value;
                    result = value === 'null';
                } else {
                    result = !input.value;
                }
                return result;
            });

            // console.log(allInputsEmpty)

            hasErrors = hasInvalidInputs || allInputsEmpty;
        } 

        // console.log(hasErrors);
        DOMUtils.disableButton(button, hasErrors);
    }

    /**
     * Initializes all Error Messages, assigning the texts to the correct containers
     * 
     * @param {HTMLFormElement} form The Form containing the Inputs
     * @param {NodeListOf<Element>} inputs The Inputs to match
     * @param {Object} [errorMessages] The Error Messages List (input type - error pairs) of the Inputs
     */
    static initializeErrorMessages(form, inputs, errorMessages) {
        Array.from(inputs).forEach(input => {
            /* Error Message Assigning */
            const type = input.dataset.type || 'input';
            const errorContainer = form.querySelector(`.errors-container.${type}-errors`); //Nearest Error Container 
            const errorMessage = errorMessages[type] || "Invalid input."; //Generic error Message (if not present)
            errorContainer.textContent = errorMessage; //Assign the error message to the container

            /* Hide the Error Message */
            this.toggleErrorMessage(errorContainer, false);
        });
    }

    /**
     * Initializes the Error Message of a given Element in a given Form
     * 
     * @param {HTMLFormElement} form The Form containing the Input
     * @param {Element} input The Input to initialize
     * @param {Object} [errorMessages] The Error Messages List (input type - error pairs) of the Inputs
     */
    static initializeError(form, input, errorMessages) {
        /* Error Message Assigning */
        const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`); //Nearest Error Container 
        const errorMessage = errorMessages[input.dataset.type] || "Invalid input."; //Generic error Message (if not present)
        errorContainer.textContent = errorMessage; //Assign the error message to the container

        /* Hide the Error Message */
        errorContainer.style.visibility = 'hidden';
    }

    /**
     * Displays the Error Message of a given Input in a given form
     * 
     * @param {HTMLFormElement} form The Form containing the Input
     * @param {Element} input The Input of which the error is displayed
     */
    static displayError(form, input) {
        /* Error Message Assigning */
        const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`); //Nearest Error Container
        /* Hide the Error Message */
        errorContainer.style.visibility = 'visible';
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

        console.log('im beign activated', password.value === repeatPassword.value);
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

