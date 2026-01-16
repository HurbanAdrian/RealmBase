# RealmBase - Portál pre správu článkov

Semestrálna práca z predmetu VAII (2025/26). Aplikácia slúži na publikovanie článkov, ich kategorizáciu a diskusiu prostredníctvom komentárov. Projekt implementuje architektúru MVC a moderné webové technológie.

## 🚀 Rýchle nasadenie (Docker)

Aplikácia je plne dockerizovaná, čo umožňuje jej okamžité spustenie bez nutnosti manuálnej inštalácie PHP alebo MySQL.

1. Uistite sa, že máte nainštalovaný **Docker Desktop**.
2. V koreňovom priečinku projektu spustite príkaz:
   ```bash
   docker-compose up -d

    Aplikácia bude dostupná na adrese: http://localhost

    Databázový nástroj (Adminer) je dostupný na: http://localhost:8080 (Server: db)

Poznámka: Databáza sa automaticky inicializuje zo súborov v priečinku ./sql pri prvom štarte.
🔑 Testovacie údaje

Pre potreby obhajoby a testovanie autorizácie sú v systéme predvytvorené nasledujúce účty (heslá sú bezpečne hashované):
Rola	Login (Username)	Heslo	E-mail
Administrátor	adminMe	admin123	admin@example.com
Používateľ	userMe	user123	user@example.com
✨ Implementované kľúčové funkcie

    Správa obsahu (CRUD): Kompletné vytváranie, čítanie, úprava a mazanie článkov.

    Kategorizácia: Články sú delené do tematických okruhov: Novinky, Bugy a Fixy, Tipy a Triky, Aktualizácie.

    Dynamické zoradenie: Možnosť triediť články podľa dátumu vytvorenia alebo názvu (vzostupne aj zostupne) so zachovaním filtra kategórie.

    Komentáre (AJAX): Asynchrónne pridávanie a mazanie komentárov bez potreby obnovenia stránky.

    Práca so súbormi: Podpora pre nahrávanie titulných obrázkov (upload) k článkom s automatickým premazávaním starých súborov pri editácii/zmazaní.

    Bezpečnosť: Ošetrenie vstupov (XSS ochrana), ochrana proti SQL Injection (Prepared Statements) a autorizácia na úrovni rolí (Admin vs. User).

    Responzívny dizajn: Vlastný Dark Mode (RealmBase) prispôsobený pre mobilné zariadenia a desktopy.

🛠 Použité technológie

    Backend: PHP 8.2 (MVC Framework Vaííčko)

    Frontend: Vanilla JavaScript (AJAX, validácie), Bootstrap 5, vlastné CSS

    Databáza: MariaDB (MySQL)

    Infraštruktúra: Docker & Docker Compose

📁 Štruktúra databázy

Aplikácia využíva 5 hlavných entít:

    users (správa používateľov a rolí)

    categories (správa tematických okruhov)

    posts (samotné články s prepojením na autora a kategóriu)

    comments (diskusia k článkom)

    logs (záznamy o systémových akciách)

Vytvorené v rámci predmetu Vývoj aplikácií pre internet a intranet.


---

### Čo si teraz skontrolovať?
1. **Súbor `database.sql` (v priečinku `./sql`):** Uisti sa, že obsahuje tvoje `CREATE TABLE` príkazy a tie `INSERT` príkazy s `adminMe` a `userMe`.
2. **Docker premazanie:** Ak si už Docker spúšťal predtým, nezabudni ho "reštartovať načisto", aby sa načítali tieto nové dáta:
   ```bash
   docker-compose down -v
   docker-compose up -d