# Aquafin — Materiaal & Bestel Webapp

Een minimalistische PHP-webapp voor het beheren van voorraad en bestellingen van Aquafin-techniekers.

📋 **Inhoudsopgave**
1. [Projectomschrijving](#1-projectomschrijving)
2. [Belangrijkste functionaliteiten](#2-belangrijkste-functionaliteiten)
3. [Technologie-stack](#3-technologie-stack)
4. [Systeemvereisten](#4-systeemvereisten)
5. [Installatie & setup (XAMPP)](#5-installatie--setup-xampp)
6. [Database-initialisatie](#6-database-initialisatie)
7. [Standaardinloggegevens](#7-standaardinloggegevens)
8. [Projectstructuur](#8-projectstructuur)
9. [Gebruik](#9-gebruik)
10. [Bijdragen](#12-bijdragen)
11. [Licentie](#13-licentie)

---

## 1. Projectomschrijving

Deze webapp vereenvoudigt het materiaal- en bestelproces voor Aquafin-techniekers.  
Techniekers kunnen snel voorraad raadplegen, gewenste aantallen in een winkelmand plaatsen en bestellingen bevestigen.  
Admins beheren het volledige materiaalbestand, inclusief aantallen en categorieën, en hebben toegang tot gegroepeerde bestelrapportages.

## 2. Belangrijkste functionaliteiten

- Inloggen per rol (technieker / admin)
- Voorraad raadplegen per categorie
- Materiaal toevoegen aan winkelmand
- Bestelling bevestigen (met voorraadupdate)
- Bestelgeschiedenis en wijzigingen (wijzigen/verwijderen)
- Admins: materialen beheren (CRUD) + gegroepeerde geschiedenis

## 3. Technologie-stack

- **Frontend**: HTML, CSS, JavaScript (vanilla)
- **Backend**: PHP 8+
- **Database**: MySQL
- **Tools**: XAMPP (Apache + MySQL), phpMyAdmin

## 4. Systeemvereisten

- XAMPP geïnstalleerd
- PHP 8 of hoger
- MySQL-server actief
- Webbrowser (Chrome/Firefox)

## 5. Installatie & setup (XAMPP)

### Stap 1: Start XAMPP
- Start Apache en MySQL via het XAMPP-controlpanel.

### Stap 2: Projectbestanden
- Clone of download dit project naar de map:  
  `C:/xampp/htdocs/aquafin_app`

### Stap 3: Open in browser  
Ga naar `http://localhost/aquafin_app` in je browser.

## 6. Database-initialisatie

1. Open `phpMyAdmin` via `http://localhost/phpmyadmin`
2. Importeer `db.sql` via het tabblad *Importeren*
3. (Optioneel) Run `seed.php` voor voorbeelddata

## 7. Standaardinloggegevens

| Rol        | Gebruikersnaam | Wachtwoord |
|------------|----------------|------------|
| Admin      | `admin`        | `admin123` |
| Technieker | `technician`   | `tech123`  |

## 8. Projectstructuur

```
aquafin_app/
│
├── app.php               # Case 2 : Weersoverzicht app (appart van case 3)
├── materials.php         # Voorraadoverzicht
├── login.php             # Loginpagina
├── logout.php            # Uitloggen
├── dashboard.php         # Hoofdmenu
├── admin.php             # Beheer materiaal (alleen voor admin)
├── history.php           # Bestelgeschiedenis gebruiker
├── history_grouped.php   # Bestellingen gegroepeerd (admin)
├── confirm_order.php     # Verwerkt winkelmandje
├── seed.php              # Voegt testgegevens toe
├── db.sql                # Database structuur
└── style.css             # CSS-styling
```

## 9. Gebruik

- Login als admin of technieker.
- Navigeer via het hoofdmenu.
- Techniekers kunnen voorraad raadplegen, toevoegen aan winkelmand, bevestigen.
- Admins kunnen alles beheren en zien extra opties.

## 10. Bijdragen

Pull requests zijn welkom! Open gerust een issue met suggesties.

## 11. Licentie

MIT © 2025 Aquafin Webapp Team