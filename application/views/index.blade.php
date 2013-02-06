@layout('layout')

@section('content')

    <div id="map-canvas"></div>  
    @foreach ($data['blocs'] as $bloc)


       <div class='bloc'>
      
          <img src="./img/flags/{{ strtolower($bloc->geonameData->countryCode) }}.png" title="{{ $bloc->geonameData->country }}"/> {{ $bloc->geonameData->name }} <span title="{{ $bloc->geonameData->feature[1] }}">({{ $bloc->geonameData->feature[0] }})</span> <a href="./bloc{{$bloc->id}}">open</a>  

     
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
