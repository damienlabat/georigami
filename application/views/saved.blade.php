@layout('layout')

@section('bodyclass')
savedindex@endsection

@section('title')
Georigami
@endsection

@section('content')
<h1>Last saved profiles</h1>
<div class="row">

 @foreach ($savedviews->results as $savedview)

            <div class=' bloc3 clearfix'>
              <img class='profil' src="{{URL::base()}}/svg/view/{{ $savedview->getDirectoryNum() }}/view{{ $savedview->id }}.svg" title="{{$savedview->bloc->location->name}}">

              <a href='{{ $savedview->get_url() }}'>
               <h4>{{$savedview->bloc->location->name}}</h4>
                @if ($savedview->bloc->location->countrycode!=='')
                <img class='flag' src="{{URL::base()}}/img/flags/{{ strtolower($savedview->bloc->location->countrycode) }}.png" title="{{ Geoname::getISO3166($savedview->bloc->location->countrycode) }}" alt=""/><br/>
                @endif
                {{Geoname::getISO3166($savedview->bloc->location->countrycode)}}<br/>
                {{$savedview->bloc->location->adminname1}}<br/>
               <br/>
                {{$savedview->created_at}}<br/>

              </a>

            </div>


    @endforeach

</div>

    {{ $savedviews->links(1, Paginator::ALIGN_CENTER); }}

@endsection
