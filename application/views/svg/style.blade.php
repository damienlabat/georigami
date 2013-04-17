 <style>

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
      fill: url(#preview2);
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
      stroke-width: 0.005;
    }



    /* FILLSTYLE */
    #svgprofil.fillstyle polygon {
      fill: url(#fillstyle);
    }
    #svgprofil.fillstyle polyline.topline {
      stroke-width: 0.005;
    }



    /* FILLSTYLE2 */
    #svgprofil.fillstyle2 polygon {
      fill: url(#fillstyle2);
    }
    #svgprofil.fillstyle2 polyline.topline {
      stroke-width: 0.005;
    }

    #svgprofil.fillstyle2 .gslice rect {
      fill: black;
    }





    /* FILLSTYLE3 */
    #svgprofil.fillstyle3 polygon {
      fill: url(#fillstyle3);
    }
    #svgprofil.fillstyle3 polyline.topline {
      stroke-width: 0.005;
    }
    #svgprofil.fillstyle3 .gslice rect {
      fill: #E1645D;
    }



    /* FILLSTYLE4 */
    #svgprofil.fillstyle4 polygon {
      fill: url(#fillstyle4);
    }
    #svgprofil.fillstyle4 polyline.topline {
      stroke-width: 0.005;
    }
    #svgprofil.fillstyle4 .gslice rect {
      fill: #45836C;
    }



    /* fillstyle5 */
    #svgprofil.fillstyle5 polygon {
      fill: url(#fillstyle5);
    }
    #svgprofil.fillstyle5 polyline.topline {
      stroke-width: 0.005;
    }
    #svgprofil.fillstyle5 .gslice rect {
      fill: #FAFAC0;
    }





/* realtopo*/
    #svgprofil.realtopo polygon {
      fill: url(#realtopo);
    }
    #svgprofil.realtopo polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.realtopo .gslice rect {
      fill: #fff;
    }


/* realtopo2*/
    #svgprofil.realtopo2 polygon {
      fill: url(#realtopo2);
    }
    #svgprofil.realtopo2 polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.realtopo2 .gslice rect {
      fill: #fff;
    }

    /* bands1000*/
    #svgprofil.bands1000 polygon {
      fill: url(#bands1000);
    }
    #svgprofil.bands1000 polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.bands1000 .gslice rect {
      fill: #fff;
    }

    /* bands100*/
    #svgprofil.bands100 polygon {
      fill: url(#bands100);
    }
    #svgprofil.bands100 polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.bands100 .gslice rect {
      fill: #fff;
    }

    /* bands10*/
    #svgprofil.bands10 polygon {
      fill: url(#bands10);
    }
    #svgprofil.bands10 polyline.topline {
      stroke: 0.01;
    }
    #svgprofil.bands10 .gslice rect {
      fill: #fff;
    }

     /* bands1000b*/
    #svgprofil.bands1000b polygon {
      fill: url(#bands1000b);
    }
    #svgprofil.bands1000b polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.bands1000b .gslice rect {
      fill: #fff;
    }

    /* bands100*/
    #svgprofil.bands100b polygon {
      fill: url(#bands100b);
    }
    #svgprofil.bands100b polyline.topline {
      stroke-width: 0.01;
    }
    #svgprofil.bands100b .gslice rect {
      fill: #fff;
    }

    /* bands10*/
    #svgprofil.bands10b polygon {
      fill: url(#bands10b);
    }
    #svgprofil.bands10b polyline.topline {
      stroke: 0.01;
    }
    #svgprofil.bands10b .gslice rect {
      fill: #fff;
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
