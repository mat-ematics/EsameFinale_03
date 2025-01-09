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

/* User Forms Validation Initialization */
initializeForm("userCreate", document.getElementById("formUserCreate"));
initializeForm('userEdit', document.getElementById("formUserEdit"));

/* Category Forms Validation Initialization */
initializeForm('categoryCreate', document.getElementById("formCategoryCreate"));
initializeForm('categoryEdit', document.getElementById("formCategoryEdit"));
