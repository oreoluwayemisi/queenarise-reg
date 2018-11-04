$(document).ready(function () {
    // DOM is fully loaded

    // Capitalize the first letter of First Name
    $('#firstName').on('change', function(e) {
        var $this = $(this),
            val = $this.val();
            regex = /\b[a-z]/g;

        val = val.charAt(0).toUpperCase() + val.substr(1);
    });

    // Capitalize the first letter of Last Name
    $('#lastName').on('change', function (e) {
        var $this = $(this),
            val = $this.val();
        regex = /\b[a-z]/g;

        val = val.charAt(0).toUpperCase() + val.substr(1);
    });
    

    // Capitalize the first letter of City
    $('#city').on('change', function (e) {
        var $this = $(this),
            val = $this.val();
        regex = /\b[a-z]/g;

        val = val.charAt(0).toUpperCase() + val.substr(1);
    });

    // change the email to lowercase
    $('#email').on('change', function(e) {
        var $this = $(this),
            val = $this.val();
            val = val.toLowerCase();
    });

    // On clicking the empress option, select the option, toggle the display of the list and hide other packages
    $('#empress').click(function(e){
        $div = $('#empressList');
        $($div).toggle();
        $('#empress').prop('checked',true);
        $('ul').not($div).hide();
    });
    // On clicking the duchess option, select the option, toggle the display of the list and hide other packages
    $('#duchess').click(function (e) {
        $div = $('#duchessList');
        $($div).toggle();
        $('#duchess').prop('checked',true);
        $('ul').not($div).hide();
    });
    // On clicking the queen option, select the option, toggle the display of the list and hide other packages
    $('#queen').click(function (e) {
        $div = $('#queenList');
        $($div).toggle();
        $('#queen').prop('checked',true);
        $('ul').not($div).hide();
    });

    //When form is submitted, grab data
    $('form').submit(function(event) {
        event.preventDefault();

        // put form data into variables
        var firstName = $.trim(document.getElementById('firstName').value);
        var lastName = $.trim(document.getElementById('lastName').value);
        var email = $.trim(document.getElementById('email').value);
        var phone = $.trim(document.getElementById('phone').value);
        var city = $.trim(document.getElementById('city').value);
        var ticketType = document.querySelector('input[name="ticketType"]:checked').value;
    });
});