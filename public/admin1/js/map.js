$('#geocodeAddress').on('click', function(e){
    e.preventDefault();

    var address = $('input[name="address"]').val();
    var zipcode = $('input[name="zipcode"]').val();
    var city = $('input[name="city"]').val();
    var country = $('#country_id option:selected').text();

    fullAddress = address + ' ' + zipcode + ' ' + city + ' ' + country;

    geocoder= new google.maps.Geocoder();
    geocodeAddress(fullAddress);
});

function initMap() {

    if(typeof initPoint == 'undefined'){
        initPoint = {lat: 48.853, lng: 2.35};
    }

    map = new google.maps.Map(
        document.getElementById('googleMap'), {
            zoom: 13, center: initPoint
        }
    );

    var draggable = false;
    if (window.location.href.indexOf("admin") > -1) {
        draggable = true;
    }

    marker = new google.maps.Marker({
        position: initPoint,
        map: map,
        draggable: draggable,
    });

    // Met à jour les positions GPS après avoir déplacé
    google.maps.event.addListener(marker, 'drag', function(evt){
        $("#lat").val(evt.latLng.lat());
        $("#lng").val(evt.latLng.lng());
    });
    google.maps.event.addListener(marker, 'dragend', function(evt){
        $("#lat").val(evt.latLng.lat());
        $("#lng").val(evt.latLng.lng());
        centreCarte();
    });
}

function geocodeAddress(address) {
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            marker.setPosition(results[0].geometry.location);
            $("#lat").val(results[0].geometry.location.lat());
            $("#lng").val(results[0].geometry.location.lng());
            map.setZoom(9);
            centreCarte();
        }
        else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

function centreCarte(){
    var center = marker.getPosition();
    window.setTimeout(function(){map.panTo(center);}, 1000);
}

$(document).on("click", '.getMap', function(e) {
    e.preventDefault();

    var lat = $(this).data('lat');
    var lng = $(this).data('lng');

    if(lat && lng){
        var newLatLng = new google.maps.LatLng(lat, lng);
        marker.setPosition(newLatLng);
        map.setCenter(newLatLng);
        $('#modalMap').modal('show');
    } else{
        create_not('warning', 'Une erreur s\'est produite lors de la génération de la carte');
    }
});
