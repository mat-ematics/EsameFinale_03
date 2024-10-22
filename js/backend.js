/* Menu delle Visualizzazioni */
const areaList = document.querySelectorAll(".area");
let currentViewItem = document.getElementById('users');

/* Select the Current View in Menu */
currentViewItem.classList.add('current');

areaList.forEach(item => {
    item.addEventListener('click', function() {
        currentViewItem.classList.remove("current");
        this.classList.add("current");
        currentViewItem = this;
    });
});