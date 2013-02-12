@layout('layout')

@section('content')
<h1>Last blocks</h1>
<div class="row">
  

 @foreach ($data['blocs']->results as $bloc) 
   
            <div class=' bloc clearfix'>
              <img src="./bloc{{$bloc->id}}N.svg" title="North view"></a>




              <a href='./bloc{{$bloc->id}}'>
               <h4>{{$bloc->location->name}}</h4>
                <img src="./img/flags/{{ strtolower($bloc->location->countrycode) }}.png" title="{{ $bloc->location->countryname }}" alt=""/><br/>
                {{$bloc->location->countryname}}<br/>
                <br/>
                {{$bloc->location->adminname1}}<br/>
                {{$bloc->width}}m x {{$bloc->height}}m<br/>
                {{$bloc->created_at}}<br/> 

              </a>

            </div>

               



      
    @endforeach   


    {{ $data['blocs']->links(1, Paginator::ALIGN_CENTER); }}




    

@endsection




          

@section('script')    

@endsection      
