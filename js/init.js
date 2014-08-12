$(function(){

	// new recordings marking
	$('.newRecording').slideDown('slow');
	// remove recordings marking on click on audio player
	$('.newRecording').find('audio').click(function(e){
		$(this).parent().parent().removeClass('newRecording').addClass('newRecordingClicked');
	});

	// error messages
	setTimeout(function(){
		if ($('.errorMessages').html() != '') {
			$('.errorMessages').slideDown('slow');
		}
	},9);	setTimeout(function(){
		$('.errorMessages').slideUp('slow');
	},3999);
	$('.errorMessages').click(function(e){
		$(this).slideUp('slow');
		e.preventDefault();
	});

	// follow scroll
	if (!($.browser.webkit)) {
		recorderFollowScroll();
  	}

	// from username to send input
	$('.username').click(function(e){
		$('#shareToUsername').val($(this).html());
		e.preventDefault();
	});

	// username field
	$('#shareToUsername').focus(function(e){
		e.stopPropagation();
		if ($(this).val() == 'username'){
			$(this).val('');
			e.preventDefault();
		}
	});
	// confirm delete click event
	$('.deleteRecInbox').click(function(e){
		if (confirmDelete("Are you sure you wish to delete this recording?")){
			return;
		}
		e.preventDefault();
	});
	$('.deleteRecOutbox').click(function(e){
		if (confirmDelete("Are you sure you wish to delete this recording?\n\nThe recording will also be deleted from the inbox of the receiver.")){
			return;
		}
		e.preventDefault();
	});

	// ajax autocomplete to get usernames
	$("#shareToUsername").autocomplete({
	    source: function( request, response ) {
	        $.ajax({
	            url: "/ajax/GetUsernameAutocomplete.php",
	            data: {term: request.term},
	            dataType: "json",
	            success: function(data) {
	                response( $.map(data, function(member) {
	                    return {
	                        value: member.username
	                    }
	                }));
	            }
	        });
	    }
	});
	
	// confirm delete
	function confirmDelete(message) {
		var agree = confirm(message);
		if (agree) {
			return true;
		} else {
			return false;
		}
	}

	function recorderFollowScroll() {
		var top = $('#rightSection').offset().top - parseFloat($('#rightSection').css('marginTop').replace(/auto/, 0));
		$(window).scroll(function (event) {
			if ($(window).height() > 1) { // if window height < 500 dont follow scroll
				// what the y position of the scroll is
				var y = $(this).scrollTop() + 0; // + height of headerBar

				// whether that's below the form
				if (y >= top) {
				  // if so, ad the fixed class
				  $('#rightSection').addClass('fixed');
				} else {
				  // otherwise remove it
				  $('#rightSection').removeClass('fixed');
				}
			}
		});
	}

});