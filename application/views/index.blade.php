@layout('layout')

@section('content')

    <div id="map-canvas"></div>  
    @foreach ($data['blocs'] as $bloc)

       <div class='bloc'>
        {{$bloc->lat}} {{$bloc->lng}} <a href="./bloc{{$bloc->id}}">open</a> {{-- TODO --}}
       </div>
    @endforeach
   
@endsection




          

@section('script')    

        <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>       
        <script>
            var Georigami={};
            Georigami.blocs_list={{$data['blocs_json']}};
            $(function() {  Georigami.initIndex();    });
        </script>
@endsection      
