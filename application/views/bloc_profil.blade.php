@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('bloc_content')

  <a name='profil' class='anchor'></a>
  <div class="btn-group">
    <a href='?face=N#profil' class="btn@if ($data['face']=='N') active@endif">North face</a>
    <a href='?face=W#profil' class="btn@if ($data['face']=='W') active@endif">West face</a>
    <a href='?face=S#profil' class="btn@if ($data['face']=='S') active@endif">South face</a>
    <a href='?face=E#profil' class="btn@if ($data['face']=='E') active@endif">East face</a>
  </div>

<div>
  
  <img src="{{URL::to_route('svg', array($data['bloc']['id'],$data['face'])) }}" title="North face">
</div>



@endsection



@section('bloc_menu')
          <li class=""><a href="{{URL::to_route('get', array($data['bloc']['id'])) }}">preview 3D</a></li>
          <li class="active"><a href="">profil</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'print')) }}">print</a></li>
@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{$data['bloc_json']}};
            
            $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
