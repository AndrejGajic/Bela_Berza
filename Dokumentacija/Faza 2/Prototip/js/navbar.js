/* Luka Tomanovic 0410/2018
   Kosta Matijevic 0034/2018 */

$(document).ready(function () {
    $('#sidebarSelector').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#sidebar').toggleClass('basic');
    });
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#sidebar').toggleClass('basic');
    });
});
