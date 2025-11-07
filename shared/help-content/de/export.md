# Daten exportieren und sichern

Ahnengalerie Pro bietet verschiedene Möglichkeiten, Ihre wertvollen Familiendaten zu exportieren und zu sichern. Diese Anleitung zeigt Ihnen alle verfügbaren Export-Optionen.

## Warum Daten exportieren?

Es gibt viele gute Gründe, Ihre Genealogie-Daten regelmäßig zu exportieren:

- **Datensicherung** - Schützen Sie sich vor Datenverlust
- **Teilen mit Familie** - Geben Sie Ihre Forschung an Verwandte weiter
- **Software-Wechsel** - Nutzen Sie Ihre Daten in anderen Programmen
- **Archivierung** - Bewahren Sie Snapshots Ihrer Forschung
- **Zusammenarbeit** - Tauschen Sie Daten mit anderen Forschern aus

## GEDCOM Export

GEDCOM (GEnealogical Data COMmunication) ist der internationale Standard für den Austausch von Genealogie-Daten. Fast alle Ahnenforschungs-Programme unterstützen dieses Format.

### So exportieren Sie Ihre Daten als GEDCOM

1. Navigieren Sie im Hauptmenü zu **Export**
2. Sie sehen eine Übersicht mit Statistiken:
   - Anzahl der Personen die exportiert werden
   - Anzahl der Beziehungen
   - Anzahl der Ereignisse
3. Klicken Sie auf **"GEDCOM-Datei herunterladen"**
4. Die Datei wird automatisch heruntergeladen

Der Dateiname enthält das aktuelle Datum, z.B.: `ahnengalerie_export_2025-11-01_143022.ged`

### Was wird exportiert?

Der GEDCOM-Export enthält:

#### Personen-Daten
- Vor- und Nachnamen
- Geburtsnamen (Mädchennamen)
- Geschlecht
- Geburts- und Sterbedaten
- Geburts- und Sterbeorte
- Berufe
- Biografien und Notizen

#### Beziehungen
- Eltern-Kind-Verbindungen
- Ehepartner und Partner
- Hochzeitsdaten
- Scheidungen
- Familien-Strukturen (FAM Records)

#### Ereignisse
- Taufen
- Hochzeiten
- Scheidungen
- Ausbildung
- Berufliche Stationen
- Wohnorte
- Ein- und Auswanderung
- Militärdienst
- Weitere Lebensereignisse

### Was wird NICHT exportiert?

Folgende Inhalte sind nicht im GEDCOM-Export enthalten:

- **Fotos und Mediendateien** - Diese müssen separat gesichert werden
- **Benutzerkonten** - Login-Daten und Berechtigungen
- **Systemeinstellungen** - Newsletter-Einstellungen, etc.

**Tipp:** Sichern Sie Ihre Fotos zusätzlich über den Dateimanager Ihres Servers oder erstellen Sie ein Backup des kompletten `uploads`-Ordners.

## GEDCOM-Datei verwenden

### In andere Programme importieren

Die exportierte GEDCOM-Datei kann in folgende Programme importiert werden:

#### Desktop-Software
- **Family Tree Maker** - Kommerzielle Software (Windows/Mac)
- **Gramps** - Open Source (Windows/Mac/Linux)
- **Legacy Family Tree** - Kostenlose Basisversion
- **RootsMagic** - Professionelle Software
- **Family Tree Builder** (MyHeritage)
- **Ahnenblatt** - Deutsches Programm

#### Online-Dienste
- **Ancestry.com** - Größte Genealogie-Plattform
- **MyHeritage** - International mit DNA-Tests
- **FamilySearch** - Kostenlos von der Kirche Jesu Christi
- **Geni** - Weltweiter Stammbaum
- **WikiTree** - Kollaborative Plattform

#### Self-Hosted Alternativen
- **Webtrees** - Open Source Web-Anwendung
- **HuMo-genealogy** - PHP-basiert
- **PhpGedView** - Ältere Alternative

### GEDCOM-Datei prüfen

Sie können Ihre GEDCOM-Datei mit einem Texteditor öffnen und prüfen:

```gedcom
0 HEAD
1 SOUR Ahnengalerie Pro
2 VERS 1.3.0
1 CHAR UTF-8
0 @I1@ INDI
1 NAME Max /Mustermann/
2 GIVN Max
2 SURN Mustermann
1 SEX M
1 BIRT
2 DATE 15 MAR 1950
2 PLAC Berlin, Deutschland
```

**Wichtige Tags:**
- `INDI` - Individual (Person)
- `FAM` - Family (Familie)
- `BIRT` - Birth (Geburt)
- `DEAT` - Death (Tod)
- `MARR` - Marriage (Hochzeit)

## Regelmäßige Backups

### Empfohlene Backup-Strategie

Wir empfehlen folgende Backup-Routine:

1. **Wöchentlich** - Bei aktiver Forschung
2. **Monatlich** - Bei gelegentlichen Updates
3. **Vor großen Änderungen** - Bevor Sie viele Daten löschen oder ändern
4. **Nach wichtigen Ergänzungen** - Wenn Sie neue Zweige hinzufügen

### Backup-Tipps

#### Versionierung
Benennen Sie Ihre Backups mit Datum:
- `stammbaum_2025-11-01.ged`
- `stammbaum_2025-11-08.ged`
- `stammbaum_2025-11-15.ged`

So können Sie bei Problemen zu einer früheren Version zurückkehren.

#### Mehrere Speicherorte
Speichern Sie Backups an verschiedenen Orten:
- Lokaler Computer
- USB-Stick
- Cloud-Speicher (Dropbox, Google Drive)
- Externe Festplatte

#### Dokumentation
Führen Sie ein Backup-Log:
```
2025-11-01: 250 Personen, neue Familie Schmidt hinzugefügt
2025-11-08: 265 Personen, Fotos der Familie Meyer ergänzt
2025-11-15: 270 Personen, Korrekturen bei Geburtsdaten
```

## Zukünftige Export-Formate

Folgende Export-Formate sind für zukünftige Versionen geplant:

### PDF Stammbuch (geplant)
- Druckbares Familienbuch
- Mit Fotos und Stammbäumen
- Perfekt als Geschenk oder für Archive

### Excel/CSV Export (geplant)
- Tabellarische Übersicht
- Ideal für eigene Analysen
- Einfache Weitergabe von Listen

### JSON/XML Export (geplant)
- Für Entwickler und APIs
- Strukturierte Daten
- Programmierbare Schnittstellen

### Druckvorlagen (geplant)
- Ahnentafeln
- Stammbaum-Poster
- Personenblätter
- Familien-Übersichten

## Datenschutz beim Teilen

Beachten Sie beim Teilen von Genealogie-Daten:

### Lebende Personen
- Geben Sie keine Daten lebender Personen ohne deren Zustimmung weiter
- Viele Programme bieten "Privatisierungs"-Optionen beim Import
- Überprüfen Sie exportierte Daten vor der Weitergabe

### Sensible Informationen
- Entfernen Sie ggf. sensible Details aus Biografien
- Achten Sie auf Adoptionen und andere vertrauliche Themen
- Respektieren Sie Familien-Geheimnisse

### Urheberrecht
- Fotos unterliegen dem Urheberrecht
- Klären Sie Rechte vor der Weitergabe
- Nennen Sie Quellen und Fotografen

## Problemlösung

### GEDCOM-Export funktioniert nicht
- Prüfen Sie, ob Personen in der Datenbank sind
- Leeren Sie den Browser-Cache
- Versuchen Sie es in einem anderen Browser

### Datei ist zu groß
- Bei sehr großen Stammbäumen (>10.000 Personen) kann der Export länger dauern
- Haben Sie Geduld, der Download startet automatisch

### Umlaute werden falsch dargestellt
- Die Datei ist UTF-8 kodiert
- Öffnen Sie sie mit einem modernen Texteditor
- Beim Import in andere Programme UTF-8 als Kodierung wählen

### Import in anderes Programm schlägt fehl
- Prüfen Sie die GEDCOM-Version (wir nutzen 5.5.1)
- Manche Programme haben Limits für Dateigröße
- Kontaktieren Sie den Support des Ziel-Programms

## Häufig gestellte Fragen

**F: Wie oft sollte ich exportieren?**  
A: Mindestens monatlich, bei aktiver Forschung wöchentlich.

**F: Kann ich die GEDCOM-Datei bearbeiten?**  
A: Ja, mit einem Texteditor, aber Vorsicht vor Syntaxfehlern.

**F: Werden Fotos mit exportiert?**  
A: Nein, Fotos müssen separat gesichert werden.

**F: Kann ich nur Teile exportieren?**  
A: Aktuell wird immer der komplette Stammbaum exportiert.

**F: Ist GEDCOM mit Excel öffenbar?**  
A: Nicht direkt, dafür ist der geplante CSV-Export gedacht.

**F: Kann ich GEDCOM wieder importieren?**  
A: Der Import ist für Version 1.4.0 geplant.

## Weitere Hilfe

Bei Fragen zum Export:

- Lesen Sie die anderen **[Hilfe-Artikel](/help)**
- Schauen Sie in die **[Ersten Schritte](/help/erste-schritte)**
- Kontaktieren Sie den Support

## Zusammenfassung

Der Datenexport ist ein wichtiger Teil der Datensicherheit:

1. **Exportieren Sie regelmäßig** Ihre Daten als GEDCOM
2. **Speichern Sie Backups** an mehreren Orten
3. **Dokumentieren Sie** Ihre Export-Versionen
4. **Beachten Sie Datenschutz** beim Teilen
5. **Sichern Sie Fotos** zusätzlich separat

Mit diesen Praktiken stellen Sie sicher, dass Ihre wertvolle Familienforschung für zukünftige Generationen erhalten bleibt! 📁