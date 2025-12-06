# 🌐 RealmBase — VAII Semester Project
Moderná MVC webová aplikácia vytvorená ako semestrálny projekt pre predmet  
**Vývoj intranetových a internetových aplikácií (VAII)** na  
[FRI UNIZA](https://www.fri.uniza.sk/).

RealmBase je komunitná platforma umožňujúca spravovať kategórie a príspevky (CRUD),  
s užívateľskými účtami, validáciou a vlastným responzívnym dizajnom.

---

## 📌 Obsah
- [✨ Funkcionalita](#-funkcionalita)
- [🧱 Použitý Framework](#-použitý-framework)
- [🐳 Docker Inštalácia](#-docker-inštalácia)
- [📂 Štruktúra projektu](#-štruktúra-projektu)
- [🔧 Technológie](#-technológie)
- [📘 Dokumentácia](#-dokumentácia)
- [👤 Autor](#-autor)

---

## ✨ Funkcionalita

### ✔ Kompletné CRUD operácie
- Správa **kategórií**
- Správa **príspevkov**
- Admin UI + formuláre + tabuľky + validácie

### ✔ Používateľský systém
- Prihlásenie / odhlásenie
- Autentifikácia (SessionAuthenticator)
- Ochrana administrácie

### ✔ Validácia vstupov
- **Client-side** validácia cez JavaScript
- **Server-side** validácia v controlleroch
- Zobrazovanie chýb vo view

### ✔ Netriviálny JavaScript
- Live search filter v tabuľkách
- Validácia formulárov
- Dynamické skrývanie/odkrývanie prvkov

### ✔ Responzívny dizajn
- Mobilné menu (hamburger)
- Prispôsobené karty + sekcie
- Optimalizované CSS pre malé displeje

### ✔ Vlastný moderný dizajn
- Dark mode
- Animácie
- Zaoblené komponenty
- Custom scrollbar

---

## 🧱 Použitý Framework

Projekt je postavený na školskom MVC frameworku **VAIICko**, ktorý slúži  
na výučbu architektúry MVC v predmete VAII.

➡️ Originál frameworku:  
https://github.com/thevajko/vaiicko

Framework obsahuje:
- MVC architektúru
- routing
- automatické načítanie controllerov a view
- modelový layer s PDO
- session manažment
- response/render systém

---

## 🐳 Docker Inštalácia

Projekt obsahuje pripravenú docker konfiguráciu v priečinku `docker/`.

### 💻 Spustenie projektu
```bash
docker compose up --build
```
Dostupné služby:
Služba	Adresa	Popis
Web aplikácia	http://localhost/
Apache + PHP 8.3
Adminer	http://localhost:8080/
Správa databázy
MariaDB	localhost:3306	Databázový server
Ďalšie informácie

Document root je public/

Xdebug beží na porte 9003

PDO je súčasťou PHP kontajnera

Prihlásenie do DB je v .env

🔧 Technológie
    PHP 8.3,
    Bootstrap 5,
    JavaScript (ES6),
    MariaDB,
    Docker,
    MVC architektúra

👤 Autor
Adrian Hurban
Semester Project – RealmBase
Predmet: VAII – Vývoj intranetových a internetových aplikácií
Fakulta riadenia a informatiky, UNIZA