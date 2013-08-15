// DOM READY
$(function()
{
	// Gravity forms plugin applies class to field wrapper instead of field
	$("input.toggle, textarea.toggle, .toggle input, .toggle textarea")
		.focus(function()
		{
			if (!$(this).attr("data-default"))
				$(this).attr("data-default", $(this).val());
			
			if ($(this).val() == $(this).attr("data-default"))
				$(this).val("");
		})
		.blur(function()
		{
			if ($(this).val() == "")
				$(this).val($(this).attr("data-default"));
		});
});