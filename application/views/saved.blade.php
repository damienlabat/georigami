@layout('layout')

@section('bodyclass')
savedindex@endsection

@section('title')
{{__('georigami.title')}} - {{__('georigami.lastsaved')}}
@endsection

@section('content')
<h1>{{__('georigami.lastsaved')}}</h1>
<div class="row">

 @foreach ($savedviews->results as $savedview)

            <div class=' bloc3 clearfix'>
              <img class='profil' src="{{URL::base()}}svg/view/{{ $savedview->getDirectoryNum() }}/view{{ $savedview->id }}.svg" title="{{$savedview->bloc->location->name}}">

              <a href='{{ $savedview->get_url() }}'>
               <h4><?php
            if ($savedview->bloc->location->name!='unknown place')  echo $savedview->bloc->location->name;
            elseif  ($savedview->bloc->location->adminname4!='')  echo $savedview->bloc->location->adminname4;
            elseif  ($savedview->bloc->location->adminname3!='')  echo $savedview->bloc->location->adminname3;
            else echo __('georigami.unnamed');
               ?></h4>
                @if ($savedview->bloc->location->countrycode!=='')
                <img class='flag' src="{{URL::base()}}img/flags/{{ strtolower($savedview->bloc->location->countrycode) }}.png" title="{{$savedview->bloc->location->countryname}}" alt=""/><br/>
                @endif
                <span class='countryname'>{{$savedview->bloc->location->countryname}}</span><br/>
                {{$savedview->bloc->location->adminname1}}<br/>
               <br/>
                {{$savedview->created_at_localized}}<br/>

              </a>

            </div>


    @endforeach

</div>

    {{ $savedviews->links(1, Paginator::ALIGN_CENTER); }}

@endsection
