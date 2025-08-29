# Chatbot Widget

Ein eigenständiges Chatbot-Widget für die Integration in beliebige Websites.

## 🚀 Schnellstart

### Integration über jsDelivr CDN

```html
<!-- Widget-Script laden -->
<script src="https://cdn.jsdelivr.net/gh/ak-pro/chatbot-widget@main/chatbot-widget.js"></script>

<!-- Widget initialisieren -->
<script>
    new ChatbotWidget({
        apiUrl: 'https://chatbot-api.ak-pro.com',
        position: 'bottom-right',
        title: 'Kundenservice'
    });
</script>
```

## ⚙️ Konfigurationsoptionen

```javascript
new ChatbotWidget({
    apiUrl: 'https://chatbot-api.ak-pro.com',    // Backend-URL (erforderlich)
    position: 'bottom-right',                    // Position: 'bottom-right', 'bottom-left', 'top-right', 'top-left'
    title: 'Kundenservice',                      // Titel im Chat-Header
    welcomeMessage: 'Hallo! Wie kann ich helfen?', // Begrüßungsnachricht
    placeholder: 'Nachricht eingeben...',       // Eingabefeld-Platzhalter
    theme: 'default',                           // Theme: 'default', 'dark', 'light'
    language: 'de',                             // Sprache: 'de', 'en'
    minimized: false,                           // Startet minimiert
    zIndex: 9999                               // CSS z-index
});
```

## 🎨 Themes

- **default**: Standard-Design mit blauen Akzenten
- **dark**: Dunkles Design
- **light**: Helles Design

## 🌍 Unterstützte Sprachen

- **de**: Deutsch
- **en**: English

## 📱 Features

- ✅ Responsive Design für Mobile und Desktop
- ✅ Minimieren/Maximieren
- ✅ Mehrsprachig
- ✅ Anpassbare Themes
- ✅ Cross-Origin-Kommunikation
- ✅ Session-Management
- ✅ Produkt-Empfehlungen
- ✅ Quick-Reply-Buttons

## 🔧 Browser-Unterstützung

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## 📄 Lizenz

Copyright (c) 2025 ak-hosting - Alle Rechte vorbehalten

## 🆘 Support

Bei Fragen oder Problemen wenden Sie sich an: support@ak-pro.com

---

**Powered by ak-pro.com**