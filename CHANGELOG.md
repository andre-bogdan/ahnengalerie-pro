# Changelog

Alle wichtigen Änderungen an diesem Projekt werden in dieser Datei dokumentiert.

Das Format basiert auf [Keep a Changelog](https://keepachangelog.com/de/1.0.0/),
und dieses Projekt folgt [Semantic Versioning](https://semver.org/lang/de/).

## Kategorien
- **Added**: Neue Features
- **Changed**: Änderungen an bestehenden Features
- **Deprecated**: Bald entfernte Features
- **Removed**: Entfernte Features
- **Fixed**: Bug-Fixes
- **Security**: Sicherheits-Updates

---

## [Unreleased]

### Geplant
- Bug in persons/edit -> Fenster blockiert beim Hinzufügen einer Beziehung
- Bug in persons/edit -> Fenster blockiert beim Hinzufügen eines Kindes

---

## [1.3.1] - 2025-11-07

### Added
- Sterbedatum eingefügt in $child['death_date'] in /Views/persons/view.php
- Button zum Sichtbar machen der Eingabe in /Views/auth/login.php
- Button zum Sichtbar machen der Eingabe in /Views/auth/profile.php
- Click-Event entfernt um den Stammbaum besser bearbeitbar zu machen aus /Views/persons/tree.php
- 'geändert von' in der Dashboard-Ansicht hinzugefügt (Anpassungen in Datenbank (updated_by in persons-Tabelle hinzugefügt),Dashboard Controller, Dashboard Model und Dashboard View angepasst) 
- Export in .csv Dateiformat hinzugefügt
- shared - Ordner (hilfe-Dateien) hinzugefügt (Dateipfad kann in .env angepasst werden)

### Fixed
- Berechnung der Tage bis zum Geburtstag (Änderung in /Controllers/Dashboard.php)

---

## [1.3.0] - 2025-10-31

### Added
- **Open-Source-Release**
  - Bereitstellung des Projektes auf github (https://github.com/andre-bogdan/ahnengalerie-pro)


**Letzte Aktualisierung:** 2025-11-07