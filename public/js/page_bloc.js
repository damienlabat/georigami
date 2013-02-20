$(function() {


  /*******************/
 /*   initBloc      */
/*******************/

  Georigami.initBloc = function() {

    var view3D= load3D( Georigami.bloc, $('.div3Dview'), $('.vs-input').val() );

    if (Georigami.face=='W') view3D.setRotation(Math.PI/2);
    if (Georigami.face=='N') view3D.setRotation(Math.PI);
    if (Georigami.face=='E') view3D.setRotation(3*Math.PI/2);
    if (Georigami.face=='S') view3D.setRotation(0);

    if (Georigami.face=='do a barrel roll') view3D.setRotation(6*Math.PI/2);
   // $('.vs-input').val(Georigami.verticalScale);

   /* $('.vs-input').change( function(){
        paperBtn.setVerticalScale( $('.vs-input').val() );
        view3D.setVerticalScale( $('.vs-input').val() );
    });*/

  }



});   