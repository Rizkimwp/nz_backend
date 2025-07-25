<!-- Bootstrap Toast -->
<div class="toast p-3" style="position: fixed; top: 2%; right: 40%; z-index: 1084;" id="sessionToast" role="alert"
    aria-live="assertive" aria-atomic="true" data-delay="8000">
    <div class="toast-header text-white" id="toastHeader">
        <strong class="me-auto" id="toastTitle">Notifikasi</strong>
        <button type="button" class="close" data-dismiss="toast" aria-label="Close">&times;</button>
    </div>
    <div class="toast-body" id="toastMessage">
        Pesan akan muncul di sini.
    </div>
</div>

<style>
.toast-success {
    background-color: #28a745;
}

.toast-error {
    background-color: #dc3545;
}

.animation-container {
    width: 50px;
    height: 50px;
    display: none;
    margin: auto;
}
</style>


<?php if ($this->session->flashdata('success') || $this->session->flashdata('error')): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.0/lottie.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var toastTitle = document.getElementById('toastTitle');
    var toastMessage = document.getElementById('toastMessage');
    var toastHeader = document.getElementById('toastHeader');

    <?php if ($this->session->flashdata('success')): ?>
    toastTitle.innerText = "Sukses!";
    toastMessage.innerHTML = `
            <div class="animation-container" id="successAnimation"></div>
            <p class="mt-2 text-center"><?= $this->session->flashdata('success') ?></p>
            <strong>Yeay, Tingkatkan Terus Kinerjamu! 🚀</strong>`;
    toastHeader.classList.add("toast-success");

    setTimeout(function() {
        lottie.loadAnimation({
            container: document.getElementById('successAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '<?= base_url('assets/animations/success.json') ?>'
        });
        document.getElementById('successAnimation').style.display = 'block';
    }, 500);

    <?php elseif ($this->session->flashdata('error')): ?>
    toastTitle.innerText = "Kesalahan!";
    toastMessage.innerHTML = `
            <div class="animation-container" id="errorAnimation"></div>
            <p class="mt-2 text-center"><?= $this->session->flashdata('error') ?></p>
            <strong>Periksa kembali, Jangan Putus Asa 🚀</strong>`;
    toastHeader.classList.add("toast-error");

    setTimeout(function() {
        lottie.loadAnimation({
            container: document.getElementById('errorAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '<?= base_url('assets/animations/failed.json') ?>'
        });
        document.getElementById('errorAnimation').style.display = 'block';
    }, 500);
    <?php endif; ?>

    // Tampilkan toast
    $('#sessionToast').toast('show');
});
</script>
<?php endif; ?>