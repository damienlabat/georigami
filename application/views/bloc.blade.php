@layout('layout')

@section('content')

        <div class='row'>
   
            <div class="span2">
               <img src="./bloc{{$data['bloc']['id']}}N.svg" title="North view">
            </div>
            <div class="span2">
               <h4>{{$data['bloc']['location']['name']}}</h4>
                <img src="./img/flags/{{ strtolower($data['bloc']['location']['countrycode']) }}.png" title="{{ $data['bloc']['location']['countryname'] }}" alt="" /><br/>
                {{$data['bloc']['location']['countryname']}}<br/>
            </div>
            <div class="span6">
                {{$data['bloc']['location']['adminname1']}}<br/>
                {{$data['bloc']['width']}}m x {{$data['bloc']['height']}}m<br/>
                {{$data['bloc']['created_at']}}
              </div>
          </div>
            
           <div class="div3Dview"></div>
           vertical scale <input class="vs-input span1" value="1" type="number" step="0.1" min="0.1">
           <div class="divPaperBtn"></div>


@endsection





          

@section('script')         

        <script>
            var Georigami={};
            
           <?php /* Georigami.location={{$data['location_json']}}; */ ?>
            Georigami.bloc={{$data['bloc_json']}};
            
            $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
