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
            <p>Build paper mountains and other stuffs from google map topographical data.</p>
          </div>

        <div class='span6'>
            @render('carousel')
        </div>

    </div>
@endsection
