# Beziehungen verstehen und pflegen

Beziehungen sind die Verbindungen zwischen Personen in Ihrem Stammbaum. Sie definieren, wer mit wem verwandt ist und wie. Dieser Artikel erklärt alle Beziehungstypen und wie Sie diese richtig verwenden.

## Beziehungstypen

Ahnengalerie Pro unterstützt vier Haupttypen von Beziehungen:

### 1. Eltern-Kind (Parent)
Die wichtigste Beziehung für den Stammbaum.

**Bedeutung:** Person A ist Elternteil von Person B

**Beispiele:**
- Maria Müller (Mutter) → Hans Müller (Kind)
- Peter Müller (Vater) → Hans Müller (Kind)

**Wichtig:** 
- Ein Kind hat **zwei Elternteile** (Mutter und Vater)
- Sie müssen **zwei separate Beziehungen** anlegen
- Die Reihenfolge ist: Elternteil → Kind (nicht umgekehrt!)

### 2. Ehepartner (Spouse)
Für verheiratete Paare.

**Bedeutung:** Person A ist verheiratet mit Person B

**Beispiele:**
- Hans Müller ↔ Anna Schmidt (verheiratet)

**Besonderheiten:**
- Diese Beziehung ist **symmetrisch** (gilt in beide Richtungen)
- Sie müssen nur **eine** Beziehung anlegen (nicht beide Richtungen)
- Optional: Start-Datum (Hochzeitsdatum) angeben

### 3. Partner (Partner)
Für unverheiratete Lebensgemeinschaften.

**Bedeutung:** Person A lebt mit Person B zusammen (ohne Trauschein)

**Verwendung:**
- Gleich wie Ehepartner, nur ohne offizielle Heirat
- Ebenfalls symmetrisch
- Für moderne oder historische Lebensgemeinschaften

### 4. Geschwister (Sibling)
Für Brüder und Schwestern.

**Hinweis:** Geschwister-Beziehungen werden **automatisch erkannt**, wenn zwei Personen die gleichen Eltern haben!

**Manuelle Angabe:**
Sie können Geschwister auch manuell verknüpfen, wenn z.B. nur ein Elternteil bekannt ist (Halbgeschwister).

## Beziehung hinzufügen

### Methode 1: Über die Personen-Detailseite

1. Öffnen Sie die Detailseite einer Person
2. Wechseln Sie zum Tab **"Beziehungen"**
3. Klicken Sie auf den passenden Button:
   - **"Elternteil hinzufügen"**
   - **"Kind hinzufügen"**
   - **"Partner hinzufügen"**
4. Wählen Sie die Person aus der Liste
5. Geben Sie optional weitere Details ein (z.B. Hochzeitsdatum)
6. Klicken Sie auf **"Speichern"**

### Methode 2: Schnelleingabe beim Anlegen

Wenn Sie eine neue Person erstellen, können Sie direkt Beziehungen hinzufügen:

1. Füllen Sie die Grunddaten aus
2. Scrollen Sie zu **"Beziehungen"**
3. Wählen Sie vorhandene Personen aus den Dropdown-Menüs
4. Speichern Sie die Person (Beziehungen werden automatisch erstellt)

## Praktische Beispiele

### Beispiel 1: Kernfamilie aufbauen

Sie möchten sich selbst, Ihre Eltern und Geschwister erfassen.

**Schritt-für-Schritt:**

1. **Sich selbst anlegen**
   - Name: Max Mustermann
   - Geboren: 15.06.1990

2. **Mutter anlegen**
   - Name: Maria Mustermann (geb. Schmidt)
   - Geboren: 20.03.1965

3. **Vater anlegen**
   - Name: Peter Mustermann
   - Geboren: 10.05.1963

4. **Beziehungen erstellen:**
   - Maria → Max (Eltern-Kind)
   - Peter → Max (Eltern-Kind)
   - Maria ↔ Peter (Ehepartner, Hochzeitsdatum: 12.08.1989)

5. **Schwester hinzufügen**
   - Name: Lisa Mustermann
   - Geboren: 03.11.1992
   - Beziehungen:
     - Maria → Lisa (Eltern-Kind)
     - Peter → Lisa (Eltern-Kind)

**Ergebnis:** Lisa und Max werden automatisch als Geschwister erkannt!

### Beispiel 2: Mehrere Ehen

Eine Person war mehrfach verheiratet.

**Situation:** Hans war erst mit Anna verheiratet, dann nach Scheidung mit Berta.

**Lösung:**

1. Hans ↔ Anna (Ehepartner)
   - Start-Datum: 10.05.1950 (Hochzeit)
   - End-Datum: 15.03.1960 (Scheidung)

2. Hans ↔ Berta (Ehepartner)
   - Start-Datum: 20.06.1962 (Hochzeit)
   - End-Datum: leer (oder Todesdatum)

**Kinder zuordnen:**
- Kinder aus erster Ehe: Hans → Kind (Anna ist zweiter Elternteil)
- Kinder aus zweiter Ehe: Hans → Kind (Berta ist zweiter Elternteil)

### Beispiel 3: Adoptivkinder

**Situation:** Ein Kind wurde adoptiert.

**Lösung:**
- Legen Sie die Beziehung normal an (Eltern → Kind)
- Vermerken Sie in der **Notiz** oder **Biografie**: "Adoptivkind"
- Optional: Legen Sie auch die biologischen Eltern an (mit Vermerk)

Es gibt aktuell keinen speziellen Beziehungstyp "Adoption", aber Sie können dies textlich festhalten.

## Beziehung bearbeiten

Bestehende Beziehungen können Sie ändern:

1. Gehen Sie zur Personen-Detailseite
2. Tab **"Beziehungen"**
3. Klicken Sie auf das **Bearbeiten-Symbol** (Stift) bei der Beziehung
4. Ändern Sie Details (z.B. Hochzeitsdatum)
5. Speichern Sie

## Beziehung löschen

⚠️ Vorsicht beim Löschen!

1. Personen-Detailseite → Tab "Beziehungen"
2. Klicken Sie auf das **Löschen-Symbol** (Papierkorb)
3. Bestätigen Sie

**Was passiert:**
- Die Beziehung wird entfernt
- Die Personen selbst bleiben erhalten
- Im Stammbaum erscheint keine Verbindung mehr

## Häufige Fehler vermeiden

### Fehler 1: Kind → Elternteil statt Elternteil → Kind

❌ **Falsch:** Hans (Kind) → Maria (Mutter) als "Eltern-Kind"

✅ **Richtig:** Maria (Mutter) → Hans (Kind) als "Eltern-Kind"

**Merkhilfe:** Der Pfeil geht immer vom Elternteil zum Kind!

### Fehler 2: Doppelte Partner-Beziehungen

❌ **Falsch:** 
- Hans → Anna (Ehepartner)
- Anna → Hans (Ehepartner)

✅ **Richtig:**
- Hans ↔ Anna (Ehepartner) - NUR EINMAL!

**Grund:** Ehe/Partner-Beziehungen sind symmetrisch.

### Fehler 3: Geschwister manuell ohne Eltern

Wenn Sie Geschwister manuell verknüpfen, obwohl die Eltern bekannt sind:

✅ **Besser:** 
- Legen Sie die Eltern an
- Verknüpfen Sie beide Kinder mit den Eltern
- Geschwister-Beziehung wird automatisch erkannt

## Beziehungen im Stammbaum

Alle Beziehungen werden in der **Stammbaum-Visualisierung** grafisch dargestellt:

- **Eltern-Kind:** Vertikale Linien nach unten
- **Ehepartner/Partner:** Horizontale Verbindung
- **Geschwister:** Gemeinsame Eltern-Knoten

Siehe auch: [Stammbaum-Visualisierung nutzen](/help/stammbaum)

## Besondere Situationen

### Unbekannte Eltern

Wenn nur ein Elternteil bekannt ist:
- Legen Sie nur diesen Elternteil an
- Das System akzeptiert auch nur einen Elternteil
- Sie können später den zweiten Elternteil ergänzen

### Patchwork-Familien

Komplexe moderne Familienstrukturen:
- Nutzen Sie Notizen bei Beziehungen
- Legen Sie biologische und soziale Eltern als separate Personen an
- Vermerken Sie in Biografien: "Stiefvater", "Stiefmutter"

### Gleichgeschlechtliche Paare

- Nutzen Sie "Ehepartner" für verheiratete Paare
- Nutzen Sie "Partner" für nicht verheiratete Paare
- Bei Kindern: Legen Sie beide Eltern normal an

### Historische Besonderheiten

- **Mehrfach verheiratete Vorfahren** (wegen frühem Tod)
- **Große Altersunterschiede** bei Ehen
- **Viele Kinder** aus einer Ehe

Dokumentieren Sie alles in den Notizen und Biografien!

## Statistiken zu Beziehungen

Im **Dashboard** sehen Sie:
- Gesamtzahl der Beziehungen
- Im **Statistik-Bereich** (falls vorhanden):
  - Durchschnittliche Kinderzahl
  - Häufigste Familiennamen-Kombinationen

## Tipps für große Stammbäume

### Systematisch vorgehen
1. Beginnen Sie mit sich selbst
2. Arbeiten Sie sich generationenweise zurück
3. Vervollständigen Sie eine Generation, bevor Sie zur nächsten gehen

### Kontrolle der Beziehungen
Regelmäßig prüfen:
- Haben alle Kinder zwei Elternteile? (wenn bekannt)
- Sind alle Ehepartner korrekt verknüpft?
- Stimmen die Daten? (z.B. Kind nicht vor Eltern geboren)

### Notizen nutzen
Bei komplizierten Verwandtschaften:
- Schreiben Sie Notizen in die Biografie
- Dokumentieren Sie Quellen
- Markieren Sie unsichere Beziehungen

## Häufige Fragen

### Kann eine Person mehrere Ehepartner haben?
Ja, Sie können mehrere Ehe-Beziehungen anlegen (zeitlich nacheinander mit Start/End-Datum).

### Wie zeige ich Stiefkinder?
Legen Sie eine Eltern-Kind-Beziehung an und vermerken in der Notiz "Stiefkind" oder in der Biografie.

### Was ist mit entfernten Verwandten (Cousins, etc.)?
Diese werden automatisch über die Eltern-Kind-Beziehungen berechnet. Sie müssen keine explizite "Cousin"-Beziehung anlegen.

### Kann ich Beziehungen rückgängig machen?
Ja, durch Löschen der Beziehung. Die Personen bleiben dabei erhalten.

## Nächste Schritte

Jetzt kennen Sie alle Beziehungstypen:

1. **[Stammbaum nutzen](/help/stammbaum)** - Sehen Sie Ihre Beziehungen grafisch
2. **[Fotos hochladen](/help/fotos)** - Ergänzen Sie Familienfotos
3. **[Statistiken](/help/statistiken)** - Analysieren Sie Ihre Familienstruktur

Viel Erfolg beim Aufbau Ihres Stammbaums! 🌳