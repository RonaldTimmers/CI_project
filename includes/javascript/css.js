$("#searchfield").live("blur", function(){
	var default_value = $(this).attr("placeholder");
	if ($(this).val() == ""){
		$(this).val(default_value);
	}
}).live("focus", function(){
	var default_value = $(this).attr("placeholder");
	if ($(this).val() == default_value){
		$(this).val("");
	}
});