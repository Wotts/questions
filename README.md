# LearnBeat tech assignment

## Setup
```bash
git clone git@github.com:Wotts/questions.git &&
cd questions &&
composer setup &&
composer run dev
```

Bezoek: http://localhost:8000


## Keuzes:
- Voor het gemak authenticatie uitgeschakeld in `bootstrap/app.php` voor het `/course` endpoint.
- Veel moderne sites gebruiken complexe UI libraries voor het renderen van componenten, maar plain HTML doet het erg goed!
- TypeScript!
- Bewust wat meer tijd in gestoken omdat ik het leuk vond met Svelte aan de gang te gaan.
- In eerste instantie bij het scoren van de vragen overwogen het questions object aan te vullen met score en vervolgens weer te 'cleanen' van antwoorden, maar het opnieuw opbouwen van een vraag+score object vond ik netter.
- Ik zou hier misbruik kunnen maken van het feit dat alle vragen en antwoorden altijd(?) in dezelfde volgorde doorkomen, maar dat is foutgevoelig vandaar de `array_find`.

## Nog te doen
- Verwerk vragen/antwoorden in het ORM via Models.
- Laden van de course SSR'en.
- Uit te zoeken: Ook bij een geforceerde Error in de frontend werd deze niet opgevangen in de Svelte template.
- Uitzoeken hoe je in Svelte eventListeners koppelt zonder setTimeout. Ik zag al iets over onsubmit.
- Vragen/antwoorden uitsplitsen in componenten.
- ~~Ingevoerde antwoord meegeven met score.~~
- Code voorzien van comments.

## Notities/opmerkingen/vragen:
- Tof te zien dat Laravel een installatie biedt met de Svelte starterkit, dat werkte prettig!
- Zorg dat je PHP 8.4 lokaal hebt en niet 8.2, dat scheelt uitzoeken waarom de Laravel installer geen Svelte biedt als starter kit.
- Uiteraard zouden de REST requests op z'n minst een course- en student-ID moeten bevatten.
- Voorgenomen een mooie commit history op te bouwen, niet gelukt. Volgende keer beter!
- Weer wat geleerd: `array_filter` behoudt de array index; `array_find` is sowieso passender omdat we maar 1 vraag zoeken.
