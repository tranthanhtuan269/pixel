/**
 * Created by MSc. Hoang Dung on 10/29/14.
 */
$(function () {
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        language: 'en',
        yearRange: "1950:2030",
        changeMonth: true,
        changeYear: true,
        pick12HourFormat: true
    });
});
$(document).ready(function () {
    $('#refresh').click(function () {
        window.location.reload();
    });
});
$(document).ready(function () {
    Tipped.create('.tooltip-inline');
});