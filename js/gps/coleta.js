import {sendErrorLog} from '../log.js'

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
                console.log('Location sent successfully: ');
            },
            error: function(xhr, status, error) {
                sendErrorLog(xhr, status, error, {
                    arquivo: "coleta.js",
                    linha: 24,  // Linha do código onde o erro ocorreu
                    funcao_metodo: "sendPosition",
                    url_requisicao: location.href,
                    dados_requisicao: 'lat_len_cookie_user_id'
                });
            }
        });
    } else {
        console.error('User ID not found in cookies.');
    }
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            sendErrorLog(xhr, status, error, {
                arquivo: "coleta.js",
                linha: 24,  // Linha do código onde o erro ocorreu
                funcao_metodo: "sendPosition",
                url_requisicao: location.href,
                dados_requisicao: 'O usuário negou a solicitação de geolocalização'
            });
            break;
        case error.POSITION_UNAVAILABLE:
            sendErrorLog(xhr, status, error, {
                arquivo: "coleta.js",
                linha: 24,  // Linha do código onde o erro ocorreu
                funcao_metodo: "sendPosition",
                url_requisicao: location.href,
                dados_requisicao: 'As informações de localização não estão disponíveis.'
            });
            break;
        case error.TIMEOUT:
            sendErrorLog(xhr, status, error, {
                arquivo: "coleta.js",
                linha: 24,  // Linha do código onde o erro ocorreu
                funcao_metodo: "sendPosition",
                url_requisicao: location.href,
                dados_requisicao: 'A solicitação para obter a localização do usuário expirou.'
            });
            break;
        case error.UNKNOWN_ERROR:
            sendErrorLog(xhr, status, error, {
                arquivo: "coleta.js",
                linha: 24,  // Linha do código onde o erro ocorreu
                funcao_metodo: "sendPosition",
                url_requisicao: location.href,
                dados_requisicao: 'Erro desconhecido'
            });
            break;
    }
}

$(document).ready(function() {
    // Seu código aqui
    setInterval(updateLocation, 60000);
    updateLocation();
});
