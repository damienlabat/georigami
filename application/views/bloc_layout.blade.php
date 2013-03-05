@layout('layout')





@section('title')
Georigami - {{$bloc->location->name}} (bloc n° {{$bloc->id}})
@endsection





@section('content')

         <ul class="pager">            
              @if ($prev!=null)
              <li class="previous">
                  <a href="{{ $prev->get_url($show) }}?face={{$face}}" title="{{$prev->location->name}}">&larr; Older</a>              
              @else
              <li class="previous disabled">
                  <a href="#">&larr; Older</a>
              @endif
              </li>
              @if ($next!=null)
              <li class="next">
                  <a href="{{ $next->get_url($show) }}?face={{$face}}" title="{{$next->location->name}}">Newer &rarr;</a>         
              @else
              <li class="next disabled">
                  <a href="#">Newer &rarr;</a>
              @endif
              </li>
          </ul>



        <div id='blocinfo' class='row' data-id='{{$bloc->id}}' data-vscale='{{$vscale}}' data-face='{{$face}}' data-view='{{$show}}'>
   
            <div class="span2">
               <img src="{{URL::to_route('svg', array($bloc->id,$face)) }}" title="{{$face}} view"  class="hidden-phone bloc-face">
            </div>
            <div class="span2">
              <a href='{{ $bloc->location->get_url() }}'>
               <h4>{{$bloc->location->name}}</h4></a>
                <img src="{{URL::base()}}/img/flags/{{ strtolower($bloc->location->countrycode) }}.png" title="{{ $bloc->location->countrycode }}" alt="" />  <a href='{{URL::to_route('map')}}#{{strtolower($bloc->location->countrycode)}}'>{{ $bloc->location->countryname }}</a><br/>
                <span title="{{ $bloc->location->fcodedetail() }}">{{ $bloc->location->fcodename() }}</span><br/>

                {{$bloc->location->adminname1}}<br/>

            </div>
            <div class="span6">
                altitude: {{round($bloc->min)}}m to {{round($bloc->max)}}m<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                rotation: {{$bloc->rotate}}°<br/>
                slices: {{$bloc->hslices}} x {{$bloc->vslices}}<br/>
                {{$bloc->vslices*$bloc->vsamples + $bloc->hslices*$bloc->hsamples}} samples<br/>
                {{$bloc->created_at}}<br/> 
            </div>
      </div>


<br/>

<div class='navbar'>
  <div class="navbar-inner">
    <a class="brand" href="#">Bloc N°{{$bloc->id}}</a>
    <ul class="nav" id="bloc-menu">@yield('bloc_menu')</ul>
  </div>
</div>

      
      @yield('bloc_content')



       


                     


 


@endsection





          

@section('script') 

@endsection      
