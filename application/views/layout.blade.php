<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        {{--HTML::style("/css/bootstrap.min.css")--}}

        {{HTML::style("/css/main.css")}}

        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        {{HTML::style("css/bootstrap-responsive.min.css")}}

        {{HTML::style("css/jquery-ui-1.10.1.custom.css")}}
        <!--link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css"/-->

        {{HTML::script("js/vendor/modernizr-2.6.2-respond-1.1.0.min.js")}}
    </head>
    <body class='@yield('bodyclass')'>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->


        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="{{URL::home()}}" title='pas très fier de ce jeu de mot :s'>Georigami</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li>{{HTML::link_to_route('index',  __('georigami.lastones'))}}</li>
                            <li>{{HTML::link_to_route('saved',  __('georigami.lastsaved'))}}</li>
                            <li>{{HTML::link_to_route('map',    __('georigami.map'))}}</li>
                            <li>{{HTML::link_to_route('new',    __('georigami.build'))}}</li>
                        </ul>
                        <ul class="nav pull-right">
                            <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">{{__('georigami.currentlang')}} <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                              @if (Config::get('application.language')!='en')
                              <li><a href="{{URL::base()}}"><img src="{{URL::base()}}img/flags/gb.png" alt=""/> English</a></li>@endif
                              @if (Config::get('application.language')!='fr')
                              <li><a href="{{URL::base()}}fr"><img src="{{URL::base()}}img/flags/fr.png" alt=""/> Français</a></li>@endif

                            </ul>
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container row-fluid">

            @yield('content')
            <hr class="clearfix">

            <footer>
                <div id="slider"></div>
                <p>{{__('georigami.footer')}}</p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>

        {{HTML::script("js/vendor/bootstrap.min.js")}}
        {{HTML::script("js/vendor/jquery-ui-1.10.1.custom.min.js")}}

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
        <!--{{HTML::script("js/markerclusterer.js")}} -->

          <!--script src="https://raw.github.com/mrdoob/three.js/master/build/three.js"></script-->
        <script>Georigami={baseurl:'{{URL::base()}}'};</script>
        {{HTML::script("js/vendor/three.min.js")}}

        <!--{{HTML::script("js/map.js")}}
        {{HTML::script("js/3d.js")}}

        {{HTML::script("js/page_new.js")}}
        {{HTML::script("js/page_map.js")}}
        {{HTML::script("js/page_location.js")}}
        {{HTML::script("js/page_bloc.js")}}



        {{HTML::script("js/main.js")}}-->

        <!-- TODO compile JS files -->
        {{HTML::script("js/combined.min.js")}}
@yield('script')

        <!--script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script-->
    </body>
</html>
