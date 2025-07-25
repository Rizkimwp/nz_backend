<div class="modal fade" id="modalDeleteUser<?= $user->id ?>" tabindex="-1" role="dialog"
    aria-labelledby="modalDeleteUserLabel<?= $user->id ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('user/delete/' . $user->id) ?>" method="post">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalDeleteUserLabel<?= $user->id ?>">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus user <strong><?= $user->name ?></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>