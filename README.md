# CM Gamerecipe

**Version**: 1.0.15  
**Författare**: Diamond Strand - CookifyMedia  
**Plugin URI**: [https://github.com/DiamondStrand/cm-gamerecipe](https://github.com/DiamondStrand/cm-gamerecipe)


> **Varning:** Detta plugin är för närvarande **inte kompatibelt med Divi 5**. Använd Divi 4 eller tidigare versioner för att säkerställa att shortcodes och funktioner fungerar som förväntat.


## Beskrivning

CM Gamerecipe är ett flexibelt och kraftfullt plugin för att skapa och hantera spelrecept. Med detta plugin kan du enkelt lägga till spel med detaljerade regler, antal deltagare, material, speltid och andra spelrelaterade data. Perfekt för att hantera spelkvällar eller andra sociala sammanhang där roliga och engagerande spel behövs.

### Funktioner:

- Anpassad posttyp (CPT) för att hantera spel.
- Lägga till regler, material, antal spelare och andra nödvändiga detaljer för varje spel.
- Ladda upp och länka till PDF-filer för spelplaner eller regelverk.
- Möjlighet att importera spel via CSV-filer.
- Skapa spel direkt från adminpanelen med anpassade fält och kategorier.

## Installation

1. Ladda ner och installera senaste versionen av [Git Updater](https://github.com/afragen/git-updater/releases).
2. Gå till **Plugins** > **Lägg till nytt** i din WordPress-administratör och aktivera Git Updater-pluginet.
3. Efter aktivering, gå till **Inställningar** > **Git Updater** > **Install Plugin**.
4. Under **Plugin URI**, skriv in: `https://github.com/DiamondStrand/cm-gamerecipe`.
5. Under **Repository Branch**, skriv in: `main`.
6. Se till att **GitHub** är valt under **Remote Repository Host**.
7. Klicka på knappen **Install Plugin**.

Nu är CM Gamerecipe installerat och redo för användning! Du kan lägga till spel, hantera befintliga spel eller importera flera spel samtidigt via importfunktionen i adminpanelen.

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

| **Shortcode**                     | **Beskrivning**                                      |
|------------------------------------|------------------------------------------------------|
| `[cm_gamerecipe_min_players]`      | Visar minsta antal spelare för spelet.               |
| `[cm_gamerecipe_max_players]`      | Visar maximala antal spelare för spelet.             |
| `[cm_gamerecipe_typical_duration]` | Visar den ungefärliga speltiden för spelet.          |
| `[cm_gamerecipe_materials]`        | Visar material som krävs för spelet.                 |
| `[cm_gamerecipe_tips]`             | Visar tips relaterade till spelet.                   |
| `[cm_gamerecipe_difficulty]`       | Visar svårighetsgraden för spelet.                   |
| `[cm_gamerecipe_game_type]`        | Visar typ av spel (t.ex. brädspel, kortspel, etc.).  |
| `[cm_gamerecipe_preparation]`      | Visar vilken typ av förberedelser spelet kräver.     |
| `[cm_gamerecipe_suitable_for]`     | Visar vilken målgrupp spelet passar för (vuxen, barn). |


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
