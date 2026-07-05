// script.js - AJAX fuzzy search with debounce

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('song-table-body');
    let debounceTimer = null;

    function renderRows(songs, query) {
        tableBody.innerHTML = '';

        if (songs.length > 0) {
            songs.forEach(song => {
                const tr = document.createElement('tr');

                const tdTitle = document.createElement('td');
                tdTitle.textContent = song.title;

                const tdInterpret = document.createElement('td');
                tdInterpret.textContent = song.interpret;

                const tdStatus = document.createElement('td');
                tdStatus.textContent = song.status;

                tr.appendChild(tdTitle);
                tr.appendChild(tdInterpret);
                tr.appendChild(tdStatus);

                tableBody.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 3;
            td.className = 'no-results';

            if (query && query.trim() !== '') {
                td.innerHTML = `
                    Keine Songs gefunden für: <strong>${escapeHtml(query)}</strong><br>
                    <form method="post" action="index.php" class="request-form">
                        <input type="hidden" name="request_title" value="${escapeHtml(query)}">
                        <button type="submit" class="btn btn-request">
                            ✨ Diesen Song anfragen! ✨
                        </button>
                    </form>
                `;
            } else {
                td.innerHTML = 'Bitte gib einen Suchbegriff ein. ✨';
            }

            tr.appendChild(td);
            tableBody.appendChild(tr);
        }
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function fetchSongs(query) {
        let url;
        if (!query || query.trim() === '') {
            url = 'api.php?action=list';
        } else {
            url = 'api.php?action=search&q=' + encodeURIComponent(query);
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!Array.isArray(data)) {
                    data = [];
                }
                renderRows(data, query);
            })
            .catch(() => {
                renderRows([], query);
            });
    }

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function () {
            const query = searchInput.value;

            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(function () {
                fetchSongs(query);
            }, 300);
        });
    }
});
