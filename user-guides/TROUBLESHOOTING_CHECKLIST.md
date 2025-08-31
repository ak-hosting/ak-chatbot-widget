# Troubleshooting-Checkliste für das Chatbot-Widget

## Schnell-Diagnose (2 Minuten)

### ✅ Grundlegende Sichtbarkeit
- [ ] **Widget sichtbar:** Ist der Chatbot-Button in der unteren rechten Ecke sichtbar?
- [ ] **Console-Fehler:** Öffnen Sie die Browser-Entwicklertools (F12) → Console-Tab
- [ ] **Script geladen:** Suchen Sie nach Fehlermeldungen beim Laden des Scripts
- [ ] **Netzwerk-Status:** Prüfen Sie den Network-Tab auf 404- oder CORS-Fehler

### ✅ Grundlegende Funktionalität
- [ ] **Widget öffnet:** Klickt sich das Widget öffnen?
- [ ] **Eingabefeld:** Ist das Eingabefeld sichtbar und anklickbar?
- [ ] **Senden-Button:** Reagiert der Senden-Button auf Klicks?
- [ ] **API-Verbindung:** Erscheinen Antworten vom Chatbot?

## Detaillierte Prüfungen

### 🔧 Script-Integration

#### CDN-Integration
```html
<!-- Prüfen Sie diese Zeile in Ihrem HTML -->
<script src="https://cdn.jsdelivr.net/gh/ak-hosting/ak-chatbot-widget@latest/chatbot-widget.js"></script>
```

**Häufige Probleme:**
- [ ] **Falsche URL:** Ist die CDN-URL korrekt?
- [ ] **Version:** Verwenden Sie die neueste Version?
- [ ] **HTTPS:** Wird HTTPS verwendet (erforderlich für moderne Browser)?
- [ ] **Verfügbarkeit:** Ist der CDN-Service erreichbar?

#### Selbst-gehostete Integration
```html
<!-- Lokale Datei -->
<script src="/js/chatbot-widget.js"></script>
```

**Prüfpunkte:**
- [ ] **Dateipfad:** Ist der Pfad zur lokalen Datei korrekt?
- [ ] **Datei existiert:** Existiert die Datei auf dem Server?
- [ ] **Berechtigungen:** Hat der Webserver Leserechte für die Datei?
- [ ] **MIME-Type:** Wird die Datei als JavaScript ausgeliefert?

### 🏗️ HTML-Container

#### Data-Attribute Methode
```html
<div id="chatbot-container" 
     data-api-url="https://chatbot-api.ak-pro.com"
     data-title="Kundenservice"
     data-primary-color="#007bff">
</div>
```

**Checkliste:**
- [ ] **Container-ID:** Ist die ID eindeutig und korrekt?
- [ ] **API-URL:** Ist die API-URL erreichbar und korrekt?
- [ ] **Attribute:** Sind alle erforderlichen Data-Attribute gesetzt?
- [ ] **Syntax:** Sind die Attribute korrekt formatiert?

#### JavaScript-Konfiguration
```javascript
window.ChatbotConfig = {
    apiUrl: 'https://chatbot-api.ak-pro.com',
    containerId: 'chatbot-container',
    title: 'Kundenservice'
};
```

**Prüfpunkte:**
- [ ] **Timing:** Wird die Konfiguration vor dem Script geladen?
- [ ] **Syntax:** Ist die JavaScript-Syntax korrekt?
- [ ] **Container:** Existiert das angegebene Container-Element?
- [ ] **API-URL:** Ist die API-URL vollständig und erreichbar?

### 🌐 API-Verbindung

#### Basis-Verbindungstest
```bash
# Terminal-Test der API
curl -X POST https://your-api.com/api/chat/message \
  -H "Content-Type: application/json" \
  -d '{"message": "Hallo", "session_id": "test123"}'
```

**Erwartete Antwort:**
```json
{
  "response": "Hallo! Wie kann ich Ihnen helfen?",
  "suggestions": ["Produkte", "Support", "Kontakt"]
}
```

**Häufige API-Probleme:**
- [ ] **404 Not Found:** API-Endpunkt existiert nicht
- [ ] **500 Server Error:** Server-seitiger Fehler
- [ ] **Timeout:** API antwortet nicht rechtzeitig
- [ ] **Falsche Response:** API sendet unerwartete Daten

#### CORS-Probleme
**Typische CORS-Fehlermeldung:**
```
Access to fetch at 'https://your-api.com/api/chat' from origin 'https://your-website.com' 
has been blocked by CORS policy
```

**Lösungsansätze:**
- [ ] **Server-Konfiguration:** CORS-Header auf dem API-Server setzen
- [ ] **Domain-Whitelist:** Ihre Domain in der API-Konfiguration freigeben
- [ ] **Preflight-Requests:** OPTIONS-Requests korrekt behandeln
- [ ] **Credentials:** Cookie/Auth-Header-Behandlung prüfen

### 🔐 Session-Management

#### Session-ID Generierung
```javascript
// Prüfen Sie in den Browser-Entwicklertools
console.log('Session ID:', localStorage.getItem('chatbot-session-id'));
```

**Prüfpunkte:**
- [ ] **LocalStorage:** Wird die Session-ID gespeichert?
- [ ] **Eindeutigkeit:** Ist die Session-ID eindeutig?
- [ ] **Persistenz:** Bleibt die Session über Seitenreloads bestehen?
- [ ] **Cleanup:** Werden alte Sessions ordnungsgemäß bereinigt?

### 🌍 Browser-Kompatibilität

#### Unterstützte Browser
- [ ] **Chrome:** Version 70+
- [ ] **Firefox:** Version 65+
- [ ] **Safari:** Version 12+
- [ ] **Edge:** Version 79+
- [ ] **Mobile Safari:** iOS 12+
- [ ] **Chrome Mobile:** Android 7+

#### JavaScript-Features
```javascript
// Prüfen Sie diese Features in der Console
console.log('Fetch API:', typeof fetch !== 'undefined');
console.log('LocalStorage:', typeof localStorage !== 'undefined');
console.log('Promises:', typeof Promise !== 'undefined');
console.log('Arrow Functions:', (() => true)());
```

### 📱 Mobile Optimierung

#### Viewport-Konfiguration
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

#### Touch-Interaktion
- [ ] **Touch-Events:** Reagiert das Widget auf Touch-Eingaben?
- [ ] **Scroll-Verhalten:** Funktioniert das Scrollen in der Chat-Historie?
- [ ] **Keyboard:** Öffnet sich die Tastatur beim Fokus des Eingabefelds?
- [ ] **Zoom:** Verhindert das Widget ungewolltes Zoomen?

#### Responsive Design
```css
/* Prüfen Sie diese CSS-Regeln */
@media (max-width: 768px) {
    .chatbot-widget {
        width: 100vw !important;
        height: 100vh !important;
    }
}
```

### ⚡ Performance

#### Ladezeiten
- [ ] **Script-Größe:** Ist das Script unter 100KB?
- [ ] **Lazy Loading:** Wird das Widget erst bei Bedarf geladen?
- [ ] **Caching:** Werden statische Ressourcen gecacht?
- [ ] **CDN:** Wird ein CDN für bessere Ladezeiten verwendet?

#### Memory-Usage
```javascript
// Performance-Monitoring
console.log('Memory:', performance.memory);
console.log('Navigation:', performance.navigation);
```

### 🔒 Sicherheit

#### Content Security Policy (CSP)
```html
<meta http-equiv="Content-Security-Policy" 
      content="script-src 'self' https://cdn.jsdelivr.net; 
               connect-src 'self' https://your-api.com;">
```

**CSP-Probleme:**
- [ ] **Script-Source:** Ist die CDN-Domain in script-src erlaubt?
- [ ] **Connect-Source:** Ist die API-Domain in connect-src erlaubt?
- [ ] **Inline-Scripts:** Werden inline-Scripts blockiert?
- [ ] **Eval:** Wird eval() verwendet und blockiert?

## Debugging-Tools

### 🔍 Browser-Entwicklertools

#### Console-Befehle
```javascript
// Debug-Informationen anzeigen
window.ChatbotWidget.debug = true;
window.ChatbotWidget.getStatus();
window.ChatbotWidget.testConnection();
```

#### Network-Tab
- [ ] **API-Requests:** Werden Requests an die API gesendet?
- [ ] **Response-Codes:** Sind die HTTP-Status-Codes korrekt (200)?
- [ ] **Response-Time:** Sind die Antwortzeiten akzeptabel (<2s)?
- [ ] **Payload:** Sind Request/Response-Daten korrekt formatiert?

#### Elements-Tab
```css
/* Widget-Elemente inspizieren */
.chatbot-widget { /* Hauptcontainer */ }
.chatbot-messages { /* Nachrichten-Bereich */ }
.chatbot-input { /* Eingabefeld */ }
```

### 🧪 Debug-Modus

#### Aktivierung
```javascript
// Debug-Modus aktivieren
window.ChatbotConfig = {
    debug: true,
    logLevel: 'verbose',
    apiUrl: 'https://chatbot-api.ak-pro.com'
};
```

#### Debug-Ausgaben
```javascript
// Erwartete Console-Ausgaben
[Chatbot] Widget initialized
[Chatbot] API connection established
[Chatbot] Session created: abc123
[Chatbot] Message sent: "Hallo"
[Chatbot] Response received: "Hallo! Wie kann ich helfen?"
```

### 🧪 Test-Umgebung

#### Lokaler Test-Server
```bash
# Einfacher HTTP-Server für Tests
python -m http.server 8000
# oder
npx serve .
```

#### Test-Nachrichten
```javascript
// Automatisierte Tests
const testMessages = [
    'Hallo',
    'Welche Produkte haben Sie?',
    'Ich brauche Hilfe',
    'Auf Wiedersehen'
];

testMessages.forEach((msg, index) => {
    setTimeout(() => {
        window.ChatbotWidget.sendMessage(msg);
    }, index * 2000);
});
```

## Häufige Fehlermeldungen

### ❌ JavaScript-Fehler

#### "ChatbotWidget is not defined"
**Ursache:** Script nicht geladen oder falsche Reihenfolge

**Lösung:**
```html
<!-- Korrekte Reihenfolge -->
<script src="chatbot-widget.js"></script>
<script>
    // Warten bis Widget geladen ist
    document.addEventListener('DOMContentLoaded', function() {
        // Widget-Code hier
    });
</script>
```

#### "Cannot read property 'apiUrl' of undefined"
**Ursache:** Konfiguration fehlt oder falsch formatiert

**Lösung:**
```javascript
// Konfiguration vor Script-Laden setzen
window.ChatbotConfig = {
    apiUrl: 'https://chatbot-api.ak-pro.com',
    // weitere Optionen...
};
```

#### "Failed to fetch"
**Ursache:** Netzwerkproblem oder CORS-Fehler

**Lösung:**
1. API-URL prüfen
2. CORS-Konfiguration auf Server prüfen
3. Netzwerkverbindung testen

### 🎨 CSS-Probleme

#### Widget nicht sichtbar
**Mögliche Ursachen:**
```css
/* Z-Index zu niedrig */
.chatbot-widget {
    z-index: 9999 !important;
}

/* Überlappende Elemente */
.other-element {
    position: relative; /* statt fixed */
}

/* CSS-Konflikte */
.chatbot-widget * {
    box-sizing: border-box !important;
}
```

#### Responsive Probleme
```css
/* Mobile Anpassungen */
@media (max-width: 768px) {
    .chatbot-widget {
        width: 100vw !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
    }
}
```

## Support-Ressourcen

### 📚 Dokumentation
- **Integrationsleitfaden:** Schritt-für-Schritt-Anleitung zur Einrichtung
- **Konfigurationsanleitung:** Alle verfügbaren Parameter und Optionen
- **CSS-Anpassungsanleitung:** Design-Anpassungen und Themes

### 💡 Praktische Beispiele
- **Basis-Integration:** Einfache HTML-Implementierung
- **Erweiterte Integration:** JavaScript-Konfiguration mit allen Optionen
- **Shopify-Integration:** E-Commerce-spezifische Implementierung
- **WordPress-Plugin:** CMS-Integration

### 🌐 Community und Support
- **E-Mail:** support@ak-pro.com
- **GitHub Repository:** Quellcode, Issues und Diskussionen
- **Beispiel-Implementierungen:** Funktionierende Code-Beispiele
- **FAQ-Sammlung:** Häufig gestellte Fragen und Antworten

### 🐛 Fehler melden

Wenn Sie einen Fehler gefunden haben:

1. **Reproduzierbarkeit:** Können Sie den Fehler reproduzieren?
2. **Browser-Info:** Welcher Browser und welche Version?
3. **Console-Logs:** Gibt es Fehlermeldungen in der Console?
4. **Konfiguration:** Wie ist Ihr Widget konfiguriert?
5. **Minimal-Beispiel:** Können Sie ein minimales Beispiel erstellen?

**Bug-Report Template:**
```markdown
## Fehlerbeschreibung
[Kurze Beschreibung des Problems]

## Reproduktion
1. Schritt 1
2. Schritt 2
3. Fehler tritt auf

## Erwartetes Verhalten
[Was sollte passieren]

## Umgebung
- Browser: [Chrome 91, Firefox 89, etc.]
- Betriebssystem: [Windows 10, macOS 11, etc.]
- Widget-Version: [1.0.0]

## Console-Logs
```
[Fehlermeldungen hier einfügen]
```

## Konfiguration
```javascript
// Ihre Widget-Konfiguration
```
```

---

## Checkliste für Go-Live

### ✅ Pre-Launch Checklist
- [ ] **Funktionstest:** Alle Grundfunktionen getestet
- [ ] **Browser-Test:** In allen Ziel-Browsern getestet
- [ ] **Mobile-Test:** Auf verschiedenen Geräten getestet
- [ ] **Performance-Test:** Ladezeiten unter 3 Sekunden
- [ ] **API-Test:** Alle API-Endpunkte funktionsfähig
- [ ] **CORS-Konfiguration:** Produktions-Domain freigegeben
- [ ] **SSL-Zertifikat:** HTTPS für API und Website
- [ ] **Monitoring:** Fehler-Tracking implementiert
- [ ] **Backup:** Fallback-Lösung vorbereitet
- [ ] **Dokumentation:** Team über Integration informiert

### 🔄 Post-Launch Monitoring
- [ ] **Error-Rate:** Unter 1% in den ersten 24h
- [ ] **Response-Time:** API-Antworten unter 2 Sekunden
- [ ] **User-Feedback:** Erste Nutzererfahrungen sammeln
- [ ] **Analytics:** Nutzungsstatistiken überwachen
- [ ] **Performance:** Server-Last und Memory-Usage prüfen

Diese Troubleshooting-Checkliste hilft Ihnen dabei, Probleme schnell zu identifizieren und zu lösen. Arbeiten Sie die Punkte systematisch ab und dokumentieren Sie gefundene Lösungen für zukünftige Referenz.