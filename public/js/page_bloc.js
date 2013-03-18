$(function() {


  /*******************/
 /*   initBloc      */
/*******************/

  Georigami.initBloc = function(view) {


    var updateParams=function(face,vscale){

      if (face===null)   face=getBlocFace();
      if (vscale===null) vscale=getBlocVScale();
      blocid= $('#blocinfo').data( 'id' );

      var new_url= 'bloc' + blocid + '_' + $('#blocinfo').data( 'view' ) + '?vscale=' + vscale + '&face=' + face ;
      if (Modernizr.history) history.replaceState(null, null, new_url);


      $('input[name=face]').val(face);

      $('#facepicker a').each(function(){
        $(this).attr('href','?vscale='+vscale+'&face='+ $(this).data('face'));
      });

      $('#bloc-menu a').each(function(){
        var action=$(this).data('action');
        if (action!==null)
          $(this).attr('href', 'bloc' + blocid + '_' + action + '?vscale=' + vscale + '&face=' + face);
      });

      $('#blocinfo img.bloc-face').attr('src',Georigami.baseurl+'/bloc' + blocid + face + '.svg');

    };




    var setBlocFace=function(face) { $('#blocinfo').data( 'face',face );          updateParams( face, null );     };
    var getBlocFace=function() { return $('#blocinfo').data( 'face' ); };
    var setBlocVScale=function(vscale) { $('#blocinfo').data( 'vscale',vscale );  updateParams(  null, vscale );  };
    var getBlocVScale=function() { return $('#blocinfo').data( 'vscale' ); };

















/* view == 3D */

    if (view=='3d') {

      var view3D= load3D( Georigami.bloc, $('.div3Dview'), $('.vs-input').val() );

     var viewFace= function(face) {
        if (face=='W') view3D.setRotation(Math.PI/2);
        if (face=='N') view3D.setRotation(Math.PI);
        if (face=='E') view3D.setRotation(3*Math.PI/2);
        if (face=='S') view3D.setRotation(0);
        if (face=='doabarrelroll') view3D.setRotation(6*Math.PI/2);
      };

      viewFace( getBlocFace() );


    $('#facepicker a').each(function(){
      $(this).click(function(){
        var face=$(this).data('face');
        viewFace( face );
        setBlocFace( face );
        $('#facepicker a.active').removeClass('active');
        $(this).addClass('active');
        return false;
      });
    });


    $('.vs-input').change(function(){
      var vscale=$(this).val();
      view3D.setVerticalScale( vscale );
      setBlocVScale( vscale );
      return false;
    });


    $('.vs-update').hide();

  }
































/* view == profil */
 if (view=='profil') {

  $('.vs-update').hide();





  var profil=function( svgobj, data, option ) {

    if (option===null)    option={};
    if (option.dx===null) option.dx=0;
    if (option.dy===null) option.dy=0;
    if (option.dscale===null) option.dscale=0;
    if (option.vscale===null) option.vscale=1;

    profilobj=option;

    profilobj.changeStyle= function(newstyle) {
      svgobj.attr('class',newstyle);
    };


    profilobj.setSlicesTransform= function(dx,dy,dscale) {

      var slCount=  svgobj.find('g.gslice').length;
      var x;
      var y;
      var scale;

      var mouseXOnMouseDown;
      var mouseYOnMouseDown;

      profilobj.dx=dx;
      profilobj.dy=dy;
      profilobj.dscale=dscale;

      for (var id = 0; id< slCount; id++) {
        var obj=  svgobj.find('#slice'+id);
        var obj2= obj.find('g.gscale');

        scale= 1- profilobj.dscale * (((slCount-1)-id)/slCount);
        x= (id-slCount/2) * profilobj.dx;
        y= (id-slCount/2) * profilobj.dy;

        obj[0].setAttribute("transform",  "translate("+x+","+y+")");
        obj2[0].setAttribute("transform", "scale("+scale+")");
     }

     updateViewBox();

    };



     profilobj.onDocumentMouseDown= function( event ) {
        event.preventDefault();

        svgobj[0].addEventListener( 'mousemove', profilobj.onDocumentMouseMove, false );
        svgobj[0].addEventListener( 'mouseup', profilobj.onDocumentMouseUp, false );
       // svgobj[0].addEventListener( 'mouseout', profilobj.onDocumentMouseOut, false );

        mouseXOnMouseDown = event.clientX ;
        mouseYOnMouseDown = event.clientY ;

      };

      profilobj.onDocumentMouseMove= function( event ) {

        mouseX = event.clientX ;
        mouseY = event.clientY ;

        profilobj.updateDxDy( (mouseX-mouseXOnMouseDown) /5000, (mouseY-mouseYOnMouseDown) /10000 );

      };

      profilobj.onDocumentMouseUp= function( event ) {

        svgobj[0].removeEventListener( 'mousemove', profilobj.onDocumentMouseMove, false );
        svgobj[0].removeEventListener( 'mouseup', profilobj.onDocumentMouseUp, false );
        svgobj[0].removeEventListener( 'mouseout', profilobj.onDocumentMouseOut, false );

      };

      profilobj.onDocumentMouseOut= function( event ) {

        svgobj[0].removeEventListener( 'mousemove', profilobj.onDocumentMouseMove, false );
        svgobj[0].removeEventListener( 'mouseup', profilobj.onDocumentMouseUp, false );
        svgobj[0].removeEventListener( 'mouseout', profilobj.onDocumentMouseOut, false );

      };

      profilobj.mousewheel= function( event ) {

          event.preventDefault();
          event.stopPropagation();

          var delta = 0;
          if ( event.wheelDelta ) { // WebKit / Opera / Explorer 9
            delta = event.wheelDelta / 40;
          } else if ( event.detail ) { // Firefox
            delta = - event.detail / 3;
          }

          profilobj.updateDScale(delta/100);
      };


      svgobj[0].addEventListener( 'mousedown', profilobj.onDocumentMouseDown , false );
      svgobj[0].addEventListener( 'mousewheel', profilobj.mousewheel, false );
      svgobj[0].addEventListener( 'DOMMouseScroll', profilobj.mousewheel, false ); // firefox




    var updateCoords= function() {

      for (var id =0, len= data.coords.length; id < len; id++) {
        var slObj= svgobj.find('#slice'+id);

        var svg_vscale= Georigami.svg_hscale*profilobj.vscale;
        var coord='';
        for (var idCoord=0, lenCoord=data.coords[id].c.length; idCoord<lenCoord; idCoord++ ) {

          var c=data.coords[id].c[idCoord];
          coord= coord+ ((c[0]-data.dim/2)*Georigami.svg_hscale)+ ',' + (-c[1]*svg_vscale) + ',';
        }

        slObj.find('polyline')[0].setAttribute("points", coord );
        slObj.find('polygon')[0]. setAttribute("points", coord + (data.dim/2)*Georigami.svg_hscale + "," + (0)*svg_vscale + "," + (-(data.dim/2)*Georigami.svg_hscale) + "," + ((0)*svg_vscale) );


      }

      updateViewBox();
    };





    var updateViewBox= function() {
      var viewbox= { 'left':10000, 'right':-10000, 'top':10000, 'bottom':-10000 };
      var svg_vscale= Georigami.svg_hscale*profilobj.vscale;

      for (var id =0, len= data.coords.length; id < len; id++) {

        var SCALE= 1-profilobj.dscale*(((len-1)-id)/len);

        var bottom= (id-len/2) *profilobj.dy + data.max/2*svg_vscale;
        var top=    (id-len/2) *profilobj.dy + data.max/2*svg_vscale - data.coords[id].m*svg_vscale*SCALE;

        var left=   (id-len/2) *profilobj.dx + data.dim/2*Georigami.svg_hscale - data.dim/2*Georigami.svg_hscale*SCALE;
        var right=  (id-len/2) *profilobj.dx + data.dim/2*Georigami.svg_hscale + data.dim/2*Georigami.svg_hscale*SCALE;

        if ( top < viewbox.top )        viewbox.top= top;
        if ( bottom > viewbox.bottom )  viewbox.bottom= bottom;

        if ( left < viewbox.left )      viewbox.left= left;
        if ( right > viewbox.right )    viewbox.right=right;
      }

     //marge 5%
      var width=  viewbox.right - viewbox.left;
      var height= viewbox.bottom - viewbox.top;

      viewbox.left=   viewbox.left  - width*0.05;
      viewbox.right=  viewbox.right + width*0.05;

      viewbox.top=    viewbox.top    - height*0.05;
      viewbox.bottom= viewbox.bottom + height*0.05;



      var VB= viewbox.left + ' ' + viewbox.top + ' ' + (viewbox.right-viewbox.left) + ' ' + (viewbox.bottom-viewbox.top);
      svgobj[0].setAttribute( 'viewBox', VB );

    };




    profilobj.updateVScale= function(value) {
      profilobj.vscale= value;
      updateCoords();
    };

    profilobj.updateDxDy= function(dx,dy) {
      $('#input-translateX').val( $('#input-translateX').val()*1+dx );
      $('#input-translateY').val( $('#input-translateY').val()*1+dy );
      $('#input-translateX').trigger('change');
      $('#input-translateY').trigger('change');
    };

    profilobj.updateDScale= function(dscale) {
      $('#input-scale').val( $('#input-scale').val()*1+dscale );
      $('#input-scale').trigger('change');
    };



    return profilobj;
  }; // fin profil






  var profilObj= profil( $('#svgprofil'), Georigami.profil, {
    'vscale': $('.vs-input').val(),
    'dx':     $('#input-translateX').val(),
    'dy':     $('#input-translateY').val(),
    'dscale': $('#input-scale').val()
  });



  $('#styleswicther').change(     function(){ profilObj.changeStyle( $(this).val() ); updateUrls(); });
  $('#input-translateX').change(  function() { updateSlicesTransform(); });
  $('#input-translateY').change(  function() { updateSlicesTransform(); });
  $('#input-scale').change(       function() { updateSlicesTransform(); });

  $('.vs-input').change(          function() { profilObj.updateVScale( $(this).val() ); updateUrls(); });



var updateSlicesTransform= function() {
  var dX= parseFloat( $('#input-translateX').val() );
  var dY= parseFloat( $('#input-translateY').val() );
  var dscale= parseFloat( $('#input-scale').val() );
  profilObj.setSlicesTransform(dX,dY,dscale);
  updateUrls();
};


var updateUrls=function(){
      var face=   $('#input-face').val();
      var vscale= $('.vs-input').val();
      var dx=     $('#input-translateX').val();
      var dy=     $('#input-translateY').val();
      var dscale= $('#input-scale').val();
      var style=  $('#styleswicther').val();
      var blocid= $('#blocinfo').data( 'id' );

      var new_url= 'bloc' + blocid + '_' + $('#blocinfo').data( 'view' ) + '?vscale=' + vscale + '&face=' + face  + '&dx=' + dx  + '&dy=' + dy  + '&dscale=' + dscale  + '&style=' + style  ;
      if (Modernizr.history) history.replaceState(null, null, new_url);


      $('#facepicker a').each(function(){
        $(this).attr('href','?vscale='+vscale+'&face='+ $(this).data('face')+ '&dx=' + dx  + '&dy=' + dy  + '&dscale=' + dscale  + '&style=' + style );
      });

      $('#bloc-menu a').each(function(){
        var action=$(this).data('action');
        if (action!==null)
          $(this).attr('href', 'bloc' + blocid + '_' + action + '?vscale=' + vscale + '&face=' + face  );
      });

    };



}


}; // end initBloc








});