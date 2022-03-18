$(function(){
	$("a.vote_up").click(function(){
		var vote_id = $(this).attr('id');
	    $("div#votes_count"+vote_id).fadeOut("fast");
		$.ajax({
			type: "POST", 
            data: "action=vote_up&id="+$(this).attr("id"),  
			url: "elements/votes.php",
			success: function(msg)
			{
				$("div#votes_count"+vote_id).html(msg);
				$("div#votes_count"+vote_id).fadeIn();
			}
		});
	});
});	
