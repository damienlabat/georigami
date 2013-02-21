$(function() {

  /*******************/
 /*   initMap       */
/*******************/

  Georigami.initMap = function() {

     //initmap
    var mapOptions = {
        center: new google.maps.LatLng (0 , 0 ),
        zoom: 2,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);  



    google.maps.event.addListener(map, 'click', function(event) {

        
        
        var html= 'lat: '+event.latLng.lat()+'<br/>lng: '+event.latLng.lng()+'<br/><br/>';
        html= html+'<a href="'+Georigami.baseurl+'/new?lat='+event.latLng.lat()+'&lng='+event.latLng.lng()+'" class="btn btn-primary btn-small"/>build a new on here</a>';

        if (infowindow) infowindow.close();
         infowindow = new google.maps.InfoWindow({content: html, position:new google.maps.LatLng( event.latLng.lat() , event.latLng.lng() ) });
         infowindow.open(map);

        });
    

    google.maps.event.addListener(map, 'zoom_changed', function() {

      if (( map.zoom>15 )&&( map.getMapTypeId()==google.maps.MapTypeId.TERRAIN )) map.setMapTypeId( google.maps.MapTypeId.ROADMAP );

      if ( map.zoom>19 ) map.setMapTypeId( google.maps.MapTypeId.ROADMAP );        

      if (map.zoom<=15) map.setMapTypeId( google.maps.MapTypeId.TERRAIN );

    });


    var markers = [];
    for (var i = 0; i < Georigami.location_list.length; i++) {
      var data=Georigami.location_list[i];
      var latLng = new google.maps.LatLng( data.lat,data.lng );

      var html='<a href="'+data.url+'"><h3 title="'+data.feature+'">'+data.name+'</h3><img src="'+Georigami.baseurl+'/img/flags/'+data.countrycode.toLowerCase()+'.png" title="'+data.countryname+'"/> '+data.countryname+' '+data.adminname1+'<br/>';
      for (var j = 0; j < data.blocs.length; j++) {
        var bloc=data.blocs[j];
        html =html+'<div>';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+bloc.id+'N.svg" title="North" width="64px">';
       /* html =html+'<img src="'+Georigami.baseurl+'/bloc'+bloc.id+'W.svg" title="West" width="100px">';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+bloc.id+'S.svg" title="South" width="100px">';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+bloc.id+'E.svg" title="Est" width="100px">';*/
        html =html+'</div>';
        html =html+'</a>';
        };
       // TODO
         
      var marker = createMarker( html , new google.maps.LatLng(data.lat,data.lng), Georigami.baseurl+'/img/ico/'+data.icon+'.png', Georigami.baseurl+'/img/ico/shadow.png');

      markers.push(marker);
    }

    var mcOptions = {gridSize: 50, maxZoom: 15 }; 
    var mapCluster = new MarkerClusterer(map, markers, mcOptions); 

  }


});