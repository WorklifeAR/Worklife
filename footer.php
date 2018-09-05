<form action="mis-publicaciones.html?opcion=5" name="publicaciones" id="publicaciones" method="get" enctype="multipart/form-data" >
        <input type="hidden" name="ses" id="ses" value="<?= session_id() ?>" />
        <input type="hidden" name="id_directorio" id="id_directorio" value="" />
        <input type="hidden" name="funcion" id="funcion" value="" />
    </form>


    <script>
        function enviarAccion(id, accion) {
            $("#id_directorio").val(id);
            $("#funcion").val(accion);

            document.publicaciones.submit();
        }
    </script>
	
<script>
$(document).ready(function() {
	$("body").fadeIn();

});
</script>

<link rel=stylesheet href="admin/calendario/xc2_default.css" type="text/css">
<script type="text/javascript" src="js/var.js"></script>

<link rel="stylesheet" type="text/css" href="shadowbox/shadowbox.css">
<script type="text/javascript" src="shadowbox/shadowbox.js"></script>

<!-- -->
<script type="text/javascript">
	Shadowbox.init({
		language: 'es',
		players:  ['img', 'html', 'iframe', 'qt', 'wmp', 'swf', 'flv']
	});
</script> 


<script language="javascript" src="calendario_eventos/calendario.js"></script>
<!-- must have -->
<link href="allinone_carousel.css" rel="stylesheet" type="text/css">
<script src="js/allinone_carousel.js" type="text/javascript"></script>
<!-- must have -->

<script>

// Somth page scroll
$(function() {
 /* $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top -40
        }, 1200);
        return false;
      }
    }
  });
*/
	$("html").niceScroll({
		scrollspeed: 100,
		mousescrollstep: 38,
		cursorwidth: 10,
		cursorborder: 0,
		cursorcolor: '#7C4793',
		autohidemode: false,
		zindex: 999999999,
		horizrailenabled: false,
		cursorborderradius: 0,
	});
});
</script>

<link href="js/easy-autocomplete.min.css" rel="stylesheet" type="text/css">
<link href="js/easy-autocomplete.themes.min.css" rel="stylesheet" type="text/css">
<script src="js/jquery.easy-autocomplete.min.js" type="text/javascript"></script>

<script type="text/javascript" src="js/jquery.validation-1.15.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.validation-1.15.0/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="js/jquery.validation-1.15.0/dist/localization/messages_es_AR.min.js"></script>
<script type="text/javascript" src="https://apis.google.com/js/api:client.js"></script>

<script type="text/javascript" src="js/login_social.js"></script>

<script language="javascript" src="js/scripts_site.js"></script>

<style>
.easy-autocomplete{ width: auto !important; }
</style>

<script>
$( function() {	
	var options = {
		url: "search.php",	
		getValue: "label",	
		template: {
			type: "description",
			fields: {
				description: "cate"
			}
		},	
		list: {
			match: {
				enabled: true
			}
		},
		theme: "square",
		list: {
			onClickEvent: function() {
				var value = $("#search_services").getSelectedItemData().label;
				var tipo = $("#search_services").getSelectedItemData().cate;
				var id = $("#search_services").getSelectedItemData().id;
				
				if(tipo=="Categoría"){
					$("#categoria_search").val(id);
				}else{
					$("#palabra_search").val(value);
				}
				
				document.serach_list.submit();
			},
			onKeyEnterEvent: function() {
				var value = $("#search_services").getSelectedItemData().label;
				var tipo = $("#search_services").getSelectedItemData().cate;
				var id = $("#search_services").getSelectedItemData().id;
				
				$("#palabra_search").val(value);
				
				document.serach_list.submit();
			},
			maxNumberOfElements: 8,
			match: {
				enabled: true
			},
			sort: {
				enabled: true
			}
		}
	};
	
	$("#search_services").easyAutocomplete(options);
} );

///////////////////////////////

$(document).ready(function() {
	
	$('#search_services').keypress(function (e) {
	 var key = e.which;
	 if(key == 13)  // the enter key code
	  {
	   $("#palabra_search").val($(this).val());
	   document.serach_list.submit();
	   
	  }
	}); 

}); 

function enviarBusqueda(){
	$("#palabra_search").val($("#search_services").val());
	 document.serach_list.submit();
}
//////////////

<?php if((!isset($_SESSION["longitud"]) || !isset($_SESSION["latitud"])) || ($_SESSION["longitud"]=="" ||  $_SESSION["latitud"]=="")){ ?>
window.onload = function() {
  var startPos;
  var geoOptions = {
	timeout: 10 * 1000,
	maximumAge: 5 * 60 * 1000,
	enableHighAccuracy: true
  }

  var geoSuccess = function(position) {
    startPos = position;

	$.ajax({
		type: "GET",
		url: "location.php",
		data: {longitud: startPos.coords.longitude, latitud: startPos.coords.latitude},
		success: function(data) {
			console.log(data);
			
			setTimeout(function(){ window.location.reload(); }, 100);													
		},
		error: function(data) {
			console.log(data);
		}					
	});
  };
  var geoError = function(error) {
    console.log('Error occurred. Error code: ' + error.code);
	
	$.ajax({
		type: "GET",
		url: "location.php",
		data: {longitud: "", latitud: ""},
		success: function(data) {
			console.log(data);
		},
		error: function(data) {
			console.log(data);			 
		}					
	});
    // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
  };

  navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
};
<?php } ?>

</script>
<script type="text/javascript" src="js/script.js"></script>