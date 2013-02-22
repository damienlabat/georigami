$(function() {



/*******************/
 /*   initLocation    */
/*******************/

  Georigami.initLocation = function() {


  function createGrid(map, data, marker) {
    var gridObj=grid(map,data.lat,data.lng,data.width,data.height,data.vslices,data.hslices,data.rotate); 
    console.log(data.rotate)        ;
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
    

    var markers = [];
    var bounds = new google.maps.LatLngBounds ();
    bounds.extend(Loc);

    for (var i = 0; i < Georigami.location.blocs.length; i++) {
      var data=Georigami.location.blocs[i];
      var latLng = new google.maps.LatLng( data.lat,data.lng );
      bounds.extend(latLng);


      var html='<a href="'+Georigami.baseurl+'/bloc'+data.id+'">';

        //html =html+data.lat+', '+data.lng+'<br/>';



        html =html+'<img src="'+Georigami.baseurl+'/bloc'+data.id+'N.svg" title="North" width="100px">';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+data.id+'W.svg" title="West" width="100px">';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+data.id+'S.svg" title="South" width="100px">';
        html =html+'<img src="'+Georigami.baseurl+'/bloc'+data.id+'E.svg" title="Est" width="100px">';

        html =html+'<br/>';

        html =html+'altitude: '+Math.round(data.min)+'m to '+Math.round(data.max)+'m<br/>';
        html =html+data.width+'m x '+data.height+'m<br/>';        
        html =html+'slices: '+data.vslices+' x '+data.hslices+'<br/>';
        html =html+(data.vslices*data.vsamples+data.hslices*data.hsamples)+' samples<br/>';
        html =html+'created at  '+data.created_at+'<br/>';

        html =html+'</a>';

      
      var marker = createMarker( html , new google.maps.LatLng(data.lat,data.lng), Georigami.baseurl+'/img/ico/'+Georigami.location.icon+'.png', Georigami.baseurl+'/img/ico/shadow.png' );
      
      gridObj=createGrid(map, data, marker);      
      bounds.union( gridObj.getBounds() );
     
    }

    map.fitBounds (bounds);    

  }


});  