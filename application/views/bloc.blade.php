@layout('layout')

@section('content')
   
           <h1>bloc n°{{ $data['bloc']->id }}</h1>
           <h2>{{ $data['bloc']->lat }} {{ $data['bloc']->lng }}</h2>
           <input class="vs-input span1" value="1"> <button class="btn btn-mini update">Update</button>
           <div class="div3Dview"></div>
           <div class="divPaperBtn"></div>

@endsection





          

@section('script')           
        <script>
            var Georigami={};
            Georigami.bloc={{$data['bloc_json']}};
            $(function() {  Georigami.initBloc();    });
           

        </script>
@endsection      
