# Karaoke Request Webprojekt ✨

## Projektbeschreibung

Dieses Projekt ist eine vollständige Karaoke‑Request‑Webseite im 90er‑Yankees‑Style.  
Es besteht aus:

- **Request‑Seite (`index.php`)** – öffentliche Seite mit Suchfeld, AJAX‑Fuzzy‑Search, Tabelle und Request‑Button.
- **Admin‑Panel (`admin.php`)** – passwortgeschütztes Backend zur Verwaltung der Songdaten.
- **Login‑Seite (`login.php`)** – Passwort‑Login für Admin.
- **CSV‑Export (`export.php`)** – Export aller Songdaten.

Design:

- starke Farben (Blau, Rot, Weiß)
- dicke Rahmen
- blinkende Elemente
- Retro‑Fonts (Impact, Arial Black)
- Side‑Scrolling‑Texte (Marquee‑Style) auf allen Seiten
- ausschließlich Emojis statt Bildern

---

## Installationsanleitung

1. Projekt in ein Verzeichnis kopieren, z. B. `/project-root`.
2. Mit Docker starten:

   ```bash
   docker-compose up --build
   ```

   Danach ist die Seite unter `http://localhost:8080` erreichbar.

3. Ohne Docker:

   - PHP 8.2 + Apache installieren
   - Projekt nach `/var/www/html` kopieren
   - `mod_rewrite` aktivieren
   - `http://localhost/index.php` im Browser öffnen

---

## Docker‑Setup

### Dockerfile

- Basisimage: `php:8.2-apache`
- `mod_rewrite` aktiviert
- Kopiert alle Projektdateien nach `/var/www/html`
- Unterstützt folgende ENV‑Variablen:
  - `ADMIN_PASSWORD`
  - `TOP_BANNER_TEXT`
  - `BOTTOM_BANNER_TEXT`

### docker-compose.yml

Beispiel:

```yaml
environment:
  ADMIN_PASSWORD: "supersecret"
  TOP_BANNER_TEXT: "✨ CUSTOM TOP BANNER ✨ LIVE KARAOKE REQUESTS ✨"
  BOTTOM_BANNER_TEXT: "✨ CUSTOM BOTTOM BANNER ✨ Powered by JSON & Emojis ✨"
```

---

## JSON‑Struktur (`songs.json`)

Die Datei wird automatisch erzeugt, falls sie nicht existiert.

```json
{
  "title": "Songtitel",
  "interpret": "Interpretname",
  "count": 1,
  "status": "requested"
}
```

Hinweise:

- keine Felder `requested_by` oder `confirmed`
- `status` nur `"ok"` oder `"requested"`
- `count` ist der Request‑Zähler

---

## Request‑Seite

### Funktionen

- Suchfeld mit **AJAX‑Fuzzy‑Search**
- Suche startet automatisch nach **Keyup** mit **300 ms Delay**
- Tabelle zeigt:
  - Songtitel
  - Interpret
  - Status
- Wenn kein Ergebnis gefunden wird:
  - Button zum Anfragen eines neuen Songs
  - POST erzeugt neuen Eintrag in `songs.json`
  - Suchfeld wird geleert
  - gesamte Tabelle wird angezeigt
  - Meldung „Song erfolgreich hinzugefügt!“ erscheint

### Fuzzy‑Search

- case‑insensitive
- substring‑Match
- Levenshtein‑Distanz

---

## Admin‑Panel

- Passwortgeschützt (ENV: `ADMIN_PASSWORD`)
- Admin kann:
  - Titel ändern
  - Interpret ändern
  - Count anpassen
  - Status ändern (`ok` / `requested`)
- CSV‑Export:
  - Link „CSV Export ✨“
  - `export.php` erzeugt `songs_export.csv` mit:
    - `title`, `interpret`, `count`, `status`

---

## Login‑Seite

- Passwort‑Login
- Passwort über ENV‑Variable `ADMIN_PASSWORD`
- Bereits eingeloggte Admins werden automatisch weitergeleitet

---

## Side‑Scrolling‑Texte

Alle Seiten verwenden `<marquee>` mit Texten im 90er‑Stil.  
Diese Texte sind über ENV‑Variablen konfigurierbar:

- `TOP_BANNER_TEXT`
- `BOTTOM_BANNER_TEXT`

---

## Emoji‑Design

- ausschließlich Emojis, z. B.:
  - `✨`, `🎤`, `⭐`, `🎶`
- keine Bilddateien
- `assets/emojis.txt` enthält eine kleine Emoji‑Sammlung

---

## Mobile‑Optimierung

- `meta viewport` in allen Seiten
- CSS‑Media‑Query für kleine Bildschirme:
  - kleinere Schriftgrößen
  - angepasste Abstände
  - horizontales Scrollen für Tabellen (`.table-wrapper`)