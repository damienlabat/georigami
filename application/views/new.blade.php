@layout('layout')

@section('content')
   
            <h2>Select area</h2>
            <form id='paramform' class='form-horizontal'>

                <div class="row">  

	                <div class="span6"> 

							<legend>Set center</legend>
                            <div class='control-group'>
    							<label class="control-label" for="input-latitude">Latitude</label>
    							<div class="controls"><input type="number" step="any" min="-90" max="90" id="input-latitude" class="span2 livechange"></div>
                            </div>
                            <div class='control-group'>
    							<label class="control-label" for="input-longitude">Longitude</label>
    							<div class="controls"><input type="number" step="any" min="-180" max="180" id="input-longitude" class="span2 livechange"></div>
                            </div>
                            <div class='control-group'>
    							<label class="control-label" for="input-search">Search by name</label>
    							<div class="controls"><input type="text" id="input-search" placeholder="Type something…" class="span2"></div>
                                <div id="search-result"></div>
                            </div>

					</div>


					<div class="span6">                	

							<legend>Set size</legend>	

                            <div class='control-group'>
    							<label class="control-label" for="input-width">Width</label>
    							<div class="controls"><input type="number" step="1" min="1" id="input-width" class="span1 livechange"> m</div>
                            </div>
                            <div class='control-group'>
    							<label class="control-label" for="input-height">Height</label>
    							<div class="controls"><input type="number" step="1" min="1" id="input-height" class="span1 livechange"> m</div>
                            </div>

					</div>

                </div>
                <div class="row"> 

					<div class="span6">            

							<legend>Set slices number</legend>

                            <div class='control-group'>
                                <label class="control-label" for="input-horizontal-slices">horizontal slices</label>
                                <div class="controls"><input type="number" step="1" min="2" max="500" id="input-horizontal-slices" class="span1 livechange"></div>
                            </div>

                            <div class='control-group'>
    							<label class="control-label" for="input-vertical-slices">vertical slices</label>
    							<div class="controls"><input type="number" step="1" min="2" max="500" id="input-vertical-slices" class="span1 livechange"></div>
                            </div>
                            
                    </div>

                    <div class="span6">  

                            <legend>Set slices samples</legend>			

                            <div class='control-group'>
                                <label class="control-label" for="input-horizontal-samples">horizontal slices samples</label>
                                <div class="controls"><input type="number" id="input-horizontal-samples" class="span1"></div>
                            </div>
                            <div class='control-group'>
                                <label class="control-label" for="input-vertical-samples">vertical slices samples</label>
                                <div class="controls"><input type="number" id="input-vertical-samples" class="span1"></div>
                            </div>

					</div>                    
                        

                </div>

                <!--button id="update-btn" class="btn btn-primary btn-small span4">Update</button-->                    
                    
			    </form>

                

            	<div id="map-canvas"></div>                

			    <button id="start-btn" class="btn btn-primary btn-large span4">Load data</button>
                <div id="status"><p class='text'></p></div>
                <div id="resultats"></div>
@endsection





          

@section('script')           
        <script>

            var Georigami={
                area: {
                    lat:0,
                    lng:0,
                    width:10000, 
                    height:10000,
                    vSlices:11,
                    hSlices:11,
                    vSamples:13,
                    hSamples:13
                }
            };

  
            $(function() {  Georigami.initNew();    });

        </script>
@endsection      
