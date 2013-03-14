
      /*************************/
     /*                       */
    /*      THREE JS         */
   /*                       */
  /*************************/
  function load3D(data,container,verticalScale) {

    var model3Dobj= {data:data.coords, verticalScale:verticalScale};
    var maxDim= Math.max(data.width,data.height);

    var W,H;

    var camera, scene, renderer, dirLight, hemiLight, parent;


    var targetRotationX = Math.PI/4;
    var targetRotationXOnMouseDown = 0;

    var mouseX = 0;
    var mouseXOnMouseDown = 0;

    var targetRotationY = Math.PI/8;
    var targetRotationYOnMouseDown = 0;

    var mouseY = 0;
    var mouseYOnMouseDown = 0;

    var camerazoom=35;

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

    };


    model3Dobj.setRotation= function(rotX,rotY) {
      //if (rotY==null) rotY=Math.PI/8;
      targetRotationX=rotX;
      if (rotY!==null) targetRotationY=rotY;
    };



    model3Dobj.addShape= function( shape, x, y, z, rx, ry, rz ) {

          var scale=300;
          var extrudeSettings = { amount: 0.2/scale ,  steps: 1 , bevelSegments: 1, bevelSize: 0.2/scale, bevelThickness:0.2/scale };
          var color = 0xffffff;
          var material= new THREE.MeshLambertMaterial( { color: color } );
          var geometry = new THREE.ExtrudeGeometry( shape, extrudeSettings );

          var mesh= new THREE.Mesh( geometry, material );
          mesh.scale.set( scale, scale, scale );
          mesh.position.set( x*scale, y*scale, z*scale );
          mesh.rotation.set( rx, ry, rz );
          mesh.castShadow = true;
          mesh.receiveShadow = true;
          parent.add( mesh );
          slicesObjs.push(mesh);



        };


    model3Dobj.renderSlices= function() {
        // Slices

        var slicePts, sl;

          for (var i = 0; i< model3Dobj.data.h.length; i++) {
            slicePts = [ new THREE.Vector2 ( data.width/maxDim, 0 ), new THREE.Vector2 ( 0, 0 )];
            sl=model3Dobj.data.h[i];
            for (var n = 0; n<sl.c.length; n++)  slicePts.push( new THREE.Vector2 ( sl.c[n][0], sl.c[n][1]*model3Dobj.verticalScale+0.1 ) );
            var sliceShape = new THREE.Shape( slicePts );
            var dx= -(data.width/maxDim)/2;
            var dy= -0.1;
            var dz= -(data.height/maxDim)/2 + (i+1)*(data.height/maxDim) / ( model3Dobj.data.h.length+1 );

            model3Dobj.addShape( sliceShape, dx, dy, dz, 0, 0, 0 );
          }

          for ( i = 0; i< model3Dobj.data.v.length; i++) {
            slicePts = [ new THREE.Vector2 ( data.height/maxDim, 0 ), new THREE.Vector2 ( 0, 0 )];
            sl=model3Dobj.data.v[i];
            for (var n = 0; n<sl.c.length; n++)  slicePts.push( new THREE.Vector2 ( sl.c[n][0], sl.c[n][1]*model3Dobj.verticalScale+0.1 ) );
            var sliceShape = new THREE.Shape( slicePts );
            var dx= (data.height/maxDim)/2;
            var dy= -0.1;
            var dz= (data.width/maxDim)/2- (i+1)*(data.width/maxDim)/(model3Dobj.data.v.length+1);
            model3Dobj.addShape( sliceShape, dz, dy, -dx, 0, -Math.PI/2, 0 );
          }

    };


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

        camera.setLens(camerazoom);

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

        container[0].addEventListener( 'mousewheel', model3Dobj.mousewheel, false );
        container[0].addEventListener( 'DOMMouseScroll', model3Dobj.mousewheel, false ); // firefox

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


      model3Dobj.mousewheel= function( event ) {


          event.preventDefault();
          event.stopPropagation();

          var delta = 0;

          if ( event.wheelDelta ) { // WebKit / Opera / Explorer 9

            delta = event.wheelDelta / 40;

          } else if ( event.detail ) { // Firefox

            delta = - event.detail / 3;

          }

          camerazoom += ( 1 / delta ) * 5;
          if (camerazoom<1) camerazoom=1;

          camera.setLens(camerazoom);

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