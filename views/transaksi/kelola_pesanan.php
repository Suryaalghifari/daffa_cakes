<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kasir') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Transaksi Penjualan</h1>

    <div class="row">
        <!-- List Produk -->
        <div class="col-md-9">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                                <td>Rp <?= number_format($p['harga']) ?></td>
                                <td><?= $p['stok'] ?></td>
                                <td>
                                    <img src="/daffa_cakes/assets/img/produk/<?= $p['gambar'] ?>" alt="<?= $p['nama_produk'] ?>" width="50" height="50" style="object-fit:cover">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick='tambahKeKeranjang(<?= json_encode($p) ?>)'>Tambah</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Keranjang -->
        <div class="col-md-3">
            <form id="formTransaksi" method="POST">
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <strong>Keranjang</strong>
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm" id="keranjang">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" name="data_keranjang" id="data_keranjang">
                        <button type="button" class="btn btn-danger btn-sm btn-block" onclick="konfirmasiKosongkan()">Batalkan</button>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <h5>Total: <span id="total">Rp 0</span></h5>
                        <input type="hidden" id="total_hidden" name="total_harga">

                        <div class="form-group">
                            <label>Metode Bayar</label>
                            <select name="metode" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer">Transfer</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Dibayar</label>
                            <input type="text" name="jumlah_dibayar" id="jumlah_dibayar" class="form-control" required autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" id="kembalian" class="form-control" readonly>
                        </div>

                        <button class="btn btn-success btn-block" type="submit">Simpan Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let keranjang = [];

    function parseNumber(str) {
        return parseInt(str.replace(/[^\d]/g, '')) || 0;
    }

    function tambahKeKeranjang(produk) {
        produk.produk_id = parseInt(produk.produk_id); // pastikan ID jadi number
        let index = keranjang.findIndex(p => p.produk_id === produk.produk_id);
        if (index !== -1) {
            if (keranjang[index].jumlah < produk.stok) {
                keranjang[index].jumlah += 1;
            } else {
                Swal.fire("Stok Habis", "Stok produk tidak mencukupi.", "warning");
            }
        } else {
            if (produk.stok > 0) {
                keranjang.push({ ...produk, jumlah: 1 });
            } else {
                Swal.fire("Stok Habis", "Stok produk tidak mencukupi.", "warning");
            }
        }
        renderKeranjang();
    }

    function ubahJumlahManual(id, jumlahBaru) {
        let index = keranjang.findIndex(p => p.produk_id === id);
        if (index !== -1) {
            // Pastikan jumlah yang dimasukkan tidak lebih dari stok
            jumlahBaru = parseInt(jumlahBaru);
            if (jumlahBaru > 0 && jumlahBaru <= keranjang[index].stok) {
                keranjang[index].jumlah = jumlahBaru;
            } else {
                Swal.fire("Stok Tidak Cukup", "Jumlah yang dimasukkan melebihi stok produk.", "warning");
            }
            renderKeranjang();
        }
    }

    function hapusDariKeranjang(id) {
        keranjang = keranjang.filter(p => p.produk_id !== id);
        renderKeranjang();
    }

    function kosongkanKeranjang() {
        keranjang = [];
        renderKeranjang();
    }

    function konfirmasiKosongkan() {
        Swal.fire({
            title: 'Batalkan Keranjang?',
            text: "Semua item akan dihapus dari keranjang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                kosongkanKeranjang();
            }
        });
    }

    function renderKeranjang() {
        let tbody = document.querySelector("#keranjang tbody");
        let total = 0;
        tbody.innerHTML = "";
        keranjang.forEach(p => {
            total += p.harga * p.jumlah;
            tbody.innerHTML += `
                <tr>
                    <td>${p.nama_produk}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="${p.jumlah}" min="1" max="${p.stok}" onchange="ubahJumlahManual(${p.produk_id}, this.value)">
                    </td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusDariKeranjang(${p.produk_id})">x</button></td>
                </tr>`;
        });
        document.getElementById("total").innerText = "Rp " + total.toLocaleString();
        document.getElementById("total_hidden").value = total;
        document.getElementById("data_keranjang").value = JSON.stringify(keranjang);
    }

    document.getElementById("formTransaksi").addEventListener("submit", function(e) {
        e.preventDefault();
        const bayarRaw = document.getElementById("jumlah_dibayar").value;
        const bayar = parseNumber(bayarRaw);
        const total = parseNumber(document.getElementById("total").innerText);

        if (keranjang.length === 0) {
            return Swal.fire("Gagal", "Keranjang masih kosong!", "warning");
        }

        if (!this.metode.value || bayar <= 0) {
            return Swal.fire("Gagal", "Lengkapi data pembayaran.", "warning");
        }

        if (bayar < total) {
            return Swal.fire("Gagal", "Jumlah dibayar kurang dari total.", "error");
        }

        fetch("proses_tambah_pesanan.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                keranjang,
                metode: this.metode.value,
                jumlah_dibayar: bayar
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire("Berhasil", data.message, "success").then(() => {
                    window.location.href = "riwayat_penjualan.php";
                });
            } else {
                Swal.fire("Gagal", data.message, "error");
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "Terjadi kesalahan pada server.", "error");
        });
    });

    document.getElementById('jumlah_dibayar').addEventListener('input', function () {
        const bayar = parseNumber(this.value);
        const total = parseNumber(document.getElementById('total').innerText);
        const kembali = bayar - total;
        this.value = bayar.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        document.getElementById('kembalian').value = kembali >= 0 ? "Rp." + kembali.toLocaleString('id-ID') : '-';
    });
</script>

<?php include_once '../layouts/footer.php'; ?>
