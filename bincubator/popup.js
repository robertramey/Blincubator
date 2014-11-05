jQuery(document).ready(function($) {
	$('.popup').click(function() {
		var w = $(this).data('width');
		var h = $(this).data('height');
		var s = $(this).data('scrollbars');
		var left = (screen.width/2) - (w/2);
		var top = (screen.height/2) - (h/2);
        var NWin = window.open(
            $(this).prop('href'),
            '',
            'scrollbars=' + s + ',resizable=yes,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left
        );
		if (window.focus){
            NWin.focus();
        }
        return false;
	});
});
