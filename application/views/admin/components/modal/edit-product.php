<!-- Modal Edit Produk -->
<div class="modal fade" id="modalEditUser<?= $user->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditUserLabel<?= $user->id ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('product/update/' . $user->id) ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user->id ?>">
            <input type="hidden" name="old_thumbnail" value="<?= $user->thumbnail ?>">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUserLabel<?= $user->id ?>">Edit Produk: <?= $user->name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Nama -->
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="<?= $user->name ?>" required>
                    </div>

                    <!-- Thumbnail -->
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail </label>
                        <input type="file" name="thumbnail" class="form-control-file" accept="image/*">
                        <?php if (!empty($user->thumbnail)) : ?>
                        <div class="mt-2">
                            <img src="<?= base_url(  $user->thumbnail) ?>" alt="Thumbnail" style="max-width: 20%;">
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"
                            required><?= $user->description ?></textarea>
                    </div>

                    <!-- Harga -->
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" name="price" class="form-control" value="<?= $user->price ?>" min="0"
                            required>
                    </div>

                    <!-- Diskon -->
                    <div class="form-group">
                        <label for="discount">Diskon</label>
                        <input type="number" name="discount" class="form-control" value="<?= $user->discount ?>"
                            min="0">
                    </div>

                    <!-- Stok -->
                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" name="stock" class="form-control" value="<?= $user->stock ?>" min="0"
                            required>
                    </div>

                    <!-- Checkbox Populer -->
                    <div class="form-check">
                        <input type="checkbox" name="is_populer" class="form-check-input" id="isPopuler<?= $user->id ?>"
                            <?= $user->is_populer ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isPopuler<?= $user->id ?>">Produk Populer</label>
                    </div>

                    <!-- Checkbox Tampilkan -->
                    <div class="form-check">
                        <input type="checkbox" name="is_published" class="form-check-input"
                            id="isPublished<?= $user->id ?>" <?= $user->is_published ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isPublished<?= $user->id ?>">Tampilkan di Website</label>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Produk</button>
                </div>
            </div>
        </form>
    </div>
</div>