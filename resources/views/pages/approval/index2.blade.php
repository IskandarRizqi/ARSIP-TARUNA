    @extends('admin.layout.main')

    @section('content')

    <head>
    <!-- JQuery DataTable Css -->
        <link href="../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    </head>

    <style>
        .small-btn {
        padding: 1px 3px; /* Mengurangi padding tombol */
        font-size: 10px;  /* Mengecilkan ukuran teks tombol */
        border-radius: 4px; /* Membuat sudut tombol lebih kecil */
    }

    .small-btn i {
        font-size: 12px; /* Mengurangi ukuran ikon */
    }

    </style>


    <body class="theme-red">

        <section class="content">
            <div class="container-fluid">
                <div class="block-header">
                </div>
                @if(session('success'))
                    <div class="alert alert-success" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        // Setelah 2 detik, sembunyikan alert
                        setTimeout(function() {
                            $('#success-alert').fadeOut('slow');
                        }, 2000); // 2000 ms = 2 detik
                    </script>
                @endif
                
                <!-- Basic Examples -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                Approve Pengajuan
                                </h2>
                            
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Peminjam</th>
                                                <th>Nama Arsip</th>
                                                <th>Jenis Arsip</th>
                                                <th>Type</th>
                                                <th>Kategori</th>
                                                <th>Tujuan</th>
                                                <th>Batas Waktu</th>
                                                <th>PIC</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            @foreach($pengajuan as $key => $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->user_name ?? 'Unknown' }}</td>
                                                <td>{{ $value->nama }}</td>
                                                <td>{{ $value->jenis_arsip}}</td>
                                                <td>{{ $value->type }}</td>
                                                <td>{{ $value->kategori_nama}}</td>
                                                <td>{{ $value->tujuan }}</td>
                                                <td id="countdown-{{ $value->id }}">
                                                    @if($value->jenis_arsip == 'fisik')
                                                        @if($value->status_approval_2 == 'pending')
                                                            {{ $value->due_date ? \Carbon\Carbon::parse($value->due_date)->format('Y-m-d H:i:s') : 'Belum Diset' }}
                                                        @else
                                                            <span class="badge badge-secondary">-</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                
                                                 <td>
                                                    @if($value->status == 'pending')
                                                        <span class="badge badge-warning text-dark" style="background-color: orange;">Menunggu Persetujuan</span>
                                                    @elseif($value->status == 'approved')
                                                        <span class="badge badge-success" style="background-color: green;">Disetujui</span>
                                                    @elseif($value->status == 'rejected')
                                                        <span class="badge badge-danger" style="background-color: red;">Ditolak</span>
                                                    @elseif($value->status == 'returned')
                                                        <span class="badge badge-primary" style="background-color: blue;">Dikembalikan</span>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if($value->status_approval_2 == 'pending')
                                                        <span class="badge badge-warning text-dark" style="background-color: orange;">Menunggu Persetujuan</span>
                                                    @elseif($value->status_approval_2 == 'approved')
                                                        <span class="badge badge-success" style="background-color: green;">Disetujui</span>
                                                    @elseif($value->status_approval_2 == 'rejected')
                                                        <span class="badge badge-danger" style="background-color: red;">Ditolak</span>
                                                    @elseif($value->status_approval_2 == 'returned')
                                                        <span class="badge badge-primary" style="background-color: blue;">Dikembalikan</span>
                                                    @endif
                                                </td>
                                                
                                                <td style="text-align: center; vertical-align: middle;">
                                                @if(in_array($value->status, ['approved', 'rejected']) && $value->status_approval_2 == 'pending')


                                                        <div class="dropdown d-inline-block position-relative">
                                                            <a href="#" id="actionMenu2{{ $value->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                            class="text-primary" style="font-size: 20px; text-decoration: none;">
                                                                <i class="fa fa-cog"></i>
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="actionMenu2{{ $value->id }}" style="min-width: 90px; padding-left: 10px;">
                                                                <form action="{{ route('approvals.approve_2', $value->id) }}" method="POST" class="action-form">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="dropdown-item" style="background:none; color:black; border:none; font-size:14px; padding:5px 0;">
                                                                        Setuju 
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('approvals.reject_2', $value->id) }}" method="POST" class="action-form">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="dropdown-item" style="background:none; color:black; border:none; font-size:14px; padding:5px 0;">
                                                                        Tolak 
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    
                                                    @endif
                                                </td>

                                                
                                                
                                                
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const forms = document.querySelectorAll('.action-form');
                                                        
                                                        forms.forEach(form => {
                                                            form.addEventListener('submit', function () {
                                                                setTimeout(() => {
                                                                    location.reload(); // Reload halaman setelah aksi agar ikon setting hilang
                                                                }, 500);
                                                            });
                                                        });
                                                    });
                                                </script>
                                                
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Basic Examples -->
            
            </div>
        </section>

        <!-- Jquery Core Js -->
        <script src="../../plugins/jquery/jquery.min.js"></script>

    
    

        <!-- Jquery DataTable Plugin Js -->
        <script src="../../plugins/jquery-datatable/jquery.dataTables.js"></script>
        <script src="../../plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
        <script src="../../plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

        <!-- Custom Js -->
        <script src="../../js/admin.js"></script>
        <script src="../../js/pages/tables/jquery-datatable.js"></script>

        {{-- script untuk set time --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let countdownIntervals = {}; // Simpan interval timer untuk setiap arsip
        
                function startCountdown(elementId, dueDate) {
                    const countDownDate = new Date(dueDate).getTime();
                    if (isNaN(countDownDate)) return;

                    const formatTime = (time) => time.toString().padStart(2, '0');

                    const x = setInterval(function() {
                        const now = new Date().getTime();
                        const distance = countDownDate - now;

                        if (distance <= 0) {
                            clearInterval(x);
                            document.getElementById(elementId).innerHTML = 
                                "<span class='badge badge-danger' style='color: white; background-color: red; padding: 5px 10px; border-radius: 5px;'>00:00:00</span>";
                            return;
                        }

                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        document.getElementById(elementId).innerHTML = 
                            `<span class="badge badge-warning" style="color: white; background-color: orange; padding: 5px 10px; border-radius: 5px;">${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}</span>`;
                    }, 1000);

                    countdownIntervals[elementId] = x;
                }

        
                @foreach($pengajuan as $value)
                    @if($value->jenis_arsip == 'fisik' && $value->status_approval_2 == 'approved' && $value->due_date)
                        startCountdown("countdown-{{ $value->id }}", "{{ $value->due_date }}");
                    @endif
                @endforeach
        
                // Hentikan timer saat tombol "Kembali" ditekan
                document.querySelectorAll(".return-btn").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault(); // Cegah submit langsung
                        let form = this.closest("form");
                        let itemId = this.getAttribute("data-id");
        
                        // Hapus tampilan timer dari tabel
                        let timerElement = document.getElementById(`countdown-${itemId}`);
                        if (timerElement) {
                            timerElement.innerHTML = "<span class='badge badge-secondary'>Dikembalikan</span>";
                        }
        
                        // Hentikan interval timer
                        if (countdownIntervals[`countdown-${itemId}`]) {
                            clearInterval(countdownIntervals[`countdown-${itemId}`]);
                        }
        
                        // Submit form setelah 500ms (untuk efek visual)
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    });
                });
            });
        </script>
        
            
            
            
            
        
        


    </body>

    </html>

    @endsection
