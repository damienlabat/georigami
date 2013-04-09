@layout('layout')

@section('title')
{{__('georigami.title')}}
@endsection

@section('bodyclass')
welcome@endsection

@section('content')

            @render('carousel')

@endsection
