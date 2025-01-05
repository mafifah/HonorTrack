@extends ('master')
@section('navigation')
@endsection
@section('content')
<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Setting System</h5>
        <!-- General Form Elements -->
        <form method="POST" action="{{ route('setting-system-update') }}">
          @csrf
          <div class="row mb-3">
            <label for="inputText" class="col-sm-6 col-form-label">Lokasi Latitude</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="schoolLat" id="schoolLat" value="{{ $schoolLat->nama }}" required>
              <input type="hidden" class="form-control" name="schoolLatid" value="{{ $schoolLat->id }}" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputText" class="col-sm-6 col-form-label">Lokasi Longitude</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="schoolLong" id="schoolLong" value="{{ $schoolLong->nama }}" required>
              <input type="hidden" class="form-control" name="schoolLongid" value="{{ $schoolLong->id }}" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="inputText" class="col-sm-6 col-form-label">Status Validasi Lokasi</label>
            <div class="col-sm-6">
              <select class="js-example-basic-single js-states form-control" name="statusValidasi" aria-label="Default select example" required>
                @if($statusValidasi->nama == 'Yes')
                <option value="Yes" selected>Aktif</option>
                <option value="No">Nonaktif</option>
                @else
                <option value="Yes">Aktif</option>
                <option value="No" selected>Nonaktif</option>
                @endif
              </select>
            </div>
          </div>
          <input type="hidden" class="form-control" name="statusValidasiid" value="{{ $statusValidasi->id }}" required>
          <div class="row mb-3">
            <div class="col-sm-6">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </form><!-- End General Form Elements -->
      </div>
    </div>
    </div>
  <div class="col-lg-6">
        <center>
            <div id="map"></div>
        </center>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // fungsi ini akan berjalan ketika akan menambahkan gambar dimana fungsi ini
    // akan membuat preview image sebelum kita simpan gambar tersebut.      
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    // Ketika tag input file denghan class image di klik akan menjalankan fungsi di atas
    $("#image").change(function() {
        readURL(this);
    });

        // Define the different tile layers
        var streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        });

        var satellite = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '© OpenTopoMap contributors'
        });

        var userLat = -7.105051082400209;
        var userLng = 113.81125484015176;
            // Try to get the user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // Get the user's latitude and longitude
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                console.log(userLat);
                console.log(userLng);
                // Center the map on the user's location
                map.setView([userLat, userLng], 15); // Adjust the zoom level as needed
            }, function(error) {
                console.error("Error getting location: " + error.message);
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }

        // Initialize the map with the streets layer
        var map = L.map('map', {
            center: [userLat, userLng],
            zoom: 9,
            layers: [streets] // Set default layer here
        });

        // Define base layers and overlays
        var baseLayers = {
            "Streets": streets,
            "Satellite": satellite
        };

        var overlays = {
            "Streets": streets,
            "Satellite": satellite
        };

        // Add layer control to the map
        L.control.layers(baseLayers, overlays).addTo(map);


        // Add a draggable marker
        var curLocation = [userLat, userLng];
        var marker = new L.marker(curLocation, {
            draggable: 'true',
        });
        map.addLayer(marker);

        marker.on('dragend', function(event) {
            var location = marker.getLatLng();
            marker.setLatLng(location, {
                draggable: 'true',
            }).bindPopup(location.toString()).update();
            document.querySelector("[name=schoolLat]").value = location.lat;
            document.querySelector("[name=schoolLong]").value = location.lng;
        });

        var locLat = document.querySelector("[name=schoolLat]");
        var locLong = document.querySelector("[name=schoolLong]");
        map.on("click", function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            if (!marker) {
                marker = L.marker(e.latlng).addTo(map);
            } else {
                marker.setLatLng(e.latlng);
            }
            locLat.value = lat;
            locLong.value = lng;
        });
</script>
@endsection