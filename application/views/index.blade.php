@layout('layout')

@section('bodyclass')
index@endsection

@section('title')
{{__('georigami.title')}} - {{__('georigami.lastones')}}
@endsection

@section('content')
<h1>{{__('georigami.lastones')}}</h1>
<div class="pagination pagination-mini">
  <ul>
    <li @if ($face=='N') 
    class="active" 
    @endif><a href='?page={{$blocs->page}}&face=N'>{{__('georigami.nface')}}</a></li>
    <li @if ($face=='E') 
    class="active" 
    @endif><a href='?page={{$blocs->page}}&face=E'>{{__('georigami.eface')}}</a></li>
    <li @if ($face=='S') 
    class="active" 
    @endif><a href='?page={{$blocs->page}}&face=S'>{{__('georigami.sface')}}</a></li>
    <li @if ($face=='W') 
    class="active" 
    @endif><a href='?page={{$blocs->page}}&face=W'>{{__('georigami.wface')}}</a></li>
  </ul>
</div>
<div class="row">

 @foreach ($blocs->results as $bloc)

            <div class=' bloc clearfix'>
              <img src="{{URL::base()}}svg/{{ $bloc->getDirectoryNum() }}/bloc{{ $bloc->id }}{{ $face }}.svg" title="{{ $face }} view">

              <a href='{{ $bloc->get_url('profil') }}?face={{ $face }}'>
               <h4><?php
            if ($bloc->location->name!='unknown place')  echo $bloc->location->name;
            elseif  ($bloc->location->adminname4!='')  echo $bloc->location->adminname4;
            elseif  ($bloc->location->adminname3!='')  echo $bloc->location->adminname3;
            else echo __('georigami.unnamed');
               ?></h4>
                @if ($bloc->location->countrycode!=='')
                <img class='flag' src="{{URL::base()}}img/flags/{{ strtolower($bloc->location->countrycode) }}.png" title="{{$bloc->location->countryname}}" alt=""/><br/>
                @endif
                <span class='countryname'>{{$bloc->location->countryname}}</span><br/>
                {{$bloc->location->adminname1}}<br/>
                <br/>{{__('georigami.altitude',array('from'=>round($bloc->min), 'to'=>round($bloc->max)))}}<br/>
               <!-- {{$bloc->width}}m x {{$bloc->height}}m<br/>
                {{__('georigami.rotation')}}: {{$bloc->rotate}}°<br/> -->
                {{__('georigami.slices')}}: {{$bloc->hslices}} x {{$bloc->vslices}}<br/>
                <!--{{__('georigami.samples')}}: {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}}<br/>-->
                {{$bloc->created_at_localized}}
              </a>

            </div>

    @endforeach

</div>

    {{ $blocs->links(1, Paginator::ALIGN_CENTER); }}

@endsection
