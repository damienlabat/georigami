@layout('layout')

@section('bodyclass')
new@endsection

@section('title')
{{__('georigami.title')}} - {{__('georigami.build')}}
@endsection

@section('content')

            <h1>{{__('georigami.build')}}</h1>
            <div id="map-canvas"></div>
            <form id='paramform' class='form-horizontal'>

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#setarea" data-toggle="tab">{{__('georigami.setarea')}}</a></li>
                    <li><a href="#setslices" data-toggle="tab">{{__('georigami.setslices')}}</a></li>

                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="setarea">

                        <div class="row">
                            <div class="span5">

                                <div class='control-group livechange'>
                                    <label class="control-label" for="input-latitude">{{__('georigami.latitude')}}</label>
                                    <div class="controls"><input type="number" step="any" min="-90" max="90" id="input-latitude"></div>
                                </div>
                                <div class='control-group livechange'>
                                    <label class="control-label" for="input-longitude">{{__('georigami.longitude')}}</label>
                                    <div class="controls"><input type="number" step="any" min="-180" max="180" id="input-longitude"></div>
                                </div>
                                <div class='control-group'>
                                    <label class="control-label" for="input-search">{{__('georigami.searchbyname')}}</label>
                                    <div class="controls"><input type="text" id="input-search" placeholder="{{__('georigami.searchbynameplaceholder')}}" value=""></div>
                                    <div id="search-result"></div>
                                </div>

                            </div>

                            <div class="span5">
                                <div class='control-group livechange'>
                                    <label class="control-label" for="input-width">{{__('georigami.width')}}</label>
                                    <div class="controls"><input type="number" step="1" min="1"  id="input-width"> m</div>
                                </div>
                                <div class='control-group livechange'>
                                    <label class="control-label" for="input-height">{{__('georigami.height')}}</label>
                                    <div class="controls"><input type="number" step="1" min="1" id="input-height"> m</div>
                                </div>
                                 <div class='control-group livechange'>
                                    <label class="control-label" for="input-rotate">{{__('georigami.rotate')}}</label>
                                    <div class="controls"><input type="range" step="1" min="-45" max="45" id="input-rotate"> °</div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane" id="setslices">

                        <div class="row">
                            <div class="span5">

                                <div class='control-group livechange tip' data-toggle="tooltip" title="{{__('georigami.hslicestip')}}">
                                    <label class="control-label" for="input-horizontal-slices">{{__('georigami.hslices')}}</label>
                                    <div class="controls"><input type="range" step="1" min="2" max="100" id="input-horizontal-slices"></div>
                                </div>

                                <div class='control-group livechange tip' data-toggle="tooltip" title="{{__('georigami.vslicestip')}}">
                                    <label class="control-label" for="input-vertical-slices">{{__('georigami.vslices')}}</label>
                                    <div class="controls"><input type="range" step="1" min="2" max="100" id="input-vertical-slices"></div>
                                </div>

                            </div>

                            <div class="span5">

                                <div class='control-group livechange tip' data-toggle="tooltip" title="{{__('georigami.hxsamplingtip')}}">
                                    <label class="control-label" for="input-hsampling">{{__('georigami.hxsampling')}}</label>
                                    <div class="controls"><input type="range" id="input-hsampling" step="1" min="1" max="170"></div>
                                </div>
                                <div class='control-group tip' data-toggle="tooltip" title="{{__('georigami.hsamplingtip')}}">
                                    <label class="control-label" for="input-horizontal-samples">{{__('georigami.hsampling')}}</label>
                                    <div class="controls"><input type="text" id="input-horizontal-samples" class="uneditable-input span2" readonly='readonly'> (max 500)</div>
                                </div>
                                <div class='control-group livechange tip' data-toggle="tooltip" title="{{__('georigami.vxsamplingtip')}}">
                                    <label class="control-label" for="input-vsampling">{{__('georigami.vxsampling')}}</label>
                                    <div class="controls"><input type="range" id="input-vsampling" step="1" min="1" max="170"></div>
                                </div>
                                <div class='control-group tip' data-toggle="tooltip" title="{{__('georigami.vsamplingtip')}}">
                                    <label class="control-label" for="input-vertical-samples">{{__('georigami.vsampling')}}</label>
                                    <div class="controls"><input type="text" id="input-vertical-samples" class="uneditable-input span2" readonly='readonly'> (max 500)</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <p id='request-count'  class="span5"></p>
                <p id='location-count' class="span5"></p>


                </form>



                <button id="start-btn" class="btn btn-primary btn-large span4">{{__('georigami.loadbutton')}}</button>
                <button id="cancel-btn" class="btn btn-small span2 disabled">{{__('georigami.cancelbutton')}}</button>
                <div id="status" class="hide"><p class='text'></p></div>
                <div id="canvas"></div>
                <div id="resultats"></div>
@endsection

@section('script')
        <script>
            Georigami.area= {

                    lat:{{$lat}},
                    lng:{{$lng}},
                    width:{{$width}},
                    height:{{$height}},
                    rotate:{{$rotate}},

                    vSlices:{{$vSlices}},
                    hSlices:{{$hSlices}},
                    hsampling:{{$hsampling}},
                    vsampling:{{$vsampling}}
            };
        </script>
@endsection
