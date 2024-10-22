# CM Gamerecipe

**Version**: 1.0.6  
**Författare**: Diamond Strand - CookifyMedia  
**Plugin URI**: [https://github.com/DiamondStrand/cm-gamerecipe](https://github.com/DiamondStrand/cm-gamerecipe)

## Beskrivning

CM Gamerecipe är ett flexibelt och kraftfullt plugin för att skapa och hantera spelrecept. Med detta plugin kan du enkelt lägga till spel med detaljerade regler, antal deltagare, material, speltid och andra spelrelaterade data. Perfekt för att hantera spelkvällar eller andra sociala sammanhang där roliga och engagerande spel behövs.

### Funktioner:

- Anpassad posttyp (CPT) för att hantera spel.
- Lägga till regler, material, antal spelare och andra nödvändiga detaljer för varje spel.
- Ladda upp och länka till PDF-filer för spelplaner eller regelverk.
- Möjlighet att importera spel via CSV-filer.
- Skapa spel direkt från adminpanelen med anpassade fält och kategorier.

## Installation

1. Ladda ner och extrahera zip-filen eller klona detta repository till din WordPress `wp-content/plugins/`-katalog.
2. Gå till **Plugins** > **Lägg till nytt** i din WordPress-administratör och aktivera pluginet.
3. Efter aktivering, gå till **Spel**-fliken i adminpanelen för att börja lägga till eller importera spel.

## CSV-Importfunktion

Version 1.0.6 introducerar möjligheten att importera spel från en CSV-fil. Detta är användbart för att snabbt lägga till många spel samtidigt.

### Exempel på CSV-struktur:

```r
Titel, Minsta spelare, Maximala spelare, Speltid, Material, Passar för, Svårighetsgrad, Förberedelser, Tips
Exempelspel 1,2,4,30,penna,papper,vuxen,lätt,inga,Ha roligt!
Exempelspel 2,3,6,45,kortlek,tärningar,vuxen,medel,lite,Tänk strategiskt!
```

### Så här använder du importfunktionen:

1. Gå till **Importera spel** i WordPress adminpanelen.
2. Ladda upp din CSV-fil och klicka på **Importera**.
3. Efter importen får du en sammanfattning av hur många spel som importerades framgångsrikt och om några rader misslyckades.

## Kortkoder

CM Gamerecipe innehåller kortkoder som du kan använda för att generera länkar till spelplaner eller regelverk.

### Exempel på kortkod:

```bash
[cm_gamerecipe_pdf_download]
```

Denna kortkod genererar en nedladdningslänk till spelplanen eller regelverket (om en PDF-fil är uppladdad för spelet).

## Bidra

Om du vill bidra till pluginet, vänligen öppna en pull request eller skapa ett issue på GitHub. Alla bidrag är välkomna!

## Framtida förbättringar

Vi arbetar på att inkludera fler funktioner, inklusive:

- Bättre hantering av kategorier och taggar för spel.
- Fler anpassningsmöjligheter för spel och regler.
- Integration med externa API:er för spelidéer och rekommendationer.

## Support

Om du stöter på problem med pluginet, skapa ett issue på GitHub-sidan: [Support Issues](https://github.com/DiamondStrand/cm-gamerecipe/issues).

---

**Licens**: GNU General Public License v2 eller senare. Se [http://www.gnu.org/licenses/gpl-2.0.html](http://www.gnu.org/licenses/gpl-2.0.html) för mer information.
