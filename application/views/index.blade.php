@layout('layout')

@section('bodyclass')
index@endsection

@section('title')
Georigami
@endsection

@section('content')
<h1>Last blocks</h1>
<div class="row">

 @foreach ($blocs->results as $bloc)

            <div class=' bloc clearfix'>
              <img src="{{URL::to_route('svg', array($bloc->id,'N')) }}" title="North view"></a>

              <a href='{{ $bloc->get_url() }}'>
               <h4>{{$bloc->location->name}}</h4>
                @if ($bloc->location->countrycode!=='')
                <img class='flag' src="{{URL::base()}}/img/flags/{{ strtolower($bloc->location->countrycode) }}.png" title="{{ Geoname::getISO3166($bloc->location->countrycode) }}" alt=""/><br/>
                @endif
                {{Geoname::getISO3166($bloc->location->countrycode)}}<br/>
                {{$bloc->location->adminname1}}<br/>
                altitude: {{round($bloc->min)}}m to {{round($bloc->max)}}m<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                rotation: {{$bloc->rotate}}°<br/>
                slices: {{$bloc->hslices}} x {{$bloc->vslices}}<br/>
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                {{$bloc->created_at}}<br/>

              </a>

            </div>

    @endforeach

</div>

    {{ $blocs->links(1, Paginator::ALIGN_CENTER); }}

@endsection
