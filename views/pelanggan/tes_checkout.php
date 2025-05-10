<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Test Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h4 class="mb-3">Tes Keranjang</h4>

<div class="row">
  <div class="col-md-8">
    <div class="row">
      <!-- Simulasi Produk -->
      <div class="col-md-4 mb-3">
        <div class="card produk-card" data-produk='{"produk_id":1,"nama_produk":"Kue Lapis","harga":10000}'>
          <div class="card-body text-center">
            <h6>Kue Lapis</h6>
            <p class="text-muted">Rp 10.000</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card produk-card" data-produk='{"produk_id":2,"nama_produk":"Kue Brownies","harga":15000}'>
          <div class="card-body text-center">
            <h6>Kue Brownies</h6>
            <p class="text-muted">Rp 15.000</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <h5>Keranjang</h5>
    <table class="table table-bordered" id="tabel-keranjang">
      <thead><tr><th>Produk</th><th>Qty</th><th>Aksi</th></tr></thead>
      <tbody></tbody>
    </table>
    <strong>Total: <span id="total">Rp 0</span></strong>
  </div>
</div>

<script>
let keranjang = [];
let total_harga = 0;

function tambahKeKeranjang(p) {
  const idx = keranjang.findIndex(k => k.produk_id === p.produk_id);
  if (idx !== -1) {
    keranjang[idx].jumlah++;
  } else {
    keranjang.push({ ...p, jumlah: 1 });
  }
  renderKeranjang();
}

function ubahJumlah(id, delta) {
  const idx = keranjang.findIndex(p => p.produk_id === id);
  if (idx !== -1) {
    keranjang[idx].jumlah += delta;
    if (keranjang[idx].jumlah <= 0) {
      keranjang.splice(idx, 1);
    }
    renderKeranjang();
  }
}

function renderKeranjang() {
  const tbody = document.querySelector("#tabel-keranjang tbody");
  let total = 0;
  tbody.innerHTML = '';
  keranjang.forEach(p => {
    const sub = p.harga * p.jumlah;
    total += sub;
    tbody.innerHTML += `
      <tr>
        <td>${p.nama_produk}</td>
        <td>${p.jumlah}</td>
        <td>
          <button type="button" class="btn btn-sm btn-outline-secondary aksi-btn" data-id="${p.produk_id}" data-delta="-1">-</button>
          <button type="button" class="btn btn-sm btn-outline-primary aksi-btn" data-id="${p.produk_id}" data-delta="1">+</button>
        </td>
      </tr>`;
  });
  document.getElementById('total').innerText = "Rp " + total.toLocaleString();
  total_harga = total;
}

// Event delegation untuk produk
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.produk-card').forEach(card => {
    card.addEventListener('click', function () {
      const p = JSON.parse(this.dataset.produk);
      tambahKeKeranjang(p);
    });
  });
});

// Event delegation untuk tombol + dan -
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('aksi-btn')) {
    const id = parseInt(e.target.dataset.id);
    const delta = parseInt(e.target.dataset.delta);
    ubahJumlah(id, delta);
  }
});
</script>

</body>
</html>
