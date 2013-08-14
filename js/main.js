// DOM READY
$(function()
{
	// Gravity forms plugin applies class to field wrapper instead of field
	$("input.toggle, textarea.toggle, .toggle input, .toggle textarea").focus(function(e)
	{
		if (!$(this).attr("data-default"))
			$(this).attr("data-default", $(this).val());
		
		if ($(this).val() == $(this).attr("data-default"))
			$(this).val("");
	});
	
	$("input.toggle, textarea.toggle, .toggle input, .toggle textarea").blur(function(e)
	{
		if ($(this).val() == "")
			$(this).val($(this).attr("data-default"));
	});
	
	/*$('#mid .slides_container').cycle({
		fx: 'fade',
	    //prev: '#prev', 
	    //next: '#next',
	    //pager:  '#mid .pagination',
		timeout: 6000,
		containerResize: false,
		slideResize: false
	});*/
});