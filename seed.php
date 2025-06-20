<?php
require_once('config.php');

function seedUsers($pdo) {

    $password1 = password_hash('admin123', PASSWORD_DEFAULT);
    $password2 = password_hash('tech123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $password1, 'admin']);
    $stmt->execute(['technician', $password2, 'technician']);
}

function seedMaterials(PDO $pdo) {
        // 1. FK-checks uit
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

    // 2. orders leegmaken (of TRUNCATE)
    $pdo->exec('TRUNCATE TABLE orders');

    // 3. materials leegmaken
    $pdo->exec('TRUNCATE TABLE materials');

    // 4. FK-checks weer aan
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    $materials = [
        ['Bevestigingsmateriaal','Bouten M6',                  500],
        ['Bevestigingsmateriaal','Bouten M8',                  400],
        ['Bevestigingsmateriaal','Bouten M10',                 400],
        ['Bevestigingsmateriaal','Bouten M12',                 300],
        ['Bevestigingsmateriaal','Bouten M16',                 200],
        ['Bevestigingsmateriaal','Bouten inox A2/A4',          200],
        ['Bevestigingsmateriaal','Bouten verzinkt',            300],
        ['Bevestigingsmateriaal','Zeskantmoeren',              500],
        ['Bevestigingsmateriaal','Borgmoeren',                 400],
        ['Bevestigingsmateriaal','Flensmoeren',                300],
        ['Bevestigingsmateriaal','Sluitringen',                800],
        ['Bevestigingsmateriaal','Veerringen',                 600],
        ['Bevestigingsmateriaal','Tandringen',                 400],
        ['Bevestigingsmateriaal','Ankerbouten',                150],
        ['Bevestigingsmateriaal','Chemische ankers (Hilti HIT)',120],
        ['Bevestigingsmateriaal','Keilbouten',                 250],
        ['Bevestigingsmateriaal','Draadstangen M6-M16',        350],
        ['Bevestigingsmateriaal','Inslagmoeren',               400],
        ['Bevestigingsmateriaal','Tapbouten',                  300],
        ['Bevestigingsmateriaal','Zeskantkop-/inbusbouten',    450],
        ['Bevestigingsmateriaal','Torx-/kruiskopschroeven',    700],
        ['Bevestigingsmateriaal','Zelftappende vijzen',        600],
        ['Bevestigingsmateriaal','Parkervijzen',               500],
        ['Bevestigingsmateriaal','Spaanplaatschroeven',        650],
        ['Bevestigingsmateriaal','Slangenklemmen',             300],

        ['PBM','Veiligheidshelm',                              80],
        ['PBM','Oordoppen / gehoorkappen',                     400],
        ['PBM','Veiligheidsbril / gelaatsscherm',              120],
        ['PBM','Stofmaskers FFP2',                             300],
        ['PBM','Stofmaskers FFP3',                             200],
        ['PBM','Werkhandschoenen',                             250],
        ['PBM','Veiligheidsschoenen',                          60],
        ['PBM','Werklaarzen',                                  40],
        ['PBM','Regenkledij',                                  50],
        ['PBM','Fluovesten EN 20471',                          75],
        ['PBM','Overall brandvertragend',                      45],
        ['PBM','Valharnas + lijn',                             30],
        ['PBM','Gasdetectiemeter (multigas)',                  15],
        ['PBM','Handontsmetting / EHBO-kit',                   100],
        ['PBM','Klim- en valbeveiliging',                      25],

        ['Gereedschap','Dopsleutelsets',                       25],
        ['Gereedschap','Ringsleutels / steeksleutels',         40],
        ['Gereedschap','Momentsleutels',                       10],
        ['Gereedschap','Inbussleutels',                        35],
        ['Gereedschap','Schroevendraaiers',                    60],
        ['Gereedschap','Tangen (diverse)',                     70],
        ['Gereedschap','Krimptang',                            12],
        ['Gereedschap','Kabelstripper',                        15],
        ['Gereedschap','Hamer / moker',                        25],
        ['Gereedschap','Breekijzer',                           10],
        ['Gereedschap','Haakse slijper',                       8],
        ['Gereedschap','Accuboormachine',                      12],
        ['Gereedschap','Schroefmachine',                       12],
        ['Gereedschap','Slagmoersleutel accu',                 6],
        ['Gereedschap','Waterpas / laserwaterpas',             18],
        ['Gereedschap','Meetlint / rolmeter',                  40],
        ['Gereedschap','Multimeter',                           10],
        ['Gereedschap','Laskist + materiaal',                  5],

        ['Technische onderhoudsmaterialen','Smeervet EP2',     50],
        ['Technische onderhoudsmaterialen','O-ringen (assort.)',200],
        ['Technische onderhoudsmaterialen','Pakkingen EPDM',   150],
        ['Technische onderhoudsmaterialen','PTFE tape / Loctite',120],
        ['Technische onderhoudsmaterialen','PVC-slangen',      100],
        ['Technische onderhoudsmaterialen','PVC-fittingen',    180],
        ['Technische onderhoudsmaterialen','Koppelingen Camlock',80],
        ['Technische onderhoudsmaterialen','V-snaren / kettingen',40],
        ['Technische onderhoudsmaterialen','Kabels + wartels', 120],
        ['Technische onderhoudsmaterialen','Aansluitdozen',    60],
        ['Technische onderhoudsmaterialen','Leidingsystemen',  70],
        ['Technische onderhoudsmaterialen','Pneumatische koppelingen',50],
        ['Technische onderhoudsmaterialen','Trillingsdempers', 30],

        ['Specifieke tools','Putdekselhaak',                   20],
        ['Specifieke tools','Rioolcamera',                     5],
        ['Specifieke tools','Gasdetectietoestel',              10],
        ['Specifieke tools','Ontstoppingsveer',                8],
        ['Specifieke tools','Hogedrukreiniger',                4],
        ['Specifieke tools','Slangenwagen',                    6],
        ['Specifieke tools','Dompelpomp',                      6],
        ['Specifieke tools','Rioolstoppen',                    15],
        ['Specifieke tools','Vlotterschakelaars',              12],
        ['Specifieke tools','Niveaumeting radar',              4],
        ['Specifieke tools','Staalnamepotten',                 30],
        ['Specifieke tools','Monsternameapparatuur',           6],

        ['Diversen','Tie-wraps',                               1000],
        ['Diversen','Kabelschoenen',                           500],
        ['Diversen','Markeringstape',                          200],
        ['Diversen','Siliconenkit',                            120],
        ['Diversen','Reinigingsdoekjes',                       300],
        ['Diversen','Sprays (WD-40, …)',                       150],
        ['Diversen','Plakband (duct/iso)',                     250],
        ['Diversen','Batterijen / accu’s',                     300],
        ['Diversen','Reserveonderdelen (PLC, relais)',         40],
        ['Diversen','Flessen perslucht / gas',                 25],
    ];

    $stmt = $pdo->prepare(
        "INSERT INTO materials (category, name, quantity) VALUES (?,?,?)"
    );
    foreach ($materials as $m) { $stmt->execute($m); }
    }

// 🟢 Seed data uitvoeren
seedUsers($pdo);
seedMaterials($pdo);

echo "✅ Database succesvol gevuld!";
?>
