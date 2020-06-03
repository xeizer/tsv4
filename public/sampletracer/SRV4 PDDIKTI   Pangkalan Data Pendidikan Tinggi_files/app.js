function load(theurl,div)
{
	$.ajax({
	    url: site+'/'+theurl,
	    success: function(response){			
			$(div).html(response);
			$('#refresh_link').attr('onclick','load("'+theurl+'","'+div+'")');
			// update info
			load_silent('admin/infofooter','.info-footer');
	    },
		dataType:"html"  		
	});
	return false;
}
function load_silent(theurl,div)
{
	$.ajax({
	    //url: site+'/'+theurl,
	    url: theurl,
	    success: function(response){			
			$(div).html(response);
	    },
		dataType:"html"  		
	});
	return false;
}
function load_silent_alt(theurl,div)
{
	$.ajax({
	    //url: site+'/'+theurl,
	    url: theurl,
	    success: function(response){			
			$(div).val(response);
	    },
		dataType:"html"  		
	});
	return false;
}
function load_url(theurl,div)
{
	$.ajax({
	    url: theurl,
	    success: function(response){			
			$(div).html(response);
			$('#refresh_link').attr('onclick','load_url("'+theurl+'","'+div+'")');
	    },
		dataType:"html"  		
	});
	return false;
}
function send_form(formObj,action,responseDIV)
{
	$.ajax({
		url: site+"/"+action, 
		data: $(formObj.elements).serialize(),
		success: function(response){
			$(responseDIV).html(response);
		    }, 
		type: "post", 
		dataType: "html"
	}); 
	return false;
}
function formatNumber(input)
{
	var num = input.value.replace(/\,/g,'');
	if(!isNaN(num)){
	if(num.indexOf('.') > -1){
	num = num.split('.');
	num[0] = num[0].toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'');
	if(num[1].length > 2){
	alert('You may only enter two decimals!');
	num[1] = num[1].substring(0,num[1].length-1);
	} input.value = num[0]+'.'+num[1];
	} else{ input.value = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1,').split('').reverse().join('').replace(/^[\,]/,'') };
	}
	else{
		//alert('Anda hanya diperbolehkan memasukkan angka!');
		input.value = input.value.substring(0,input.value.length-1);
	}
}
function convert_paging(domId)
{
	$(".pagination,.pager").find("a").each(function(i){
		if(!$(this).hasClass("notAjax")){
			var thisHref = $(this).attr("href");
			$(this).prop('href','javascript:void(0)');
			$(this).prop('rel',thisHref);
			$(this).bind('click', function(){
				load_url(thisHref,domId);
				return false;
			});
		}
	});
}
function scroll_to(domscroll,whichdom,scrollspeed)
{
	var speed = scrollspeed || 800;
	var dom = whichdom || 'body';
	$(dom).scrollTo($(domscroll),speed);
}
function scroll_to_top(whichdom,scrollspeed)
{
	var speed = scrollspeed || 800;
	var dom = whichdom || 'body';
	$(dom).scrollTo(0,speed);
}
function showAlert(title,cls,msg,fn)
{
	bootbox.alert("<h4 class='title text-"+cls+"'>"+title+"</h4>"+msg,fn);	
}
