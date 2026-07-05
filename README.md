# Karaoke Request Night – Yankees 90s Webprojekt

## Projektbeschreibung

Dieses Projekt ist eine einfache, aber bewusst retro gestaltete Webanwendung, um Song-Requests für einen Karaoke-Abend zu sammeln und zu verwalten.  
Die Seite bietet:

- Ein öffentliches Formular für Gäste, um Songs zu wünschen.
- Eine Übersicht aller Requests, sortiert nach Anzahl der Wünsche.
- Ein Admin-Panel, in dem der Status und die Bestätigung einzelner Songs gepflegt werden können.

Backend: PHP ohne Framework  
Datenspeicher: JSON-Datei `songs.json`  
Frontend: HTML + CSS im Yankees-90er-Style (Blau/Rot/Weiß, dicke Rahmen, blinkende Elemente, animierte Sterne).

---

## Dateistruktur

```text
/project-root
├── index.php          # Hauptseite mit Request-Formular und Song-Tabelle
├── api.php            # Backend-Endpoint für neue Requests
├── admin.php          # Admin-Oberfläche zur Status-/Confirmed-Pflege
├── style.css          # Yankees-90er-Style CSS
├── songs.json         # JSON-Datenspeicher für Songs
├── Dockerfile         # Container-Build für php:8.2-apache
├── docker-compose.yml # Einfacher Webservice mit Port-Mapping 8080:80
├── README.md          # Dieses Dokument
└── assets/
    └── stars.gif      # Animiertes GIF mit Sternen für Retro-Optik
