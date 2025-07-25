<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Table Product</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
        data-target="#modalTambahProduk">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Product
    </a>

</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Product</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Product</th>
                        <th>Gambar</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Stok</th>
                        <th>Tanggal Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product->name) ?></td>
                        <td>
                            <?php if (!empty($product->thumbnail)): ?>
                            <img src="<?= base_url($product->thumbnail) ?>" alt="Thumbnail"
                                style="max-width: 100px; height: auto;">
                            <?php else: ?>
                            <span>Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($product->price) ?></td>
                        <td><?= htmlspecialchars($product->discount) ?></td>
                        <td><?= htmlspecialchars($product->stock) ?></td>
                        <td><?= date('d M Y', strtotime($product->created_at)) ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                data-target="#modalEditUser<?= $product->id ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <?php $this->load->view('admin/components/modal/edit-product', ['user' => $product]); ?>

                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#modalDeleteProduct<?= $product->id ?>">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <?php $this->load->view('admin/components/modal/delete-product', ['user' => $product]); ?>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('admin/components/modal/add-product'); ?>