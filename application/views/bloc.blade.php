@layout('layout')

@section('content')
   
           <h1>bloc n°{{ $data['bloc']->id }}</h1>
           <h2>{{ $data['bloc']->lat }} {{ $data['bloc']->lng }}</h2>
            
           <div class="div3Dview"></div>
           vertical scale <input class="vs-input span1" value="1" type="number" step="0.1" min="0.1">
           <div class="divPaperBtn"></div>

@endsection





          

@section('script')           
        <script>
            var Georigami={};
            Georigami.bloc={{$data['bloc_json']}};
            $(function() {  Georigami.initBloc();    });
           

        </script>
@endsection      
