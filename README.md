# Vortrag Contao-Konferenz 2023 in Kiel

Mit MetaModels (MM) ist es möglich, ohne Programmieraufwand eigene Datenstrukturen in Contao
anzulegen und die Daten auf verschiedenste Weise im Frontend auszugeben. MM liefert mit seinen
modularen Repositories verschiedene Relationstypen und Filter mit, so dass damit einfache als
auch komplexe Projekte umgesetzt werden können.

Bei besonderen Projektaufgaben können die implementierten Möglichkeiten nicht ausreichen und
müssen um eigene Anpassungen ergänzt werden. Hier stehen verschiedene Methoden von MM oder
auch von DC_General (DCG) zur Verfügung, um mit wenigen Zeilen diese Aufgaben umzusetzen.

In dem Vortrag soll ein Einstieg mit Beispielen dazu gegeben werden.

Folien und Video sind auf der Webseite auf [e-spin.de](https://www.e-spin.de/contao-metamodels/metamodels-vortrag-contao-konferenz-2023.html) zu finden.

## one more thing...

Zum Testen von MM 2.3 (Contao 4.13 + PHP 8.1) stellt das MM-Team einen Zugangs-Key bereit, der
bis zum 01.01.2024 gilt: ``one-more-thing_contao-konferenz-2023``

Folgende Pakete können installiert werden:

* attribute_alias
* attribute_checkbox
* attribute_file
* attribute_longtext
* attribute_numeric
* attribute_select
* attribute_text
* attribute_timestamp
* attribute_translatedalias
* attribute_translatedtext
* attribute_url
* core
* filter_checkbox
* filter_select
* filter_text

Sollte bis zu dem Datum das Fundraising zu MM 2.3 noch nicht abgeschlossen sein, kann man mit
einer Zuwendung einen persönlichen, dauerhaften Zugangskey erhalten - [mehr beim Fundraising](https://now.metamodel.me/de/unterstuetzer/fundraising#metamodels_2-3) -
natürlich dann auch mit Zugang zu [allen anderen Paketen](https://github.com/MetaModels/core/issues/1469).

**Hinweise zur Installation:**

Beim Update auf folgende Hürden achten bei "[Siehe auch](https://metamodels.readthedocs.io/de/latest/manual/install.html#installation-von-mm-2-3-fur-contao-4-13-und-php-8)".

Folgend sind zwei composer.json-Dateien abgelegt, deren Einstellungen aus ``require`` und ``repositories``
zu übernehmen sind oder mit einer der beiden Version starten:

* [default-min-composer.json](src/Docs/default-min-composer.json)
* [default-composer.json](src/Docs/default-composer.json)

Update ist mit Contao-Manager oder über die Konsole möglich - ich empfehle Konsole.

**Achtung!** Durch den neuen Schemamanager werden angelegte Attribute bzw. Änderungen an Typ und
Spaltenname nicht wie bisher direkt in die DB übernommen. Zur Übernahme bitte eine DB-Migration mit dem
Install-Tool, CManager oder auf Konsole ausführen.
Mehr dazu [hier im Handbuch]( https://metamodels.readthedocs.io/de/latest/manual/component/schema-manager.html).

Wer von MM 2.0 oder 2.1 kommt, hier eine Seite mit allen (wichtigen) Änderungen zu MM 2.2,
was natürlich auch für 2.3 gilt

* [MM 2.2](https://metamodels.readthedocs.io/de/latest/manual/new-in-mm-22.html )
* [MM 2.3](https://metamodels.readthedocs.io/de/latest/manual/new-in-mm-23.html)  

auf der Seite gibt es unten jeweils auch eine Checkliste für ggf. notwendige manuelle Anpassungen.

Sofern es die weiteren Erweiterungen zulassen, sollte man das "legacy routing" in Contao abschalten - 
[siehe Handbuch.](https://metamodels.readthedocs.io/de/latest/manual/new-in-mm-23.html?highlight=legacy_routing#allgemein-und-core)


## Tipps und Hinweise zu den Code-Snippets

Dazu gibt es eine eigene [readme.md](src/Docs/readme.md).
