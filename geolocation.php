<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Get the Latitude and Longitude of address</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h3>Enter Location or drag pin to location on the map</h3>
		<div class="row">
			<div class="col-md-4">
				<div class="form-horizontal">
			
					<div class="row">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="form-group">
									<label for="address" class="control-label col-sm-3">
										<span>Address</span>
									</label>
									<div class="col-sm-6">
										<input type="text" id="address" name="address" class="form-control" autocomplete="off" value="<?= isset($_GET['address']) && !empty($_GET['address'])? htmlspecialchars($_GET['address']) : '' ?>" />
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="radius" class="control-label col-sm-3">
										<span>Radius</span>
									</label>
									<div class="col-sm-6">
										<input type="number" id="radius" value="20" name="radius" class="form-control"/>
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="latitude" class="control-label col-sm-3">
										<span>Latitude</span>
									</label>
									<div class="col-sm-6">
										<input type="number" id="lat" name="lat" class="form-control"/>
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="lng" class="control-label col-sm-3">
										<span>Longtitude</span>
									</label>
									<div class="col-sm-6">
										<input type="number" id="lng" name="lng" class="form-control"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="text-center" style="width:100%">
						<button class="btn btn-primary save-location">Save Location</button>
					</div>
					<br>
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="text-center"><div id="somecomponent" style="width: 100%; height: 400px;"></div>	</div>			
			</div>
		</div>
		
		
	</div>
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8hclMOOPdv1qow53H7FyWwo60bgW9qLM&libraries=visualization,drawing,geometry,places"></script> 
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-locationpicker/0.1.12/locationpicker.jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>

	<script>
	var caller;
	var address = '<?= isset($_GET['address']) && !empty($_GET['address'])? htmlspecialchars($_GET['address']) : '' ?>';
	$(document).ready(function(){
		$('#somecomponent').locationpicker({
			location: {
		        latitude: 6.5483136,
		        longitude: 3.257414
		    },
		    radius: 300,
		    inputBinding: {
		        latitudeInput: $('#lat'),
		        longitudeInput: $('#lng'),
		        radiusInput: $('#radius'),
		        locationNameInput: $('#address')
		    },
		    enableAutocomplete: true,
		    onchanged: function (currentLocation, radius, isMarkerDropped) {
		        //console.log("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
		        var win = caller || window.opener;
		        var data = {
					lat: currentLocation.latitude,
					lng: currentLocation.longitude
				};
				win.postMessage(data, location.origin);
				console.log(data);
		    },
		    oninitialized: function(component) {
		    	var urlParams = new URLSearchParams(window.location.search);
		    	if(urlParams.has('address') && urlParams.get('address').length > 1){
		    	// if(typeof address != 'undefined' || address.length > 1){
		    		$('#address').val(_.escape(urlParams.get('address')));
		    		// $('#address').val(address);
		    	}
		    	$('#address').focus();
		    }
		});
		$('button.save-location').click(function(e){
				e.preventDefault();
				var data = {
					lat: $('#lat').val(),
					lng: $('#lng').val()
				};
				var win = caller || window.opener;
				win.postMessage(data, location.origin);
				console.log(data);
				window.close();
		});
		function receiveMessage(event){
			console.log('called');
			if(event.origin !== location.origin){
				return;
			}
			caller = event.source; 
			if(event.data){
				var providedData = event.data;
				var address = providedData.address;
				if(typeof address !== 'undefined' ){
					$('#address').val(address).focus();
				}
			} 
			
		}
		window.addEventListener("message", receiveMessage, false);
	});
</script>
</body>
</html>