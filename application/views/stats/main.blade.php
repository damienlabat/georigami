@if (Config::get('georigami.googleanalytics-account')!='')
    @render('stats.googleanalytics')
@endif
@if (Config::get('georigami.piwik-url')!='')
    @render('stats.piwik')
@endif
