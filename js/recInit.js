$(function(){
	$.jRecorder({ 
		host : '/RecordCall.php', /////

		callback_started_recording:     function(){callback_started(); },
		callback_stopped_recording:     function(){callback_stopped(); },
		callback_activityLevel:         function(level){callback_activityLevel(level); },
		callback_activityTime:     		function(time){callback_activityTime(time); },
		callback_finished_sending:     	function(time){ callback_finished_sending() },

		swf_path : '/js/jRecorder.swf'

	});
});