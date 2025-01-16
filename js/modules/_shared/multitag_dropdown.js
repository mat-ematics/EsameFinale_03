/* Imports */
import DOMUtils from "./_dom_utils";

/**
 * Function that intializes and manages a MultiTag Dropdown Behaviour
 * 
 * @param {Element} container The Element containing the dropdown
 * @param {Object} options An object containing the options for the dropdown
 * @param {boolean} options.createHiddenInput If true (default), creates
 * @returns {boolean} True on initialization success, otherwise false (necessary elements not found)
 */
export default function manageMultitagDropdown(container, options = {createHiddenInput: true}) {
    /* Define all Inputs */
    const dropdownContainer = container.querySelector('.global-multitag-dropdown-container')
    const input = container.querySelector('.tags-input');
    const dropdownMenu = container.querySelector('.dropdown-menu');
    const tagsContainer = container.querySelector('.tags-container');
    const form = container.closest('form'); //If the container is the form, container will be equal to form
    const errorContainer = form.querySelector(`.errors-container.${input.dataset.type}-errors`) || null;

    if (!tagsContainer || !input || !dropdownMenu) return false; // Ensure all required elements are present

    /* Define Selected Tags Set (unique value list) */
    const selectedTags = new Set();
    
    // Function to check tags presence and adds validity
    function checkTags() {
    	const tagsPresent = tagsContainer.children.length != 0;

        if (tagsPresent) {
            dropdownContainer.classList.add('valid');
            dropdownContainer.classList.remove("invalid");
            
            if (errorContainer) {
                DOMUtils.toggleErrorMessage(errorContainer, false);
            }
        } else {
            dropdownContainer.classList.remove('valid');
            dropdownContainer.classList.add("invalid");

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

        /* Filtering Behaviour */
        items.forEach(item => {
            // Get tag value to lowercase
            const text = item.textContent.toLowerCase();
            //Checks for Correspondance
            if (text.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
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
            
            // Remove corresponding hidden input
            const hiddenInput = form.querySelector(`input[name="languages[]"][value="${value}"]`);
            if (hiddenInput) {
                form.removeChild(hiddenInput);
            }

            // Optionally remove the hidden input
            if (options.createHiddenInput) {
                const hiddenInput = form.querySelector(`input[name="languages[]"][value="${value}"]`);
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
        if (options.createHiddenInput) {
            // Create hidden input for the tag
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'languages[]'; // Name for form submission as an array
            hiddenInput.value = value;
            form.appendChild(hiddenInput);
            checkTags();
        }

    }
    
    /* Event Listeners */

    // Hide dropdown on outside click
    container.addEventListener('click', (event) => {
        if (!event.target.closest('.dropdown-container')) {
            dropdownMenu.style.display = 'none';
        }
    });
    // Prevent dropdown from closing on input click
    input.addEventListener('click', (event) => {
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