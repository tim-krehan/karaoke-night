# Karaoke Request Webprojekt ✨

## Projektbeschreibung

Dieses Projekt ist eine vollständige Karaoke‑Request‑Webseite im 90er‑Yankees‑Style.  
Es besteht aus:

- **Request‑Seite (`index.php`)** – öffentliche Seite mit Suchfeld, Tabelle und Request‑Button.
- **Admin‑Panel (`admin.php`)** – passwortgeschütztes Backend zur Verwaltung der Songdaten.
- **Login‑Seite (`login.php`)** – Passwort‑Login für Admin.

Die Seite nutzt starke Farben, dicke Rahmen, blinkende Elemente, Retro‑Fonts und Side‑Scrolling‑Texte.  
Statt Bildern werden ausschließlich Emojis verwendet.

---

## Request‑Seite

- Fuzzy‑Search (case‑insensitive)
- Tabelle mit:
  - Songtitel
  - Interpret
  - Status
- Wenn kein Ergebnis gefunden wird:
  - Button zum Erstellen eines neuen Eintrags

---

## Admin‑Panel

- Passwortgeschützt (ENV: `ADMIN_PASSWORD`)
- Admin kann:
  - Titel ändern
  - Interpret ändern
  - Count ändern
  - Status ändern (`ok` / `requested`)

---

## Login‑Seite

- Passwort‑Login
- Bereits eingeloggte Admins werden automatisch weitergeleitet

---

## JSON‑Struktur

```json
{
  "title": "Songtitel",
  "interpret": "Interpretname",
  "count": 1,
  "status": "requested"
}
```

## CSV‑Export

Das Admin‑Panel enthält einen Button **„CSV Export ✨“**.

Der Export erzeugt eine Datei:

`songs_export.csv`

Die Datei enthält alle Felder:

- title
- interpret
- count
- status

Der Download wird automatisch ausgelöst.