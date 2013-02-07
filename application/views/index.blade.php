@layout('layout')

@section('content')

   
    @foreach ($data['locations'] as $location)

       <div class='locations'>
         <a href="./location{{$location['id']}}"><img src="./img/flags/{{ strtolower($location['countrycode']) }}.png" title="{{ $location['countryname'] }}"/> {{ $location['name'] }} <span title="{{ $location['featuredetail'] }}">({{ $location['feature'] }})</span> X{{ count($location['blocs']) }}</a><br/>
         @foreach ($location['blocs'] as $bloc)
          <div>
            <a href='./bloc{{$bloc['id']}}N.svg'><img src="./bloc{{$bloc['id']}}N.svg" title="North" width='200px'></a>
            <a href='./bloc{{$bloc['id']}}W.svg'><img src="./bloc{{$bloc['id']}}W.svg" title="West" width='200px'></a>
            <a href='./bloc{{$bloc['id']}}S.svg'><img src="./bloc{{$bloc['id']}}S.svg" title="South" width='200px'></a>
            <a href='./bloc{{$bloc['id']}}E.svg'><img src="./bloc{{$bloc['id']}}E.svg" title="Est" width='200px'></a>
            {{$bloc['id']}}
          </div>
        @endforeach
      
         

     
       </div>
    @endforeach
   
@endsection




          

@section('script')    

        <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js"></script>       
        <script>
            var Georigami={};
            Georigami.location_list={{$data['locations_json']}};
            $(function() {  Georigami.initIndex();    });
        </script>
@endsection      
