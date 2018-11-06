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
        var dataString = 'firstName=' + firstName + '&lastName=' + lastName + '&email=' + email + '&phone=' + phone + '&city=' + city + '&ticketType=' + ticketType;
        var amount;
        if(ticketType == 'Duchess') {
            amount = 500000;
        } else if(ticketType == 'Empress') {
            amount = 1500000;
        } else if(ticketType == 'Queen') {
            amount = 2500000;
        }

        //when payment has been successfully made, this is the dataString to send by POST
        var dataString2 = dataString + '&paid=1';

        // check to see if user has registered
        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: dataString,
            success: function(result) {
                if(result == "user exists") {
                    swal("Already Registered", "You have already registered for the Conference", "warning");
                } else {
                    // Bring up the Payment Page
                    function payWithPaystack() {
                        var handler = PaystackPop.setup({
                            key: 'pk_live_e9ec9b0c43f8564af3fe8f2560b5d5c41e4b8548',
                            email: email,
                            amount: amount,
                            firstname: firstName,
                            lastname: lastName,
                            metadata: {
                                custom_fields: [
                                    {
                                        display_name: "Mobile Number",
                                        variable_name: "mobile_number",
                                        value: phone
                                    }
                                ]
                            },
                            callback: function (response) {

                                // console.log(response);
                                // If payment was successful, then post the User Data to the Database and give value
                                if (response.status == 'success') {
                                    $.ajax({
                                        type: 'POST',
                                        url: 'register.php',
                                        data: dataString2,
                                        success: function (datapost) {

                                        }
                                    });

                                    // Alert the User of a successful registration
                                    swal("Success", "Your registration was successful and your transaction ref is " + response.reference, "success");
                                    setTimeout(function () {
                                        window.location = 'https://cgeee.org/queenarise/thank-you'
                                    }, 3000);
                                }
                                
                            },
                            
                        });
                        handler.openIframe();
                    }

                    payWithPaystack();
                }
            }
        });
        
    });
});