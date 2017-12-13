$(document).ready(function() {
	$('button.delete_entity').click(function(e) {
		e.preventDefault();
		form = $(this).closest('form');
		$("#confirmBox_delete").data('form',form).dialog('open');
		return false;
	});

	$("#confirmBox_delete").dialog({
		autoOpen:false,
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Supprimer": function() {
				$(this).data('form').submit();
				$( this ).dialog( "close" );
			},
		Cancel: function() {
				$( this ).dialog( "close" );
			}
		}

	});//end dialog

});

	 