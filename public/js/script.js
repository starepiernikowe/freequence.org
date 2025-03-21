document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger-menu');
    const navMenu = document.querySelector('.nav-menu');
    const closeButton = document.querySelector('.nav-close-button');

    hamburger.addEventListener('click', function() {
        navMenu.classList.add('open');
    });

    closeButton.addEventListener('click', function() {
        navMenu.classList.remove('open');
    });

     // Close the menu if a click occurs outside of it.
    document.addEventListener('click', function(event) {
        if (!navMenu.contains(event.target) && !hamburger.contains(event.target)) {
            navMenu.classList.remove('open');
        }
    });
});