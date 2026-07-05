document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('song-table-body');
    let timer = null;

    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, m => ({
            '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'
        })[m]);
    }

    function renderRows(songs, query) {
        tableBody.innerHTML = '';

        if (songs.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 3;
            td.className = 'no-results';

            if (query.trim() !== '') {
                td.innerHTML = `
                    Keine Songs gefunden für: <strong>${escapeHtml(query)}</strong><br>
                    <form method="post" action="index.php">
                        <input type="hidden" name="request_title" value="${escapeHtml(query)}">
                        <button class="btn btn-request">✨ Diesen Song anfragen! ✨</button>
                    </form>
                `;
            } else {
                td.textContent = 'Bitte gib einen Suchbegriff ein. ✨';
            }

            tr.appendChild(td);
            tableBody.appendChild(tr);
            return;
        }

        songs.forEach(song => {
            const tr = document.createElement('tr');

            const td1 = document.createElement('td');
            td1.dataset.label = 'Songtitel 🎵';
            td1.textContent = song.title;

            const td2 = document.createElement('td');
            td2.dataset.label = 'Interpret 🎤';
            td2.textContent = song.interpret;

            const td3 = document.createElement('td');
            td3.dataset.label = 'Status ✅';
            td3.textContent = song.status;

            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);

            tableBody.appendChild(tr);
        });
    }

    function fetchSongs(query) {
        const url = query.trim() === ''
            ? 'api.php?action=list'
            : 'api.php?action=search&q=' + encodeURIComponent(query);

        fetch(url)
            .then(r => r.json())
            .then(data => renderRows(Array.isArray(data) ? data : [], query))
            .catch(() => renderRows([], query));
    }

    searchInput.addEventListener('keyup', () => {
        clearTimeout(timer);
        timer = setTimeout(() => fetchSongs(searchInput.value), 300);
    });
});
