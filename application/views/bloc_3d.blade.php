@layout('bloc_layout')

@section('bodyclass')
bloc3d@endsection

@section('title')
{{__('georigami.title')}}
@endsection
@section('bloc_content')

<div class='row'>

  <div class="div3Dview span8"></div>

  <div class="span4">

     <div id='facepicker' class="btn-group">
      <a data-face='N' href='?vscale={{ $vscale }}&face=N' class="btn@if ($face=='N')
      active@endif">{{__('georigami.nface')}}</a>
      <a data-face='W' href='?vscale={{ $vscale }}&face=W' class="btn@if ($face=='W')
      active@endif">{{__('georigami.wface')}}</a>
      <a data-face='S' href='?vscale={{ $vscale }}&face=S' class="btn@if ($face=='S')
      active@endif">{{__('georigami.sface')}}</a>
      <a data-face='E' href='?vscale={{ $vscale }}&face=E' class="btn@if ($face=='E')
      active@endif">{{__('georigami.eface')}}</a>
    </div>

    <div class=''>
      <form method='get' class='form-inline'>
        <input type='hidden' name='face' value='{{ $face }}'/>
        <label for="vscale">{{__('georigami.verticalscale')}}</label>
        <input class="vs-input" name='vscale' value="{{ $vscale }}" type="number" step="0.1" min="0" max="5">
        <input type='submit' value='{{__('georigami.update')}}' class='vs-update btn'/>
      </form>
    </div>

  </div>

</div>

@endsection

@section('bloc_menu')
          <li class=""><a data-action="profil" href="{{ $bloc->get_url('profil') }}?vscale={{ $vscale }}&face={{ $face }}">{{__('georigami.profil')}}</a></li>
          <li class="active"><a href="#">{{__('georigami.3dview')}}</a></li>
          <li class=""><a data-action="print" href="{{ $bloc->get_url('print') }}?vscale={{ $vscale }}&face={{ $face }}">{{__('georigami.printmodel')}}</a></li>
@endsection

@section('script')
        <script>
            Georigami.bloc={{ $bloc_json }};
        </script>
@endsection
