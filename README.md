# Komfortkasse-Plugin für JTL-Shop

Das Komfortkasse-Plugin für JTL-Shop (JTL-Shop 5) automatisiert den Zahlungsabgleich für **Vorkasse, Rechnung und Nachnahme**. Komfortkasse ordnet eingehende Überweisungen automatisch den offenen Bestellungen Ihres Shops zu und setzt den Bestellstatus, sobald das Geld da ist. Das Plugin verbindet den JTL-Shop mit Komfortkasse; den Abgleich mit dem Bankkonto übernimmt Komfortkasse. Nutzen Sie JTL-Wawi, übernimmt sie Bestellstatus und Zahlungseingang beim nächsten Abgleich mit dem Shop.

[Komfortkasse für JTL-Shop](https://komfortkasse.eu/shopsysteme/jtl-shop/) · [Kostenlos registrieren](https://ssl.komfortkasse.eu/public/register.jsf) · [Installationsanleitung](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161591) · [Hilfe & Dokumentation](https://komfortkasse.freshdesk.com/support/solutions/14000075022)

## Eckdaten

| Merkmal | Angabe |
|---|---|
| Shopsystem | [JTL-Shop](https://www.jtl-software.com/de/shop-software/) von JTL-Software |
| Unterstützte Versionen | JTL-Shop ab 5.2 (Ordner `komfortkasse_jtl`); für JTL-Shop 4 ein älteres Plugin im Ordner `komfortkasse_jtl4` (veraltet) |
| Zahlungsarten | Vorkasse (Vorauskasse, Banküberweisung), Rechnung (Kauf auf Rechnung), Nachnahme |
| Aufgabe des Plugins | Überträgt offene Bestellungen an Komfortkasse und setzt den Bestellstatus bei Zahlungseingang oder Storno |
| Für wen | Betreiber von JTL-Shops und Agenturen, die solche Shops betreuen |
| Kosten | Das Plugin ist kostenlos. Für Komfortkasse gelten die [Preise](https://komfortkasse.eu/preise/) auf komfortkasse.eu. |
| Anbieter | LTC Information Services GmbH, [komfortkasse.eu](https://komfortkasse.eu/) |

## Was ist Komfortkasse?

Komfortkasse ist ein Online-Dienst der LTC Information Services GmbH, der Zahlungen per Banküberweisung für Onlineshops automatisiert. Komfortkasse ruft die Zahlungseingänge vom Bankkonto ab, ordnet sie den offenen Bestellungen zu und meldet den Zahlungsstatus an den Shop zurück. Der Dienst richtet sich an Online-Händler in Deutschland, Österreich und der Schweiz.

Komfortkasse unterstützt die Zahlungsarten, bei denen das Geld per Überweisung kommt:

- [Vorkasse](https://komfortkasse.eu/loesungen/vorkasse/): Der Käufer überweist vor dem Versand. Komfortkasse prüft den Zahlungseingang und setzt den Bestellstatus im Shop automatisch.
- [Rechnung](https://komfortkasse.eu/loesungen/rechnung/): Die Ware wird sofort geliefert, der Käufer überweist danach. Komfortkasse überwacht die Zahlung und sendet bei Bedarf Zahlungserinnerungen und Mahnungen.
- [Nachnahme](https://komfortkasse.eu/loesungen/nachnahme/): Der Versanddienstleister kassiert bei der Zustellung und überweist den Betrag. Komfortkasse überwacht diese Überweisung.

### So funktioniert Komfortkasse mit JTL-Shop

1. Das Komfortkasse-Plugin überträgt die offenen Bestellungen aus JTL-Shop an Ihren Komfortkasse-Account.
2. Komfortkasse ist mit Ihrem Bankkonto verbunden ([unterstützte Banken](https://komfortkasse.eu/banken/)) und ordnet eingehende Zahlungen den Bestellungen zu.
3. Das Plugin setzt die Bestellung in JTL-Shop auf den gewählten Status, z. B. „Zahlung eingegangen“, oder auf „storniert“, wenn keine Zahlung kommt.

Komfortkasse versendet außerdem Zahlungserinnerungen an Käufer, behandelt Teil- und Überzahlungen und wickelt Rückerstattungen ab. Alle Funktionen sind auf [komfortkasse.eu](https://komfortkasse.eu/) beschrieben.

## Funktionen des Komfortkasse-Plugins für JTL-Shop

- **Bestellungen übertragen:** Das Plugin exportiert offene Bestellungen nach Zahlungsart (Vorkasse, Rechnung, Nachnahme) und Bestellstatus. Welche Bestellungen dazugehören, legen Sie in den Einstellungen fest: je Zahlungsart die Zahlungsmethoden des Shops und die Bestellstatus, die als offen gelten. Je Bestellung übergibt das Plugin unter anderem Bestellnummer, Bestelldatum, Gesamtbetrag, Währung, Zahlungsart, Kundennummer, E-Mail-Adresse, Sprache, Rechnungs- und Lieferadresse sowie die Artikelnummern (ersatzweise die Artikelnamen).
- **Bestellstatus setzen:** Bei Zahlungseingang setzt das Plugin die Bestellung auf den JTL-Status „Bezahlt“ und trägt das Zahlungsdatum ein, bei Storno bzw. ausbleibender Zahlung setzt es sie auf „Storno“. Bestellungen, die nicht mehr in einem der offenen Status stehen, etwa weil sie von Hand bearbeitet wurden, lässt das Plugin unverändert. Einzige Ausnahme: Geht für eine stornierte Bestellung doch noch Geld ein, setzt das Plugin sie auf „Bezahlt“. Eine Status-E-Mail an den Käufer verschickt das Plugin dabei nicht.
- **Zahlungseingang für JTL-Wawi:** Bei Zahlungseingang legt das Plugin zusätzlich einen Zahlungseingang in JTL-Shop an (Tabelle `tzahlungseingang`): Zahlungsanbieter „Komfortkasse“, als Betrag die Gesamtsumme der Bestellung, im Hinweis die Komfortkasse-Transaktions-ID. Bestellung und Zahlungseingang markiert das Plugin als noch nicht abgeholt (`cAbgeholt = 'N'`), damit [JTL-Wawi](https://www.jtl-software.com/de/warenwirtschaftssystem/) beides beim nächsten Abgleich übernimmt.
- **Vorhandene Zahlungsarten nutzen:** Das Plugin arbeitet mit den Zahlungsarten des Shops; in den Einstellungen wählen Sie sie aus den aktiven Zahlungsarten aus. Es fügt im Checkout keine eigene Zahlungsart hinzu.
- **Echtzeit-Übertragung:** Komfortkasse liest neue Bestellungen regelmäßig aus dem Shop aus. Zusätzlich meldet das Plugin jede neue Bestellung sofort beim Bestellabschluss (Hook `HOOK_BESTELLABSCHLUSS_PAGE`), sodass der Käufer die Zahlungsinformationen ohne Wartezeit erhält. Code-Ergänzungen im Shop sind dafür nicht nötig. Das Plugin sendet die Meldung ausschließlich per HTTPS an `ssl.komfortkasse.eu` und prüft dabei das Zertifikat. Schlägt die Meldung fehl oder steht HTTPS nicht zur Verfügung, übernimmt Komfortkasse die Bestellung beim nächsten regelmäßigen Abruf.
- **Verschlüsselte Übertragung:** Das Plugin überträgt die Daten verschlüsselt mit OpenSSL. Die Schlüssel setzt Komfortkasse beim Verbinden des Shops automatisch.
- **Geschützter Aufruf:** Komfortkasse ruft das Plugin über die Adresse des Shops mit dem Parameter `kkapi` auf; JTL-Shop führt dann über den Hook `HOOK_INDEX_NAVI_HEAD_POSTGET` die Schnittstelle des Plugins aus. Bestelldaten gibt das Plugin nur gegen den Zugriffscode heraus; auch den Bestellstatus ändert es nur mit diesem Code. Den Zugriffscode speichert das Plugin nur als MD5-Hash, und einen bereits gesetzten Code überschreibt es beim Verbinden nicht.
- **Statusseite:** Im Admin-Bereich zeigt der Reiter „Status“ des Plugins, ob der Shop mit Komfortkasse verbunden ist.

## Voraussetzungen

- JTL-Shop ab Version 5.2 (zu JTL-Shop 4 siehe „Häufige Fragen“)
- Ein Komfortkasse-Account ([kostenlos registrieren](https://ssl.komfortkasse.eu/public/register.jsf))
- Ein Bankkonto, das Sie mit Komfortkasse verbinden ([unterstützte Banken](https://komfortkasse.eu/banken/)), oder ein integriertes Bankkonto von Komfortkasse
- Aktive Zahlungsarten im Shop für die Zahlungsarten, die Komfortkasse übernehmen soll, z. B. Vorkasse per Überweisung, Rechnung oder Nachnahme
- Empfohlen: PHP mit OpenSSL-Erweiterung, für die Verschlüsselung und für die Echtzeit-Übertragung per HTTPS. Ohne OpenSSL überträgt das Plugin die Daten nur Base64-kodiert; das ist keine Verschlüsselung.
- Für die Echtzeit-Übertragung zusätzlich: `allow_url_fopen` in der PHP-Konfiguration aktiv
- Für den Upload im Admin-Bereich: Schreibrechte im Ordner `plugins/` des Shops oder eine FTP-/SFTP-Verbindung in JTL-Shop ([JTL-Guide: Dateisystem](https://guide.jtl-software.com/jtl-shop/shop-erweitern/dateisystem/))

## Installation in JTL-Shop

Die Installation besteht aus vier Schritten: Plugin herunterladen, im Admin-Bereich hochladen und installieren, Einstellungen prüfen und den Shop mit dem Komfortkasse-Account verbinden.

1. **Plugin herunterladen:** Auf der Seite [Komfortkasse für JTL-Shop](https://komfortkasse.eu/shopsysteme/jtl-shop/) über „Aktuelle Version herunterladen“ die ZIP-Datei mit dem Plugin laden. Den Quellcode des Plugins für JTL-Shop 5 enthält dieses Repository im Ordner `komfortkasse_jtl`.
2. **Hochladen und installieren:** Im Admin-Bereich von JTL-Shop unter „Plugins“ → „Pluginverwaltung“ den Reiter „Upload“ öffnen, über „Auswählen“ die ZIP-Datei wählen und auf „Hochladen“ klicken. Danach im Reiter „Verfügbar“ das Komfortkasse-Plugin markieren und auf „Installieren“ klicken. Alternativ den Ordner `komfortkasse_jtl` entpackt nach `<Shop-Root>/plugins/` kopieren und das Plugin im Reiter „Verfügbar“ installieren.
3. **Einstellungen prüfen:** Unter „Plugins“ → „Komfortkasse“ den Reiter „Einstellungen“ öffnen. „Export Bestellungen“ und „Update von Bestellungen“ müssen aktiv sein. Für Vorkasse, Rechnung und Nachnahme jeweils wählen: unter „Zahlungsmethoden“ die Zahlungsarten des Shops (leer lassen, wenn Komfortkasse eine Zahlungsart nicht übernehmen soll), unter „Status offen“ die Status offener Bestellungen (mehrere möglich), unter „Status bezahlt“ den Status bei Zahlungseingang und unter „Status Storno“ den Status bei Storno.
4. **Shop verbinden:** Den Shop in Ihrem Komfortkasse-Account verbinden ([Erste Schritte: Einrichtung und Verbindung zum Shop](https://komfortkasse.freshdesk.com/support/solutions/articles/14000114082)). Die Felder im Abschnitt „Verbindung“ (Verschlüsselung, Access Code, API Key, Public Key, Private Key) füllt Komfortkasse dabei automatisch; sie dürfen nicht geändert werden. Danach meldet der Reiter „Status“: „Die Verbindung zum Shop wurde hergestellt.“

Nach der Installation stehen in den Einstellungen diese Standardwerte (JTL-Bestellstatus):

| Zahlungsart | Zahlungsmethoden | Status offen | Status bezahlt | Status Storno |
|---|---|---|---|---|
| Vorkasse | Zahlungsart mit der ID `1` | Offen | Bezahlt | Storno |
| Rechnung | Zahlungsart mit der ID `3` | Versandt | Bezahlt | Storno |
| Nachnahme | Zahlungsart mit der ID `2` | Versandt | Bezahlt | Storno |

„Export Bestellungen“ und „Update von Bestellungen“ sind ab Werk aktiv. Die Vorauswahl bei „Zahlungsmethoden“ richtet sich nach der internen ID der Zahlungsart (`kZahlungsart`); welche Zahlungsarten das sind, hängt vom Shop ab. Prüfen Sie die Auswahl daher.

Behalten Sie bei „Status bezahlt“ und „Status Storno“ die Standardwerte „Bezahlt“ und „Storno“. Das Plugin ändert den Bestellstatus nur auf diese beiden Status. Ist dort ein anderer Status gewählt, etwa „In Bearbeitung“, lässt das Plugin die Bestellung bei Zahlungseingang bzw. Storno unverändert.

Ausführliche Anleitung: [Installationsanleitung JTL-Shop](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161591)

## Update

Für ein Update laden Sie die neue Version wie bei der Installation in der Pluginverwaltung hoch; JTL-Shop erkennt die neuere Version und bietet ein Update an. Die Einstellungen und die Verbindung zu Komfortkasse bleiben dabei erhalten.

1. Die neue Version auf der Seite [Komfortkasse für JTL-Shop](https://komfortkasse.eu/shopsysteme/jtl-shop/) herunterladen.
2. Unter „Plugins“ → „Pluginverwaltung“ im Reiter „Upload“ die ZIP-Datei hochladen.
3. In der Pluginverwaltung beim Komfortkasse-Plugin auf „Update“ klicken. Laut [JTL-Entwicklerdokumentation](https://jtl-devguide.readthedocs.io/projects/jtl-shop/de/latest/shop_plugins/allgemein.html) erkennt die Pluginverwaltung die neue Version automatisch und bietet dafür einen Update-Button an; nach dem Update ist das Plugin direkt aktiviert.

Deinstallieren Sie das Plugin für ein Update nicht: Bei der Deinstallation löscht JTL-Shop alle Einstellungen des Plugins, auch die Verbindungsdaten. Der Shop müsste danach neu mit Komfortkasse verbunden werden.

## Häufige Fragen

### Was kostet das Komfortkasse-Plugin für JTL-Shop?

Das Plugin ist kostenlos. Für Komfortkasse selbst fällt eine prozentuale Gebühr pro Zahlungseingang an, ohne Grundgebühr und ohne Einrichtungsgebühr. Die aktuellen Sätze stehen auf der [Preisseite](https://komfortkasse.eu/preise/). Neue Accounts können Komfortkasse kostenlos testen.

### Ist das Komfortkasse-Plugin eine Zahlungsart?

Nein. Das Komfortkasse-Plugin fügt im Checkout keine Zahlungsart hinzu. Es arbeitet mit den Zahlungsarten, die der JTL-Shop bereits nutzt, zum Beispiel einer Zahlungsart für Vorkasse per Überweisung, für Rechnung oder für Nachnahme. Welche Zahlungsarten das Plugin berücksichtigt, wählen Sie in den Einstellungen unter „Zahlungsmethoden“ aus den aktiven Zahlungsarten des Shops.

### Was passiert, wenn die Bestellnummer fehlt oder der Betrag abweicht?

Komfortkasse ordnet Zahlungen fehlertolerant zu. Die Zuordnung funktioniert auch, wenn die Bestellnummer im Verwendungszweck fehlt, der Name des Zahlers nicht zum Besteller passt oder der Betrag abweicht. Auch Teil- und Überzahlungen behandelt Komfortkasse.

### Was passiert, wenn ein Käufer nicht zahlt?

Komfortkasse sendet automatisch Zahlungserinnerungen an den Käufer. Bleibt die Zahlung aus, setzt das Plugin die Bestellung in JTL-Shop auf den Status, den Sie dafür gewählt haben. Wirksam ist dort nur der JTL-Status „Storno“, der ab Werk bei Vorkasse, Rechnung und Nachnahme unter „Status Storno“ eingestellt ist. Geht das Geld später doch noch ein, setzt das Plugin die stornierte Bestellung auf „Bezahlt“.

### Welche Banken unterstützt Komfortkasse?

Komfortkasse arbeitet mit Ihrem eigenen Bankkonto oder mit einem integrierten Bankkonto von Komfortkasse. Welche Banken sich anbinden lassen, zeigt die Liste der [unterstützten Banken](https://komfortkasse.eu/banken/).

### Wird der Zahlungseingang auch in JTL-Wawi gebucht?

Ja. Komfortkasse aktualisiert den Bestellstatus direkt im JTL-Shop, und JTL-Shop überträgt ihn beim nächsten Abgleich automatisch an JTL-Wawi; die Bestellung steht dann auch in JTL-Wawi auf bezahlt bzw. storniert. Bei Zahlungseingang legt das Komfortkasse-Plugin außerdem einen Zahlungseingang an (Zahlungsanbieter „Komfortkasse“, Betrag: Gesamtsumme der Bestellung, Hinweis: Komfortkasse-Transaktions-ID). Bestellung und Zahlungseingang markiert es als noch nicht abgeholt (`cAbgeholt = 'N'`); solche Datensätze gibt JTL-Shop beim Abgleich an JTL-Wawi weiter.

### Müssen für das Komfortkasse-Plugin Dateien des JTL-Shops geändert werden?

Nein. Das Komfortkasse-Plugin nutzt das Pluginsystem von JTL-Shop 5 und liegt komplett im Ordner `plugins/komfortkasse_jtl`. Es klinkt sich über zwei Hooks ein: `HOOK_INDEX_NAVI_HEAD_POSTGET` (132) für die Schnittstelle zu Komfortkasse und `HOOK_BESTELLABSCHLUSS_PAGE` (2) für die Echtzeit-Übertragung neuer Bestellungen. Einstellungen und Menüpunkte legt JTL-Shop bei der Installation anhand der `info.xml` des Plugins an. Auch die Echtzeit-Übertragung braucht keine Code-Ergänzungen.

### Gibt es das Komfortkasse-Plugin auch für JTL-Shop 4?

Ja, aber nur als älteres Plugin im Ordner `komfortkasse_jtl4` dieses Repositorys; es nutzt das alte Pluginsystem von JTL-Shop 4 und gilt als veraltet. Für aktuelle Shops ist das Plugin im Ordner `komfortkasse_jtl` gedacht. Es nutzt das Pluginsystem von JTL-Shop 5 und setzt laut seiner `info.xml` JTL-Shop ab Version 5.2 voraus.

### Gibt es Komfortkasse auch für andere Shopsysteme?

Ja. Komfortkasse gibt es unter anderem für Shopify, Shopware, WooCommerce, PrestaShop, Gambio und xt:Commerce. Alle Systeme stehen in der [Übersicht der Shopsysteme](https://komfortkasse.eu/shopsysteme/), weitere Plugin-Repositories unter [github.com/ltckomfortkasse](https://github.com/ltckomfortkasse).

## Support

Fragen zur Einrichtung beantworten die [Hilfe-Seiten](https://komfortkasse.freshdesk.com/support/solutions/14000075022) und der [Komfortkasse-Support](https://komfortkasse.eu/kontakt/).

## English

**Komfortkasse plugin for JTL-Shop (JTL-Shop 5).** Komfortkasse automatically matches incoming bank transfers to open orders in your shop and updates the order status as soon as the payment has arrived. It supports prepayment by bank transfer (Vorkasse), invoice and cash on delivery. Komfortkasse is a service of LTC Information Services GmbH for online merchants in Germany, Austria and Switzerland.

The plugin supports JTL-Shop 5.2 or later; an older plugin for JTL-Shop 4 is in the folder `komfortkasse_jtl4` (legacy). It does not add a payment method to the checkout; it works with the shop's existing payment methods. The plugin is free of charge; Komfortkasse itself is a paid service ([pricing](https://komfortkasse.eu/preise/)). When a payment arrives, the plugin sets the order to "Bezahlt" (paid) and records the payment, which JTL-Wawi picks up at its next sync.

Installation: download the plugin ZIP from the landing page, upload it in the JTL-Shop admin under Plugins → Pluginverwaltung (tab "Upload"), install it from the tab "Verfügbar", check the settings and connect the shop in your Komfortkasse account. More information: [Komfortkasse for JTL-Shop](https://komfortkasse.eu/shopsysteme/jtl-shop/) · [Installation guide (English)](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161592)
