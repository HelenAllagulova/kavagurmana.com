(function($) {
    $(function() {
        function getWindow(){
            seconds = $('#sub_timer').html();
            seconds = seconds*1 + 1;
            if(seconds < 6){
                $('#sub_timer').html(seconds);
                ajax('/pages/seconds/'+seconds, '', 'show_timer');
            }
            if(seconds == 5){
                $('.offer').arcticmodal({
                    closeOnOverlayClick: true,
                    closeOnEsc: true
                });

            }
        };
        setInterval (getWindow, 1000);
    })
})(jQuery);

function close_window(){
    $('.offer').arcticmodal('close');
}