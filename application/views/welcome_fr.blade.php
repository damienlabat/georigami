@layout('layout')

@section('title')
{{__('georigami.title')}}
@endsection

@section('bodyclass')
welcome@endsection

@section('content')
    <div class='row'>

        <div class='span6 hero-unit'>
            <h1>Georigami</h1>
            <br/>
            <p>Fabriquez des montagnes en papier et plein d'autres choses à partir des données topographiques de google map.</p>
        </div>

        <div class='span6'>
            @render('carousel')
        </div>

    </div>
@endsection
