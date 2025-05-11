<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: login.php");
    exit;
}

$pelanggan_id = $_SESSION['pelanggan_id'];
$data = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = $pelanggan_id");
$pelanggan = mysqli_fetch_assoc($data);
$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");

include '../halamanweb/templates/header.php';
?>

<main class="main">
  <section class="section py-5">
    <div class="container">
      <div class="row">
        <!-- Kolom Produk -->
        <div class="col-md-8 mb-4">
          <div class="bg-white p-4 shadow rounded">
            <h4 class="mb-4">Pilih Produk</h4>
            <div class="row" id="produk-list">
              <!-- Produk akan dimuat lewat JavaScript -->
            </div>
          </div>
        </div>

        <!-- Kolom Keranjang -->
        <div class="col-md-4">
          <div class="bg-white p-4 shadow rounded">
            <h4 class="mb-3">Keranjang</h4>
            <form id="formCheckout" method="POST" enctype="multipart/form-data">
              <table class="table table-sm table-bordered mb-3" id="tabel-keranjang">
                <thead>
                  <tr><th>Produk</th><th>Qty</th><th></th></tr>
                </thead>
                <tbody></tbody>
              </table>

              <div class="text-end mb-3">
                <strong>Total: <span id="total">Rp 0</span></strong>
              </div>

              <div class="mb-3">
                <label>Metode Bayar</label>
                <select name="metode" id="metodeBayar" class="form-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="Transfer">Transfer</option>
                  <option value="QRIS">QRIS</option>
                </select>
              </div>

              <div class="mb-3" id="buktiDiv" style="display: none;">
                <label>Upload Bukti Pembayaran</label>
                <input type="file" name="bukti" id="inputBukti" class="form-control" accept="image/*">
              </div>

              <div class="mb-3">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" class="form-control" required><?= htmlspecialchars($pelanggan['alamat'] ?? '') ?></textarea>
              </div>

              <input type="hidden" name="keranjang" id="inputKeranjang">
              <input type="hidden" name="total_harga" id="inputTotal">

              <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>views/halamanweb/index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Kirim Pesanan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../halamanweb/templates/footer.php'; ?>

<!-- Inject produk dari PHP -->
<script>
let dataProduk = <?php
$daftar = [];
while ($p = mysqli_fetch_assoc($produk)) {
    $daftar[] = [
        'produk_id' => (int)$p['produk_id'],
        'nama_produk' => $p['nama_produk'],
        'harga' => (int)$p['harga'],
        'stok' => (int)$p['stok'],
        'gambar' => $p['gambar'] ?? 'default.png'
    ];
}
echo json_encode($daftar);
?>;

let keranjang = [];
let total_harga = 0;

function renderProduk() {
  const el = document.getElementById('produk-list');
  el.innerHTML = '';
  dataProduk.forEach(p => {
    el.innerHTML += `
      <div class="col-md-4 mb-3">
        <div class="card produk-card" onclick='tambahKeKeranjang(${JSON.stringify(p)})' style="cursor:pointer;">
          <img src="/daffa_cakes/assets/img/produk/${p.gambar}" class="card-img-top" style="height: 140px; object-fit: cover;">
          <div class="card-body text-center">
            <h6 class="mb-1">${p.nama_produk}</h6>
            <p class="text-muted">Rp ${p.harga.toLocaleString()}</p>
          </div>
        </div>
      </div>`;
  });
}

function tambahKeKeranjang(p) {
  const idx = keranjang.findIndex(k => k.produk_id === p.produk_id);
  if (p.stok === 0) {
    Swal.fire("Stok Habis", "Produk tidak tersedia.", "warning");
    return;
  }
  if (idx !== -1) keranjang[idx].jumlah++;
  else keranjang.push({ ...p, jumlah: 1 });
  renderKeranjang();
}

function renderKeranjang() {
  const tbody = document.querySelector("#tabel-keranjang tbody");
  let total = 0;
  tbody.innerHTML = '';
  keranjang.forEach(p => {
    const sub = p.harga * p.jumlah;
    total += sub;
    tbody.innerHTML += `
      <tr data-id="${p.produk_id}">
        <td>${p.nama_produk}</td>
        <td><input type="number" class="form-control form-control-sm input-qty" value="${p.jumlah}" min="0" max="${p.stok}"></td>
        <td><button type="button" class="btn btn-sm btn-danger btn-hapus">x</button></td>
      </tr>`;
  });
  total_harga = total;
  document.getElementById('total').innerText = "Rp " + total.toLocaleString();
  document.getElementById('inputKeranjang').value = JSON.stringify(keranjang);
  document.getElementById('inputTotal').value = total;
}

document.addEventListener('DOMContentLoaded', () => {
  renderProduk();

  document.querySelector('#tabel-keranjang tbody').addEventListener('change', function (e) {
    if (e.target.classList.contains('input-qty')) {
      const tr = e.target.closest('tr');
      const id = parseInt(tr.getAttribute('data-id'));
      const qty = parseInt(e.target.value);
      const index = keranjang.findIndex(p => p.produk_id === id);
      if (qty <= 0) {
        keranjang = keranjang.filter(p => p.produk_id !== id);
      } else if (index !== -1) {
        keranjang[index].jumlah = qty;
      }
      renderKeranjang();
    }
  });

  document.querySelector('#tabel-keranjang tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-hapus')) {
      const id = parseInt(e.target.closest('tr').getAttribute('data-id'));
      Swal.fire({
        title: "Hapus Produk?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus"
      }).then(result => {
        if (result.isConfirmed) {
          keranjang = keranjang.filter(p => p.produk_id !== id);
          renderKeranjang();
        }
      });
    }
  });

  document.getElementById('metodeBayar').addEventListener('change', function () {
    const div = document.getElementById('buktiDiv');
    const fileInput = document.getElementById('inputBukti');
    const show = this.value === 'Transfer' || this.value === 'QRIS';
    div.style.display = show ? 'block' : 'none';
    if (!show) fileInput.value = '';
  });

  document.getElementById("formCheckout").addEventListener("submit", function (e) {
    e.preventDefault();
    if (keranjang.length === 0) {
      Swal.fire("Oops", "Keranjang masih kosong.", "error");
      return;
    }

    const metode = document.getElementById("metodeBayar").value;
    const bukti = document.getElementById("inputBukti");

    if ((metode === "Transfer" || metode === "QRIS") && bukti.files.length === 0) {
      return Swal.fire("Gagal", "Mohon unggah bukti pembayaran!", "error");
    }

    const formData = new FormData(this);
    formData.set("keranjang", JSON.stringify(keranjang));
    formData.set("total_harga", total_harga);

    fetch("proses_checkout.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        Swal.fire("Berhasil", data.message, "success").then(() => {
          window.location.href = "../halamanweb/index.php";
        });
      } else {
        Swal.fire("Gagal", data.message, "error");
      }
    })
    .catch(() => Swal.fire("Error", "Terjadi kesalahan server.", "error"));
  });
});
</script>
