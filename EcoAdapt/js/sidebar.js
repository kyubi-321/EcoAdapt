// sidebar

function toggleDropdown() {
    var submenu = document.getElementById("profile-submenu");
    var arrowIcon = document.getElementById("arrow-icon");
    
    submenu.classList.toggle("active");
    arrowIcon.classList.toggle("rotate");

    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}