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



	$('.spinner').each( function(k,obj){ 
		if (obj.type!='number') {
			$(this).spinner();

		}
	});
	


  if(typeof Georigami == 'undefined') Georigami={};
Georigami.verticalScale=1;



});
