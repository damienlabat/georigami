$(function() {

  draggableRectangle= function( map, lat, lng, width, height, options ) {

    var DR={ lat: lat, lng: lng, width: width, height: height };
    var center= new google.maps.LatLng( lat , lng );
    var updateSizeFromRect=true;
    var changeFunction= function(){};
    if (options.onChange!==null) changeFunction=options.onChange;

    

    DR.getBounds= function() {
        var e= google.maps.geometry.spherical.computeOffset(center, DR.width/2, 90);
        var s= google.maps.geometry.spherical.computeOffset(center, DR.height/2, 180);
        var n= google.maps.geometry.spherical.computeOffset(center, DR.height/2, 0);
        var w= google.maps.geometry.spherical.computeOffset(center, DR.width/2, -90);
        
        return new google.maps.LatLngBounds( new google.maps.LatLng( n.lat() , w.lng() ), new google.maps.LatLng( s.lat() , e.lng() ) );
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
    
    google.maps.event.addListener(marker, 'dragend', function(event) {
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



  Georigami.init = function() {

    var slicesObj=[];
    var cadre=null;

    //initmap
    var mapOptions = {
        center: new google.maps.LatLng (Georigami.area.lat , Georigami.area.lng ),
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);    



    var updateForm= function() {

      $('#input-latitude').val(  Georigami.rectangle.lat );
      $('#input-longitude').val( Georigami.rectangle.lng );

      $('#input-width').val(  Georigami.rectangle.width );
      $('#input-height').val( Georigami.rectangle.height );

      drawSlices();
    }





    var drawSlices= function() {

        var center= new google.maps.LatLng( Georigami.rectangle.lat , Georigami.rectangle.lng );

        //clear
        if (cadre!=null) cadre.setMap(null);
        for (var i = slicesObj.length - 1; i >= 0; i--) {
            slicesObj[i].setMap(null);
          };
        slicesObj.length = 0;

          /* */

          var distDiag= Math.sqrt( (Georigami.rectangle.width*Georigami.rectangle.height)/2 );

          var NE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, Georigami.rectangle.height/2, 0), Georigami.rectangle.width/2,90); // NE
          var SE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, Georigami.rectangle.height/2, 180), Georigami.rectangle.width/2,90); // SE
          var NW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, Georigami.rectangle.height/2, 0), Georigami.rectangle.width/2,-90); // NW
          var SW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, Georigami.rectangle.height/2, 180), Georigami.rectangle.width/2,-90); // SW

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

                 
          var nbL= parseInt($('#input-vertical-slices').val());
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
              slicesObj.push(slicePath);

             }

          nbL= parseInt($('#input-horizontal-slices').val());
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
              slicesObj.push(slicePath);

             }
  }



   
    $('#input-vertical-slices').val(   Georigami.area.vSlices );
    $('#input-horizontal-slices').val( Georigami.area.hSlices );
    Georigami.rectangle= draggableRectangle(map, Georigami.area.lat , Georigami.area.lng, Georigami.area.width, Georigami.area.height, {onChange: updateForm });
    updateForm();

    

    map.fitBounds(Georigami.rectangle.getBounds());

    $('#update-btn').click( function(){
        Georigami.rectangle.updateCenter( $('#input-latitude').val() , $('#input-longitude').val() );
        Georigami.rectangle.updateDimensions( $('#input-width').val() , $('#input-height').val() );
        drawSlices();
        map.fitBounds(Georigami.rectangle.getBounds());
        return false;
    });


    var loadSlice= function(i) {
      var slice=slicesObj[i];
      var pathstr='';
      $.each( slice.getPath().getArray(), function(i2, pt) {
            if (pathstr!='') pathstr=pathstr+'|';
            pathstr=pathstr+pt.lat()+','+pt.lng();
        });
       var url='http://maps.googleapis.com/maps/api/elevation/json?path='+pathstr+'&samples=500&sensor=false';
       $.getJSON(url, function(data) {
          console.log(data);
          slice.data=data;
          if (i+1<slicesObj.length) loadSlice(i+1);
          else console.log(slicesObj);
              });
    }


    $('#start-btn').click( function(){
        loadSlice(0);
        });




       // 
        
/*
        $.ajax({
          dataType: "json",
          url: url,
          data: data,
          success: success
        });*/

        return false;


  };







  Georigami.init();




	});
