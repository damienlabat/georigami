@layout('bloc_layout')





@section('title')
Georigami - {{$data['bloc']['location']['name']}} (bloc n° {{$data['bloc']['id']}})@endsection


@section('bodyclass')
print@endsection



@section('bloc_content')







<?php 
  $hscale= 100;
  $vscale= $data['vscale'] * $hscale;
?>





  <h4>horizontal</h4>
  <div class='row'>

  @foreach ($data['bloc']['coords']->h as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$hscale }} {{ -0.01*$vscale }} {{ 1.02*$hscale }} {{ ($slice->m+0.12)*$vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=$c[0]*$hscale.','.($slice->m-$c[1])*$vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".(1*$hscale).",".($slice->m+0.1)*$vscale.",0,".($slice->m+0.1)*$vscale."'/>";

      $di=round( count($slice->c)/($data['bloc']['vslices']+2 ));
      for ($i=$di; $i <= count($slice->c)-$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".$slice->c[$i][0]*$hscale."' x2='".$slice->c[$i][0]*$hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$vscale."' y2='".($slice->m+0.1)*$vscale."'  />";
      }

    ?>
      <text x="{{0.01*$hscale}}" y="{{ ($slice->m+0.09)*$vscale }}" >{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach

</div>



  <h4>vertical</h4>
  <div class='row'>

  @foreach ($data['bloc']['coords']->v as $slice)
  <div class='span12'>
    <svg  class='svgprint' viewBox="{{ -0.01*$hscale }} {{ -0.01*$vscale }} {{ 1.02*$hscale }} {{ ($slice->m+0.12)*$vscale }}">
    <?php


      $coord='';
      foreach ($slice->c as $c) $coord.=$c[0]*$hscale.','.($slice->m-$c[1])*$vscale.',';
      echo "<polygon class='svgslice'  points='" . $coord . " ".(1*$hscale).",".($slice->m+0.1)*$vscale.",0,".($slice->m+0.1)*$vscale."'/>";

      $di=round( count($slice->c)/($data['bloc']['hslices']+2 ));
      for ($i=$di; $i <= count($slice->c)-$di; $i=$i+$di) { 
         echo "<line class='svgcut'  x1='".$slice->c[$i][0]*$hscale."' x2='".$slice->c[$i][0]*$hscale."' y1='".($slice->m-($slice->c[$i][1]-0.1)/2)*$vscale."' y2='".($slice->m-($slice->c[$i][1]))*$vscale."'  />";
      }

    ?>
      <text x="{{0.01*$hscale}}" y="{{ ($slice->m+0.09)*$vscale }}" >{{$slice->t}}</text>
    </svg>
  </div>

  @endforeach





</div>




    <br/>
    <div class='clearfix'>vertical scale <input class="vs-input span1" value="{{$data['vscale']}}" type="number" step="0.1" min="0.1"></div>

@endsection



@section('bloc_menu')
          <li class=""><a href="{{URL::to_route('get', array($data['bloc']['id'])) }}">preview 3D</a></li>
          <li class=""><a href="{{URL::to_route('getplus', array($data['bloc']['id'], 'profil')) }}">profil</a></li>
          <li class="active"><a href="#">print</a></li>
@endsection





          

@section('script')         

        <script>
            
            Georigami.bloc={{$data['bloc_json']}};
            
            $(function() {  Georigami.initBloc();    });
           

        </script>

@endsection      
