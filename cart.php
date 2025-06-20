<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8"><title>Winkelmand</title>
<link rel="stylesheet" href="style.css">
<style>
table{width:100%;background:#fff;border-collapse:collapse;}
th,td{padding:8px;border:1px solid #ddd;text-align:left;}
button{padding:8px 12px;background:#007BFF;color:#fff;border:none;border-radius:4px;}
</style>
</head>
<body>
<h1>Mijn winkelmand</h1>
<table id="mand"></table>
<button onclick="bestel()">Winkelmand bevestigen</button>
<script>
let cart = JSON.parse(sessionStorage.getItem('cart')||'{}');
function render(){
  const tbl=document.getElementById('mand');
  tbl.innerHTML='<tr><th>Materiaal</th><th>Aantal</th><th></th></tr>';
  for(const id in cart){
    tbl.innerHTML += `<tr><td data-id="${id}"></td><td>${cart[id]}</td>
      <td><button onclick="del(${id})">🗑️</button></td></tr>`;
  }
  if(Object.keys(cart).length===0) tbl.innerHTML+='<tr><td colspan="3">Leeg</td></tr>';
  // haal namen via fetch ( éénmalig )
  fetch('materials_names.php?ids='+Object.keys(cart).join(','))
    .then(r=>r.json()).then(data=>{
      document.querySelectorAll('[data-id]').forEach(td=>{
        td.textContent = data[td.dataset.id] || 'onbekend';
      });
    });
}
function del(id){ delete cart[id]; sessionStorage.setItem('cart',JSON.stringify(cart)); render(); }
function bestel(){
  if(Object.keys(cart).length===0) return alert('Lege winkelmand');
  fetch('order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(cart)})
    .then(r=>r.text()).then(msg=>{
      alert(msg); sessionStorage.removeItem('cart'); location='materials.php';
    });
}
render();
</script>
</body>
</html>
