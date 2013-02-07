$(function() {




  draggableRectangle= function( map, lat, lng, width, height, options ) {

    var DR={ lat: lat, lng: lng, width: width, height: height };
    var center= new google.maps.LatLng( lat , lng );
    var updateSizeFromRect=true;
    var changeFunction= function(){};
    if (options.onChange!==null) changeFunction=options.onChange;

    

    DR.getBounds= function(returnStr) {
        if (returnStr==null) returnStr=false;
        var e= google.maps.geometry.spherical.computeOffset(center, DR.width/2, 90);
        var s= google.maps.geometry.spherical.computeOffset(center, DR.height/2, 180);
        var n= google.maps.geometry.spherical.computeOffset(center, DR.height/2, 0);
        var w= google.maps.geometry.spherical.computeOffset(center, DR.width/2, -90);
        
        if (returnStr) return w.lng() + ',' + s.lat() + ',' + e.lng() + ',' + n.lat();
          else return new google.maps.LatLngBounds( new google.maps.LatLng( n.lat() , w.lng() ), new google.maps.LatLng( s.lat() , e.lng() ) );
    }
  

    DR.updateCenter=function(lat, lng) {
        updateSizeFromRect=false;
        center= new google.maps.LatLng( lat , lng );
        DR.lat=lat;
        DR.lng=lng;
        DR.bounds=DR.getBounds();
        rectangle.setOptions({bounds: DR.bounds });

        marker.setPosition(center);        
    }

    DR.updateDimensions=function(width, height) {
        updateSizeFromRect=false;
        DR.width=width;
        DR.height=height;
        DR.bounds=DR.getBounds();
        rectangle.setOptions({bounds: DR.bounds });       
    }


    if (options==null) options={};
    if (options.strokeColor==null)   options.strokeColor="#000000";
    if (options.strokeOpacity==null) options.strokeOpacity=0;
    if (options.strokeWeight==null)  options.strokeWeight=2;
    if (options.fillColor==null)     options.fillColor="#000000";
    if (options.fillOpacity==null)   options.fillOpacity=0;
    options.editable= true;
    DR.bounds=DR.getBounds();
    options.bounds= DR.bounds;

    var rectangle = new google.maps.Rectangle(options);
    rectangle.setMap(map);

    var icon = new google.maps.MarkerImage("./img/hang.png", null, null, new google.maps.Point(8, 8));
    var marker = new google.maps.Marker({ map: map, position: center, draggable: true, icon: icon, raiseOnDrag:false, zIndex: 10002});
    marker.setAnimation(null);
    
    google.maps.event.addListener(marker, 'drag', function(event) {
          DR.updateCenter( event.latLng.lat() , event.latLng.lng() );
          changeFunction();
        });

    google.maps.event.addListener(rectangle, 'bounds_changed', function() {
      DR.bounds= rectangle.getBounds();
      center= DR.bounds.getCenter();
      DR.lat= center.lat();
      DR.lng= center.lng();
      marker.setPosition(center);  

      if (updateSizeFromRect) {
        var ne= DR.bounds.getNorthEast();
        var sw= DR.bounds.getSouthWest();
        DR.height=  Math.round( google.maps.geometry.spherical.computeDistanceBetween( new google.maps.LatLng( ne.lat() , DR.lng ) , new google.maps.LatLng( sw.lat() , DR.lng ) ));
        DR.width=   Math.round( google.maps.geometry.spherical.computeDistanceBetween( new google.maps.LatLng( DR.lat , ne.lng() ) , new google.maps.LatLng( DR.lat , sw.lng() ) ));
        changeFunction();
      }
      else updateSizeFromRect=true;                
    });
        
    return DR
  };



$('#input-search').change( function(){

  $.ajax({
                url: "search",
                type: "POST",
                data: {q:$('#input-search').val()},
                success: function(data){

                 $('#search-result').html('');

                 if (data.geonames.length>0) $('#search-result').html('<ul></ul>');
                  else $('#search-result').html('<span style="color:red">no results</span>'); //TODO

                 for (var i = 0; i< data.geonames.length; i++) {
                    var html='<li><a href="#" data-lat="'+data.geonames[i].lat+'" data-lng="'+data.geonames[i].lng+'">';
                    if (data.geonames[i].country!='') html=html+'<img src="./img/flags/'+ data.geonames[i].countryCode.toLowerCase()+'.png" title="'+data.geonames[i].country+'"/> ';
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



















  if(typeof Georigami == 'undefined') Georigami={};

Georigami.verticalScale=1;


/*******************/
 /*   initIndex    */
/*******************/

  Georigami.initIndex = function() {

  }



  /*******************/
 /*   initMap       */
/*******************/

  Georigami.initMap = function() {

     var infowindow;
     //initmap
    var mapOptions = {
        center: new google.maps.LatLng (0 , 0 ),
        zoom: 2,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);  

    var markers = [];
    for (var i = 0; i < Georigami.location_list.length; i++) {
      var data=Georigami.location_list[i];
      var latLng = new google.maps.LatLng( data.lat,data.lng );

      var html='<h3 title="'+data.feature+'">'+data.name+'</h3><img src="./img/flags/'+data.countrycode.toLowerCase()+'.png" title="'+data.countryname+'"/> '+data.countryname+'<br/><br/>';
      for (var j = 0; j < data.blocs.length; j++) {
        var bloc=data.blocs[j];
        html =html+'<a href="./location'+data.id+'/'+(j+1)+'"><div>';
        html =html+'<img src="./bloc'+bloc.id+'N.svg" title="North" width="100px">';
        html =html+'<img src="./bloc'+bloc.id+'W.svg" title="West" width="100px">';
        html =html+'<img src="./bloc'+bloc.id+'S.svg" title="South" width="100px">';
        html =html+'<img src="./bloc'+bloc.id+'E.svg" title="Est" width="100px">';
        html =html+'</div>';
        html =html+'</a>';
        };
       // TODO
         
      var marker = createMarker( html , new google.maps.LatLng(data.lat,data.lng) );

      markers.push(marker);
    }


    var mcOptions = {gridSize: 50, maxZoom: 15}; 
    var mapCluster = new MarkerClusterer(map, markers, mcOptions); 

    
function createMarker(name, latlng) {
    var marker = new google.maps.Marker({position: latlng, map: map});
    google.maps.event.addListener(marker, "click", function() {
      if (infowindow) infowindow.close();
      infowindow = new google.maps.InfoWindow({content: name});
      infowindow.open(map, marker);
    });
    return marker;
  }



  }





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




  /*******************/
 /*   initNew       */
/*******************/

  Georigami.initNew = function() {

    var slices=[];
    var slicesData=[];
    var cadre=null;

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
      res.vSamples= parseFloat( $('#input-vertical-samples').val() );
      res.hSamples= parseFloat( $('#input-horizontal-samples').val() );
      res.bbox= Georigami.rectangle.getBounds(true);
      return res;
    }



    var initParams= function(params) {
      $('#input-latitude').val( params.lat );
      $('#input-longitude').val( params.lng );
      $('#input-width').val( params.width );
      $('#input-height').val( params.height );
      $('#input-vertical-slices').val( params.vSlices );
      $('#input-horizontal-slices').val( params.hSlices );
      $('#input-vertical-samples').val( params.vSamples );
      $('#input-horizontal-samples').val( params.hSamples );
    }





    var drawSlices= function(params) {

        var center= new google.maps.LatLng( params.lat , params.lng );

        //clear
        if (cadre!=null) cadre.setMap(null);
        for (var i = slices.length - 1; i >= 0; i--) {
            slices[i].setMap(null);
          };
        slices.length = 0;
        slicesData.length = 0;

        var NE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, params.height/2, 0),   params.width/2,90); // NE
        var SE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, params.height/2, 180), params.width/2,90); // SE
        var NW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, params.height/2, 0),   params.width/2,-90); // NW
        var SW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, params.height/2, 180), params.width/2,-90); // SW

        cadre = new google.maps.Polygon({
              path: [NE,SE,SW,NW],
              strokeColor: "#000000",
              strokeOpacity: 1.0,
              strokeWeight: 0.25,   
              fillColor: "#FFFFFF",
              geodesic: true,
              zIndex: 10001
            });

        cadre.setMap(map);   

                 
        var nbL= params.vSlices;
        for (var i = 1; i<=nbL; i++) {
           var lineCoord = [
              google.maps.geometry.spherical.interpolate(NW, NE, i/(nbL+1) ),
              google.maps.geometry.spherical.interpolate(SW, SE, i/(nbL+1) )
            ]

            var slicePath = new google.maps.Polyline({
              path: lineCoord,
              strokeColor: "#FF0000",
              strokeOpacity: 1.0,
              strokeWeight: 1,
              geodesic: true,
              zIndex: 10001
            });

            slicePath.setMap(map);   
            slices.push(slicePath);
            slicesData.push({
              type: 'vertical',
              index: i,
              path: lineCoord
              
            });

           }


        nbL= params.hSlices;
        for (var i = 1; i<=nbL; i++) {
           var lineCoord = [
              google.maps.geometry.spherical.interpolate(NW, SW, i/(nbL+1) ),
              google.maps.geometry.spherical.interpolate(NE, SE, i/(nbL+1) )
            ]

            var slicePath = new google.maps.Polyline({
              path: lineCoord,
              strokeColor: "#FF0000",
              strokeOpacity: 1.0,
              strokeWeight: 1,
              geodesic: true,
              zIndex: 10001
            });

            slicePath.setMap(map);   
            slices.push(slicePath);
            slicesData.push({
              type: 'horizontal',
              index: i,
              path: lineCoord              
            });

           }
  }



    //init params
    initParams(Georigami.area);

    Georigami.rectangle= draggableRectangle(map, Georigami.area.lat , Georigami.area.lng, Georigami.area.width, Georigami.area.height, { 
        onChange: updateForm 
      });

    updateForm();

    

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


    Georigami.update= function() {
        Georigami.rectangle.updateCenter( $('#input-latitude').val() , $('#input-longitude').val() );
        Georigami.rectangle.updateDimensions( $('#input-width').val() , $('#input-height').val() );
        drawSlices( getParams() );
        map.fitBounds(Georigami.rectangle.getBounds());       
    }



    var buildSlices= function(result) {     

      var data=result.params;
      //console.log(result);
      data.vSlicesObj=[];
      data.hSlicesObj=[];
      data.min= Infinity;
      data.max= -Infinity;
      var x,y,z;
      var maxDim= Math.max(data.width,data.height); 

      // get min and max
      for (var i = 0; i< result.slices.length; i++)
        for (var n = 0; n< result.slices[i].data.results.length; n++) {          
          z=result.slices[i].data.results[n].elevation;
          if (z<data.min) data.min=z;
          if (z>data.max) data.max=z;
        }

      for (var i = 0; i< result.slices.length; i++) {
        var s=result.slices[i];
        var sCoords=[];        
        for (var n = 0; n< s.data.results.length; n++) { 
          z=s.data.results[n].elevation;
          if (s.type=='vertical') 
            x= n/(s.data.results.length-1)*data.height/maxDim;
          else
            x= n/(s.data.results.length-1)*data.width/maxDim;
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




    var loadSlice= function(result,i) {
      var slice=result.slices[i];
      var pathstr='';
      var samples;
      showLoading( 'loading slice '+(i+1)+'/'+result.slices.length, (i+1)/result.slices.length );

      $.each( slice.path, function(i2, pt) {
            if (pathstr!='') pathstr=pathstr+'|';
            pathstr=pathstr+pt.lat()+','+pt.lng();
        });
      if (slice.type=='vertical') samples=result.params.vSamples;
        else samples=result.params.hSamples;
      var url='http://maps.googleapis.com/maps/api/elevation/json?path='+pathstr+'&samples='+samples+'&sensor=false';
      console.log(result);
      // var url='ex2.json';
      $.getJSON(url, function(data) {
          if (data.status!='OK') {
            alert('ERROR status: '+data.status)
            return
          }
          slice.data=data;
          if (i+1<result.slices.length) loadSlice(result,i+1);
            else {
             // showLoading( 'building geom' );
              post=buildSlices(result);
              post.coords=JSON.stringify( {'v':post.vSlicesObj, 'h':post.hSlicesObj } );
              delete post.vSlicesObj;
              delete post.hSlicesObj;


              $.ajax({
                type: "POST",
                data: post,
                success: function(data){

                  console.log(data);
                  showLoading( 'show result' );
                  var visu= visuSlice( Georigami.results.length+1, data, $('#resultats') );
                  Georigami.results.push( { data:data, view3D:visu.view3D, paperBtn:visu.paperBtn } );  
                  showLoading( '' );

                  },
                });
              

              

            }
      });
    }



    $('#start-btn').click( function(){
        var data={params:getParams(),slices:slicesData};
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
    loadSlice(data,0);
} // fin init NEW





    

} //fin init

  /********************/
  /*     visuSlice    */
  /********************/



  var visuSlice= function(id,data,obj) {
    
    var html='<div class="result">'+
      '<p class="index">result '+id+'</p>'+
      '<div class="row">'+
      '<div class="span9 resultcontent"><div class="div3Dview"></div><div class="divPaperBtn"></div></div>'+
      '<div class="span2">'+
      '<table class="table">'+             
        '<thead><tr> <th>params</th><th></th></tr></thead>'+
        '<tbody>' +
        '<tr> <td>place</td><td class="placename"></td></tr>'+
        '<tr> <td>latitude</td><td>'+data.lat+'</td></tr>'+
        '<tr> <td>longitude</td><td>'+data.lng+'</td></tr>'+
        '<tr> <td>width</td><td>'+data.width+'</td></tr>'+
        '<tr> <td>height</td><td>'+data.height+'</td></tr>'+
        '<tr> <td>vSlices</td><td>'+data.vSlices+'</td></tr>'+
        '<tr> <td>hSlices</td><td>'+data.hSlices+'</td></tr>'+
        '<tr> <td>vSamples</td><td>'+data.vSamples+'</td></tr>'+
        '<tr> <td>hSamples</td><td>'+data.hSamples+'</td></tr></tbody>'+
        '<tr> <td>vertical scale</td><td><input class="vs-input span1" type="number" step="0.1" min="0.1" value="'+Georigami.verticalScale+'"></td></tr></tbody>'+
            
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

      /*************************/
     /*                       */
    /*        PAPER          */
   /*                       */
  /*************************/
  function addDownloadButton(data,obj,vs) {

    var btn={verticalScale:vs};
    btn.button=$('<button class="btn btn-small btn-primary" type="button">print paper</button>').appendTo( obj );

    btn.setVerticalScale= function(vs) { btn.verticalScale=vs; }

    btn.button.click( function(){
      buildPaper(data,btn.verticalScale);
      return false;
    });  

    return btn;

  }


  var buildPaper= function(data, verticalScale) {

    var tiles=[];

    var coef=500;
    
    var html = "<html>"
      html += "<head><title>Images</title><style type='text/css'>";
      html += "img{margin:10px} body{color: #333333;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 14px;line-height: 20px;}";
      html += "</style></head>";
      html += "<body>";
      html += '<h1>intro</h1>';
      html += '<h2>horizontal</h2>';
      for (var i = 0; i< data.coords.h.length; i++) html +=    "<img src='" + getImage( data.coords.h[i], coef, verticalScale ) + "' title='" + data.coords.h[i].t + "'/>";
      html += '<h2>vertical</h2>';
      for (var i = data.coords.v.length-1; i>=0 ; i--) html += "<img src='" + getImage( data.coords.v[i], coef, verticalScale ) + "' title='" + data.coords.v[i].t + "'/>";

      html += "</body></html>";   

    var blob = new Blob([html], {type: 'text/html'}); 
    window.open( window.URL.createObjectURL( blob ));
  }



  var getImage=function( slice,coef,verticalScale) {
   
    var W=Math.round( coef );
    var H=Math.round( slice.m*coef*verticalScale+ 0.1*coef )+5;

    var canvas=  $( '<canvas width="'+W+'" height="'+H+'"/>');
    var c=canvas[0];
    var ctx=c.getContext("2d");
    var x;
    var y;
    ctx.beginPath();
    ctx.strokeStyle="#000000";  
    ctx.lineWidth   = 1;  
    ctx.moveTo(0,H);
    for (i=0; i<slice.c.length; i++) {      
      x=slice.c[i][0]*coef;
      y=H- slice.c[i][1]*coef*verticalScale - 0.1*coef;
      ctx.lineTo(x,y);          
    }

    ctx.lineTo(x,H);        
    ctx.lineTo(0,H);
    ctx.stroke();


    ctx.font="7px Arial";
    ctx.fillText(slice.t, 10, H-10);


    var b64= c.toDataURL('image/png');  

    return b64;
  }






      /*************************/
     /*                       */
    /*      THREE JS         */
   /*                       */
  /*************************/
  function load3D(data,container,verticalScale) {

    var model3Dobj= {data:data.coords, verticalScale:verticalScale};
    var maxDim= Math.max(data.width,data.height);
    
    var container,W,H;

    var camera, scene, renderer, dirLight, hemiLight, parent;


    var targetRotationX = Math.PI/4;
    var targetRotationXOnMouseDown = 0;

    var mouseX = 0;
    var mouseXOnMouseDown = 0;

    var targetRotationY = Math.PI/8;
    var targetRotationYOnMouseDown = 0;

    var mouseY = 0;
    var mouseYOnMouseDown = 0;

    var windowHalfX = 0;
    var windowHalfY = 0;  
    slicesObjs=[];




    model3Dobj.setVerticalScale= function(vs) { 
      model3Dobj.verticalScale=vs; 
      
      for (var i = 0; i< slicesObjs.length; i++) {
        parent.remove( slicesObjs[i] );
      }

      slicesObjs.length=0;
      model3Dobj.renderSlices();

    }
 


    model3Dobj.addShape= function( shape, x, y, z, rx, ry, rz ) {

          var scale=300;
          var extrudeSettings = { amount: 0.2/scale , bevelSegments: 1, steps: 1 , bevelSegments: 1, bevelSize: 0.2/scale, bevelThickness:0.2/scale };
          var color = 0xffffff;
          var material= new THREE.MeshLambertMaterial( { color: color } );
          var geometry = new THREE.ExtrudeGeometry( shape, extrudeSettings );

          var mesh= new THREE.Mesh( geometry, material )
          mesh.scale.set( scale, scale, scale );
          mesh.position.set( x*scale, y*scale, z*scale );
          mesh.rotation.set( rx, ry, rz );        
          mesh.castShadow = true;
          mesh.receiveShadow = true;  
          parent.add( mesh );
          slicesObjs.push(mesh);

  

        }


    model3Dobj.renderSlices= function() {
        // Slices

          for (var i = 0; i< model3Dobj.data.h.length; i++) {
            var slicePts = [ new THREE.Vector2 ( data.width/maxDim, 0 ), new THREE.Vector2 ( 0, 0 )];
            var sl=model3Dobj.data.h[i];
            for (var n = 0; n<sl.c.length; n++)  slicePts.push( new THREE.Vector2 ( sl.c[n][0], sl.c[n][1]*model3Dobj.verticalScale+0.1 ) );
            var sliceShape = new THREE.Shape( slicePts );
            var dx= -(data.width/maxDim)/2;         
            var dy= -0.1;
            var dz= -(data.height/maxDim)/2 + (i+1)*(data.height/maxDim) / ( model3Dobj.data.h.length+1 );

            model3Dobj.addShape( sliceShape, dx, dy, dz, 0, 0, 0 );
          }

          for (var i = 0; i< model3Dobj.data.v.length; i++) {
            var slicePts = [ new THREE.Vector2 ( data.height/maxDim, 0 ), new THREE.Vector2 ( 0, 0 )];
            var sl=model3Dobj.data.v[i];
            for (var n = 0; n<sl.c.length; n++)  slicePts.push( new THREE.Vector2 ( sl.c[n][0], sl.c[n][1]*model3Dobj.verticalScale+0.1 ) );
            var sliceShape = new THREE.Shape( slicePts );
            var dx= (data.height/maxDim)/2;
            var dy= -0.1;
            var dz= (data.width/maxDim)/2- (i+1)*(data.width/maxDim)/(model3Dobj.data.v.length+1);
            model3Dobj.addShape( sliceShape, dz, dy, -dx, 0, -Math.PI/2, 0 );
          }

    }


    model3Dobj.init= function() {

        W = container.width();
        H = container.height();

        windowHalfX = W/2;
        windowHalfY = H/2;

        renderer = new THREE.WebGLRenderer( { antialias: true } );
        renderer.setSize( W, H );

        renderer.physicallyBasedShading = true;

        renderer.shadowMapEnabled = true;
        renderer.shadowMapCullFace = THREE.CullFaceBack;

        container[0].appendChild( renderer.domElement );
        
        camera = new THREE.PerspectiveCamera( 50, W / H, 1, 1000 );
        camera.position.set( 0, 150, 500 );

        scene = new THREE.Scene();
      /* 
        scene.fog = new THREE.Fog( 0xfafafa, 1000, 10000 );
        scene.fog.color.setHSV( 0.6, 0.125, 1 );
      */

      // LIGHTS

        dirLight1 = new THREE.DirectionalLight( 0xffffff, 0.4 );
       
        dirLight1.position.set( 1, 0, 1 );
        dirLight1.position.multiplyScalar( 500 );
        scene.add( dirLight1 );


        //

        dirLight = new THREE.DirectionalLight( 0xffffff, 1 );
       
        dirLight.position.set( -1, 1, 1 );
        dirLight.position.multiplyScalar( 500 );
        scene.add( dirLight );

        dirLight.castShadow = true;

        dirLight.shadowMapWidth = 2048;
        dirLight.shadowMapHeight = 2048;

        var d = 500;

        dirLight.shadowCameraLeft = -d;
        dirLight.shadowCameraRight = d;
        dirLight.shadowCameraTop = d;
        dirLight.shadowCameraBottom = -d;

        dirLight.shadowCameraFar = 3500;
        dirLight.shadowBias = -0.0001;
        dirLight.shadowDarkness = 0.10;



        parent = new THREE.Object3D();
        parent.position.y = 100;
        scene.add( parent );
     
    

        
        model3Dobj.renderSlices();

        container[0].addEventListener( 'mousedown', model3Dobj.onDocumentMouseDown, false );
        container[0].addEventListener( 'touchstart', model3Dobj.onDocumentTouchStart, false );
        container[0].addEventListener( 'touchmove', model3Dobj.onDocumentTouchMove, false );

        //

        window.addEventListener( 'resize', model3Dobj.onWindowResize, false );

      }// fin init



      model3Dobj.onWindowResize= function() {

        W = container.width();
        H = container.height();

        windowHalfX = W / 2;
        windowHalfY = H / 2;

        camera.aspect = W / H;
        camera.updateProjectionMatrix();

        renderer.setSize( W, H );

      }

      //

      model3Dobj.onDocumentMouseDown= function( event ) {

        event.preventDefault();

        container[0].addEventListener( 'mousemove', model3Dobj.onDocumentMouseMove, false );
        container[0].addEventListener( 'mouseup', model3Dobj.onDocumentMouseUp, false );
        container[0].addEventListener( 'mouseout', model3Dobj.onDocumentMouseOut, false );

        mouseXOnMouseDown = event.clientX - windowHalfX;
        targetRotationXOnMouseDown = targetRotationX;

        mouseYOnMouseDown = event.clientY - windowHalfY;
        targetRotationYOnMouseDown = targetRotationY;

      }

      model3Dobj.onDocumentMouseMove= function( event ) {

        mouseX = event.clientX - windowHalfX;
        mouseY = event.clientY - windowHalfY;

        targetRotationX = targetRotationXOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.02;
        targetRotationY = targetRotationYOnMouseDown + ( mouseY - mouseYOnMouseDown ) * 0.02;

      }

      model3Dobj.onDocumentMouseUp= function( event ) {

        container[0].removeEventListener( 'mousemove', model3Dobj.onDocumentMouseMove, false );
        container[0].removeEventListener( 'mouseup', model3Dobj.onDocumentMouseUp, false );
        container[0].removeEventListener( 'mouseout', model3Dobj.onDocumentMouseOut, false );

      }

      model3Dobj.onDocumentMouseOut= function( event ) {

        container[0].removeEventListener( 'mousemove', model3Dobj.onDocumentMouseMove, false );
        container[0].removeEventListener( 'mouseup', model3Dobj.onDocumentMouseUp, false );
        container[0].removeEventListener( 'mouseout', model3Dobj.onDocumentMouseOut, false );

      }

      model3Dobj.onDocumentTouchStart= function( event ) {

        if ( event.touches.length == 1 ) {

          event.preventDefault();

          mouseXOnMouseDown = event.touches[ 0 ].pageX - windowHalfX;
          targetRotationXOnMouseDown = targetRotationX;

          mouseYOnMouseDown = event.touches[ 0 ].pageY - windowHalfY;
          targetRotationYOnMouseDown = targetRotationY;

        }

      }

      model3Dobj.onDocumentTouchMove= function( event ) {

        if ( event.touches.length == 1 ) {

          event.preventDefault();

          mouseX = event.touches[ 0 ].pageX - windowHalfX;
          targetRotationX = targetRotationXOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.05;

          mouseY = event.touches[ 0 ].pageY - windowHalfY;
          targetRotationY = targetRotationYOnMouseDown + ( mouseY - mouseYOnMouseDown ) * 0.05;

        }

      }

      //

       model3Dobj.animate=function() {

        requestAnimationFrame( model3Dobj.animate );
        model3Dobj.render();

      }

       model3Dobj.render=function() {

        parent.rotation.y += ( targetRotationX - parent.rotation.y ) * 0.05;
        parent.rotation.x += ( targetRotationY - parent.rotation.x ) * 0.05;

        if (parent.rotation.x<-Math.PI/4) parent.rotation.x=-Math.PI/4;
        if (parent.rotation.x>Math.PI/2)  parent.rotation.x=Math.PI/2;


        renderer.render( scene, camera );

      }


      model3Dobj.init();
      model3Dobj.animate();

      return model3Dobj;
  }


       /********************/
      /*      FIN 3D      */
     /********************/

});
