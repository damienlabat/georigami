@layout('layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('content')

         <ul class="pager">            
              @if ($data['prev']!=null)
              <li class="previous">
                  <a href="{{URL::to_route('get', array($data['prev']->id)) }}" title="{{$data['prev']->location->name}}">&larr; Older</a>              
              @else
              <li class="previous disabled">
                  <a href="#">&larr; Older</a>
              @endif
              </li>
              @if ($data['next']!=null)
              <li class="next">
                  <a href="{{URL::to_route('get', array($data['next']->id)) }}" title="{{$data['next']->location->name}}">Newer &rarr;</a>         
              @else
              <li class="next disabled">
                  <a href="#">Newer &rarr;</a>
              @endif
              </li>
          </ul>



        <div class='row'>
   
            <div class="span2">
               <img src="{{URL::to_route('svg', array($data['bloc']['id'],'N')) }}" title="North view"  class="hidden-phone">
            </div>
            <div class="span2">
              <a href='{{URL::to_route('location', array($data['bloc']['location']['id'])) }}'>
               <h4>{{$data['bloc']['location']['name']}}</h4></a>
                <img src="{{URL::base()}}/img/flags/{{ strtolower($data['bloc']['location']['countrycode']) }}.png" title="{{ $data['bloc']['location']['countrycode'] }}" alt="" />  <a href='{{URL::to_route('map')}}#{{strtolower($data['bloc']['location']['countrycode'])}}'>{{ Geoname::getISO3166( $data['bloc']['location']['countrycode'])}}</a><br/>
                <span title="{{Geoname::getFCode($data['location']->fcode)[1]}}">{{Geoname::getFCode($data['location']->fcode)[0]}}</span><br/>
                <!--{{Geoname::getFcl($data['location']->fcl)}}<br/-->
                {{$data['bloc']['location']['adminname1']}}<br/>
              <!--/a-->
            </div>
            <div class="span6">
                {{$data['bloc']['hslices']}} x {{$data['bloc']['vslices']}}<br/>
                {{$data['bloc']['width']}}m x {{$data['bloc']['height']}}m<br/>
                {{$data['bloc']['created_at']}}
            </div>
      </div>




<ul class="nav nav-tabs">@yield('bloc_menu')</ul>

      
      @yield('bloc_content')



       


                     


 


@endsection





          

@section('script') 

@endsection      
