<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM materials ORDER BY category, name");

$cats = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cats[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Voorraad</title>
<style>
    body{
        font-family:sans-serif;
        background:#1e1e1e;
        color:#fff;
        margin:0;
        padding:20px;
        display:flex;
        flex-direction:column;
        align-items:center;
        min-height:100vh;
    }

    h1{color:#00aaff;text-align:center;margin-bottom:20px;}

    h2{
        cursor:pointer;
        background:#007BFF;
        color:#fff;
        padding:10px;
        border-radius:5px;
        margin:10px 0;
        width:850px;
        text-align:center;
        user-select:none;
    }

    .material{
        width:850px;
        margin:0 auto;
        background:#fff;
        color:#000;
        border-bottom:1px solid #eee;
        padding:6px 10px;
        display:flex;
        justify-content:space-between;
        align-items:center;
    }
    .material input{width:60px;}

    #winkelmand-btn{
        position:fixed;bottom:30px;right:30px;
        background:#28a745;color:#fff;border:none;
        padding:15px;border-radius:50%;font-size:20px;cursor:pointer;
        box-shadow:0 2px 6px rgba(0,0,0,.3);
    }

    #toast {
        position: fixed;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: #28a745;
        color: #fff;
        padding: 12px 18px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,.3);
        font-size: 14px;
        z-index: 9999;
        opacity: 0;
        transition: opacity .3s ease;
    }

    #cart-overlay {
        position: fixed;
        bottom: 100px;
        right: 30px;
        background: #fff;
        color: #000;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        display: none;
        z-index: 10000;
        max-width: 300px;
    }

    #cart-overlay h3 {
        margin-top: 0;
    }

    #cart-overlay ul {
        list-style: none;
        padding: 0;
        margin: 0 0 10px 0;
    }

    #cart-overlay button {
        margin-top: 10px;
    }

    #close-cart {
        position: absolute;
        top: 5px;
        right: 10px;
        cursor: pointer;
        font-weight: bold;
    }
</style>
</head>
<body>

<h1>Voorraad per categorie</h1>

<input type="text" id="searchInput" placeholder="🔍 Zoek materiaal..." onkeyup="filterMaterials()"
       style="width: 400px; padding: 10px; margin-bottom: 20px; font-size: 16px; border-radius: 5px; border: none;">


<?php foreach($cats as $cat => $items): ?>
    <h2 onclick="toggle('<?= md5($cat) ?>')">
        <?= htmlspecialchars($cat) ?>
    </h2>
    <div id="<?= md5($cat) ?>" style="display:none;">
        <?php foreach($items as $mat): ?>
            <div class="material">
                <span>
                    <?= htmlspecialchars($mat['name']) ?> (<?= $mat['quantity'] ?> beschikbaar)
                </span>
                <div>
                    <input type="number" id="qty<?= $mat['id'] ?>" value="1" min="1" max="<?= $mat['quantity'] ?>">
                    <?php
                        $id = $mat['id'];
                        $name = json_encode($mat['name'], JSON_HEX_APOS | JSON_HEX_QUOT); // veilig encoden
                        $qty = $mat['quantity'];
                    ?>
                    <button type="button" onclick='addCart(<?= $id ?>, <?= $name ?>, <?= $qty ?>)'>🛒</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<div id="cart-overlay">
    <span id="close-cart" onclick="toggleCart()">✖</span>
    <h3>Winkelmand</h3>
    <ul id="cart-items"></ul>
    <button onclick="submitOrder()">Bevestigen</button>
</div>

<div id="toast"></div>

<button id="winkelmand-btn" onclick="toggleCart()">🛒</button>

<p style="margin-top: 40px;">
  <a href="dashboard.php" style="color:#00aaff; text-decoration:none;">⬅ Terug naar hoofdmenu</a>
</p>

<script>
function toggle(id){
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function showToast(txt){
    let toast = document.getElementById('toast');
    toast.textContent = txt;
    toast.style.opacity = 1;
    setTimeout(() => { toast.style.opacity = 0; }, 5000);
}

function addCart(id, name, max){
    const qtyInput = document.getElementById('qty' + id);
    const qty      = parseInt(qtyInput.value, 10);

    if (qty < 1 || qty > max){
        alert('Ongeldige hoeveelheid'); return;
    }

    // haal huidige cart op
    let cart = JSON.parse(localStorage.getItem('cart') || '{}');

    // bewaar zowel naam als aantal
    if (!cart[id]) cart[id] = { name: name, qty: 0 };
    cart[id].qty += qty;

    localStorage.setItem('cart', JSON.stringify(cart));

    showToast(`${qty} × ${name} toegevoegd aan je winkelmand`);
}

function toggleCart(){
    const overlay = document.getElementById('cart-overlay');
    overlay.style.display = (overlay.style.display === 'block') ? 'none' : 'block';
    if (overlay.style.display === 'block') renderCart();       // telkens opnieuw invullen
}

function renderCart(){
    const list   = document.getElementById('cart-items');
    const cart   = JSON.parse(localStorage.getItem('cart') || '{}');

    list.innerHTML = '';
    let leeg = true;

    for (let id in cart){
        leeg = false;
        list.innerHTML += `<li>${cart[id].qty} × ${cart[id].name}</li>`;
    }
    if (leeg){
        list.innerHTML = '<li>Je winkelmand is leeg.</li>';
    }
}

function submitOrder(){
    const cart = JSON.parse(localStorage.getItem('cart') || '{}');
    if (Object.keys(cart).length === 0){
        alert('Je winkelmand is leeg.'); return;
    }

    fetch('submit_order.php', {
        method : 'POST',
        headers: {'Content-Type':'application/json'},
        body   : JSON.stringify(cart)
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok){
            alert(data.msg);
            localStorage.removeItem('cart');
            document.getElementById('cart-overlay').style.display = 'none';
            location.reload();              // vernieuw voorraad
        } else {
            alert('Fout: ' + data.error);
        }
    })
    .catch(() => alert('Netwerkfout, probeer opnieuw.'));
}

function filterMaterials() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const blocks = document.querySelectorAll('.material');

    blocks.forEach(block => {
        const text = block.querySelector('span').textContent.toLowerCase();
        block.style.display = text.includes(input) ? 'flex' : 'none';
    });
}


</script>

</body>
</html>