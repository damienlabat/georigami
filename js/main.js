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

    var slices=[];
    var slicesData=[];
    var cadre=null;

    Georigami.results=[];

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

    

    map.fitBounds(Georigami.rectangle.getBounds());



    $('#update-btn').click( function(){
        Georigami.rectangle.updateCenter( $('#input-latitude').val() , $('#input-longitude').val() );
        Georigami.rectangle.updateDimensions( $('#input-width').val() , $('#input-height').val() );
        drawSlices( getParams() );
        map.fitBounds(Georigami.rectangle.getBounds());
        return false;
    });



    var buildSlices= function(result) {

      showLoading( 'building geom', result);

      var data=result.params;
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
        var sObj={coord:[[0,0]],cut:[]}        
        for (var n = 0; n< s.data.results.length; n++) {          
          z=s.data.results[n].elevation;
          if (s.type=='vertical') 
            x= n/(s.data.results.length)*data.height/maxDim;
          else
            x= n/(s.data.results.length)*data.width/maxDim;
          y=0.1+(z-data.min)/maxDim;
          sObj.coord.push([x,y]);
        }
        
        if (s.type=='vertical') {
          sObj.coord.push([data.height/maxDim,0]);
          data.vSlicesObj.push(sObj);
        }
        else {
          sObj.coord.push([data.width/maxDim,0]);
          data.hSlicesObj.push(sObj);
        }
      }

      // TODO! add serveur ping
      load3D(result,data);
      // addDownloadButton();
    }




    var loadSlice= function(result,i) {
      var slice=result.slices[i];
      var pathstr='';
      var samples;
      showLoading( 'loading slice '+(i+1)+'/'+result.slices.length, result);

      $.each( slice.path, function(i2, pt) {
            if (pathstr!='') pathstr=pathstr+'|';
            pathstr=pathstr+pt.lat()+','+pt.lng();
        });
      if (slice.type=='vertical') samples=result.params.vSamples;
        else samples=result.params.hSamples;
      //var url='http://maps.googleapis.com/maps/api/elevation/json?path='+pathstr+'&samples='+samples+'&sensor=false';
      var url='./ex.json';
      $.getJSON(url, function(data) {
          if (data.status!='OK') {
            alert('ERROR status: '+data.status)
            return
          }
          slice.data=data;
          if (i+1<result.slices.length) loadSlice(result,i+1);
            else buildSlices(result);
      });
    }



    $('#start-btn').click( function(){
        Georigami.results.push( startWork( getParams(), slicesData ) );        
        return false;
     });


  var showLoading= function(content, obj) {
      obj.divObj.find('.loading').html(content);
  }



  var startWork= function(params, slices) {
    var result={params:params,slices:slices};
    var id= Georigami.results.length+1;
    var html='<div class="result">'+
      '<p class="index">result '+id+'</p>'+
      '<div class="loading span12">loading</div>'+
      '<div class="row">'+
      '<div class="span9 resultcontent"><div class="div3Dview"></div></div>'+
      '<div class="span2">'+
      '<table class="table">'+             
        '<thead><tr> <th>params</th><th></th></tr></thead>'+
        '<tbody><tr> <td>latitude</td><td>'+params.lat+'</td></tr>'+
        '<tr> <td>longitude</td><td>'+params.lng+'</td></tr>'+
        '<tr> <td>width</td><td>'+params.width+'</td></tr>'+
        '<tr> <td>height</td><td>'+params.height+'</td></tr>'+
        '<tr> <td>vSlices</td><td>'+params.vSlices+'</td></tr>'+
        '<tr> <td>hSlices</td><td>'+params.hSlices+'</td></tr>'+
        '<tr> <td>vSamples</td><td>'+params.vSamples+'</td></tr>'+
        '<tr> <td>hSamples</td><td>'+params.hSamples+'</td></tr></tbody>'+
            
      '</table>'+
      '</div>'+
      '</div>'+
      '</div>';

    result.divObj=$(html).insertAfter($('#start-btn'));

    loadSlice(result,0);


    return result;
  }




      /*************************/
     /*                       */
    /*      THREE JS         */
   /*                       */
  /*************************/
  var load3D= function(result,data) {

    showLoading( 'render 3D', result);


    var model3DObj={result:result, data:data};
    var maxDim= Math.max(data.width,data.height);
    
    var container,W,H;

    var camera, scene, renderer, dirLight, hemiLight, parent;


    var targetRotationX = 0;
    var targetRotationXOnMouseDown = 0;

    var mouseX = 0;
    var mouseXOnMouseDown = 0;

    var targetRotationY = 0;
    var targetRotationYOnMouseDown = 0;

    var mouseY = 0;
    var mouseYOnMouseDown = 0;

    var windowHalfX = 0;
    var windowHalfY = 0;    

    





    model3DObj.init= function() {

        container = model3DObj.result.divObj.find('.div3Dview');
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
       /* scene.fog = new THREE.Fog( 0xfafafa, 1000, 10000 );
        scene.fog.color.setHSV( 0.6, 0.125, 1 );*/

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





        model3DObj.addShape= function( shape, x, y, z, rx, ry, rz ) {

          var scale=300;
          var extrudeSettings = { amount: 1/scale , bevelSegments: 1, steps: 1 , bevelSegments: 1, bevelSize: 1/scale, bevelThickness:1/scale };
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

  

        }


        // Slices

          // CEST LA MERDE
        for (var i = 0; i< model3DObj.data.hSlicesObj.length; i++) {
          var slicePts = [];
          var sl=model3DObj.data.hSlicesObj[i];
          for (var n = 0; n<sl.coord.length; n++)  slicePts.push( new THREE.Vector2 ( sl.coord[n][0], sl.coord[n][1] ) );
          var sliceShape = new THREE.Shape( slicePts );
          var dx= -(maxDim/data.width)/2;
          var dy= -0.1;
          var dz= -(maxDim/data.height)/2 + (i+1)*(maxDim/data.height) / ( model3DObj.data.hSlicesObj.length+2 );
          model3DObj.addShape( sliceShape, dx, dy, dz, 0, 0, 0 );
        }

        for (var i = 0; i< model3DObj.data.vSlicesObj.length; i++) {
          var slicePts = [];
          var sl=model3DObj.data.vSlicesObj[i];
          for (var n = 0; n<sl.coord.length; n++)  slicePts.push( new THREE.Vector2 ( sl.coord[n][0], sl.coord[n][1] ) );
          var sliceShape = new THREE.Shape( slicePts );
          var dx= -(maxDim/data.height)/2;
          var dy= -0.1;
          var dz= -(maxDim/data.width)/2+(i+1)*(maxDim/data.width)/(model3DObj.data.vSlicesObj.length+2);
          model3DObj.addShape( sliceShape, dz, dy, dx, 0, -Math.PI/2, 0 );
        }



    

        


        container[0].addEventListener( 'mousedown', model3DObj.onDocumentMouseDown, false );
        container[0].addEventListener( 'touchstart', model3DObj.onDocumentTouchStart, false );
        container[0].addEventListener( 'touchmove', model3DObj.onDocumentTouchMove, false );

        //

        window.addEventListener( 'resize', model3DObj.onWindowResize, false );

      }

      model3DObj.onWindowResize= function() {

        W = container.width();
        H = container.height();

        windowHalfX = W / 2;
        windowHalfY = H / 2;

        camera.aspect = W / H;
        camera.updateProjectionMatrix();

        renderer.setSize( W, H );

      }

      //

      model3DObj.onDocumentMouseDown= function( event ) {

        event.preventDefault();

        container[0].addEventListener( 'mousemove', model3DObj.onDocumentMouseMove, false );
        container[0].addEventListener( 'mouseup', model3DObj.onDocumentMouseUp, false );
        container[0].addEventListener( 'mouseout', model3DObj.onDocumentMouseOut, false );

        mouseXOnMouseDown = event.clientX - windowHalfX;
        targetRotationXOnMouseDown = targetRotationX;

        mouseYOnMouseDown = event.clientY - windowHalfY;
        targetRotationYOnMouseDown = targetRotationY;

      }

      model3DObj.onDocumentMouseMove= function( event ) {

        mouseX = event.clientX - windowHalfX;
        mouseY = event.clientY - windowHalfY;

        targetRotationX = targetRotationXOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.02;
        targetRotationY = targetRotationYOnMouseDown + ( mouseY - mouseYOnMouseDown ) * 0.02;

      }

      model3DObj.onDocumentMouseUp= function( event ) {

        container[0].removeEventListener( 'mousemove', model3DObj.onDocumentMouseMove, false );
        container[0].removeEventListener( 'mouseup', model3DObj.onDocumentMouseUp, false );
        container[0].removeEventListener( 'mouseout', model3DObj.onDocumentMouseOut, false );

      }

      model3DObj.onDocumentMouseOut= function( event ) {

        container[0].removeEventListener( 'mousemove', model3DObj.onDocumentMouseMove, false );
        container[0].removeEventListener( 'mouseup', model3DObj.onDocumentMouseUp, false );
        container[0].removeEventListener( 'mouseout', model3DObj.onDocumentMouseOut, false );

      }

      model3DObj.onDocumentTouchStart= function( event ) {

        if ( event.touches.length == 1 ) {

          event.preventDefault();

          mouseXOnMouseDown = event.touches[ 0 ].pageX - windowHalfX;
          targetRotationXOnMouseDown = targetRotationX;

          mouseYOnMouseDown = event.touches[ 0 ].pageY - windowHalfY;
          targetRotationYOnMouseDown = targetRotationY;

        }

      }

      model3DObj.onDocumentTouchMove= function( event ) {

        if ( event.touches.length == 1 ) {

          event.preventDefault();

          mouseX = event.touches[ 0 ].pageX - windowHalfX;
          targetRotationX = targetRotationXOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.05;

          mouseY = event.touches[ 0 ].pageY - windowHalfY;
          targetRotationY = targetRotationYOnMouseDown + ( mouseY - mouseYOnMouseDown ) * 0.05;

        }

      }

      //

       model3DObj.animate=function() {

        requestAnimationFrame( model3DObj.animate );
        model3DObj.render();

      }

       model3DObj.render=function() {

        parent.rotation.y += ( targetRotationX - parent.rotation.y ) * 0.05;
        parent.rotation.x += ( targetRotationY - parent.rotation.x ) * 0.05;

        if (parent.rotation.x<-Math.PI/4) parent.rotation.x=-Math.PI/4;
        if (parent.rotation.x>Math.PI/2)  parent.rotation.x=Math.PI/2;


        renderer.render( scene, camera );

      }


      model3DObj.init();
      model3DObj.animate();
  }


       /********************/
      /*      FIN 3D      */
     /********************/


} //fin init




  Georigami.init();




	});
