/* Imports */
import initializeForm from "./modules/form_handlers/form_toggle";

/* Menu delle Visualizzazioni */
const areaList = document.querySelectorAll(".area");
let currentViewItem = document.getElementById('users');

/* Select the Current View in Menu */
currentViewItem.classList.add('current');

/* Area Selection Behaviour */
areaList.forEach(item => {
    item.addEventListener('click', function() {
        currentViewItem.classList.remove("current");
        this.classList.add("current");
        currentViewItem = this;
    });
});

/* Create User Form Validation Initialization */
initializeForm("signup", document.getElementById("formCreateUser"));