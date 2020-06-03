(function ($) {
	var ajaxtimeout = 20000;
	
	$(document).ajaxError(function(event,request,settings,error) {
		alert("Terjadi kesalahan dalam pengambilan data");
	});
	
	// untuk mendapatakan option select
	$.fn.xhrSetOption = function (kode, param, callback) {
		var ajaxurl = g_abs_url + "ajax/" + kode;
		if (typeof(param) == "object")
			ajaxurl += "/" + param.join("/");
		
		return $(this).each(function() {
			var jqelem = $(this);
			
			var jqxhr = $.ajax({
				url: ajaxurl,
				timeout: ajaxtimeout
			});
			jqxhr.done(function(data) {
				jqelem.html(data);
				
				// panggil fungsi callback
				if (typeof(callback) == "function")
					callback();
			});
		});
	}
	function encodeURL (str) {
		str = encodeURI(str);
		return str.replace(/'/g, "%27");  
	}
	// untuk autocomplete dengan ajax (menggunakan jquery ui autocomplete)
	$.fn.xhrAutoComplete = function (idtarget, kode, callback) {
		var ajaxurl = g_abs_url + "ajax/" + kode; 

		return $(this).each(function() {
			$(this).autocomplete({
				source: function(request, response) {
					ajaxurlac = ajaxurl + "/" + encodeURL(request.term);
					
					var jqxhr = $.ajax({
						url: ajaxurlac,
						timeout: ajaxtimeout,
						dataType: "json"
					});
					jqxhr.done(function(data) {
						response($.map(data.items,function(item) {
							return {
								label: item.label,
								value: item.value
							}
						}));
					});
				},
				minLength: 2,
				select: function(event,ui) {
					event.preventDefault();
					
					$("#"+idtarget).val(ui.item.value);
					$(this).val(ui.item.label);

					// panggil fungsi callback
					if(typeof(callback) == "function")
						callback();
				},
				focus: function(event,ui){
					//$(this).val(ui.item.label);
					event.preventDefault();
					
					$("#"+idtarget).val(ui.item.value);
					$(this).val(ui.item.label);

					// panggil fungsi callback
					if(typeof(callback) == "function")
						callback();
				}/*,
				change: function(event,ui) {
					$("#"+idtarget).val("");
				} */
			});
		});
	}
	
	// untuk autocomplete dengan ajax (menggunakan jquery ui autocomplete)
	$.fn.xhrAutoCompleteSol = function (idtarget, kode, callback) {
		var ajaxurl = g_abs_url + "ajax/" + kode; 

		return $(this).each(function() {
			$(this).autocomplete({
				source: function(request, response) {
					ajaxurlac = ajaxurl + "/" + encodeURL(request.term);
					
					var jqxhr = $.ajax({
						url: ajaxurlac,
						timeout: ajaxtimeout,
						dataType: "json"
					});
					jqxhr.done(function(data) {
						response($.map(data.items,function(item) {
							return {
								label: item.label,
								value: item.value
							}
						}));
					});
				},
				minLength: 2,
				select: function(event,ui) {
					event.preventDefault();
					
					$("#"+idtarget).val(ui.item.value);
					$(this).val(ui.item.label);

					// panggil fungsi callback
					if(typeof(callback) == "function")
						callback();
				},
				focus: function(event,ui){
					$(this).val(ui.item.label);
				}/*,
				change: function(event,ui) {
					$("#"+idtarget).val("");
				} */
			});
		});
	}
	
	// untuk autocomplete dengan ajax (menggunakan jquery ui autocomplete)
	$.fn.xhrAutoCompleteplus = function (idtarget, kode, idplus, callback) {
		var ajaxurl = g_abs_url + "ajax/" + kode; 

		return $(this).each(function() {
			$(this).autocomplete({
				source: function(request, response) {
					ajaxurlac = ajaxurl + "/" + $('#'+idplus).val() + "/" + encodeURL(request.term);
					
					var jqxhr = $.ajax({
						url: ajaxurlac,
						timeout: ajaxtimeout,
						dataType: "json"
					});
					jqxhr.done(function(data) {
						response($.map(data.items,function(item) {
							return {
								label: item.label,
								value: item.value
							}
						}));
					});
				},
				minLength: 2,
				select: function(event,ui) {
					event.preventDefault();
					
					$("#"+idtarget).val(ui.item.value);
					$(this).val(ui.item.label);

					// panggil fungsi callback
					if(typeof(callback) == "function")
						callback();
				},
				focus: function(event,ui){
					$(this).val(ui.item.label);
				}/*,
				change: function(event,ui) {
					$("#"+idtarget).val("");
				} */
			});
		});
	}
	
	$.fn.xhrAutoSelect = function (kode) {
		var ajaxurl = g_abs_url + "ajax/" + kode;
		
		return $(this).each(function() {
			$(this).autocomplete({
				source: function(request, response) {
					ajaxurlac = ajaxurl + "/" + encodeURL(request.term);
					
					var jqxhr = $.ajax({
						url: ajaxurlac,
						timeout: ajaxtimeout,
						dataType: "json"
					});
					jqxhr.done(function(data) {
						response($.map(data.items,function(item) {
							return {
								label: item.label
							}
						}));
					});
				},
				minLength: 0
			});
			
			$(this).mousedown(function() {
				$(this).autocomplete("search","");
			});
		});
	}
	
	// untuk meload page
	$.fn.xhrSetHTML = function (kode, param, callback) {
		var ajaxurl = g_abs_url + "ajax/" + kode + "/" + param;
		
		return $(this).each(function() {
			var jqelem = $(this);
			
			// beri loading
			jqelem.html('<img src="' + g_assets_url + 'images/loading.gif" />');
			
			var jqxhr = $.ajax({
				url: ajaxurl,
				timeout: ajaxtimeout
			});
			jqxhr.done(function(data) {
				jqelem.html(data);
				
				// panggil fungsi callback
				if (typeof(callback) == "function")
					callback();
			});
		});
	}
	
	// pesan error
})(jQuery);