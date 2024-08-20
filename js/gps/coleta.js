function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function updateLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(sendPosition, showError, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function sendPosition(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    var userId = getCookie('user_id');

    if (userId) {
        $.ajax({
            url: '../php/gps/coleta.php',
            type: 'POST',
            data: {
                user_id: userId,
                latitude: lat,
                longitude: lon
            },
            success: function(response) {
                console.log('Location sent successfully: ' + response);
            },
            error: function(xhr, status, error) {
                console.error('Error sending location: ' + error);
            }
        });
    } else {
        console.error('User ID not found in cookies.');
    }
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            alert("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            alert("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred.");
            break;
    }
}

$(document).ready(function() {
    // Seu código aqui
    setInterval(updateLocation, 60000);
    updateLocation();
});
