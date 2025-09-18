$(function () {
    $(document).ready(function () {
        const body = $('html');
        const toggle = $('#themeToggle');
        const sunIcon = $("#sun-icon");
        const moonIcon = $("#moon-icon");

        function updateIcons() {
            if (body.hasClass("dark")) {
                sunIcon.addClass("opacity-0 absolute");
                moonIcon.removeClass("opacity-0 absolute");
            } else {
                sunIcon.removeClass("opacity-0 absolute");
                moonIcon.addClass("opacity-0 absolute");
            }
        }

        if (localStorage.getItem('theme') === 'dark') {
            body.addClass('dark');
        }

        updateIcons();

        toggle.on('click', function () {
            body.toggleClass('dark');

            body.hasClass('dark')
                ? localStorage.setItem('theme', 'dark')
                : localStorage.setItem('theme', 'light');

            updateIcons();
        });
    });
});