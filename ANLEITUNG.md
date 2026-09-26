# YouTube-Plugin 1.1.0

Das Plugin zeigt zunächst einen lokalen Platzhalter und lädt den YouTube-Player nach Zustimmung beziehungsweise aufgrund einer zuvor gespeicherten Auswahl. Es ist kostenlos, ohne Video-Limit, eigenen API-Key oder Lizenzschlüssel. Das Plugin selbst fügt keine Werbung hinzu; YouTube kann im Player weiterhin Werbung anzeigen.

## Installieren und Video einfügen

Das installierbare Paket heißt `gdpr-dsgvo-compliant-embeds-for-youtube-videos-1.1.0.zip`. Unter **Plugins → Neues Plugin hinzufügen → Plugin hochladen** installieren beziehungsweise aktualisieren. Erforderlich sind WordPress 6.2 und PHP 7.4 oder neuer.

Unter **Videos → Neues Video hinzufügen** den vollständigen iframe von **YouTube → Teilen → Einbetten** einfügen. Unterstützt werden HTTPS-Player von `www.youtube.com` und `www.youtube-nocookie.com`. Ein normaler Video- oder youtu.be-Link ist kein Einbettungscode. Einstellungen wählen, veröffentlichen und den Shortcode in einen Shortcode-Block setzen:

```text
[dsgvo_video id="123"]
```

Die ID steht im Videoeditor. JavaScript ist für Laden und Zurücksetzen erforderlich. Ein Klick lädt den Player; YouTube kann anschließend einen weiteren Klick zum Abspielen verlangen oder die Wiedergabe aus eigenen Gründen blockieren.

## Neue Optionen ohne automatische Umgestaltung

Bestehende Videos behalten ihre IDs, Shortcodes und kompatiblen Größen/Stile. Die fünf neuen Schriftfelder für Button, Nachricht, Datenschutztext, Datenschutzlink und Merktext sind zunächst leer. **Leer lassen übernimmt das bisherige Aussehen.** Eine optionale Nachricht erscheint zwischen Ladebutton und Datenschutzhinweis.

Gruppenladen, Merkfunktion und moderne Checkbox müssen ausdrücklich aktiviert werden. Gruppenladen betrifft nur entsprechend aktivierte Videos auf derselben Seite. Beschriften Sie den Button/Hinweis so, dass dieser Umfang verständlich ist.

Für die neue Checkbox unter **Videos → Video bearbeiten → Ladeverhalten → Checkbox-Design** die Option **Modernes Design (größere Checkbox)** wählen und aktualisieren. Die Merkoption muss ebenfalls eingeschaltet sein. **Bisheriges Design** bleibt Standard. Screenshot 5 zeigt die Einstellung; Screenshots 6/7 zeigen den aktivierten modernen Stil mit 22px-Checkbox und gestalteter Beschriftungsfläche.

Wählen Sie genügend Höhe für lange Hinweise, besonders mobil. Prozentuale Höhen behalten das bisherige Seitenverhältnis-Verhalten; bei voller Breite sind 56,25% ein gängiger Wert für 16:9. Bestehende Videos werden nicht automatisch vergrößert.

## Auswahl merken: kein Sessioncookie

Ohne Merk-Häkchen setzt das Laden keinen neuen Zustimmungscookie. Eine bereits gespeicherte Auswahl wird dadurch nicht gelöscht. Mit aktiviertem Häkchen und eingefügtem gültigem Player wird `dsgvo_yt_consent=1` für bis zu **180 Tage** gespeichert. Der Browser kann Cookies früher löschen oder blockieren; ein automatisches Ende beim Schließen des Browsers ist nicht zugesagt.

Die Auswahl gilt websiteweit nur für Videos mit aktivierter Merkoption. Sie ist unabhängig vom Zustimmungscookie des Google-Maps-Plugins. Eine YouTube-Zustimmung lädt deshalb keine Karten. Das Cookie ist eine Ladepräferenz, kein serverseitiges Einwilligungsprotokoll.

## Separater Zurücksetzen-Button

An beliebiger Stelle im Seiteninhalt, etwa auf der Datenschutzseite, einen Shortcode-Block einfügen:

```text
[dsgvo_video_reset text="Auswahl zurücksetzen"]
```

Ohne `text` verwendet `[dsgvo_video_reset]` die übersetzte Standardbeschriftung. Der Button ist auch ohne Video oder gespeicherte Auswahl sichtbar und bedienbar. Er löscht die YouTube-Auswahl, entlädt alle Plugin-Player auf der aktuellen Seite und entfernt sämtliche dortigen Merk-Häkchen. Eine Statusmeldung bestätigt den Vorgang; der Tastaturfokus bleibt am separaten Button. Screenshot 9 zeigt diese Variante.

## Optionaler Button im Playerbereich

Screenshot 8 zeigt diese ausdrücklich aktivierte Option:

```text
[dsgvo_video id="123" show_reset="true"]
```

Der Inline-Button erscheint erst beim geladenen Player und belegt 64px der konfigurierten Höhe. Der normale Video-Shortcode fügt keinen Reset hinzu. Für die volle Playerfläche den separaten Reset außerhalb des Videos verwenden.

Beide Varianten lassen andere offene Tabs und bereits an Google übertragene Daten unberührt. Die gespeicherte Maps-Auswahl wird nicht gelöscht. Alternativ können Besucher den Cookie über ihre Browsereinstellungen entfernen.

## Datenschutz und Grenzen

Nach dem Laden verbindet sich der Browser mit YouTube/Google; dabei können unter anderem IP-Adresse, Geräteinformationen, Website-Origin und Cookie-/Kontoinformationen verarbeitet werden. Der datenschutzverbesserte youtube-nocookie-Modus verhindert weder sämtliche Datenverarbeitung noch jede Werbung. Hinweise dazu stehen in [YouTubes Dokumentation](https://support.google.com/youtube/answer/171780?hl=de), [Googles Datenschutzerklärung](https://policies.google.com/privacy) und den [YouTube-Nutzungsbedingungen](https://www.youtube.com/t/terms).

Das Plugin verzögert nur seine eigenen konfigurierten Player. Andere Website-Inhalte werden nicht blockiert. Verständliche Hinweise, gültige Zustimmung und eine geeignete Widerrufsmöglichkeit bleiben erforderlich; das Plugin allein garantiert keine DSGVO-Konformität.

## Pakete, Prüfungen und Hilfe

`wordpress-org-assets-1.1.0.zip` enthält ausschließlich Verzeichnisgrafiken und ist nicht installierbar. Alle neuen Screenshots werden tatsächlich in WordPress aufgenommen; alte 1.0.1-Unterlagen sind separat archiviert. Aktuelle Ergebnisse stehen in `TESTING.md`, der Veröffentlichungsablauf in `RELEASING.md`.

Hersteller: **[Tsambasis & Tsambasis](https://tsambasis.net/)**. Die [Live-Demonstration](https://plugin-demo.m00dy.org/live-demonstration/) und [Plugininformationen](https://solutionfirst.m00dy.org/wp-plugin/) behalten ihre bisherigen URLs. `solutionfirst` bleibt der technische WordPress.org-Mitwirkendenname.
