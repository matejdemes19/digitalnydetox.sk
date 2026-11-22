# DigitalnyDetox.sk – pôvodné PHP snippety testu

Tento repozitár uchováva pôvodnú dvojicu PHP snippetov pre DD Test V2 vo WordPresse.

## Súbory

1. `snippets/01-dd-test-v2-core.php` – jadro, vytvorenie databázovej tabuľky, spracovanie odoslania, bodovanie a uloženie výsledku.
2. `snippets/02-dd-test-v2-frontend.php` – frontend testu, otázky a zobrazenie jednej otázky na obrazovke; shortcode `[dd_test]`.

## Poradie nasadenia

- Vložte oba snippety do Code Snippets (alebo ekvivalentného správcu kódu).
- Aktivujte najskôr jadro a potom frontend.
- Na stránke, kde má byť test zobrazený, použite shortcode `[dd_test]`.

## Poznámka

Súbory sú uložené ako archív pôvodných snippetov. Obsah nebol zámerne refaktorovaný ani modernizovaný. Pred nasadením na produkčný web odporúčame otestovať ich na testovacej kópii webu.
