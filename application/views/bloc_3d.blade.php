@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})
@endsection





@section('bloc_content')

 
  <div class="div3Dview"></div>
  <br/>
 <div class='row'>   


  <div class="span6">
      <form method='get' class='form-inline'>
        <input type='hidden' name='face' value='{{$data['face']}}'/>
        vertical scale <input class="vs-input span1" name='vscale' value="{{$data['vscale']}}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='btn'/>
      </form>
  </div>



  <div class="btn-group span6">
    <a href='?vscale={{$data['vscale']}}&face=N' class="btn@if ($data['face']=='N') active@endif">North face</a>
    <a href='?vscale={{$data['vscale']}}&face=W' class="btn@if ($data['face']=='W') active@endif">West face</a>
    <a href='?vscale={{$data['vscale']}}&face=S' class="btn@if ($data['face']=='S') active@endif">South face</a>
    <a href='?vscale={{$data['vscale']}}&face=E' class="btn@if ($data['face']=='E') active@endif">East face</a>
  </div>

</div>
  

@endsection



@section('bloc_menu')          
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'profil')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">profil</a></li>
          <li class="active"><a href="#">preview 3D</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'print')) }}?vscale={{$data['vscale']}}&face={{$data['face']}}">print</a></li>
@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{ $data['bloc_json'] }};
            Georigami.face='{{ $data['face'] }}';
            
           $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
