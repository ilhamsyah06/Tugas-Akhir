$(document).ready(function() {

	setInterval(function() {
		$('#datawaktu').html(new Date().toString('dddd, dd MMMM yyyy  HH:mm:ss'))
	}, 1000);

});