
<?php
require_once __DIR__ . '/classes/Tenda.php';
require_once __DIR__ . '/classes/SleepingBag.php';
require_once __DIR__ . '/classes/Pelanggan.php';
require_once __DIR__ . '/classes/Penyewaan.php';

session_start();

if (!isset($_SESSION['alat'])) {
    $_SESSION['alat'] = [
        new Tenda("A01", "Tenda Dome", 50000, 5, 4),
        new Tenda("A02", "Tenda Family", 85000, 3, 6),
        new SleepingBag("A03", "Sleeping Bag Polar", 20000, 10, "Polar")
    ];
}

$pesan = "";
$struk = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $pilihan = (int)($_POST['alat'] ?? -1);
    $hari = (int)($_POST['hari'] ?? 0);
    $jumlah = (int)($_POST['jumlah'] ?? 0);

    if ($nama === '' || $telepon === '' ||
        !isset($_SESSION['alat'][$pilihan]) ||
        $hari <= 0 || $jumlah <= 0) {
        $pesan = "Mohon isi semua data dengan benar.";
    } else {
        $alat = $_SESSION['alat'][$pilihan];

        if (!$alat->kurangiStok($jumlah)) {
            $pesan = "Maaf, stok alat tidak mencukupi.";
        } else {
            $pelanggan = new Pelanggan($nama, $telepon);

            $struk = [
                'nama' => $nama,
                'telepon' => $telepon,
                'alat' => $alat->getNama(),
                'jumlah' => $jumlah,
                'hari' => $hari,
                'subtotal' => $alat->getHargaPerHari() * $hari * $jumlah,
                'total' => Penyewaan::hitungTotal(
                    $alat->getHargaPerHari(), $hari, $jumlah
                )
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Penyewaan Alat Camping</title>

<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f1;
    color: #26352b;
}

header {
    background: #214b35;
    color: white;
    padding: 30px 8%;
}

header h1 { margin: 0; }
header p { color: #d5e8d8; }

.container {
    max-width: 1050px;
    margin: 35px auto;
    padding: 0 20px;
}

h2 { color: #214b35; }

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}

.card, form, .receipt, .notice {
    background: white;
    padding: 22px;
    border-radius: 12px;
    box-shadow: 0 3px 12px #00000010;
}

.price {
    color: #28764b;
    font-weight: bold;
    font-size: 19px;
}

form { margin-top: 30px; }

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-top: 7px;
    border: 1px solid #ccd5cc;
    border-radius: 7px;
}

button {
    margin-top: 22px;
    background: #28764b;
    color: white;
    border: none;
    padding: 13px 22px;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.notice, .receipt { margin-top: 20px; }

footer {
    text-align: center;
    padding: 25px;
    color: #718075;
}
</style>
</head>

<body>

<header>
    <h1>🏕️ Camping Rental</h1>
    <p>Sistem Penyewaan Alat Camping</p>
</header>

<div class="container">

<h2>Daftar Alat Camping</h2>

<div class="grid">
<?php foreach ($_SESSION['alat'] as $alat): ?>
    <div class="card">
        <h3><?= htmlspecialchars($alat->getNama()) ?></h3>
        <p><?= htmlspecialchars($alat->getJenis()) ?></p>
        <p class="price">
            Rp<?= number_format($alat->getHargaPerHari(), 0, ',', '.') ?> / hari
        </p>
        <p>Stok tersedia: <?= $alat->getStok() ?></p>
    </div>
<?php endforeach; ?>
</div>

<form method="POST">
    <h2>Form Penyewaan</h2>

    <label>Nama Pelanggan</label>
    <input type="text" name="nama" required>

    <label>Nomor Telepon</label>
    <input type="text" name="telepon" required>

    <label>Pilih Alat Camping</label>
    <select name="alat" required>
        <?php foreach ($_SESSION['alat'] as $i => $alat): ?>
            <option value="<?= $i ?>">
                <?= htmlspecialchars($alat->getNama()) ?>
                - Rp<?= number_format($alat->getHargaPerHari(), 0, ',', '.') ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Lama Sewa (Hari)</label>
    <input type="number" name="hari" min="1" required>

    <label>Jumlah Unit</label>
    <input type="number" name="jumlah" min="1" required>

    <button type="submit">Sewa Sekarang</button>
</form>

<?php if ($pesan): ?>
    <div class="notice">
        <?= htmlspecialchars($pesan) ?>
    </div>
<?php endif; ?>

<?php if ($struk): ?>
    <div class="receipt">
        <h2>Transaksi Berhasil!</h2>
        <p>Nama: <?= htmlspecialchars($struk['nama']) ?></p>
        <p>Telepon: <?= htmlspecialchars($struk['telepon']) ?></p>
        <p>Alat: <?= htmlspecialchars($struk['alat']) ?></p>
        <p>Jumlah: <?= $struk['jumlah'] ?> unit</p>
        <p>Lama sewa: <?= $struk['hari'] ?> hari</p>
        <p>Subtotal: Rp<?= number_format($struk['subtotal'], 0, ',', '.') ?></p>
        <p>Biaya admin: Rp5.000</p>
        <h3>Total: Rp<?= number_format($struk['total'], 0, ',', '.') ?></h3>
    </div>
<?php endif; ?>

</div>

<footer>
    Sistem Penyewaan Alat Camping | PHP OOP
</footer>

</body>
</html>
