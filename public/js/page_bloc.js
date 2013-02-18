$(function() {


  /*******************/
 /*   initBloc      */
/*******************/

  Georigami.initBloc = function() {

    var view3D= load3D( Georigami.bloc, $('.div3Dview'), Georigami.verticalScale );
    var paperBtn= addDownloadButton( Georigami.bloc ,$('.divPaperBtn'), Georigami.verticalScale ); 

    $('.vs-input').val(Georigami.verticalScale);

    $('.vs-input').change( function(){
        paperBtn.setVerticalScale( $('.vs-input').val() );
        view3D.setVerticalScale( $('.vs-input').val() );
    });

  }



});   