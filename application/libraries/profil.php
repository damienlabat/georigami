<?php

class profil
{


   /**
     * return svg profil image
     *
     * @param  array $profilData see Bloc::profil_data
     * @param  array $options    option: vscale, style, dx, dy, dscale, header, svg_hscale
     * @return View
     *
     * @see profil::profil_data()
     */
    public static  function profil_svg($profilData, $options=array() )
    {
        if (!isset($options['vscale'])) $options['vscale']=1;
        if (!isset($options['style']))  $options['style']='';
        if (!isset($options['dx']))     $options['dx']=0;
        if (!isset($options['dy']))     $options['dy']=0;
        if (!isset($options['dscale'])) $options['dscale']=0;
        if (!isset($options['header'])) $options['header']=false;
        if (!isset($options['svg_hscale'])) $options['svg_hscale']=100;
        if (!isset($options['reduce'])) $options['reduce']=false;
        if (!isset($options['crop']))   $options['crop']=false;

        $data=array_merge($profilData, $options);

          $data['svg_vscale']= $data['vscale'] * $data['svg_hscale'];

        return View::make('svg/profil')->with($data);

    }


    /**save reduce profil svg image
     * @param  Bloc $bloc
     * @param  string $face face to show (N|W|S|E)
     * @return void
     */
    public static  function save_svg($bloc,$face)
    {

            $profilData=$bloc->profil_data($face);

            $data['style']=       'preview'.$face;
            $data['dx']=          0;

            if (($face=='N')||($face=='S'))
                $data['dy']=      30/$bloc->hslices;
            else
                $data['dy']=      30/$bloc->vslices;

            $data['dscale']=      0.2;
            $data['header']=      true;
            $data['reduce']=      true;

            $svg= self::profil_svg($profilData, $data);

            File::put($bloc->getDirectory('svg').'bloc'.$bloc->id.$face.'.svg', $svg);
    }


     /**save reduce profil svg image
     * @param  Bloc $bloc
     * @param  string $params face to show (N|W|S|E)
     * @param  array $profilData
     * @return void
     */
    public static  function save_view($bloc,$profilData,$params,$showinsaved = false)
    {
            if (!$saved_view=Savedview::where('bloc_id', '=', $bloc->id)->where('params','=',json_encode($params))->first()) {
                $saved_view = new Savedview;
                $saved_view->params = json_encode($params);
                $saved_view->show= $showinsaved;
                $saved_view->useragent=$_SERVER['HTTP_USER_AGENT'];
                $bloc->saved_views()->insert($saved_view);
            }

            $params['header']=      true;
            $params['crop']=      true;
            $params['reduce']=      true;

            $svg= self::profil_svg($profilData, $params);

            File::put($saved_view->getDirectory().'view'.$saved_view->id.'.svg', $svg );
    }


}