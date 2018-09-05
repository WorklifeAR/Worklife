function nuevoAjax()
{
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false;
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); }

	return xmlhttp;
}

//--------------------------------------------------------------------------------------------------------

$(window).load(function() {
		// TinyNav.js 1
		$('#nav').tinyNav({
			active: 'selected',
			header: 'Menu Worklife'
		});
	});

	$(function() {
		if ($('#bannerscollection_zoominout_opportune').length){
			jQuery('#bannerscollection_zoominout_opportune').bannerscollection_zoominout({
				skin: 'opportune',
				responsive:true,
				width100Proc:true,
				showPreviewThumbs: false,
				showBottomNav: false,
				showCircleTimer: false,
				autoPlay:3,
				width: 1600,
				height: 437,
				fadeSlides:1,
				circleRadius:8,
				circleLineWidth:4,
				circleColor: "#ffffff", //849ef3
				circleAlpha: 50,
				behindCircleColor: "#000000",
				behindCircleAlpha: 20,
				thumbsWrapperMarginTop:25
			});		
		}
			
	});
		
$(document).ready(function(){
	if ($('#slider3').length){
		var sudoSliderServiciosInterna = $("#slider3").sudoSlider({
			responsive: true,		
			numeric: false, 		
			continuous: true, 				
			prevNext: true,
			auto: true,
			resumePause: 10000,
			pause: '4000',
			effect: 'slide'
		});
	}
	
	if ($('#slider2').length){
		var sudoSliderPublica = $("#slider2").sudoSlider({
			responsive: true,		
			numeric: true, 		
			continuous: true, 				
			prevNext: false,
			auto: true,
			resumePause: 10000,
			pause: '4000',
			effect: 'slide'
		});
	}
	
	if ($('#slider1').length){
		var sudoSlider = $("#slider1").sudoSlider({
			responsive: true,		
			numeric: false, 		
			continuous: true, 				
			prevNext: true,
			auto: true,
			resumePause: 10000,
			pause: '4000',
			slideCount: 4,
			effect: 'slide'
		});
	}
	
	if ($('#sliderGallery').length){
		var sliderGallery = $("#sliderGallery").sudoSlider({
			responsive: true,		
			numeric: false, 		
			continuous: true, 				
			prevNext: true,
			auto: true,
			slideCount: 3,
			resumePause: 10000,
			pause: '4000',
			effect: 'slide'
		});
	}
	
	/*if ($('#sliderproductos').length){
		var sliderproductos = $("#sliderproductos").sudoSlider({
			responsive: true,		
			numeric: false, 		
			continuous: true, 				
			prevNext: true,
			auto: true,
			slideCount: 3,
			resumePause: 10000,
			pause: '4000',
			effect: 'slide'
		});
	}*/
			 
	$(window).on("resize focus", function () {
		
		if ($('#slider1').length){
			var width = $("#slider1").width();

			var orgSlideCount = sudoSlider.getOption("slideCount");
			var slideCount;
			if (width < 500) {
				slideCount = 1;
			} else if (width < 800) {
				slideCount = 2;
			} else {
				slideCount = 4;
			}
			if (slideCount != orgSlideCount) {
				sudoSlider.setOption("slideCount", slideCount);
			}
		}
		
		if ($('#sliderGallery').length){
			var width = $("#sliderGallery").width();

			var orgSlideCount = sliderGallery.getOption("slideCount");
			var slideCount;
			if (width < 500) {
				slideCount = 1;
			} else if (width < 800) {
				slideCount = 2;
			} else {
				slideCount = 3;
			}
			if (slideCount != orgSlideCount) {
				sliderGallery.setOption("slideCount", slideCount);
			}
		}
		
		/*if ($('#sliderproductos').length){
			var width = $("#sliderproductos").width();

			var orgSlideCount = sliderproductos.getOption("slideCount");
			var slideCount;
			if (width < 500) {
				slideCount = 1;
			} else if (width < 600) {
				slideCount = 2;
			} else {
				slideCount = 3;
			}
			if (sliderproductos != orgSlideCount) {
				sliderproductos.setOption("slideCount", slideCount);
			}
		}*/
	}).resize();
	
});


$(document).ready(function() {
		
	$("#owl-demo").owlCarousel({	
		autoPlay: 3000, //Set AutoPlay to 3 seconds
		
		items : 4,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [979,3]	
	});

    $('.lightbox').nivoLightbox({ effect: 'fade' });
	
	$('*').each(function(){
		if($(this).attr('data-animation')) {
			var $animationName = $(this).attr('data-animation'),
				$animationDelay = "delay-"+$(this).attr('data-animation-delay');
			$(this).appear(function() {
				$(this).addClass('animated').addClass($animationName);
				$(this).addClass('animated').addClass($animationDelay);
			});
		}
	});
	
	$('#cssmenu').prepend('<div id="menu-button">Menu</div>');
	$('#cssmenu #menu-button').on('click', function(){
		var menu = $(this).next('ul');
		if (menu.hasClass('open')) {
			menu.removeClass('open');
		}
		else {
			menu.addClass('open');
		}
	});
	$('#cssmenu ul li a').on('click', function(){
		$('#cssmenu ul').removeClass('open');
	});
});


//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////


function ezBSAlert (options) {
	var deferredObject = $.Deferred();
	var defaults = {
		type: "alert", //alert, prompt,confirm 
		modalSize: 'modal-sm', //modal-sm, modal-lg
		okButtonText: 'Ok',
		cancelButtonText: 'Cancelar',
		yesButtonText: 'Si',
		noButtonText: 'No',
		headerText: 'Atención',
		messageText: 'Mensaje',
		alertType: 'default', //default, primary, success, info, warning, danger
		inputFieldType: 'text', //could ask for number,email,etc
	}
	$.extend(defaults, options);
  
	var _show = function(){
		var headClass = "navbar-default";
		switch (defaults.alertType) {
			case "primary":
				headClass = "alert-primary";
				break;
			case "success":
				headClass = "alert-success";
				break;
			case "info":
				headClass = "alert-info";
				break;
			case "warning":
				headClass = "alert-warning";
				break;
			case "danger":
				headClass = "alert-danger";
				break;
        }
		$('BODY').append(
			'<div id="ezAlerts" class="modal fade">' +
			'<div class="modal-dialog" class="' + defaults.modalSize + '">' +
			'<div class="modal-content">' +
			'<div id="ezAlerts-header" class="modal-header ' + headClass + '">' +
			'<button id="close-button" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Cerrar</span></button>' +
			'<h4 id="ezAlerts-title" class="modal-title">Modal title</h4>' +
			'</div>' +
			'<div id="ezAlerts-body" class="modal-body">' +
			'<div id="ezAlerts-message" style="text-align:center"></div>' +
			'</div>' +
			'<div id="ezAlerts-footer" class="modal-footer">' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>'
		);

		$('.modal-header').css({
			'padding': '10px 10px',
			'-webkit-border-top-left-radius': '5px',
			'-webkit-border-top-right-radius': '5px',
			'-moz-border-radius-topleft': '5px',
			'-moz-border-radius-topright': '5px',
			'border-top-left-radius': '5px',
			'border-top-right-radius': '5px'
		});
    
		$('#ezAlerts-title').text(defaults.headerText);
		$('#ezAlerts-message').html(defaults.messageText);

		var keyb = "false", backd = "static";
		var calbackParam = "";
		switch (defaults.type) {
			case 'alert':
				keyb = "true";
				backd = "true";
				$('#ezAlerts-footer').html('<button class="btn btn-' + defaults.alertType + '">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
					calbackParam = true;
					$('#ezAlerts').modal('hide');
				});
				break;
			case 'confirm':
				var btnhtml = '<button id="ezok-btn" class="btn btn-primary">' + defaults.yesButtonText + '</button>';
				if (defaults.noButtonText && defaults.noButtonText.length > 0) {
					btnhtml += '<button id="ezclose-btn" class="btn btn-default">' + defaults.noButtonText + '</button>';
				}
				$('#ezAlerts-footer').html(btnhtml).on('click', 'button', function (e) {
						if (e.target.id === 'ezok-btn') {
							calbackParam = true;
							$('#ezAlerts').modal('hide');
						} else if (e.target.id === 'ezclose-btn') {
							calbackParam = false;
							$('#ezAlerts').modal('hide');
						}
					});
				break;
			case 'prompt':
				$('#ezAlerts-message').html(defaults.messageText + '<br /><br /><div class="form-group"><input type="' + defaults.inputFieldType + '" class="form-control" id="prompt" /></div>');
				$('#ezAlerts-footer').html('<button class="btn btn-primary">' + defaults.okButtonText + '</button>').on('click', ".btn", function () {
					calbackParam = $('#prompt').val();
					$('#ezAlerts').modal('hide');
				});
				break;
		}
   
		$('#ezAlerts').modal({ 
          show: false, 
          backdrop: backd, 
          keyboard: keyb 
        }).on('hidden.bs.modal', function (e) {
			$('#ezAlerts').remove();
			deferredObject.resolve(calbackParam);
		}).on('shown.bs.modal', function (e) {
			if ($('#prompt').length > 0) {
				$('#prompt').focus();
			}
		}).modal('show');
	}
    
  _show();  
  return deferredObject.promise();    
}

