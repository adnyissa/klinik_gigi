(function ($) {
    "use strict";

    // 1. Spinner (Loading)
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    // 2. Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });

    // 3. Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });

    // 4. Progress Bar
    $('.pg-bar').waypoint(function () {
        $('.progress .progress-bar').each(function () {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, {offset: '80%'});

    // 5. Calender
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });

    // 6. Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: true,
        loop: true,
        nav : false
    });

})(jQuery);


// --- KODE CHART / GRAFIK (Warna Cyan / Soft) ---
document.addEventListener("DOMContentLoaded", function() {
    
    // Cek apakah elemen chart ada di halaman ini?
    var chartElement = document.getElementById("kunjungan-chart");
    
    if (chartElement) {
        var ctx = chartElement.getContext("2d");
        
        // Membuat efek gradient (WARNA CYAN)
        var gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(38, 198, 218, 0.5)'); // Cyan Pekat
        gradient.addColorStop(1, 'rgba(38, 198, 218, 0.01)'); // Transparan

        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Minggu 1", "Minggu 2", "Minggu 3", "Minggu 4"],
                datasets: [{
                    label: "Jumlah Pasien",
                    data: [5, 12, 8, 20], // Dummy Data
                    backgroundColor: gradient,
                    borderColor: "#26c6da", // <--- WARNA GARIS CYAN
                    borderWidth: 3,
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#26c6da", // <--- TITIK CYAN
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } }
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // 1. Definisi Elemen DOM
    const form = document.getElementById('formPasien');
    const modalTitle = document.getElementById('modalTitle');
    const btnAdd = document.getElementById('btnAddPasien');
    const methodField = document.getElementById('methodField');

    // Ambil URL default (Store) dari action awal form
    const storeUrl = form.getAttribute('action');
    
    // Ambil pola URL Update dari data-attribute
    const baseUpdateUrl = form.getAttribute('data-update-url');

    // ===========================================
    // LOGIKA 1: KLIK TOMBOL TAMBAH (Reset Form)
    // ===========================================
    btnAdd.addEventListener('click', function () {
        form.reset(); // Kosongkan input
        form.action = storeUrl; // Kembalikan ke URL Store
        methodField.innerHTML = ''; // Hapus input hidden PATCH
        
        // Ubah Judul & Icon Modal
        modalTitle.innerHTML = '<i class="fas fa-user-plus me-2"></i> Tambah Pasien Baru';
    });

    // ===========================================
    // LOGIKA 2: KLIK TOMBOL EDIT (Isi Form)
    // ===========================================
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // A. Ambil data dari atribut tombol (Sesuai nama kolom DB)
            const id            = this.getAttribute('data-id');
            const nama          = this.getAttribute('data-nama');
            const nik           = this.getAttribute('data-nik');
            const nomor_telepon = this.getAttribute('data-nomor_telepon'); 
            const tanggal_lahir = this.getAttribute('data-tanggal_lahir');
            const jenis_kelamin = this.getAttribute('data-jenis_kelamin');
            const alamat        = this.getAttribute('data-alamat');

            // B. Masukkan data ke dalam Input Form
            document.getElementById('nama').value = nama;
            document.getElementById('nik').value = nik;
            document.getElementById('nomor_telepon').value = nomor_telepon;
            document.getElementById('tanggal_lahir').value = tanggal_lahir;
            document.getElementById('jenis_kelamin').value = jenis_kelamin;
            document.getElementById('alamat').value = alamat;

            // C. Ubah Action Form menjadi Update
            const finalUpdateUrl = baseUpdateUrl.replace(':id', id);
            form.action = finalUpdateUrl;

            // D. Tambahkan Method PATCH (FIX: Mengganti PUT menjadi PATCH)
            // Error sebelumnya terjadi karena server hanya menerima PATCH
            methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';

            // E. Ubah Judul & Icon Modal
            modalTitle.innerHTML = '<i class="fas fa-user-edit me-2"></i> Edit Data Pasien';
        });
    });

    
});
/* File: public/assets2/js/dokter.js */

document.addEventListener('DOMContentLoaded', function() {
    
    // DEFINISI ELEMENT DOM
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('formDokter');
    const btnAdd = document.getElementById('btnAddDokter');
    const methodField = document.getElementById('methodField');
    const passHelp = document.getElementById('passHelp');

    // INPUT FIELDS
    const inputNama = document.getElementById('nama');
    const inputSIP = document.getElementById('no_sip'); 
    const inputSpesialis = document.getElementById('spesialisasi');
    const inputHP = document.getElementById('nomor_telepon');
    const inputEmail = document.getElementById('email');
    
    // AMBIL URL DINAMIS (Agar tidak hardcode manual)
    // Pastikan di HTML form sudah ada attribute: action="..." dan data-update-url="..."
    const storeUrl = form.getAttribute('action');
    const baseUpdateUrl = form.getAttribute('data-update-url');

    // ===========================================
    // LOGIKA 1: KLIK TOMBOL TAMBAH
    // ===========================================
    if(btnAdd) {
        btnAdd.addEventListener('click', function() {
            modalTitle.innerHTML = '<i class="fas fa-plus-circle me-2"></i> Tambah Dokter Baru';
            form.reset(); 
            
            // Set ke URL Store (Simpan Baru)
            form.action = storeUrl; 
            
            // Hapus method (jadi POST biasa)
            methodField.innerHTML = ''; 
            
            // Sembunyikan pesan bantuan password
            if(passHelp) passHelp.classList.add('d-none');
        });
    }

    // ===========================================
    // LOGIKA 2: KLIK TOMBOL EDIT
    // ===========================================
    document.body.addEventListener('click', function(e) {
        // Cari tombol edit terdekat yang diklik
        const btnEdit = e.target.closest('.btn-edit');
        
        if (btnEdit) {
            // A. AMBIL DATA DARI ATRIBUT TOMBOL
            const id = btnEdit.dataset.id;
            const nama = btnEdit.dataset.nama;
            const sip = btnEdit.dataset.sip;
            
            // PERBAIKAN UTAMA: Harus 'spesialisasi' (sesuai data-spesialisasi di HTML)
            const spesialis = btnEdit.dataset.spesialisasi; 
            
            const hp = btnEdit.dataset.hp;     
            const email = btnEdit.dataset.email;

            // B. UPDATE UI MODAL
            modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i> Edit Data Dokter';
            
            // Update URL Form (Ganti :id dengan ID dokter)
            const finalUpdateUrl = baseUpdateUrl.replace(':id', id);
            form.action = finalUpdateUrl;
            
            // Tambahkan Method PUT (Sesuai pesan error: Supported methods: PUT, DELETE)
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // C. ISI VALUE INPUT
            inputNama.value = nama;
            inputSIP.value = sip;
            inputSpesialis.value = spesialis; // Masukkan data spesialisasi
            inputHP.value = hp;
            inputEmail.value = email;

            // D. TAMPILKAN PESAN BANTUAN PASSWORD
            if(passHelp) passHelp.classList.remove('d-none');
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    
    // DOM ELEMENTS
    const form = document.getElementById('formKasir');
    const modalTitle = document.getElementById('modalTitle');
    const btnAdd = document.getElementById('btnAddKasir');
    const methodField = document.getElementById('methodField');
    const passHelp = document.getElementById('passHelp');

    // Input Fields
    const inputNama = document.getElementById('nama');
    const inputEmail = document.getElementById('email');
    const inputHp = document.getElementById('no_hp');
    const inputShift = document.getElementById('shift_kerja');
    const inputAlamat = document.getElementById('alamat');

    // URLs
    const storeUrl = form ? form.getAttribute('action') : '';
    const baseUpdateUrl = form ? form.getAttribute('data-update-url') : '';

    // ==========================================
    // 1. TOMBOL TAMBAH
    // ==========================================
    if (btnAdd) {
        btnAdd.addEventListener('click', function () {
            form.reset();
            form.action = storeUrl;
            methodField.innerHTML = ''; // Method POST
            modalTitle.innerHTML = '<i class="fas fa-user-plus me-2"></i> Tambah Kasir Baru';
            if(passHelp) passHelp.classList.add('d-none');
        });
    }

    // ==========================================
    // 2. TOMBOL EDIT (Delegation)
    // ==========================================
    document.body.addEventListener('click', function (e) {
        const btnEdit = e.target.closest('.btn-edit');

        if (btnEdit) {
            const id = btnEdit.getAttribute('data-id');
            const nama = btnEdit.getAttribute('data-nama');
            const email = btnEdit.getAttribute('data-email');
            const hp = btnEdit.getAttribute('data-hp');
            const shift = btnEdit.getAttribute('data-shift');
            const alamat = btnEdit.getAttribute('data-alamat');

            // Isi Form
            inputNama.value = nama;
            inputEmail.value = email;
            inputHp.value = hp;
            inputShift.value = shift;
            inputAlamat.value = alamat;

            // Setup Update Action
            const finalUpdateUrl = baseUpdateUrl.replace(':id', id);
            form.action = finalUpdateUrl;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            modalTitle.innerHTML = '<i class="fas fa-user-edit me-2"></i> Edit Akun Kasir';
            
            if(passHelp) passHelp.classList.remove('d-none');
        }
    });

    // ==========================================
    // 3. LIVE SEARCH
    // ==========================================
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#tableKasir tbody tr.data-row');
    const noResultRow = document.getElementById('noResultRow');

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            let hasResult = false;

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    hasResult = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResultRow) {
                noResultRow.classList.toggle('d-none', hasResult);
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('jadwalModal');
    const form = document.getElementById('formJadwal');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const updateUrlTemplate = form.getAttribute('data-update-url');

    // Listener untuk semua tombol Edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            // 1. Ambil Data dari Atribut Tombol dan bersihkan
            const id = this.getAttribute('data-id');
            const dokterId = this.getAttribute('data-dokter_id');
            const hari = this.getAttribute('data-hari').trim();
            const jamMulai = this.getAttribute('data-jam_mulai').trim();
            const jamSelesai = this.getAttribute('data-jam_selesai').trim();
            
            // PENTING: Mengambil status, menggunakan regex untuk membersihkan SEMUA whitespace (\s) 
            // dan mengubah ke huruf kecil untuk perbandingan yang sangat andal
            const statusCleaned = this.getAttribute('data-status').replace(/\s/g, '').toLowerCase(); 

            // 2. Isi Modal dengan Data
            modalTitle.innerHTML = '<i class="fas fa-pencil-alt me-2"></i> Edit Jadwal';
            
            document.getElementById('dokter_id').value = dokterId;
            document.getElementById('hari').value = hari;
            document.getElementById('jam_mulai').value = jamMulai;
            document.getElementById('jam_selesai').value = jamSelesai;

            // 3. Set Status Radio Button
            // Gunakan status yang sudah dibersihkan untuk memilih yang benar
            // Perbandingan: 'aktif' vs 'libur'
            if (statusCleaned === 'libur') {
                document.getElementById('statusLibur').checked = true;
            } else {
                // Default ke Aktif jika status adalah 'aktif', kosong, atau nilai lain
                document.getElementById('statusAktif').checked = true;
            }

            // 4. Ubah Action Form dari store ke update
            const updateUrl = updateUrlTemplate.replace(':id', id);
            form.action = updateUrl;

            // 5. Masukkan Method Field (PATCH)
            methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
        });
    });

    // Listener untuk tombol Tambah Jadwal (memastikan form direset)
    document.getElementById('btnAddJadwal').addEventListener('click', function() {
        form.reset();
        modalTitle.innerHTML = '<i class="fas fa-plus-circle me-2"></i> Tambah Jadwal';
        // Kembalikan action form ke store
        form.action = "{{ route('admin.jadwal.store') }}"; 
        // Hapus method field PUT/PATCH
        methodField.innerHTML = ''; 
        // Pastikan status aktif terpilih saat tambah baru
        document.getElementById('statusAktif').checked = true;
    });

    // Reset method field saat modal ditutup (tambahan safety)
    modal.addEventListener('hidden.bs.modal', function () {
        methodField.innerHTML = '';
        document.getElementById('jamErrorAlert').classList.add('d-none'); // Sembunyikan alert error
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // --- Setup Variabel ---
    const form = document.getElementById('formRM');
    // Pastikan Bootstrap tersedia untuk Modal
    const rmModalElement = document.getElementById('rmModal');
    const rmModal = rmModalElement ? new bootstrap.Modal(rmModalElement) : null;
    const detailModalElement = document.getElementById('detailModal');
    const detailModal = detailModalElement ? new bootstrap.Modal(detailModalElement) : null;

    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const updateUrlTemplate = form ? form.getAttribute('data-update-url') : '';
    const storeUrl = form ? form.getAttribute('data-store-url') : ''; 
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('tableRM');
    const rows = table ? table.querySelectorAll('tbody .data-row') : [];
    const noResultRow = document.getElementById('noResultRow');

    // --- Logika Live Search ---
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            let hasResult = false;

            rows.forEach(row => {
                // Ambil teks dari kolom Pasien (indeks 1) dan Dokter (indeks 2)
                const pasienText = row.cells[1].textContent.toLowerCase();
                const dokterText = row.cells[2].textContent.toLowerCase();

                // Juga cari di Diagnosis (indeks 3)
                const diagnosisText = row.cells[3].textContent.toLowerCase();

                if (pasienText.includes(query) || dokterText.includes(query) || diagnosisText.includes(query)) {
                    row.style.display = '';
                    hasResult = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResultRow) {
                // Tampilkan pesan 'Data tidak ditemukan' hanya jika ada baris data tetapi tidak ada yang cocok
                if (hasResult || rows.length === 0) {
                    noResultRow.classList.add('d-none');
                } else {
                    noResultRow.classList.remove('d-none');
                }
            }
        });
    }

    // --- Logika Modal Form Tambah/Edit ---
    
    // 1. Tombol Tambah Baru (Reset form dan atur mode Tambah)
    const btnAddRM = document.getElementById('btnAddRM');
    if (btnAddRM && form) {
        btnAddRM.addEventListener('click', function() {
            form.reset();
            modalTitle.innerHTML = '<i class="fas fa-plus-circle me-2"></i> Buat Rekam Medis Baru';
            methodField.innerHTML = ''; 
            form.action = storeUrl; 
        });
    }
    
    // 2. Tombol Edit (Mengisi form dan atur mode Edit)
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            // Ambil Data dari Atribut Tombol
            const id = this.getAttribute('data-id');
            const konsultasiId = this.getAttribute('data-konsultasi_id');
            const dokterId = this.getAttribute('data-dokter_id');
            const kasirId = this.getAttribute('data-kasir_id');
            const diagnosis = this.getAttribute('data-diagnosis');
            const tindakan = this.getAttribute('data-tindakan');
            const biayaTotal = this.getAttribute('data-biaya_total');
            
            // Isi Modal dengan Data
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="fas fa-pencil-alt me-2"></i> Edit Rekam Medis (ID: ' + id + ')';
            }
            
            document.getElementById('konsultasi_id').value = konsultasiId;
            document.getElementById('dokter_id').value = dokterId;
            document.getElementById('kasir_id').value = kasirId;
            document.getElementById('diagnosis').value = diagnosis;
            document.getElementById('tindakan').value = tindakan;
            document.getElementById('biaya_total').value = biayaTotal;

            // Ubah Action Form
            if (form && updateUrlTemplate) {
                const updateUrl = updateUrlTemplate.replace(':id', id);
                form.action = updateUrl;
            }

            // Masukkan Method Field (PATCH)
            if (methodField) {
                methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';
            }
        });
    });

    // 3. Tombol View Detail
    document.querySelectorAll('.btn-view-detail').forEach(button => {
        button.addEventListener('click', function() {
            const diagnosis = this.getAttribute('data-diagnosis');
            const tindakan = this.getAttribute('data-tindakan');
            const biaya = this.getAttribute('data-biaya');
            const pasien = this.getAttribute('data-pasien');
            const dokter = this.getAttribute('data-dokter');

            document.getElementById('detailPasien').textContent = pasien;
            document.getElementById('detailDokter').textContent = dokter;
            document.getElementById('detailDiagnosis').textContent = diagnosis;
            // Gunakan innerHTML jika tindakan mungkin mengandung baris baru, tapi textContent untuk keamanan
            document.getElementById('detailTindakan').textContent = tindakan; 
            document.getElementById('detailBiaya').textContent = 'Rp. ' + biaya;

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    // 4. Reset method field saat modal ditutup
    if (rmModalElement) {
        rmModalElement.addEventListener('hidden.bs.modal', function () {
            if (methodField) {
                methodField.innerHTML = '';
            }
            // Tambahkan reset action kembali ke store untuk memastikan mode default adalah 'Tambah'
            if (form) {
                form.action = storeUrl; 
            }
        });
    }
});

function formatRupiah(angka) {
    if (isNaN(angka)) return 'Rp 0';
    var numberString = angka.toString();
    var sisa = numberString.length % 3;
    var rupiah = numberString.substr(0, sisa);
    var ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return 'Rp ' + rupiah;
}

// Pastikan JQuery sudah dimuat di layout utama sebelum menjalankan script ini
$(document).ready(function() {
    const pembayaranModal = $('#pembayaranModal');
    const detailTagihan = $('#detailTagihan');
    const totalBayarInput = $('#total_bayar');
    const submitBtn = $('#submitPembayaranBtn');
    const formPembayaran = $('#formPembayaran');
    const ajaxErrorAlert = $('#ajaxErrorAlert');
    const ajaxSuccessAlert = $('#ajaxSuccessAlert'); 

    // Ambil URL dari hidden input di view
    const urlFetchDetail = $('#url_fetch_detail').val();
    const urlStorePembayaran = $('#url_store_pembayaran').val();
    const urlRedirectSuccess = $('#url_redirect_success').val();
    // Ambil token CSRF dari hidden input di form
    const csrfToken = $('input[name="_token"]').val(); 

    // Fungsi untuk menampilkan pesan (sukses/gagal) di dalam modal
    function showMessage(type, message) {
        if (type === 'success') {
            ajaxErrorAlert.addClass('d-none').text('');
            ajaxSuccessAlert.removeClass('d-none').html(message);
        } else { // 'error'
            ajaxSuccessAlert.addClass('d-none').text('');
            ajaxErrorAlert.removeClass('d-none').html(message);
        }
    }

    // 1. Logic saat tombol "Proses Pembayaran" diklik
    $('.bayar-btn').on('click', function() {
        const rmId = $(this).data('rm-id');
        const pasienNama = $(this).data('pasien-nama');

        // Reset modal dan tampilkan loading
        $('#modal_rekam_medis_id').val(rmId);
        $('#pasien_nama').val(pasienNama);
        $('#rm_id_display').val('RM-' + rmId);
        totalBayarInput.val('');
        detailTagihan.html('<p class="text-center text-muted" id="loadingMessage">Memuat detail tagihan...</p>');
        submitBtn.prop('disabled', true);
        ajaxErrorAlert.addClass('d-none').text('');
        ajaxSuccessAlert.addClass('d-none').text(''); // Reset sukses alert

        // 2. Ambil Detail Tagihan via AJAX
        $.ajax({
            url: urlFetchDetail,
            method: 'POST',
            data: {
                _token: csrfToken,
                rekam_medis_id: rmId
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const totalTagihan = data.total_tagihan;

                    // Isi field total_bayar
                    totalBayarInput.val(totalTagihan);

                    // Buat Tampilan Detail Tagihan
                    let detailHtml = `
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Biaya Dokter</td>
                                <td class="text-end">${formatRupiah(data.biaya_dokter)}</td>
                            </tr>
                            <tr>
                                <td>Layanan & Obat</td>
                                <td class="text-end">${formatRupiah(data.biaya_layanan)}</td>
                            </tr>
                            <tr class="fw-bold bg-primary text-white">
                                <td>TOTAL TAGIHAN</td>
                                <td class="text-end">${formatRupiah(totalTagihan)}</td>
                            </tr>
                        </table>
                    `;
                    detailTagihan.html(detailHtml);
                    submitBtn.prop('disabled', false); // Aktifkan tombol submit
                } else {
                    const errorMessage = response.message || 'Gagal memuat detail tagihan. Silakan coba lagi.';
                    detailTagihan.html('<p class="text-center text-danger">Gagal memuat detail tagihan: ' + errorMessage + '</p>');
                    showMessage('error', '<strong>Gagal Memuat Data:</strong> ' + errorMessage);
                    submitBtn.prop('disabled', true);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat berkomunikasi dengan server. (404/500)';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = 'Error: ' + xhr.responseJSON.message;
                }
                detailTagihan.html('<p class="text-center text-danger">Error: ' + errorMessage + '</p>');
                showMessage('error', '<strong>AJAX Error:</strong> ' + errorMessage);
                submitBtn.prop('disabled', true);
            }
        });
    });

    // 3. Logic untuk submit form pembayaran via AJAX
    formPembayaran.on('submit', function(e) {
        e.preventDefault(); 
        submitBtn.prop('disabled', true).text('Memproses...');
        ajaxErrorAlert.addClass('d-none').text('');
        ajaxSuccessAlert.addClass('d-none').text('');

        const formData = $(this).serialize();

        $.ajax({
            url: urlStorePembayaran, 
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Redirect ke halaman index. Laravel akan menampilkan pesan flash.
                    // Gunakan window.location.replace() agar halaman ini tidak tersimpan di history.
                    window.location.replace(urlRedirectSuccess); 
                } else {
                    // Gagal proses server (misal, validasi bisnis)
                    showMessage('error', '<strong>Gagal:</strong> ' + (response.message || 'Terjadi kesalahan saat mencatat pembayaran.'));
                    submitBtn.prop('disabled', false).html('<i class="fa-solid fa-check-circle"></i> Catat Pembayaran');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Gagal memproses pembayaran. Coba periksa kembali data Anda.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                     // Ambil pesan error validasi dari Laravel
                     errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                     errorMessage = xhr.responseJSON.message; 
                }
                
                showMessage('error', errorMessage);
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-check-circle"></i> Catat Pembayaran');
            }
        });
    });

    // Reset modal saat ditutup
    pembayaranModal.on('hidden.bs.modal', function () {
        formPembayaran[0].reset();
        detailTagihan.html('<p class="text-center text-muted">Memuat detail tagihan...</p>');
        totalBayarInput.val('');
        submitBtn.prop('disabled', true);
        ajaxErrorAlert.addClass('d-none').text('');
        ajaxSuccessAlert.addClass('d-none').text('');
    });
});

function updateClock() {
    const timeElement = document.getElementById('live-time');
    if (timeElement) {
        const now = new Date();
        // Format tanggal dan waktu ke bahasa Indonesia
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };
        timeElement.textContent = now.toLocaleDateString('id-ID', options);
    }
}

// Jalankan saat dokumen siap
$(document).ready(function() {
    // Inisialisasi dan jalankan jam real-time
    updateClock();
    setInterval(updateClock, 1000);
});

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. JAM REAL-TIME
    function updateClock() {
        const timeElement = document.getElementById('live-time');
        if(timeElement) {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            };
            timeElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Contoh: Auto-refresh tabel antrian pembayaran (Simulasi)
    // setInterval(() => {
    //     console.log("Memperbarui data antrian...");
    //     // fetch('/api/kasir/antrian')...
    // }, 30000); // Tiap 30 detik
});

document.addEventListener("DOMContentLoaded", function() {
    // 1. Inisialisasi Chart Kunjungan
    var chartElement = document.getElementById("kunjungan-chart");
    
    if (chartElement) {
        var ctx = chartElement.getContext("2d");
        var myChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                datasets: [{
                    label: "Pasien",
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: "rgba(25, 135, 84, .3)", // Hijau transparan
                    borderColor: "rgba(25, 135, 84, .7)", // Hijau Solid
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // 2. Update Jam Realtime (ID: live-time)
    var timeElement = document.getElementById('live-time');
    if (timeElement) {
        setInterval(function() {
            var now = new Date();
            // Format HH:mm (Contoh: 14:30)
            var timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            timeElement.textContent = timeString;
        }, 1000);
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const editButtons = document.querySelectorAll(".btn-edit");

    editButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            let id = this.dataset.id;

            document.getElementById("editNama").value = this.dataset.nama;
            document.getElementById("editNik").value = this.dataset.nik;
            document.getElementById("editHp").value = this.dataset.hp;
            document.getElementById("editLahir").value = this.dataset.lahir;
            document.getElementById("editKelamin").value = this.dataset.kelamin;
            document.getElementById("editAlamat").value = this.dataset.alamat;

            // Set action URL
            document.getElementById("formEdit").action = "/admin/pasien/" + id;
        });
    });

});
