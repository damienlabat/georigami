$(function() {

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

			var divnumber= $('<input type="number" value="'+target.val()+'" min="'+obj.min+'" max="'+obj.max+'" step="1" class="'+target.attr('class')+'"></div>').insertAfter(target);
			$('<br/>').insertAfter(target);
			target.removeClass().change(function(){
				divnumber.val( target.val() );
			});

			divnumber.change(function() {	target.val( divnumber.val() );	});
			divnumber.keypress(function() {	setTimeout( function() {target.val( divnumber.val() )},1);	});			

		}
		else // jqueryUI
		{
			obj.type='number';

			var divslider= $('<div class="slider"></div>').insertBefore(target);

			var slider= divslider.slider({ 
				value: 	parseFloat(target.val()),
				min: 	parseFloat(obj.min),
				max: 	parseFloat(obj.max),

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
	Georigami.verticalScale=1;










	// INIT

	if ($('body').hasClass('bloc3d'))      		Georigami.initBloc('3d');
	if ($('body').hasClass('print'))      		Georigami.initBloc('print');
	if ($('body').hasClass('profil'))      		Georigami.initBloc('profil');

	if ($('body').hasClass('location'))      	Georigami.initLocation();
	if ($('body').hasClass('map'))      		Georigami.initMap();
	if ($('body').hasClass('new'))      		Georigami.initNew();


});
