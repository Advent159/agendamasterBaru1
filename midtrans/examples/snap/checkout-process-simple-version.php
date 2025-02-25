<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
// Set Your server key
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'SB-Mid-server-g3dp-OI7VgvBfGn-7G3xY9Ac';
Config::$clientKey = 'SB-Mid-client-LCd0xm_KRtTDJKnA';

// non-relevant function only used for demo/example purpose


// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

include "../../../koneksi/koneksi.php";
$order_id = $_GET['order_id'];

$query = "SELECT * FROM payment WHERE order_id='$order_id'";
$sql = mysqli_query($conn, $query);
$data = mysqli_fetch_array($sql);

$nama = $data['username'];
$email = $data['email'];
$biaya = $data['price'];

$transaction_details = [
    'order_id' => $order_id,
    'gross_amount' => $biaya,
];

$jumlah_tiket = $data['jumlah_tiket'];


// Required
$item_details = [
    [
        'id' => 'a1',
        'price' => $biaya / $jumlah_tiket, // Harga per tiket
        'quantity' => $jumlah_tiket,
        'name' => "PEMBAYARAN SEMINAR"
    ],
];

$customer_details = [
    'first_name' => $nama,
    'email' => $email,
];

$transaction = [
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
];

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
} catch (\Exception $e) {
    echo $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-pay, .btn-cancel {
            width: 100%;
            font-size: 18px;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4 text-center" style="max-width: 400px; margin: auto;">
            <h2 class="mb-3"><i class="fas fa-money-bill-wave"></i> Konfirmasi Pembayaran</h2>
            <p>Silakan klik tombol di bawah untuk menyelesaikan pembayaran Anda.</p>
            <hr>
            <p><strong>Nama:</strong> <?php echo $nama; ?></p>
            <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($biaya, 0, ',', '.'); ?></p>
            <button id="pay-button" class="btn btn-primary btn-pay"><i class="fas fa-credit-card"></i> Bayar Sekarang</button>
            <button id="cancel-button" class="btn btn-danger btn-cancel mt-2"><i class="fas fa-times-circle"></i> Batalkan Pesanan</button>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function() {
            Swal.fire({
                title: "Konfirmasi Pembayaran",
                text: "Apakah Anda yakin ingin melanjutkan pembayaran?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, bayar!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    snap.pay('<?php echo $snap_token?>');
                }
            });
        };

        document.getElementById('cancel-button').onclick = function() {
            Swal.fire({
                title: "Batalkan Pesanan?",
                text: "Pesanan akan dihapus dan tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, batalkan!",
                cancelButtonText: "Kembali"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "hapus_pesanan.php",
                        type: "POST",
                        data: { order_id: "<?php echo $order_id; ?>" },
                        success: function(response) {
                            Swal.fire({
                                title: "Dibatalkan!",
                                text: "Pesanan Anda telah dibatalkan.",
                                icon: "success",
                                confirmButtonColor: "#28a745"
                            }).then(() => {
                                window.location.href = "../../../event_detail.php?id=<?php echo $data['event_id']; ?>";
                            });
                        }
                    });
                }
            });
        };
    </script>
</body>
</html>
