import {sendErrorLog} from '../log.js'
$(document).ready(function() {
    agendaDiaria();
});

// Função para capturar o valor do cookie
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

// Função agendaDiaria
function agendaDiaria() {
    // Capturar o user_id do cookie
    let userId = getCookie('user_id');

    $.ajax({
        url: "../php/leitura/ler_agenda.php",
        type: 'GET',
        data: { user_id: userId }, // Enviar o user_id como parâmetro
        success: function(response) {
            
            let data = response;

            var quadro = document.getElementById("meu-quadro");
            quadro.innerHTML = '';

            

            data.forEach(e => {
                quadro.innerHTML += `
                    <tr>
                        <td>${e.data}</td>
                        <td>${e.horario_inicio}</td>
                        <td>${e.horario_fim}</td>
                        <td>${e.medico}</td>
                        <td>${e.hospital}</td>
                        <td>${e.material}</td>
                        <td>${e.convenio}</td>
                        <td>${e.observacoes}</td>
                    </tr>
                `;
            });
        },
        error: function(xhr, status, error) {
            sendErrorLog(xhr, status, error, {
                arquivo: "dash.html",
                linha: 487,  // Linha do código onde o erro ocorreu
                funcao_metodo: "agendaDiaria",
                url_requisicao: location.href,
                dados_requisicao: 'cookie_user_id'
            });
        }
    });
}