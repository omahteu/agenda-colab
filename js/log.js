export function sendErrorLog(xhr, status, error, additionalData = {}) {
    // Capturar informações da requisição e do erro
    const logData = {
        tipo_erro: status || "Unknown",
        mensagem_erro: xhr && xhr.responseText ? xhr.responseText : error || "No error message available",
        arquivo: additionalData.arquivo || window.location.href,  // Usar a URL da página como arquivo
        linha: additionalData.linha || 0,  // Linha pode ser personalizada se houver
        funcao_metodo: additionalData.funcao_metodo || "N/A",
        usuario_id: getCookie('user_id') || 7,  // Adicionar um fallback se o cookie não existir
        url_requisicao: additionalData.url_requisicao || (xhr && xhr.responseURL ? xhr.responseURL : "N/A"),
        dados_requisicao: JSON.stringify(additionalData.dados_requisicao || {}),
        dados_resposta: xhr && xhr.responseText ? xhr.responseText : "No response",
    };

    // Enviar os dados via AJAX para logs.php
    $.ajax({
        url: "../php/logs.php",
        type: "POST",
        data: JSON.stringify(logData),  // Envia os dados em formato JSON
        contentType: "application/json",  // Definir o tipo de conteúdo
        processData: false,  // Impedir o processamento automático dos dados
        success: function (response) {
            console.log("Log de erro enviado com sucesso.");
        },
        error: function (xhr, status, error) {
            console.error("Falha ao enviar o log de erro:", error);
        }
    });
}

function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}