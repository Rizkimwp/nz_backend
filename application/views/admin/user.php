<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Table User</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
        data-target="#modalTambahUser">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah User
    </a>

</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Terdaftar Sejak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->name) ?></td>
                        <td><?= htmlspecialchars($user->email) ?></td>
                        <td><?= date('d M Y', strtotime($user->created_at)) ?></td>
                        <td>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                data-target="#modalEditUser<?= $user->id ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <?php $this->load->view('admin/components/modal/edit-user', ['user' => $user]); ?>

                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#modalDeleteUser<?= $user->id ?>">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <?php $this->load->view('admin/components/modal/delete-user', ['user' => $user]); ?>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('admin/components/modal/add-user'); ?>