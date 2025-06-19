<?php
require_once('config.php');

function seedUsers($pdo) {
    $password1 = password_hash('admin123', PASSWORD_DEFAULT);
    $password2 = password_hash('tech123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $password1, 'admin']);
    $stmt->execute(['technician', $password2, 'technician']);
}

function seedMaterials($pdo) {
    $materials = [
        ['Bouten M6', 100],
        ['Moeren Zeskant', 150],
        ['Veiligheidshelm', 50],
        ['Werkhandschoenen', 200],
        ['Schroevendraaiers', 75]
    ];

    $stmt = $pdo->prepare("INSERT INTO materials (name, quantity) VALUES (?, ?)");

    foreach ($materials as $mat) {
        $stmt->execute([$mat[0], $mat[1]]);
    }
}

seedUsers($pdo);
seedMaterials($pdo);

echo "✅ Database seeded met succes!";
?>
