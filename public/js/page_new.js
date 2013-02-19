$(function() {


  /*******************/
 /*   initNew       */
/*******************/

  Georigami.initNew = function() {

    var gridObj=null;





$('#input-search').change( function(){

  var req=$('#input-search').val();

  if (req=='') {
    $('#search-result').html('');
    return false;
  }

  $('#search-result').html('searching ...');

  $.ajax({
                url: Georigami.baseurl+"/search",
                type: "POST",
                data: {q:$('#input-search').val()},
                success: function(data){

                 $('#search-result').html('');

                 if (data.geonames.length>0) $('#search-result').html('<ul></ul>');
                  else $('#search-result').html('<span style="color:red">no results</span>'); //TODO

                 for (var i = 0; i< data.geonames.length; i++) {
                    var html='<li><a href="#" data-lat="'+data.geonames[i].lat+'" data-lng="'+data.geonames[i].lng+'">';
                    if (data.geonames[i].country!='') html=html+'<img src="'+Georigami.baseurl+'/img/flags/'+ data.geonames[i].countryCode.toLowerCase()+'.png" title="'+data.geonames[i].country+'"/> ';
                    html=html+data.geonames[i].name;
                    if (data.geonames[i].feature!='') html=html+' ('+data.geonames[i].feature[0]+')';
                    html=html+'</a></li>';

                    $(html).appendTo( $('#search-result>ul') );
                 };

                 $('#search-result a').on("click", function(){                    
                  
                    $('#input-latitude').val(  $(this).data("lat") );
                    $('#input-longitude').val( $(this).data("lng") );

                    Georigami.update();

                    return false
                  });


                },
              });
  return false
});





    var slices=[];
    var slicesData=[];
    var cadre=null;
    var requestDelay=5000;

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
    }




    var getParams= function() {
      var res={};
      res.lat= parseFloat( $('#input-latitude').val() );
      res.lng= parseFloat( $('#input-longitude').val() );
      res.width= parseFloat( $('#input-width').val() );
      res.height= parseFloat( $('#input-height').val() );
      res.vSlices= parseFloat( $('#input-vertical-slices').val() );
      res.hSlices= parseFloat( $('#input-horizontal-slices').val() );
      res.sampling= parseFloat( $('#input-sampling').val() );

      res.vSamples= res.sampling* (res.hSlices+2);
      $('#input-vertical-samples').val(res.vSamples);

      res.hSamples= res.sampling* (res.vSlices+2);
      $('#input-horizontal-samples').val(res.hSamples);

     /* res.vSamples= parseFloat( $('#input-vertical-samples').val() );
      res.hSamples= parseFloat( $('#input-horizontal-samples').val() );*/

      res.bbox= Georigami.rectangle.getBounds(true);
      
      $('#request-count').html((res.vSlices+res.hSlices) + ' request (max by day: 2 500)');
      $('#location-count').html((res.vSlices*res.vSamples+res.hSlices*res.hSamples) + ' points (max by day: 25 000)');

      return res;
    }



    var initParams= function(params) {
      $('#input-latitude').val( params.lat );
      $('#input-longitude').val( params.lng );
      $('#input-width').val( params.width );
      $('#input-height').val( params.height );
      $('#input-vertical-slices').val( params.vSlices );
      $('#input-horizontal-slices').val( params.hSlices );
      $('#input-sampling').val( params.sampling );
    }





  var drawSlices= function(params) {
    if (gridObj!=null) gridObj.clear();
    gridObj= grid(map,params.lat,params.lng,params.width,params.height,params.vSlices,params.hSlices,null);
  }

  Georigami.update= function() {
        Georigami.rectangle.updateCenter( $('#input-latitude').val() , $('#input-longitude').val() );
        Georigami.rectangle.updateDimensions( $('#input-width').val() , $('#input-height').val() );
        drawSlices( getParams() );
        map.fitBounds(Georigami.rectangle.getBounds());       
    }



    //init params
    initParams(Georigami.area);

    Georigami.rectangle= draggableRectangle(map, Georigami.area.lat , Georigami.area.lng, Georigami.area.width, Georigami.area.height, { 
        onChange: updateForm 
      });

    updateForm();
    if ((Georigami.area.lat!=0)||(Georigami.area.lng!=0)) Georigami.update();


    

    //map.fitBounds(Georigami.rectangle.getBounds());


    $('input.livechange').keypress( function(){
        setTimeout('Georigami.update()',1);;
    });
    $('input.livechange').mousedown( function(){
        setTimeout('Georigami.update()',1);;
    });

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

      for (var i = 0; i< result.slices.length; i++) {
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
          var n=data.vSlicesObj.length;
          data.vSlicesObj.push(sCoords);          
        }
        else {
          var n=data.hSlicesObj.length;
          data.hSlicesObj.push(sCoords);
        }

      }

      data.vSlicesObj= data.vSlicesObj.reverse();

      return data;
      
    }





    Georigami.loadSlice= function(result,i) {
      var slice=result.slices[i];
      var pathstr='';
      var samples;
      showLoading( 'loading slice '+(i+1)+'/'+result.slices.length, (i+1)/result.slices.length );

      var path=[];
      $.each( slice.path, function(i2, pt) {
            if (pathstr!='') pathstr=pathstr+'|';
            pathstr=pathstr+pt.lat()+','+pt.lng();
            path.push( new google.maps.LatLng( pt.lat() , pt.lng() ) );
        });

      if (slice.type=='vertical') samples=result.params.vSamples;
        else samples=result.params.hSamples;


       var pathRequest={
            'path': path,
            'samples': samples
          }

      elevator = new google.maps.ElevationService();
      elevator.getElevationAlongPath(pathRequest, plotElevation);




       function plotElevation(results, status) {
           if (status!='OK') {
            alert('GOOGLE ELEVATION API ERROR status: '+status)
            return
          }

          slice.data=results;
          console.log("delay: "+requestDelay*(samples/500)+'ms');
          if (i+1<result.slices.length) setTimeout(function(){ Georigami.loadSlice(result,i+1) }, requestDelay*(samples/500)  ); // too be kind with google api
            else {
             // showLoading( 'building geom' );

              post=buildSlices(result);
              post.coords=JSON.stringify( {'v':post.vSlicesObj, 'h':post.hSlicesObj } );
              delete post.vSlicesObj;
              delete post.hSlicesObj;


              $.ajax({
                url: Georigami.baseurl+'/new',
                type: "POST",
                data: post,
                success: function(data){

                  showLoading( 'show result' );
                  var visu= visuSlice( Georigami.results.length+1, data, $('#resultats') );
                  Georigami.results.push( { data:data, view3D:visu.view3D, paperBtn:visu.paperBtn } );  
                  showLoading( '' );

                  },
                });
              
              }
       }


    }



    $('#start-btn').click( function(){
        var data={params:getParams(),slices:gridObj.getData() };
        startWork( data );        
        return false;
     });


  var showLoading= function(content, pp) {
      $('#status .text').html(content);
      if (pp!=null) {
          if (!$('#status .progress').length) $('<div class="progress"><div class="bar" style="width: '+(pp*100)+'%;"></div></div>').appendTo( $('#status') );      
          else $('#status .progress .bar').css('width',(pp*100)+'%');
        }
        else $('#status .progress').remove();

  }


var startWork= function(data) {
    Georigami.loadSlice(data,0);
} // fin init NEW





    

} //fin init

  /********************/
  /*     visuSlice    */
  /********************/



  var visuSlice= function(id,data,obj) {

    
    var html='<div class="result">'+
      '<p class="index">result '+id+'</p>'+
      '<div class="row">'+
      '<div class="span8 resultcontent"><div class="div3Dview"></div><div class="divPaperBtn"></div></div>'+
      '<div class="span3">'+
      '<table class="table">'+             
        '<tbody>' +
        '<tr> <td>place</td><td class="placename"><a href="'+Georigami.baseurl+'/location'+data.location.id+'"><img src="'+Georigami.baseurl+'/img/flags/'+data.location.countrycode.toLowerCase()+'.png" title="'+data.location.countryname+'"/> '+data.location.countryname+'<br/>'+data.location.name+'</a></td></tr>'+
        '<tr> <td>latitude</td><td>'+data.lat+'</td></tr>'+
        '<tr> <td>longitude</td><td>'+data.lng+'</td></tr>'+
        '<tr> <td>altitude</td><td>'+Math.round(data.min)+'m to '+Math.round(data.max)+'m</td></tr>'+
        '<tr> <td>width</td><td>'+data.width+'m</td></tr>'+
        '<tr> <td>height</td><td>'+data.height+'m</td></tr>'+
        '<tr> <td>vSlices</td><td>'+data.vslices+'</td></tr>'+
        '<tr> <td>hSlices</td><td>'+data.hslices+'</td></tr>'+
        '<tr> <td>vertical scale</td><td><input class="vs-input span1" type="number" step="0.1" min="0.1" value="'+Georigami.verticalScale+'"></td></tr>'+
        '</tbody>'+
            
      '</table>'+
      '</div>'+
      '</div>'+
      '</div>';

    var bloc=$(html).prependTo(obj);         

    var view3D= load3D( data, bloc.find('.div3Dview'), Georigami.verticalScale );
    var paperBtn= addDownloadButton( data ,bloc.find('.divPaperBtn'), Georigami.verticalScale );   

    bloc.find('.vs-input').change( function(){
        paperBtn.setVerticalScale( bloc.find('.vs-input').val() );
        view3D.setVerticalScale( bloc.find('.vs-input').val() );
    });
    

    return {view3D:view3D, paperBtn:paperBtn};
  }



});  