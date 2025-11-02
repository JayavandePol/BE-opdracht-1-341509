# Backend Opdracht 1 – Jamin Magazijn

Een Laravel 10 applicatie voor het beheren van het Jamin-magazijn. Medewerkers kunnen het productoverzicht raadplegen, leverantiegegevens bekijken en allergenen beheren. Klanten kunnen zonder in te loggen allergeneninformatie per product opzoeken.

## Inhoud

- [Belangrijkste functionaliteit](#belangrijkste-functionaliteit)
- [Benodigdheden](#benodigdheden)
- [Installatie en setup](#installatie-en-setup)
- [Database en stored procedures](#database-en-stored-procedures)
- [Seeds en testdata](#seeds-en-testdata)
- [Scripts](#scripts)
- [Tests uitvoeren](#tests-uitvoeren)
- [Authenticatie](#authenticatie)

## Belangrijkste functionaliteit

- **Productoverzicht** – alleen zichtbaar na login. Toont voorraad, leveringsinformatie en links naar detailpagina's.
- **Leverantiepagina** – detailoverzicht van leveringen inclusief fallbackbericht voor ontbrekende voorraad.
- **Allergeneninformatie** – publiek toegankelijk. Toont allergenen per product of een fallback wanneer er geen allergenen zijn.
- **Allergenenbeheer** – medewerkers kunnen allergenen toevoegen, wijzigen en verwijderen via opgeslagen procedures.
- **Consistente navigatie** – gedeelde layout met responsieve navigatiebalk en footer.

## Benodigdheden

- PHP 8.2+
- Composer 2+
- Node.js 20+ en npm 10+
- MySQL 8+
- Een werkende `.env` met databaseverbinding en applicatiesleutel (`php artisan key:generate`).

## Installatie en setup

1. Installeer PHP-dependencies:
   ```bash
   composer install
   ```
2. Installeer frontend-dependencies:
   ```bash
   npm install
   ```
3. Configureer `.env` op basis van `.env.example` en vul databasegegevens in.
4. Genereer een applicatiesleutel (indien nog niet aanwezig):
   ```bash
   php artisan key:generate
   ```
5. Voer migraties en seeds uit (zie ook volgende paragraaf):
   ```bash
   php artisan migrate:fresh --seed
   ```
6. Bouw de frontend-assets (vereist voor tests en productie):
   ```bash
   npm run build
   ```
7. Start de ontwikkelserver:
   ```bash
   php artisan serve
   ```

## Database en stored procedures

De applicatie maakt gebruik van MySQL stored procedures. Deze worden automatisch aangemaakt via de migratie `database/migrations/2025_11_02_000003_create_allergen_stored_procedures.php`. Een aparte map `database/createscripts/` bevat referentie-SQL-scripts die overeenkomen met de stored procedures.

Belangrijke procedures:

- `sp_GetAllProducts`
- `sp_GetLeverantieInfo`
- `sp_GetAllergenById`
- `sp_CreateAllergeen`
- `sp_UpdateAllergeen`
- `sp_DeleteAllergeen`

Zorg dat de databaseuser de rechten heeft om procedures aan te maken en uit te voeren.

## Seeds en testdata

`DatabaseSeeder` roept `JaminSeeder` aan om basisdata voor producten, leveringen en allergenen in te vullen. Daarnaast wordt een standaardgebruiker aangemaakt:

- **E-mail**: `test@example.com`
- **Wachtwoord**: `password`

Gebruik deze gebruiker om in te loggen en de beveiligde onderdelen te bekijken.

## Scripts

| Doel                 | Command                                    |
|----------------------|---------------------------------------------|
| Ontwikkelserver      | `php artisan serve`
| Vite dev server      | `npm run dev`
| Productiebouw        | `npm run build`
| Migrations + seeds   | `php artisan migrate:fresh --seed`
| Tests uitvoeren      | `php artisan test`

## Tests uitvoeren

1. Zorg dat de database opgefrist is en seeds draaien (`php artisan migrate:fresh --seed`).
2. Voer een build uit zodat `public/build/manifest.json` bestaat (`npm run build`).
3. Draai alle tests:
   ```bash
   php artisan test
   ```

## Authenticatie

- Routes voor `products` en `product/{id}/leverantieInfo` zijn met middleware `auth` beschermd.
- `product/{id}/allergenenInfo` en `allergeen.index` zijn publiek.
- Acties om allergenen te beheren (create, store, edit, update, delete) vereisen login.

Bij vragen of problemen kun je het beste beginnen met het raadplegen van de logbestanden in `storage/logs/` en de databaseprocedures controleren.
