$(function() {


	defaultParam= function( var_name, default_value) {
		return (typeof var_name === "undefined") ? default_value : var_name;
	};



	Georigami.alert= function(title,html) {



		html='<div id="myModal" class="modal hide fade alert-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
			'<div class="modal-header">'+
				'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
				'<h3 id="myModalLabel">'+title+'</h3>'+
			'</div>'+
			'<div class="modal-body">'+
				html+
			'</div>'+
			'<div class="modal-footer">'+
				'<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>'+
			'</div>'+
		'</div>';

		$(html).modal('show');
	}





	$('input[type="number"]').each( function(k,obj){
		if (obj.type!='number') {
			var target=$(this);
			target.spinner();
			target.on('spin', function(event,ui) { setTimeout( function() { target.trigger('change'); },1); });

		}
	});





	$('input[type="range"]').each( function(k,obj){
		var target=$(this);

		if (obj.type=='range') { //HTML5
			var step=obj.step;
			if (null===step) step=1;

			var divnumber= $('<input type="number" value="'+target.val()+'" min="'+obj.min+'" max="'+obj.max+'" step="'+step+'" class="'+target.attr('class')+'"></div>').insertAfter(target);
			$('<br/>').insertAfter(target);
			target.removeClass().change(function(){
				divnumber.val( target.val() );
			});

			divnumber.change(function() {	target.val( divnumber.val() ); target.trigger('change');	});
			divnumber.keypress(function() {	setTimeout( function() {target.val( divnumber.val() )},1);	});

		}
		else // jqueryUI
		{
			obj.type='number';

			var divslider= $('<div class="slider"></div>').insertBefore(target);
			var step=obj.step;
			if (step==null) step=1;
			step=parseFloat(step);

			var slider= divslider.slider({
				value: 	parseFloat(target.val()),
				min: 	parseFloat(obj.min),
				max: 	parseFloat(obj.max),
				step:   step,

				slide:  function( event, ui ) {
					target.val( ui.value );
					target.trigger('change');
				}

			});

			target.change(function() {	divslider.slider( "option", "value", target.val() );	});
			target.keypress(function() {	setTimeout( function() {divslider.slider( "option", "value", target.val() ) },1);	});
		}
	});









  if(typeof Georigami == 'undefined') Georigami={};
	//Georigami.verticalScale=1;



	// INIT


	$('.tip').tooltip({placement:'top', delay:{show:2000, hide:0}, trigger: 'hover'});

	if ($('body').hasClass('bloc3d')) 		Georigami.initBloc('3d');
	if ($('body').hasClass('print'))  		Georigami.initBloc('print');
	if ($('body').hasClass('profil')) 		Georigami.initBloc('profil');

	if ($('body').hasClass('location')) 	Georigami.initLocation();
	if ($('body').hasClass('savedview')) 	Georigami.initLocation();
	if ($('body').hasClass('map')) 			Georigami.initMap();
	if ($('body').hasClass('new')) 			Georigami.initNew();
	if ($('body').hasClass('welcome')) 		Georigami.initWelcome();



	// blocsmap


	if ( $('.blocsmap').length ) {


		


  function createGrid(map, data, marker) {
    var gridObj=grid(map,data.lat,data.lng,data.width,data.height,data.vslices,data.hslices,data.rotate);
      google.maps.event.addListener(marker, "mouseover", function() {
        gridObj.setOptions( { strokeColor: "#00FF00"  } );
      });

      google.maps.event.addListener(marker, "mouseout", function() {
        gridObj.setOptions( { strokeColor: "#FF0000"  } );
      });
    return gridObj;
  }




     //initmap

    var Loc=new google.maps.LatLng (Georigami.location.lat , Georigami.location.lng );
    var mapOptions = {
        center: Loc,
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    map = new google.maps.Map($(".blocsmap")[0], mapOptions);


    google.maps.event.addListener(map, 'click', function(event) {

        var html= '<p class="coords">lat: '+event.latLng.lat()+'<br/>lng: '+event.latLng.lng()+'</p>';
        html= html+'<a href="'+Georigami.baseurl+'new?lat='+event.latLng.lat()+'&lng='+event.latLng.lng()+'" class="btn btn-primary btn-small"/>'+Lang.buildanew+'</a>';

        if (infowindow) infowindow.close();
         infowindow = new google.maps.InfoWindow({content: html, position:new google.maps.LatLng( event.latLng.lat() , event.latLng.lng() ) });
         infowindow.open(map);

        });


    var markers = [];
    var bounds = new google.maps.LatLngBounds ();
    bounds.extend(Loc);

    for (var i = 0; i < Georigami.location.blocs.length; i++) {
      var data=Georigami.location.blocs[i];
      var latLng = new google.maps.LatLng( data.lat,data.lng );
      bounds.extend(latLng);


      var html='<a href="'+Georigami.baseurl+Georigami.lang+'/'+Georigami.location.name+'_'+Georigami.location.id+'/bloc'+data.id+'">'; //TODO! style in  css

        //html =html+data.lat+', '+data.lng+'<br/>';



        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'N.svg" title="'+Lang.nface+'" width="64px">'; //TODO! style in  css
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'W.svg" title="'+Lang.wface+'" width="64px">';
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'S.svg" title="'+Lang.sface+'" width="64px">';
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'E.svg" title="'+Lang.eface+'" width="64px">';

        html =html+'<br/>';

        html =html+Lang.altitude(Math.round(data.min),Math.round(data.max))+'<br/>';
        html =html+data.width+'m x '+data.height+'m<br/>';
        html =html+Lang.rotation+': '+data.rotate+'°<br/>';
        html =html+Lang.slices+': '+data.vslices+' x '+data.hslices+'<br/>';
        html =html+Lang.samples+': '+(data.vslices*data.vsamples+data.hslices*data.hsamples)+'<br/>';

        html =html+'</a>';


        var marker = createMarker( html , new google.maps.LatLng(data.lat,data.lng), Georigami.baseurl+'img/ico/'+Georigami.location.icon+'.png', Georigami.baseurl+'img/ico/shadow.png' );

      gridObj=createGrid(map, data, marker);
      bounds.union( gridObj.getBounds() );

    }

    map.fitBounds (bounds);


	}





	//reload cropped svg ( usefull with chrome )


	$(window).load(function(){
		setTimeout(function(){		$('body.savedindex img.profil').width( function(){ return $(this).width()-1; } );		},10	);
		setTimeout(function(){		$('body.savedindex img.profil').width( function(){ return $(this).width()+1; } );		},20	);
	});



});
