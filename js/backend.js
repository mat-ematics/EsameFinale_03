/* Imports */
import initializeForm from "./modules/form_handlers/_form_toggle";
// Shared Imports
import DOMUtils from "./modules/_shared/_dom_utils";
import manageMultitagDropdown from "./modules/_shared/multitag_dropdown";

/* Menu delle Visualizzazioni */
const areaNavItems = document.querySelectorAll(".area");
const areaList = document.querySelectorAll(".area-div");
let currentViewItem;

/* Select the Current View in Menu */


/* Area Selection Behaviour */
areaNavItems.forEach(item => {
    item.addEventListener('click', function() {
        const newViewItem = this;
        currentViewItem = DOMUtils.changeArea(areaList, areaNavItems, newViewItem, currentViewItem);
    });
});

/* User Forms Validation Initialization */
initializeForm("userCreate", document.getElementById("formUserCreate"));
initializeForm('userEdit', document.getElementById("formUserEdit"));
initializeForm('userDelete', document.getElementById('formUserDelete'));

/* Category Forms Validation Initialization */
initializeForm('categoryCreate', document.getElementById("formCategoryCreate"));
initializeForm('categoryEdit', document.getElementById("formCategoryEdit"));
initializeForm('categoryDelete', document.getElementById('formCategoryDelete'));

/* Work Forms Validation Initialization */
initializeForm('workCreate', document.getElementById("formWorkCreate"));
initializeForm('workEdit', document.getElementById("formWorkEdit"));
initializeForm('workDelete', document.getElementById('formWorkDelete'));

/* Multilanguage Dropdown Behaviour */
manageMultitagDropdown(document.getElementById('formWorkCreate'), {createHiddenInput: true, hiddenInputName: 'work_languages'});
manageMultitagDropdown(document.getElementById('formWorkEdit'), {createHiddenInput: true, hiddenInputName: 'work_languages', allowEmpty: true});