function renderScores(data) {
    const tbody = document.getElementById('scoreboard-body');
    tbody.innerHTML = '';

    data.forEach(row => {
        let cssClass = '';
        const points = parseInt(row.average_points, 10);

        if (points >= 80) cssClass = 'high';
        else if (points >= 50) cssClass = 'medium';
        else cssClass = 'low';

        const tr = document.createElement('tr');
        tr.className = cssClass;
        tr.innerHTML = `
            <td>${row.rank}</td>
            <td>${row.username}</td>
            <td>${points}</td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('last-updated').innerText = `Last updated: ${new Date().toLocaleTimeString()}`;
}

const eventSource = new EventSource('scoreboard.php');

eventSource.onmessage = function(event) {
    const data = JSON.parse(event.data);
    renderScores(data);
};

eventSource.onerror = function() {
    console.error("SSE connection error.");
};