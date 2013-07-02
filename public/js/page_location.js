$(function() {



/*******************/
 /*   initLocation    */
/*******************/

  Georigami.initLocation = function() {


  function createGrid(map, data, marker) {
    var gridObj=grid(map,data.lat,data.lng,data.width,data.height,data.vslices,data.hslices,data.rotate);
      google.maps.event.addListener(marker, "mouseover", function() {
        gridObj.setOptions( { strokeColor: "#00FF00"  } );
      });

      google.maps.event.addListener(marker, "mouseout", function() {
        gridObj.setOptions( { strokeColor: "#FF0000"  } );
      });
    return gridObj;
  }




     //initmap

    var Loc=new google.maps.LatLng (Georigami.location.lat , Georigami.location.lng );
    var mapOptions = {
        center: Loc,
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };

    map = new google.maps.Map(document.getElementById("map-canvas2"), mapOptions);


    google.maps.event.addListener(map, 'click', function(event) {

        var html= '<p class="coords">lat: '+event.latLng.lat()+'<br/>lng: '+event.latLng.lng()+'</p>';
        html= html+'<a href="'+Georigami.baseurl+'new?lat='+event.latLng.lat()+'&lng='+event.latLng.lng()+'" class="btn btn-primary btn-small"/>'+Lang.buildanew+'</a>';

        if (infowindow) infowindow.close();
         infowindow = new google.maps.InfoWindow({content: html, position:new google.maps.LatLng( event.latLng.lat() , event.latLng.lng() ) });
         infowindow.open(map);

        });


    var markers = [];
    var bounds = new google.maps.LatLngBounds ();
    bounds.extend(Loc);

    for (var i = 0; i < Georigami.location.blocs.length; i++) {
      var data=Georigami.location.blocs[i];
      var latLng = new google.maps.LatLng( data.lat,data.lng );
      bounds.extend(latLng);


      var html='<a href="'+Georigami.baseurl+Georigami.lang+'/'+Georigami.location.name+'_'+Georigami.location.id+'/bloc'+data.id+'">'; //TODO! style in  css

        //html =html+data.lat+', '+data.lng+'<br/>';



        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'N.svg" title="'+Lang.nface+'" width="64px">'; //TODO! style in  css
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'W.svg" title="'+Lang.wface+'" width="64px">';
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'S.svg" title="'+Lang.sface+'" width="64px">';
        html =html+'<img src="'+Georigami.baseurl+'svg/'+Math.floor(data.id/100)+'/bloc'+data.id+'E.svg" title="'+Lang.eface+'" width="64px">';

        html =html+'<br/>';

        html =html+Lang.altitude(Math.round(data.min),Math.round(data.max))+'<br/>';
        html =html+data.width+'m x '+data.height+'m<br/>';
        html =html+Lang.rotation+': '+data.rotate+'°<br/>';
        html =html+Lang.slices+': '+data.vslices+' x '+data.hslices+'<br/>';
        html =html+Lang.samples+': '+(data.vslices*data.vsamples+data.hslices*data.hsamples)+'<br/>';

        html =html+'</a>';


        var marker = createMarker( html , new google.maps.LatLng(data.lat,data.lng), Georigami.baseurl+'img/ico/'+Georigami.location.icon+'.png', Georigami.baseurl+'img/ico/shadow.png' );

      gridObj=createGrid(map, data, marker);
      bounds.union( gridObj.getBounds() );

    }

    map.fitBounds (bounds);

  }


});