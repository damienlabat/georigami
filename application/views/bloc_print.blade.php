@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('bloc_content')

 
  print TODO!

@endsection



@section('bloc_menu')
          <li class=""><a href="{{URL::to_route('get', array($data['bloc']['id'])) }}">preview 3D</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'profil')) }}">profil</a></li>
          <li class="active"><a href="#">print</a></li>
@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{$data['bloc_json']}};
            
            $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
