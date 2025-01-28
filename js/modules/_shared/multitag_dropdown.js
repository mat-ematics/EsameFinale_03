/* Imports */
import DOMUtils from "./_dom_utils";

/**
 * Function that intializes and manages a MultiTag Dropdown Behaviour
 * 
 * @param {Element} container The Element containing the dropdown
 * @param {Object} options An object containing the options for the dropdown
 * @param {boolean} options.createHiddenInput If true (default), creates hidden inputs for each tag
 * @param {string} options.hiddenInputName The Name of the Hidden Inputs (collected as array). Works only if createHiddenInput is true
 * @param {boolean} options.allowEmpty If `true`, allows for no tags (defaults to false)
 * @returns {boolean} True on initialization success, otherwise false (necessary elements not found)
 */
export default function manageMultitagDropdown(container, options = {createHiddenInput: true, hiddenInputName: 'tag', allowEmpty: false}) {

    const passedOptions = {
        ...{
            createHiddenInput: true,
            hiddenInputName: 'tag',
            allowEmpty: false
        },

        ...options,
    }

    /* Define all Inputs */
    const dropdownContainer = container.querySelector('.global-multitag-dropdown-container')
    const input = container.querySelector('.tags-input');
    const dropdownMenu = container.querySelector('.dropdown-menu');
    const tagsContainer = container.querySelector('.tags-container');
    const form = container.closest('form'); //If the container is the form, container will be equal to form
    const errorType = input.dataset.type || 'default';
    const errorContainer = form.querySelector(`.errors-container.${errorType}-errors`) || null;

    if (!tagsContainer || !input || !dropdownMenu) return false; // Ensure all required elements are present

    /* Form Existance Control */
    if (!form) {
        console.warn('No form found for the dropdown container. Some features may not work.'); //Warn that no form is found
    }

    /* Define Selected Tags Set (unique value list) */
    const selectedTags = new Set();
    
    // Function to check tags presence and adds validity
    function checkTags() {
        const tagsPresent = tagsContainer.children.length !== 0;
    
        if (tagsPresent || passedOptions.allowEmpty) {
            dropdownContainer.classList.add('valid');
            dropdownContainer.classList.remove('invalid');
    
            if (errorContainer) {
                DOMUtils.toggleErrorMessage(errorContainer, false);
            }
        } else {
            dropdownContainer.classList.remove('valid');
            dropdownContainer.classList.add('invalid');
    
            if (errorContainer) {
                DOMUtils.toggleErrorMessage(errorContainer, true);
            }
        }
    }

    // Function that Shows dropdown when input is focused or typed
    function filterDropdown() {
        /* Input Value and Dropdown Items */
        const filter = input.value.toLowerCase();
        const items = dropdownMenu.querySelectorAll('li');

        /* Show Dropdown */
        dropdownMenu.style.display = 'block';
        
        let flagVisibleItems = false; //Flag to check if there are visible items 

        /* Filtering Behaviour */
        items.forEach(item => {
            // Get tag value to lowercase
            const text = item.textContent.toLowerCase();
            //Checks for Correspondance
            if (text.includes(filter)) {
                item.style.display = '';
                flagVisibleItems = true;
            } else {
                item.style.display = 'none';
            }
        });

        //Hide Dropdown Menu if there are no visible items (no matches)
        dropdownMenu.style.display = flagVisibleItems ? 'block' : 'none'; 
    }
    
    // Add tag on selecting from dropdown
    function selectTag(event) {
        /* Works if the target is an item of the dropdown */
        if (event.target.tagName === 'LI') {
            /* Get the Item Value */
            const value = event.target.dataset.value;

            /* Creates a Tag for the Item */
            if (!selectedTags.has(value)) {
                addTag(value);
                selectedTags.add(value);
            }
            /* Reset the Input */
            input.value = ''; //Set no value
            dropdownMenu.style.display = 'none'; //Hide the dropdown
        }
    }

    // Add tag
    function addTag(value) {
        /* Create the Tag element */
        const tag = document.createElement('div');
        tag.className = 'tag';
        tag.textContent = value;

        /* Remove Button Creation */
        const removeBtn = document.createElement('span');
        removeBtn.className = 'remove-tag';
        removeBtn.textContent = 'x';
        // Remove button Behaviour
        removeBtn.addEventListener('click', () => {
            tagsContainer.removeChild(tag);
            selectedTags.delete(value);

            // Optionally remove the hidden input
            if (passedOptions.createHiddenInput) {
                const hiddenInput = form.querySelector(`input[name="${passedOptions.hiddenInputName}[]"][value="${value}"]`);
                if (hiddenInput) {
                    form.removeChild(hiddenInput);
                }
                //Check tags
                checkTags();
            }
            
        });

        //Appends the Tag to the container
        tag.appendChild(removeBtn);
        tagsContainer.appendChild(tag);
        
        //Optionally removes the (optional) hidden input
        if (passedOptions.createHiddenInput) {
            // Create hidden input for the tag
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `${passedOptions.hiddenInputName}[]`; // Name for form submission as an array
            hiddenInput.value = value;
            form.appendChild(hiddenInput);
            checkTags();
        }

    }
    
    /* Event Listeners */

    // Hide dropdown on outside click
    container.addEventListener('click', (event) => {
        if (!event.target.closest('.global-multitag-dropdown-container')) {
            dropdownMenu.style.display = 'none';
        }
    });
    // Prevent dropdown from closing on input click
    input.addEventListener('focus', (event) => {
        event.stopPropagation();
        dropdownMenu.style.display = 'block';
    });
    // Add filtering to the dropdown Menu
    input.addEventListener('input', filterDropdown);
    // Add Tag Selection behaviour to the dropdown
    dropdownMenu.addEventListener('click', selectTag);

    /* Return true for success */
    return true; 
};