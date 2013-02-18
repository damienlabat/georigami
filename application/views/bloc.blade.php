@layout('layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('content')

        <h1>{{$data['show']}}</h1>

         <ul class="pager">            
              @if ($data['prev']!=null)
              <li class="previous">
                  <a href="./bloc{{$data['prev']->id}}" title="{{$data['prev']->location->name}}">&larr; Older</a>              
              @else
              <li class="previous disabled">
                  <a href="#">&larr; Older</a>
              @endif
              </li>
              @if ($data['next']!=null)
              <li class="next">
                  <a href="./bloc{{$data['next']->id}}" title="{{$data['next']->location->name}}">Newer &rarr;</a>         
              @else
              <li class="next disabled">
                  <a href="#">Newer &rarr;</a>
              @endif
              </li>
          </ul>



        <div class='row'>
   
            <div class="span2">
               <img src="./bloc{{$data['bloc']['id']}}N.svg" title="North view"  class="hidden-phone">
            </div>
            <div class="span2">
              <a href='./location{{$data['bloc']['location']['id']}}'>
               <h4>{{$data['bloc']['location']['name']}}</h4>
                <img src="./img/flags/{{ strtolower($data['bloc']['location']['countrycode']) }}.png" title="{{ $data['bloc']['location']['countrycode'] }}" alt="" /> {{ Geoname::getISO3166( $data['bloc']['location']['countrycode'])}}<br/>
                <span title="{{Geoname::getFCode($data['location']->fcode)[1]}}">{{Geoname::getFCode($data['location']->fcode)[0]}}</span><br/>
                <!--{{Geoname::getFcl($data['location']->fcl)}}<br/-->
                {{$data['bloc']['location']['adminname1']}}<br/>
              </a>
            </div>
            <div class="span6">
                
                {{$data['bloc']['width']}}m x {{$data['bloc']['height']}}m<br/>
                {{$data['bloc']['created_at']}}
            </div>
      </div>



    <div class='row'>

      <div class="span2">
        <ul class="nav nav-list">
          <li class="active"><a href="#">preview 3D</a></li>
          <li class=""><a href="./bloc{{$data['bloc']['id']}}/profil">profil</a></li>
          <li class=""><a href="./bloc{{$data['bloc']['id']}}/print">print</a></li>
        </ul>
         vertical scale <input class="vs-input span1" value="1" type="number" step="0.1" min="0.1">
      </div>

      <div class="div3Dview span8"></div>

    </div>
          
         <div class="divPaperBtn"></div>

       


                     


 


@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{$data['bloc_json']}};
            
            $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
