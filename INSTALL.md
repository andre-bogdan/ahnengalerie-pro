# 🌳 Ahnengalerie - Installationsanleitung

Willkommen! Diese Anleitung hilft dir, Ahnengalerie in wenigen Minuten zu installieren.

---

## ⚡ Schnellstart (5 Minuten)

### Voraussetzungen prüfen

- ✅ **PHP 8.1 oder höher** installiert
- ✅ PHP-Extensions: `pdo_sqlite`, `gd`, `mbstring`, `intl`
- ✅ Mindestens 100 MB freier Speicherplatz

**PHP-Version prüfen:**
```bash
php -v
```

**Extensions prüfen:**
```bash
php -m | grep -E "pdo_sqlite|gd|mbstring|intl"
```

---

## 🚀 Installation - 3 Methoden

Wähle die Methode, die am besten zu dir passt:

### **Methode 1: Entwicklungsserver (Schnellste)**

Perfekt zum Testen und für lokale Nutzung.
```bash
# 1. ZIP entpacken
unzip ahnengalerie-v1.3.0.zip
cd ahnengalerie-v1.3.0

# 2. .env-Datei anpassen (siehe unten)
cp .env.example .env
nano .env    # oder Editor deiner Wahl

# 3. Berechtigungen setzen
chmod -R 777 writable/

# 4. Server starten
php spark serve
```

**Fertig!** Öffne: **http://localhost:8080**

---

### **Methode 2: Apache Webserver (Empfohlen für Production)**

#### **Option A: DocumentRoot auf `/public` setzen (Beste Methode)**

**Apache VirtualHost konfigurieren:**
```apache
<VirtualHost *:80>
    ServerName meine-ahnengalerie.local
    DocumentRoot /var/www/ahnengalerie/public
    
    <Directory /var/www/ahnengalerie/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/ahnengalerie-error.log
    CustomLog ${APACHE_LOG_DIR}/ahnengalerie-access.log combined
</VirtualHost>
```

**mod_rewrite aktivieren:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

#### **Option B: Root-Installation mit Redirect (Falls DocumentRoot nicht änderbar)**

Falls du den DocumentRoot nicht auf `/public` setzen kannst (z.B. bei Shared Hosting):

**1. Dateien ins Webroot kopieren:**
```bash
# Alle Dateien in dein Webroot (z.B. /var/www/html)
cp -r ahnengalerie-v1.3.0/* /var/www/html/
```

**2. `.htaccess` im Root liegt bereits bei - sie leitet automatisch nach `/public` um:**
```apache
# Diese .htaccess ist bereits in der ZIP enthalten
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]
</IfModule>
```

✅ **Kein weiterer Schritt nötig** - funktioniert automatisch!

---

### **Methode 3: nginx (Für Fortgeschrittene)**

**nginx-Konfiguration:**
```nginx
server {
    listen 80;
    server_name meine-ahnengalerie.local;
    
    root /var/www/ahnengalerie/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Verbiete Zugriff auf writable/
    location ~ ^/writable/ {
        deny all;
    }
}
```

---

## ⚙️ Konfiguration (.env anpassen)

**Wichtig:** Die Datenbank ist bereits vorbereitet und enthält einen Admin-Account!

### **1. .env-Datei erstellen/bearbeiten:**
```bash
cp .env.example .env
nano .env    # oder dein bevorzugter Editor
```

### **2. Wichtige Einstellungen:**
```env
#--------------------------------------------------------------------
# UMGEBUNG
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP URL (ANPASSEN!)
#--------------------------------------------------------------------
app.baseURL = 'http://localhost:8080/'
# Beispiele:
# app.baseURL = 'http://meine-ahnengalerie.local/'
# app.baseURL = 'https://familie-mueller.de/'

#--------------------------------------------------------------------
# EMAIL (ANPASSEN!)
#--------------------------------------------------------------------
email.fromEmail = noreply@example.com
email.fromName = 'Meine Ahnengalerie'
```

Pfad zur Datenbank anpassen.

**Das war's!** 

---

## 🔐 Erster Login

### **Standard-Admin-Account:**
```
Benutzername: admin
Passwort: admin123
```

### ⚠️ **WICHTIG: Passwort sofort ändern!**

Nach dem ersten Login:

1. Klicke auf **"Profil"** (oben rechts)
2. Ändere dein Passwort
3. Optional: Username und Email anpassen

---

## ✅ Installation testen

### **1. Öffne die Anwendung im Browser**

Je nach Setup:
- Spark serve: `http://localhost:8080`
- Apache: `http://meine-ahnengalerie.local`
- Production: `https://deine-domain.de`

### **2. Erwartetes Ergebnis:**

✅ Login-Seite wird angezeigt  
✅ Login mit `admin / admin123` funktioniert  
✅ Dashboard öffnet sich  
✅ Keine Fehlermeldungen  

---

## 🐛 Problemlösung

### **Fehler: "Unable to connect to the database"**

**Lösung:**
```bash
# Berechtigungen prüfen
ls -la writable/database/

# Falls genealogy.db nicht existiert oder nicht beschreibbar:
chmod 666 writable/database/genealogy.db
chmod 777 writable/database/
```

---

### **Fehler: "404 Not Found" auf allen Seiten**

**Apache:**
```bash
# mod_rewrite aktivieren
sudo a2enmod rewrite
sudo systemctl restart apache2

# .htaccess prüfen
ls -la public/.htaccess
```

**nginx:**
```nginx
# try_files in Config prüfen
try_files $uri $uri/ /index.php?$query_string;
```

---

### **Fehler: "500 Internal Server Error"**

**Lösung:**
```bash
# Berechtigungen prüfen
chmod -R 777 writable/

# Error-Log ansehen
tail -f writable/logs/log-*.log
```

---

### **Fotos können nicht hochgeladen werden**

**Lösung:**
```bash
# Upload-Ordner beschreibbar machen
chmod -R 777 writable/uploads/

# PHP upload_max_filesize prüfen
php -i | grep upload_max_filesize
```

In `php.ini` anpassen falls nötig:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

---

## 🔒 Sicherheit (Production)

### **1. Admin-Passwort ändern**
✅ **Erste Priorität!** Ändere `admin123` sofort.

### **2. .env schützen**
```bash
# .env darf NICHT öffentlich zugänglich sein
chmod 600 .env

# Prüfen ob .env per HTTP erreichbar ist:
# https://deine-domain.de/.env
# → Sollte 403 oder 404 ergeben!
```

### **3. writable/ Ordner schützen**

Bereits in `.htaccess` enthalten:
```apache
<Directory writable>
    Require all denied
</Directory>
```

### **4. SSL-Zertifikat (HTTPS)**

**Let's Encrypt (kostenlos):**
```bash
sudo apt-get install certbot python3-certbot-apache
sudo certbot --apache -d deine-domain.de
```

---

## 📚 Nächste Schritte

Nach erfolgreicher Installation:

1. ✅ **Passwort ändern** (siehe oben)
2. 📖 **Hilfe-System erkunden** - Klicke auf "Hilfe" im Menü
3. 👥 **Erste Person anlegen** - Dashboard → "Person hinzufügen"
4. 📸 **Fotos hochladen** - In Personen-Details
5. 🌳 **Stammbaum anschauen** - Menü → "Stammbaum"

### **Weitere Dokumentation:**

- 📘 [Benutzerhandbuch](http://localhost:8080/help) (in der App)


## 🎉 Fertig!

**Viel Spaß mit deiner Ahnengalerie!**

---

**Version:** 1.3.0  
**Letzte Aktualisierung:** Oktober 2025