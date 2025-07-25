<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1" role="dialog" aria-labelledby="modalTambahProdukLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?= base_url('product/store') ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahProdukLabel">Tambah Produk Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Form Input -->
                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control-file" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" name="price" class="form-control" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="discount">Diskon</label>
                        <input type="number" name="discount" class="form-control" min="0" value="0">
                    </div>

                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" name="stock" class="form-control" min="0" required>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_populer" class="form-check-input" id="isPopuler">
                        <label class="form-check-label" for="isPopuler">Produk Populer</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" checked>
                        <label class="form-check-label" for="isPublished">Tampilkan di Website</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('input[name="thumbnail"]').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const preview = document.createElement('img');
        preview.src = URL.createObjectURL(file);
        preview.style.maxWidth = '100%';
        preview.classList.add('mt-2');
        e.target.parentNode.appendChild(preview);
    }
});
</script>