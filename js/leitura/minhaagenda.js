import {sendErrorLog} from '../log.js'
$(document).ready(function() {
    agendaDiaria();
});

function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function agendaDiaria() {
    // Capturar o user_id do cookie
    let userId = getCookie('user_id');

    $.ajax({
        url: "../php/leitura/ler_agenda.php",
        type: 'GET',
        data: { user_id: userId }, // Enviar o user_id como parâmetro
        success: function(response) {
            let data = response;

            // Obter as datas de hoje e amanhã
            const hoje = new Date();
            const amanha = new Date();
            amanha.setDate(hoje.getDate() + 1);

            // Função para formatar a data como "YYYY-MM-DD" para comparação
            function formatDate(date) {
                let d = new Date(date);
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                let year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            }

            // Formatar as datas de hoje e amanhã no padrão "YYYY-MM-DD"
            const hojeStr = formatDate(hoje);
            const amanhaStr = formatDate(amanha);

            // Função para converter a data do formato brasileiro (dd/mm/yyyy) para ISO (yyyy-mm-dd)
            function convertDataBrasileiraParaISO(dataBrasileira) {
                const [dia, mes, ano] = dataBrasileira.split('/');
                return `${ano}-${mes}-${dia}`; // Retornar no formato ISO
            }

            var quadro = document.getElementById("minhagendadiaria");
            quadro.innerHTML = '';

            // Filtrar os dados apenas para os registros de hoje e amanhã
            data.forEach(e => {
                // Converter a data recebida para o formato ISO
                const dataISO = convertDataBrasileiraParaISO(e.data);

                // Comparar se a data do evento é igual a hoje ou amanhã
                if (dataISO === hojeStr || dataISO === amanhaStr) {
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
                }
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