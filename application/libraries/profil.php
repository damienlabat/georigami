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
        $data['slCount']= count($data['coords']);

          $data['svg_vscale']= $data['vscale'] * $data['svg_hscale'];
          $data['viewbox']= self::get_vb($data);

        return View::make('svg/profil')->with($data);

    }



    public static function get_vb($data) {

        $viewbox=array( 'left'=>1000, 'right'=>0, 'top'=>1000, 'bottom'=>0 );


        foreach ($data['coords'] as $k=>$slice) {

            $SCALE= 1-$data['dscale']*((($data['slCount']-1)-$k)/$data['slCount']);

            $left=   ($k-$data['slCount']/2) *$data['dx'] + $data['dim']/2*$data['svg_hscale'] - $data['dim']/2*$data['svg_hscale']*$SCALE;
            $right=  ($k-$data['slCount']/2) *$data['dx'] + $data['dim']/2*$data['svg_hscale'] + $data['dim']/2*$data['svg_hscale']*$SCALE;

            $bottom= ($k-$data['slCount']/2) *$data['dy'] + $data['max']*$data['svg_hscale'];
            $top=    ($k-$data['slCount']/2) *$data['dy'] + $data['max']*$data['svg_hscale'] - $slice->m*$data['svg_vscale']*$SCALE;

            

            if ( $top < $viewbox['top'] )       $viewbox['top']=$top;
            if ( $bottom > $viewbox['bottom'] ) $viewbox['bottom']=$bottom;

            if ( $left < $viewbox['left'] )   $viewbox['left']=$left;
            if ( $right > $viewbox['right'] ) $viewbox['right']=$right;
        }


     //marge 5%
        $width=  $viewbox['right']-$viewbox['left'];
        $height= $viewbox['bottom']-$viewbox['top'];

        $viewbox['left']  -= $width*0.05;
        $viewbox['right'] += $width*0.05;

        $viewbox['top']  -= $height*0.05;
        $viewbox['bottom'] += $height*0.1;

        return $viewbox;

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