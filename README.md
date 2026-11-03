# AK Chatbot Widget

Eigenständiges Vanilla-JavaScript-Widget plus öffentlicher, vollständig lokaler UI-Playground.

```bash
npm test
npm run lint
npm run build
python3 -m http.server 8000
```

## Integration

```html
<script src="chatbot-widget.js"></script>
<script>
  new ChatbotWidget({
    apiUrl: "https://your-own-backend.example",
    theme: "dark",
    position: "bottom-right"
  });
</script>
```

API-Schlüssel und Modellzugriff gehören ausschließlich ins eigene Backend. CORS, Authentifizierung, Rate Limits, Datenaufbewahrung und Datenschutzhinweise müssen vor einem produktiven Einsatz projektbezogen umgesetzt werden. Der öffentliche Playground lädt absichtlich keine API und sendet keine Nachrichten.

Dokumentation: [`user-guides/README.md`](user-guides/README.md)

Live-Demo: https://ak-hosting.github.io/ak-chatbot-widget/
