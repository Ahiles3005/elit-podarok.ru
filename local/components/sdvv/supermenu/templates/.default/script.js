$(document).ready(function () {
    //svg4everybody({});

    $( ".js-open-blok" ).hover(
        function() {
            if( $(this).data("openblock") ){
                $(this).parent().find('.active').each( function(){
                    $( this ).removeClass('active')
                });
                $(this).addClass('active');

                $(this).parent().parent().find(".dropdown-sub-menu").each( function(){
                    $( this ).removeClass('show')
                });
                var openblock = '#' + $(this).data("openblock");
                $( openblock ).addClass('show');
            }
        }, function() {
        }
    );
});