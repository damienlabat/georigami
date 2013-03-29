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

    #svgprofil polygon {
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
    #svgprofil.lines polygon {
      fill: white;
      fill-opacity: 0.2;
    }
    #svgprofil.lines polyline.topline {
      stroke: black;
      stroke-width: 0.15;
    }


  /*beetlejuice*/

    #svgprofil.beetlejuice polygon {
      fill: white;
    }

    #svgprofil.beetlejuice .odd polygon {
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



    /* FILLSTYLE3 */
    #svgprofil.fillstyle3 polygon {
      fill: url(#fillstyle3);
    }
    #svgprofil.fillstyle3 polyline.topline {
      stroke: none;
    }



    /* FILLSTYLE4 */
    #svgprofil.fillstyle4 polygon {
      fill: url(#fillstyle4);
    }
    #svgprofil.fillstyle4 polyline.topline {
      stroke: none;
    }



    /* JOY DIVISION */
    #svgprofil.joydiv rect.rectbackground {
      fill: black;
    }
    #svgprofil.joydiv polygon {
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
    #svgprofil.joydiv2 polygon {
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

    #svgprofil.joydiv3 polygon {
      fill: black;
    }
    #svgprofil.joydiv3 polyline.topline {
      stroke: white;
      stroke-width: 0.033;
    }




    </style>
