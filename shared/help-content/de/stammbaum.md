# Stammbaum-Visualisierung nutzen

Der Stammbaum ist das Herzstück von Ahnengalerie Pro. Hier sehen Sie Ihre gesamte Familie auf einen Blick - interaktiv, übersichtlich und schön gestaltet.

## Stammbaum öffnen

1. Klicken Sie in der Navigation auf **"Stammbaum"**
2. Der Stammbaum wird automatisch geladen und zentriert angezeigt
3. Sie sehen alle erfassten Personen und ihre Beziehungen

**Ladezeit:** Bei großen Stammbäumen (100+ Personen) kann das Laden einige Sekunden dauern.

## Die Stammbaum-Ansicht

### Was Sie sehen

#### Personen-Knoten
Jede Person wird als farbiger Kreis (Knoten) dargestellt:

**Farben:**
- **Blau** - Männlich
- **Rosa** - Weiblich  
- **Lila** - Divers oder unbekanntes Geschlecht

**Inhalt:**
- **Name** (Vor- und Nachname)
- **Lebensdaten** (geboren - gestorben)
- **Foto** (wenn als Hauptfoto gesetzt)

#### Verbindungslinien
Linien zeigen die Beziehungen:

**Linientypen:**
- **Durchgezogen** - Eltern-Kind-Beziehung (vertikal)
- **Durchgezogen** - Ehe/Partner-Beziehung (horizontal)
- **Gestrichelt** - Optional für besondere Beziehungen

**Linienfarben:**
- **Grau** - Standard
- **Dicker/Heller** beim Hover (Hervorhebung)

### Layout-Algorithmus

Der Stammbaum verwendet ein **hierarchisches Layout**:

- **Generationen** werden automatisch erkannt
- **Ältere Generationen** oben
- **Jüngere Generationen** unten
- **Partner** nebeneinander
- **Geschwister** auf gleicher Höhe

## Interaktive Bedienung

### Zoom

**Mit Maus:**
- **Mausrad hoch** - Hineinzoomen
- **Mausrad runter** - Herauszoomen

**Mit Buttons:**
- Verwenden Sie die **+ und - Buttons** rechts unten

**Mit Touchscreen:**
- **Pinch-Geste** (zwei Finger zusammen/auseinander)

**Zoom-Bereich:** 10% bis 500%

### Navigation (Verschieben)

**Mit Maus:**
- **Klicken und Ziehen** auf leeren Bereichen
- Der Stammbaum verschiebt sich

**Mit Touchscreen:**
- **Wischen** mit einem Finger

**Zurück zum Zentrum:**
- Klicken Sie auf **"Zentrieren"** (falls vorhanden)
- Oder: Doppelklick auf leeren Bereich

### Person auswählen

**Klick auf Person:**
1. Klicken Sie auf einen Personen-Knoten
2. Die Person wird **hervorgehoben**
3. Ein **Tooltip** erscheint mit Details
4. Verbundene Personen werden betont

**Details anzeigen:**
- Klicken Sie erneut → Zur Personen-Detailseite

### Filtern und Suchen

#### Personen-Filter (falls verfügbar)
- Filtern Sie nach **Geschlecht**
- Filtern Sie nach **Generation**
- Nur **Vorfahren** einer Person zeigen

#### Suche verwenden
Die globale Suche (oben rechts) funktioniert auch:
1. Tippen Sie einen Namen ein
2. Klicken Sie auf die Person
3. Sie werden zur Detailseite weitergeleitet
4. Von dort können Sie in den Stammbaum springen

## Stammbaum-Features

### Generationen erkennen

Der Algorithmus berechnet automatisch:
- **Generation 0** - Älteste erfasste Vorfahren
- **Generation 1** - Deren Kinder
- **Generation 2** - Deren Enkel
- usw.

**Beispiel:**
```
Generation 0: Urgroßeltern
Generation 1: Großeltern
Generation 2: Eltern
Generation 3: Sie selbst
Generation 4: Ihre Kinder
```

### Ehepartner-Darstellung

Verheiratete Paare werden:
- **Nebeneinander** platziert
- **Mit Verbindungslinie** zwischen ihnen
- **Kinder darunter** (von beiden ausgehend)

### Mehrfache Ehen

Wenn eine Person mehrfach verheiratet war:
- Alle Partner werden angezeigt
- Jeweils mit eigener Verbindung
- Kinder werden dem richtigen Paar zugeordnet

### Adoptiv- und Stiefkinder

- Werden normal dargestellt
- Unterscheidung nur in der Detail-Ansicht sichtbar
- Verbindungslinien sind identisch

## Probleme und Lösungen

### "Ich finde mich nicht im Stammbaum"

Mögliche Ursachen:
1. **Keine Beziehungen:** Sie haben sich angelegt, aber keine Eltern/Kinder verknüpft
2. **Isolierte Person:** Sie sind nicht mit dem Hauptbaum verbunden

**Lösung:**
- Fügen Sie mindestens eine Beziehung hinzu (Eltern oder Kinder)
- Der Stammbaum zeigt nur **verbundene** Personen

### "Der Stammbaum ist zu groß/unübersichtlich"

**Lösungen:**
- **Herauszoomen** für Gesamtübersicht
- **Hineinzoomen** für Details
- **Filter verwenden** (falls verfügbar)
- **Nach Person suchen** und Details ansehen

### "Linien überschneiden sich"

Bei großen, komplexen Stammbäumen kann es zu Überschneidungen kommen:

**Aktuell:**
- Der Algorithmus versucht Überschneidungen zu minimieren
- 100% perfekte Darstellung ist schwierig

**In Zukunft:**
- Verbesserte Layout-Algorithmen geplant
- Manuelle Verschiebung von Knoten

### "Person wird doppelt angezeigt"

Das sollte nicht passieren! Mögliche Ursachen:
- Datenbank-Inkonsistenz
- Zwei verschiedene Personen mit gleichem Namen

**Lösung:**
- Prüfen Sie die Personen-IDs
- Kontaktieren Sie den Administrator

## Tipps für schöne Stammbäume

### Klein anfangen
Beginnen Sie mit einer Kernfamilie:
- Sie selbst
- Eltern
- Geschwister
- Großeltern

Erweitern Sie dann schrittweise.

### Vollständige Daten
Der Stammbaum sieht besser aus mit:
- **Fotos** als Hauptbilder
- **Vollständigen Daten** (Geburts-/Sterbedaten)
- **Korrekten Beziehungen**

### Symmetrie bewahren
Versuchen Sie:
- Beide Elternteile einer Person anzulegen
- Alle Geschwister zu erfassen
- Partner komplett zu erfassen

### Konsistente Namensschreibweise
- Einheitliche Schreibweise (z.B. "Müller" nicht "Mueller")
- Vollständige Namen (nicht "M." sondern "Maria")

## Mobile Nutzung

### Auf Smartphone/Tablet

Der Stammbaum ist auch mobil nutzbar:

**Bedienung:**
- **Wischen** zum Verschieben
- **Pinch** zum Zoomen
- **Tippen** auf Person für Details

**Einschränkungen:**
- Kleinerer Bildschirm = weniger Übersicht
- Touch-Bedienung weniger präzise als Maus

**Tipp:** Nutzen Sie für große Stammbäume einen Desktop-Computer.

## Technische Details

### Rendering-Engine
Der Stammbaum nutzt **Vis.js Network**:
- Performante JavaScript-Bibliothek
- Automatisches Layout
- Interaktive Bedienung

### Performance

**Optimal:**
- Bis zu 100 Personen: Flüssig
- 100-500 Personen: Gut
- 500+ Personen: Kann langsam werden

**Bei Problemen:**
- Schließen Sie andere Browser-Tabs
- Nutzen Sie einen modernen Browser (Chrome, Firefox, Edge)
- Aktualisieren Sie Ihren Browser

### Browser-Kompatibilität

✅ **Unterstützt:**
- Chrome/Chromium (empfohlen)
- Firefox
- Safari
- Edge

❌ **Nicht unterstützt:**
- Internet Explorer

## Export (geplant)

In Zukunft geplant:
- **PNG-Export** - Stammbaum als Bild speichern
- **PDF-Export** - Druckbare Version
- **SVG-Export** - Für Grafikprogramme

## Häufige Fragen

### Kann ich den Stammbaum drucken?
Aktuell nur über Browser-Druckfunktion (nicht optimal). PDF-Export ist geplant.

### Kann ich Personen manuell verschieben?
Nein, das Layout ist automatisch. Manuelle Anpassung ist für die Zukunft geplant.

### Zeigt der Stammbaum alle Personen?
Nur Personen **mit mindestens einer Beziehung**. Isolierte Personen erscheinen nicht.

### Kann ich nur einen Teil des Stammbaums anzeigen?
Filter-Funktionen sind geplant (z.B. "Nur Vorfahren von Person X").

### Warum sind manche Personen ganz oben/unten?
Die Position ergibt sich aus den Generationen. Älteste Vorfahren oben, jüngste Nachkommen unten.

### Kann ich verschiedene Zweige farblich unterscheiden?
Aktuell nein. Erweiterte Farboptionen sind für die Zukunft geplant.

## Stammbaum erweitern

### Neue Personen hinzufügen

Aus dem Stammbaum heraus:
1. Gehen Sie zur Personen-Liste
2. Fügen Sie neue Personen hinzu
3. Verknüpfen Sie diese mit Beziehungen
4. Laden Sie den Stammbaum neu

Der Stammbaum aktualisiert sich bei jedem Aufruf automatisch.

### Beziehungen ändern

Wenn Sie Beziehungen ändern:
1. Gehen Sie zur Personen-Detailseite
2. Ändern Sie Beziehungen
3. Laden Sie den Stammbaum neu

Die Änderungen sind sofort sichtbar.

## Best Practices

### Regelmäßig prüfen
- Öffnen Sie den Stammbaum regelmäßig
- Prüfen Sie auf Fehler oder Lücken
- Ergänzen Sie fehlende Personen

### Logik beachten
Unmögliche Konstellationen vermeiden:
- Kind kann nicht vor Eltern geboren sein
- Person kann nicht mit sich selbst verheiratet sein
- Zu große Altersunterschiede (z.B. Mutter 5 Jahre alt)

### Dokumentation
Nutzen Sie Biografien, um komplexe Situationen zu erklären:
- Adoptionen
- Patchwork-Familien
- Unklare Verwandtschaften

## Nächste Schritte

Jetzt kennen Sie den Stammbaum:

1. **[Beziehungen verstehen](/help/beziehungen)** - Bauen Sie Ihren Baum richtig auf
2. **[Personen anlegen](/help/personen-anlegen)** - Fügen Sie mehr Vorfahren hinzu
3. **[Fotos hochladen](/help/fotos)** - Verschönern Sie Ihren Stammbaum

Viel Spaß beim Erkunden Ihrer Familiengeschichte! 🌳