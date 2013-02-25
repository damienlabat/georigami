@layout('bloc_layout')


@section('bodyclass')
bloc3d@endsection


@section('title')
Georigami - {{ $bloc->location->name }} (bloc n° {{ $bloc->id }})
@endsection





@section('bloc_content')

 
  <div class="div3Dview"></div>
  <br/>
 <div class='row'>   


  <div class="span6">
      <form method='get' class='form-inline'>
        <input type='hidden' name='face' value='{{ $face }}'/>
        vertical scale <input class="vs-input span1" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0.1">
        <input type='submit' value='update' class='btn'/>
      </form>
  </div>



  <div class="btn-group span6">
    <a href='?vscale={{ $vscale }}&face=N' class="btn@if ($face=='N') active@endif">North face</a>
    <a href='?vscale={{ $vscale }}&face=W' class="btn@if ($face=='W') active@endif">West face</a>
    <a href='?vscale={{ $vscale }}&face=S' class="btn@if ($face=='S') active@endif">South face</a>
    <a href='?vscale={{ $vscale }}&face=E' class="btn@if ($face=='E') active@endif">East face</a>
  </div>

</div>
  

@endsection



@section('bloc_menu')          
          <li class=""><a href="{{ $bloc->get_url('profil') }}?vscale={{ $vscale }}&face={{ $face }}">profil</a></li>
          <li class="active"><a href="#">preview 3D</a></li>
          <li class=""><a href="{{ $bloc->get_url('print') }}?vscale={{ $vscale }}&face={{ $face }}">print</a></li>
@endsection





          

@section('script')     
        <script>            
            Georigami.bloc={{ $bloc_json }};
            Georigami.face='{{ $face }}';        
        </script>
@endsection      
