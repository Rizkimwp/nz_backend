<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Table Pesanan</h1>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Pemesan</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Barang Pesanan</th>
                        <th>Total Pesanan</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaksi): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaksi->customer_name) ?></td>
                        <td><?= htmlspecialchars($transaksi->phone) ?></td>
                        <td><?= htmlspecialchars($transaksi->email) ?></td>
                        <td><?= htmlspecialchars($transaksi->address) ?></td>
                        <td><?= $transaksi->product_name ?></td>
                        <td>Rp <?= number_format($transaksi->grand_total_amount, 0, ',', '.') ?></td>
                        <td>
                            <?php if ($transaksi->is_paid == 1): ?>
                            <span class="badge badge-success">Sudah Dibayar</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Belum Dibayar</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <!-- Tombol Edit -->
                            <?php
                            $no_hp = preg_replace('/[^0-9]/', '', $transaksi->phone);
                            if (strpos($no_hp, '0') === 0) {
                            $no_hp = '62' . substr($no_hp, 1);
                            }

                            $nama = $transaksi->customer_name;
                            $produk = $transaksi->product_name;
                            $total = number_format($transaksi->grand_total_amount, 0, ',', '.');
                            $pesan = urlencode("Halo $nama,\nPesanan kamu sedang dalam pengiriman.\n\n📦 Produk:
                            $produk\n💰 Total: Rp $total\n📮 \nTerima kasih telah berbelanja!");

                            $wa_link = "https://wa.me/{$no_hp}?text={$pesan}";
                            ?>


                            <a href="<?= $wa_link ?>" target="_blank" class="btn btn-sm btn-success">
                                <i class="fab fa-whatsapp"></i> Kirim via WhatsApp
                            </a>



                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>