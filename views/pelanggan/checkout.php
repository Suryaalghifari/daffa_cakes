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

<style>
html, body {
  height: 100%;
  display: flex;
  flex-direction: column;
}
main {
  flex: 1;
}
</style>

<main class="main">
  <section class="section py-5">
    <div class="container">
      <div class="row">
        <!-- Kolom Produk -->
        <div class="col-md-8 mb-4">
          <div class="bg-white p-4 shadow rounded">
            <h4 class="mb-4">Pilih Produk</h4>
            <div class="row">
              <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
                <div class="col-md-4 mb-3">
                  <div class="card produk-card" onclick='tambahKeKeranjang(<?= json_encode($p) ?>)'>
                    <img src="<?= BASE_URL ?>assets/img/produk/<?= $p['gambar'] ?>" class="card-img-top" style="height: 140px; object-fit: cover;">
                    <div class="card-body text-center">
                      <h6><?= $p['nama_produk'] ?></h6>
                      <p class="text-muted">Rp <?= number_format($p['harga']) ?></p>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
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
                  <option value="COD">COD</option>
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

<script>
let keranjang = [];
let total_harga = 0;

function tambahKeKeranjang(p) {
  const idx = keranjang.findIndex(k => k.produk_id === p.produk_id);
  if (idx !== -1) keranjang[idx].jumlah++;
  else keranjang.push({ ...p, jumlah: 1 });
  renderKeranjang();
}

function konfirmasiHapus(id) {
  Swal.fire({
    title: 'Hapus Produk?',
    text: 'Apakah kamu yakin ingin menghapus produk ini dari keranjang?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      hapusDariKeranjang(id);
    }
  });
}

function hapusDariKeranjang(id) {
  keranjang = keranjang.filter(p => p.produk_id !== id);
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
      <tr>
        <td>${p.nama_produk}</td>
        <td>${p.jumlah}</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus(${p.produk_id})">x</button></td>
      </tr>`;
  });
  total_harga = total;
  document.getElementById('total').innerText = "Rp " + total.toLocaleString();
  document.getElementById('inputKeranjang').value = JSON.stringify(keranjang);
  document.getElementById('inputTotal').value = total;
}

document.getElementById('metodeBayar').addEventListener('change', function () {
  const div = document.getElementById('buktiDiv');
  const fileInput = document.getElementById('inputBukti');
  const show = this.value === 'Transfer' || this.value === 'QRIS';
  div.style.display = show ? 'block' : 'none';
  if (!show) fileInput.value = '';
});

document.getElementById("formCheckout").addEventListener("submit", function(e) {
  e.preventDefault();
  if (keranjang.length === 0) {
    return Swal.fire("Gagal", "Keranjang masih kosong!", "error");
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
</script>
