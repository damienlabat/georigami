@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('bloc_content')

 
  <div class="div3Dview"></div>
  <br/>
    vertical scale <input class="vs-input span1" value="{{$data['vscale']}}" type="number" step="0.1" min="0.1">
  

@endsection



@section('bloc_menu')
          <li class="active"><a href="#">preview 3D</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'profil')) }}">profil</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'print')) }}">print</a></li>
@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{$data['bloc_json']}};
            
           $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
