jQuery(document).ready(function($) {

	$(document).on('click', '.prev-month a, .next-month a', function(event) {
		var newMonth = $(this).attr('rel');
		var divCalendar = $(this).closest('div.events-calendar-widget');
		var divRel = divCalendar.attr('rel');
		var tdSpinner = divCalendar.find('td.ajax-spinner');
		var divSpinner = divCalendar.find('td.ajax-spinner div');

		divSpinner.css('top', parseInt((tdSpinner.height() - 16) / 2)+'px').css('left', parseInt((tdSpinner.width() - 16) / 2)+'px').fadeIn(300);

		$.ajax({
			type: 'POST',
			url: emArgs.ajaxurl,
			data: {
				action: 'get-widget-calendar-month',
				date: newMonth,
				widget_id: divCalendar.attr('rel'),
				nonce: emArgs.nonce
			},
			dataType: 'html'
		})
		.done(function(data) {
			divCalendar.fadeOut(300, function() {
				divCalendar.replaceWith(data);
				$('#event-calendar-'+divRel).fadeIn(300);
			});
		}).fail(function(data) {
			//
		});

		return false;
	});
});