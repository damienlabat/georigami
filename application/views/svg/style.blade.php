 <style>

  /* crash in firefox18 on linux if fill:url(gradient); is in an external .css file */

    #svgprofil {
      width: 100%;
      height: 700px;
    }

    #svgprofil rect.rectbackground {
      fill: none;
      stroke: none;
    }

    #svgprofil polyline.topline {
      stroke: black;
      stroke-width: 0.05;
      fill: none;
    }

    #svgprofil polyline.baseline {
      stroke: black;
      stroke-width: 0.01;
      fill: none;
    }

    #svgprofil polygon {
      fill: white;
      stroke: none;
    }

    /* white */
    #svgprofil.white polygon {
      fill: url(#greygradient);
    }

     /* lines */
    #svgprofil.lines rect.rectbackground {
      fill: white;
    }
    #svgprofil.lines polygon {
      fill: white;
      fill-opacity: 0.2;
    }
    #svgprofil.lines polyline.baseline {
      stroke: none;
    }
    #svgprofil.lines polyline.topline {
      stroke: black;
      stroke-width: 0.15;
    }

    /* FILLSTYLE */
    #svgprofil.fillstyle polygon {
      fill: url(#fillstyle);
    }

    /* FILLSTYLE2 */
    #svgprofil.fillstyle2 polygon {
      fill: url(#fillstyle2);
    }

    /* FILLSTYLE3 */
    #svgprofil.fillstyle3 polygon {
      fill: url(#fillstyle3);
    }

    /* FILLSTYLE4 */
    #svgprofil.fillstyle4 polygon {
      fill: url(#fillstyle4);
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

    </style>
