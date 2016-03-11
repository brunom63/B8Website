function replaceValues (str) {
	str = str.replace(/http:/gi, '@SDK@1');
	str = str.replace(/https:/gi, '@SDK@2');
	str = str.replace(/iframe/gi, '@SDK@3');
	str = str.replace(/img/gi, '@SDK@4');
	
	str = str.replace(/</gi, '@SDK@5');
	str = str.replace(/>/gi, '@SDK@6');
	str = str.replace(/&gt;/gi, "@SDK@7");
	str = str.replace(/&lt;/gi, "@SDK@8");
	
	str = str.replace(/\//gi, "@SDK@9");
	str = str.replace(/\\/gi, "@SDK@10");
	str = str.replace(/&frasl;/gi, "@SDK@11");
	str = str.replace(/&#8260;/gi, "@SDK@12");
	str = str.replace(/&#x2044;/gi, "@SDK@13");
	

	return str;
}

$(document).ready(function(){
    $(document).on('submit', 'form', function(e){
        //e.preventDefault();
		
		$('input, select, textarea').each(function(index) {
			$(this).val(replaceValues($(this).val()));
			//alert($(this).val());
		});
    });
});

