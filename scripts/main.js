$(document).ready(function () {
    // DOM is fully loaded
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
});