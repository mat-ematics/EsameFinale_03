/* Imports */
import initializeForm from "./modules/form_handlers/_form_toggle";
import DOMUtils from "./modules/_shared/_dom_utils";

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

/* Create User Form Validation Initialization */
initializeForm("signup", document.getElementById("formCreateUser"));
initializeForm('editUser', document.getElementById("formEditUser"));