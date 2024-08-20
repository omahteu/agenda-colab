$(document).ready(function() {
    filtrarAgendaPorUsuario();
});

async function filtrarAgendaPorUsuario() {
    // Função para obter o id do usuário dos cookies
    function getUserIdFromCookies() {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; userId=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Obtém o id do usuário
    const userId = getUserIdFromCookies();

    // Cria a URL com o id do usuário como parâmetro
    let url = `../php/leitura/ler_agenda.php?`;
    if (userId) url += `colaborador=${encodeURIComponent(userId)}`;

    // Faz a requisição filtrada
    const rq = await fetch(url);
    const rs = await rq.json();
    let data = rs;
    console.log(data);

    // Atualiza a tabela com os dados filtrados
    var quadro = document.getElementById("meu-quadro");
    quadro.innerHTML = '';

    data.forEach(e => {
        quadro.innerHTML += `
            <tr>
                <td>${e.data}</td>
                <td>${e.cep}</td>
                <td>${e.numero}</td>
                <td>${e.horario}</td>
                <td>${e.observacoes}</td>
            </tr>
        `;
    });
}

