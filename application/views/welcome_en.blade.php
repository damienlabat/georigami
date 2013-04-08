@layout('layout')

@section('title')
{{__('georigami.title')}}
@endsection

@section('bodyclass')
welcome@endsection

@section('content')
            <div class="hero-unit">
                <h1>Georigami</h1>
                <p></p>

              @render('carousel')

            </div>

@endsection
