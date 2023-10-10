# Hinweise zur Arbeit mit den Code-Snippets des Vortrags

Für die Ausgaben von ``dump()`` muss der Debugmodus eingeschaltet sein - entweder im Backend
aktivieren oder in Dev-Umgebung über ``.env`` mit Eintrag ``APP_ENV=dev`` aktivieren

Wenn nicht nur mit dem Ordner ``src/EventListener`` und der Attribut- bzw. Annotation-Magic
gearbeitet wird, sondern die Services über eine eigene service.yml geladen werden sollen,
dann folgendes von ``autoload`` und ``extra`` in composer.json einfügen:

```
,
"autoload": {
    "psr-4": {
        "App\\": "src/"
    }
}
...
,
"extra": {
...
    "contao-manager-plugin": "App\\ContaoManager\\Plugin"
},
```

Anschließend composer "install ausführen":

```
php -d memory_limit=-1 -d max_execution_time=900 web/contao-manager.phar.php composer install
// bzw.
php -d memory_limit=-1 -d max_execution_time=900 public/contao-manager.phar.php composer install
```

## Prüfungen

prüfen, ob Templatehelper geladen ist:

```
php vendor/bin/contao-console debug:container App\Helper\MetaModelsTemplateHelper
```

prüfen, ob Hook geladen wird:

```
php vendor/bin/contao-console debug:container --tag=contao.hook
```

prüfen, ob Event - z. B. ``dc-general.model.pre-persist `` geladen wird:

```
php vendor/bin/contao-console debug:event-dispatcher dc-general.model.pre-persist
```
