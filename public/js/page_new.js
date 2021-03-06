$(function() {

  Georigami.status=null;




  var showLoading= function(content, pp) {
      if (((content!=='') && (content!==undefined)) || (pp!==undefined))
          $('#status').slideDown();
      else
          $('#status').slideUp();


      $('#status .text').html(content);
      if (pp!==undefined) {
          if (!$('#status .progress').length) $('<div class="progress progress-striped active"><div class="bar" style="width: '+(pp*100)+'%;"></div></div>').appendTo( $('#status') );
          else $('#status .progress .bar').css('width',(pp*100)+'%');

        }
        else $('#status .progress').remove();

  };


  /*******************/
 /*   initNew       */
/*******************/

  Georigami.initNew = function() {

    var gridObj=null;






 Georigami.setStatus= function (status) {

  if (status=='ready') {
    $('#start-btn').removeClass('disabled');
    $('#cancel-btn').addClass('disabled');
  }
  else
  {
    $('#start-btn').addClass('disabled');
  }

  if (status=='loading')  $('#cancel-btn').removeClass('disabled');

  Georigami.status=status;
 };




Georigami.setStatus(null);


$('#input-search').change( function(){

  var req=$('#input-search').val();

  if (req==='') {
    $('#search-result').html('');
    return false;
  }

  $('#search-result').html('<i class="loading"></i> '+Lang.searching);

  $.ajax({
                url: Georigami.baseurl+"/search",
                type: "POST",
                data: {q:$('#input-search').val()},
                success: function(data){

                 $('#search-result').html('');

                 if (data.geonames.length>0) $('#search-result').html('<ul></ul>');
                  else $('#search-result').html('<div class="alert alert-error">'+Lang.noresultfor+$('#input-search').val()+'</div>'); //TODO

                 for (var i = 0; i< data.geonames.length; i++) {
                    var html='<li><a href="#" data-lat="'+data.geonames[i].lat+'" data-lng="'+data.geonames[i].lng+'">';
                    if (data.geonames[i].countryCode!=undefined) html=html+'<img src="'+Georigami.baseurl+'img/flags/'+ data.geonames[i].countryCode.toLowerCase()+'.png" title="'+data.geonames[i].countryName+'"/> ';
                    html=html+data.geonames[i].name;
                    if ((data.geonames[i].adminName1!=undefined)&&(data.geonames[i].adminName1!='')) html=html+' ('+data.geonames[i].adminName1+')';
                    html=html+'</a></li>';

                    $(html).appendTo( $('#search-result>ul') );
                 }

                 $('#search-result a').on("click", function(){

                    $('#input-latitude').val(  $(this).data("lat") );
                    $('#input-longitude').val( $(this).data("lng") );

                    Georigami.update();

                    return false;
                  });


                }
              });
  return false;
});





    var slices=[];
    var slicesData=[];
    var cadre=null;
    var requestDelay=4000; // best for 5000

    Georigami.results=[];

    //initmap
    var mapOptions = {
        center: new google.maps.LatLng (Georigami.area.lat , Georigami.area.lng ),
        zoom: 2,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);





    var updateForm= function() {
      $('#input-latitude').val(  Georigami.rectangle.lat );
      $('#input-longitude').val( Georigami.rectangle.lng );
      $('#input-width').val(  Georigami.rectangle.width );
      $('#input-height').val( Georigami.rectangle.height );
      drawSlices(  getParams()  );


    };




    var getParams= function() {
      var res={};
      res.lat= parseFloat( $('#input-latitude').val() );
      res.lng= parseFloat( $('#input-longitude').val() );
      res.width= parseFloat( $('#input-width').val() );
      res.height= parseFloat( $('#input-height').val() );
      res.vSlices= parseFloat( $('#input-vertical-slices').val() );
      res.hSlices= parseFloat( $('#input-horizontal-slices').val() );
      res.vSampling= parseFloat( $('#input-vsampling').val() );
      res.hSampling= parseFloat( $('#input-hsampling').val() );
      res.rotate= parseFloat( $('#input-rotate').val() );

      res.vSamples= res.vSampling* (res.hSlices+1) + 1;
      $('#input-vertical-samples').val(res.vSamples);

      res.hSamples= res.hSampling* (res.vSlices+1) + 1;
      $('#input-horizontal-samples').val(res.hSamples);


      res.bbox= Georigami.rectangle.getBounds(true);

       if ((Georigami.rectangle.lat!==0)||(Georigami.rectangle.lng!==0)) {
          if (Georigami.status!='loading') Georigami.setStatus('ready');
       }

      return res;
    };



    var initParams= function(params) {
      $('#input-latitude').val( params.lat );
      $('#input-longitude').val( params.lng );
      $('#input-width').val( params.width );
      $('#input-height').val( params.height );
      $('#input-vertical-slices').val( params.vSlices );
      $('#input-horizontal-slices').val( params.hSlices );
      $('#input-hsampling').val( params.hsampling );
      $('#input-vsampling').val( params.vsampling );
      $('#input-rotate').val( params.rotate );
      $('input').trigger('change');
    };





  var drawSlices= function(params) {
    if (gridObj!==null) gridObj.clear();
    gridObj= grid(map,params.lat,params.lng,params.width,params.height,params.vSlices,params.hSlices,params.rotate);
  };

  Georigami.update= function() {
        Georigami.rectangle.updateCenter( $('#input-latitude').val() , $('#input-longitude').val() );
        Georigami.rectangle.updateDimensions( $('#input-width').val() , $('#input-height').val() );
        drawSlices( getParams() );
        map.fitBounds(Georigami.rectangle.getBounds());
    };



    //init params
    initParams(Georigami.area);

    Georigami.rectangle= draggableRectangle(map, Georigami.area.lat , Georigami.area.lng, Georigami.area.width, Georigami.area.height, {
        onChange: updateForm
      });

    updateForm();
    if ((Georigami.area.lat!==0)||(Georigami.area.lng!==0)) Georigami.update();






    $('.livechange').keypress( function(){    setTimeout('Georigami.update()',1);    });
    $('.livechange').mouseup( function(){     Georigami.update();    });
    $('.livechange').change( function(){     Georigami.update();    });


    $('#update-btn').mousedown( function(){
        Georigami.update();
        return false;
    });

    $('#paramform').submit( function(){
        return false;
    });






    var buildSlices= function(result) {

      var data=result.params;

      data.vSlicesObj=[];
      data.hSlicesObj=[];
      data.min= Infinity;
      data.max= -Infinity;
      var x,y,z;
      var maxDim= Math.max(data.width,data.height);

      // get min and max
      for (var i = 0; i< result.slices.length; i++)
        for (var n = 0; n< result.slices[i].data.length; n++) {
          z=result.slices[i].data[n].elevation;
          if (z<data.min) data.min=z;
          if (z>data.max) data.max=z;
        }

      for ( i = 0; i< result.slices.length; i++) {
        var s=result.slices[i];
        var sCoords=[];
        for (var n = 0; n< s.data.length; n++) {
          z=s.data[n].elevation;
          if (s.type=='vertical')
            x= n/(s.data.length-1)*data.height/maxDim;
          else
            x= n/(s.data.length-1)*data.width/maxDim;
          y=(z-data.min)/maxDim;
          sCoords.push([x,y]);
        }

        if (s.type=='vertical') {
          data.vSlicesObj.push(sCoords);
        }
        else {
          data.hSlicesObj.push(sCoords);
        }

      }

      data.vSlicesObj= data.vSlicesObj.reverse();

      return data;

    };





    Georigami.loadSlice= function(result,i) {
      var slice=result.slices[i];
      var pathstr='';
      var samples;
      showLoading( Lang.loadingslice((i+1),result.slices.length), (i+1)/result.slices.length );


      var path=[];
      $.each( slice.path, function(i2, pt) {
            if (pathstr!=='') pathstr=pathstr+'|';
            pathstr=pathstr+pt.lat()+','+pt.lng();
            path.push( new google.maps.LatLng( pt.lat() , pt.lng() ) );
        });

      if (slice.type=='vertical') samples=result.params.vSamples;
        else samples=result.params.hSamples;


       var pathRequest={
            'path': path,
            'samples': samples
          };

      if (Georigami.status!='cancel') {
        elevator = new google.maps.ElevationService();
        elevator.getElevationAlongPath(pathRequest, plotElevation);
      }
      else {
        showLoading('');
        Georigami.setStatus('ready');
      }

       function plotElevation(results, status) {
           if (status!='OK') {
            Georigami.alert('GOOGLE ELEVATION API ERROR',status );
            showLoading('');
            Georigami.setStatus('ready');
            return;
          }

          slice.data=results;

          if (i+1<result.slices.length) setTimeout(function(){ Georigami.loadSlice(result,i+1);}, requestDelay*(samples/500)  ); // too be kind with google api
            else {

              post=buildSlices(result);
              post.coords=JSON.stringify( {'v':post.vSlicesObj, 'h':post.hSlicesObj } );
              delete post.vSlicesObj;
              delete post.hSlicesObj;

              showLoading( Lang.loading );
              $.ajax({
                url: Georigami.baseurl+Georigami.lang+'/new',
                type: "POST",
                data: post})
                .done(function(data){
                  var visu= visuSlice( Georigami.results.length+1, data, $('#resultats') );
                  Georigami.results.push( { data:data } );
                  showLoading();

                  })
                .fail(function(jqXHR, textStatus) {
                  console.log(jqXHR);
                  showLoading( '<h1 style="color:red">'+jqXHR.statusText+'</h1><div style="text-align:left">'+jqXHR.responseText+'</div>');
                });

                

              }
       };

    };






    $('#start-btn').click( function(){
      if (Georigami.status=='ready') {
          var data={params:getParams(),slices:gridObj.getData() };
          startWork( data );
          Georigami.setStatus('loading');
        }
        else
          if (Georigami.status=='loading') Georigami.alert('Please wait', 'Work in progress');
            else Georigami.alert('but where ?', 'Please select an area');
        return false;
     });


    $('#cancel-btn').click( function(){
      if (Georigami.status=='loading') {
        Georigami.setStatus('cancel');
      }
    });





var startWork= function(data) {
    $('.div3Dview').hide();
    Georigami.setStatus('loading');
    Georigami.loadSlice(data,0);
}







}; //fin init

  /********************/
  /*     visuSlice    */
  /********************/



  var visuSlice= function(id,data,obj) {
    Georigami.setStatus('ready');

    var html='<div class="result">'+
      '<p class="index">result '+id+'</p>'+
      '<a href="'+data.location.url+'"><img src="'+Georigami.baseurl+'img/flags/'+data.location.countrycode.toLowerCase()+'.png" title="'+data.location.countryname+'"/> <span class="countryname">'+data.location.countryname+'</span><br/>'+data.location.name+'</a></td></tr>'+
      '<div class="row">'+
        '<a href="'+data.url+'">'+
          '<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'N.svg" class="span2" title="'+Lang.nface+'"/>'+
          '<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'E.svg" class="span2" title="'+Lang.eface+'"/>'+
          '<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'S.svg" class="span2" title="'+Lang.sface+'"/>'+
          '<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'W.svg" class="span2" title="'+Lang.wface+'"/>'+
          '<div class="span2">'+
          Lang.altitude(Math.round(data.min),Math.round(data.max))+'<br/>'+
          data.width+'m x '+data.height+'m<br/>'+
          Lang.rotation+': '+data.rotate+'°<br/>'+
          Lang.slices+': '+data.vslices+' x '+data.hslices+'<br/>'+
          Lang.samples+': '+(data.vslices*data.vsamples+data.hslices*data.hsamples)+'<br/>'+
            data.created_at_localized+
          '</div>'+
        '</a>'+
      "</div>"+
      //'<div class="div3Dview"></div>'+
      '</div>';



    var bloc=$(html).prependTo(obj);
    //var view3D= load3D( data, bloc.find('.div3Dview'), Georigami.verticalScale );

  };



});