<div class="modal fade" id="modalEditUser<?= $user->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalEditUserLabel<?= $user->id ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('user/update/' . $user->id) ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUserLabel<?= $user->id ?>">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" value="<?= $user->name ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= $user->email ?>" class="form-control" required>
                    </div>
                    <!-- Tambahkan field lain jika perlu -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>