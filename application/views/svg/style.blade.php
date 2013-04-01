 <style>

  /* crash in firefox18 on linux if fill:url(gradient); is in an external .css file */

    #svgprofil {
      width: 100%;
      height: 700px;
    }



    #svgprofil rect.rectbackground {
      fill: none;
    }

    #svgprofil polyline.topline {
      stroke: black;
      stroke-width: 0.05;
      fill: none;
    }

    #svgprofil polygon,#svgprofil .gslice rect {
      fill: white;
      stroke: none;
    }



    /* preview */
    #svgprofil.preview polygon {
      fill: white;
    }

    #svgprofil.preview polyline.topline {
      stroke-width: 0.25;
    }



    /* grey */
    #svgprofil.grey polygon {
      fill: url(#greygradient);
    }

    #svgprofil.grey polyline.topline {
      stroke: black;
      stroke-width: 0.005;
    }

     /* lines */
    #svgprofil.lines rect.rectbackground {
      fill: white;
    }
    #svgprofil.lines polygon, #svgprofil.lines .gslice rect {
      fill: white;
      fill-opacity: 0.2;
    }
    #svgprofil.lines polyline.topline {
      stroke: black;
      stroke-width: 0.15;
    }


  /*beetlejuice*/

    #svgprofil.beetlejuice polygon, #svgprofil.beetlejuice .gslice rect {
      fill: white;
    }

    #svgprofil.beetlejuice .odd polygon,#svgprofil.beetlejuice .gslice.odd rect {
      fill: black;
    }

    #svgprofil.beetlejuice polyline.topline {
      stroke: none;
    }



    /* FILLSTYLE */
    #svgprofil.fillstyle polygon {
      fill: url(#fillstyle);
    }
    #svgprofil.fillstyle polyline.topline {
      stroke: none;
    }



    /* FILLSTYLE2 */
    #svgprofil.fillstyle2 polygon {
      fill: url(#fillstyle2);
    }
    #svgprofil.fillstyle2 polyline.topline {
      stroke: none;
    }

    #svgprofil.fillstyle2 .gslice rect {
      fill: black;
    }



    /* FILLSTYLE3 */
    #svgprofil.fillstyle3 polygon {
      fill: url(#fillstyle3);
    }
    #svgprofil.fillstyle3 polyline.topline {
      stroke: none;
    }
    #svgprofil.fillstyle3 .gslice rect {
      fill: #E1645D;
    }



    /* FILLSTYLE4 */
    #svgprofil.fillstyle4 polygon {
      fill: url(#fillstyle4);
    }
    #svgprofil.fillstyle4 polyline.topline {
      stroke: none;
    }
    #svgprofil.fillstyle4 .gslice rect {
      fill: #45836C;
    }



    /* JOY DIVISION */
    #svgprofil.joydiv rect.rectbackground {
      fill: black;
    }
    #svgprofil.joydiv polygon, #svgprofil.joydiv .gslice rect {
      fill: black;
    }
    #svgprofil.joydiv polyline.topline {
      stroke: white;
      stroke-width: 0.3;
    }



        /* JOY DIVISION 2 */
    #svgprofil.joydiv2 rect.rectbackground {
      fill: black;
    }
    #svgprofil.joydiv2 polygon, #svgprofil.joydiv2 .gslice rect {
      fill: black;
    }
    #svgprofil.joydiv2 polyline.topline {
      stroke: white;
      stroke-width: 0.1;
    }

            /* JOY DIVISION 3 */

    #svgprofil.joydiv3 rect.rectbackground {
      fill: black;
    }

    #svgprofil.joydiv3 polygon, #svgprofil.joydiv3 .gslice rect {
      fill: black;
    }
    #svgprofil.joydiv3 polyline.topline {
      stroke: white;
      stroke-width: 0.033;
    }




    </style>
