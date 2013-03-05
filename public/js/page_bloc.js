$(function() {


  /*******************/
 /*   initBloc      */
/*******************/

  Georigami.initBloc = function(view) {


    var updateParams=function(face,vscale){
      if (face==null)   face=getBlocFace();
      if (vscale==null) vscale=getBlocVScale();
      blocid= $('#blocinfo').data( 'id' );

      var new_url= 'bloc' + blocid + '_' + $('#blocinfo').data( 'view' ) + '?vscale=' + vscale + '&face=' + face ;
      if (Modernizr.history) history.replaceState(null, null, new_url);

      //console.log(face,vscale);

      $('input[name=face]').val(face);

      $('#facepicker a').each(function(){
        $(this).attr('href','?vscale='+vscale+'&face='+ $(this).data('face'));
      });

      $('#bloc-menu a').each(function(){
        var action=$(this).data('action');
        if (action!=null)
          $(this).attr('href', 'bloc' + blocid + '_' + action + '?vscale=' + vscale + '&face=' + face);
      });

      $('#blocinfo img.bloc-face').attr('src',Georigami.baseurl+'/bloc' + blocid + face + '.svg');

    }




    var setBlocFace=function(face) { $('#blocinfo').data( 'face',face );          updateParams( face, null );     }
    var getBlocFace=function() { return $('#blocinfo').data( 'face' ); }
    var setBlocVScale=function(vscale) { $('#blocinfo').data( 'vscale',vscale );  updateParams(  null, vscale );  }
    var getBlocVScale=function() { return $('#blocinfo').data( 'vscale' ); }







/* view == 3D */

    if (view=='3d') {
      var view3D= load3D( Georigami.bloc, $('.div3Dview'), $('.vs-input').val() );

      var viewFace= function(face) {
        if (face=='W') view3D.setRotation(Math.PI/2);
        if (face=='N') view3D.setRotation(Math.PI);
        if (face=='E') view3D.setRotation(3*Math.PI/2);
        if (face=='S') view3D.setRotation(0);
        if (face=='doabarrelroll') view3D.setRotation(6*Math.PI/2);
      }

      viewFace(Georigami.face);
    }



    $('#facepicker a').each(function(){
      $(this).click(function(){
        var face=$(this).data('face');
        viewFace( face );
        setBlocFace( face );
        $('#facepicker a.active').removeClass('active');
        $(this).addClass('active');
        return false
      });
    });


    $('.vs-input').change(function(){
      var vscale=$(this).val();
      view3D.setVerticalScale( vscale );
      setBlocVScale( vscale );
      return false
    });


    $('.vs-update').hide();

    
   // $('.vs-input').val(Georigami.verticalScale);

   /* $('.vs-input').change( function(){
        paperBtn.setVerticalScale( $('.vs-input').val() );
        view3D.setVerticalScale( $('.vs-input').val() );
    });*/

  }



});   