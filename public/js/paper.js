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