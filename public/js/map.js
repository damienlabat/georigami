var map;
var elevator;
var infowindow;




findTile= function(map, latlng) {

    var maxZoomService = new google.maps.MaxZoomService();


  maxZoomService.getMaxZoomAtLatLng(latlng, function(response) {
    if (response.status != google.maps.MaxZoomStatus.OK) {
      alert("Error in MaxZoomService");
      return;
    } else {
      //console.log("The maximum zoom at this location is: " + response.zoom);
      if (response.zoom>15) response.zoom=15;
      var pt= map.getProjection().fromLatLngToPoint(latlng);
      var x= pt.x;
      var y= pt.y;

    //  response.zoom=response.zoom-1
      var cell= Math.pow( 2, response.zoom);

      console.log(x,y,cell);

      url='https://khms0.googleapis.com/kh?v=125&x='+ Math.floor(x/256*cell) +'&y='+ Math.floor(y/256*cell) +'&z='+ response.zoom;
      console.log(url);
    }
  });
}



















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

    var icon = new google.maps.MarkerImage(Georigami.baseurl+"/img/hang.png", null, null, new google.maps.Point(8, 8));
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

    google.maps.event.addListener(map, 'click', function(event) {
          DR.updateCenter( event.latLng.lat() , event.latLng.lng() );
          changeFunction();

			//console.log( findTile( map, event.latLng  ) );

        });
        
    return DR
};












function createMarker(name, latlng, icon, shadow) {

    var options={position: latlng, map: map};
    
    if (icon!=null) options.icon=new google.maps.MarkerImage(icon,
        new google.maps.Size(32.0, 37.0),
        new google.maps.Point(0, 0),
        new google.maps.Point(16.0, 18.0)
    );

    if (shadow!=null) options.shadow=new google.maps.MarkerImage(
        shadow,
        new google.maps.Size(51.0, 37.0),
        new google.maps.Point(0, 0),
        new google.maps.Point(16.0, 18.0)
    );;

    var marker = new google.maps.Marker(options);
    google.maps.event.addListener(marker, "click", function() {
      if (infowindow) infowindow.close();
      infowindow = new google.maps.InfoWindow({content: name});
      infowindow.open(map, marker);
    });
    google.maps.event.addListener(marker, "mouseover", function() {
      marker.setAnimation(google.maps.Animation.BOUNCE);
      setTimeout(function(){marker.setAnimation(null)}, 750*3);
    });
    marker.setAnimation(google.maps.Animation.DROP);
    return marker;
  }












function grid(map,lat,lng,width,height,vSlices,hSlices,options) {

	var grid={};
	var slices=[];
	var slicesData=[];
	var cadre=null;	   
       
	var center= new google.maps.LatLng( lat , lng );    

    var NE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, height/2, 0),   width/2,90); // NE
    var SE= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, height/2, 180), width/2,90); // SE
    var NW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, height/2, 0),   width/2,-90); // NW
    var SW= google.maps.geometry.spherical.computeOffset( google.maps.geometry.spherical.computeOffset(center, height/2, 180), width/2,-90); // SW


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

                 
        
        var nbL= parseInt(vSlices);
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


        nbL= parseInt(hSlices);
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





    grid.clear= function() {

        if (cadre!=null) cadre.setMap(null);
        for (var i = slices.length - 1; i >= 0; i--) {
            slices[i].setMap(null);
          };
        slices.length = 0;

	}



    grid.setOptions= function( newoptions ) {
		for (var i = slices.length - 1; i >= 0; i--) {
            slices[i].setOptions( newoptions )
          };
    }


    grid.getBounds=function() {
    	return new google.maps.LatLngBounds( SW,NE );
    }


    grid.getData=function() {
    	return slicesData;
    }  

  	
	return grid
}