<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>HonorTrack</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css" rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">


  

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="">
        <span class="d-none d-lg-block">HonorTrack</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">@if(Auth::user() != null){{ Auth::user()->nama }} @endif</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              @if(Auth::user() != null)
              <h6>{{ Auth::user()->nama }}</h6>
              <span>{{ Auth::user()->role }}</span>
              @endif
              @if(Auth::user() == null)
              <a class="dropdown-item d-flex align-items-center" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Login</span>
              @endif
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            @if(Auth::user() != null)
            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
              </a>
            </li>

            @endif

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      @if(Auth::user() != null)
      <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @endif
      @if(Auth::user() != null && Auth::user()->role != 'Guru')
      <li class="nav-heading">Master</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Akademik</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content {{ request()->is('akademik*') ? '' : 'collapsed' }} " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('kelas') }}" class="{{ request()->is('akademik/kelas*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Kelas</span>
            </a>
          </li>
          <li>
            <a href="{{ route('matapelajaran') }}" class="{{ request()->is('akademik/matapelajaran*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Mata Pelajaran</span>
            </a>
          </li>
          <li>
            <a href="{{ route('jadwal') }}" class="{{ request()->is('akademik/jadwal*') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Jadwal</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->
      <li class="nav-item">
        <a class="nav-link {{ request()->is('staf') ? '' : 'collapsed' }}" href="{{ route('staf') }}">
          <i class="bi bi-person"></i>
          <span>Staf</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @endif
     
      
      @if(Auth::user() != null && Auth::user()->role != 'Guru')
      <li class="nav-heading">Informasi</li>

      <li class="nav-item">
        <a class="nav-link {{ request()->is('informasi/buku*') ? '' : 'collapsed' }}" href="{{ route('info-honor') }}">
          <i class="bi bi-arrow-return-right"></i>
          <span>Informasi Honor</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link {{ request()->is('informasi/pengunjung*') ? '' : 'collapsed' }}" href="{{ route('info-absensi') }}">
          <i class="bi bi-arrow-return-right"></i>
          <span>Informasi Absensi</span>
        </a>
      </li><!-- End Register Page Nav -->
      @endif

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      @section('navigation')
      <!--h1>Blank Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Pages</li>
          <li class="breadcrumb-item active">Blank</li>
        </ol>
      </nav-->
    </div><!-- End Page Title -->

    <section class="section {{ request()->is('dashboard') ? 'dashboard' : '' }}">
      @yield('content')
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= >
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/>
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer>< End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js') }}/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/sweetalert/sweetalert2.all.min.js') }}"></script>


  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  

  <script>
    $('#myTable').DataTable();
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    
    function prosesFilter(token, act, form) {
        var tanggalAwal = $('#tanggalAwal').val();
        var tanggalAkhir = $('#tanggalAkhir').val();
        

        if(form == "Absensi"){
          var idguru = $('#idguru').val();
          $.post(act, {
                _token: token,
                tanggalAwal: tanggalAwal,
                tanggalAkhir: tanggalAkhir,
                idguru: idguru,
            },
            function (data) {
                $('#table-data').html(data);
            });
        }else{
          $.post(act, {
                _token: token,
                tanggalAwal: tanggalAwal,
                tanggalAkhir: tanggalAkhir,
            },
            function (data) {
                $('#table-data').html(data);
            });
        }
        
    }

    function cetakData(act, form) {
        var tanggalAwal = $('#tanggalAwal').val();
        var tanggalAkhir = $('#tanggalAkhir').val();
        document.getElementById('tanggalAwalCetak').value = tanggalAwal;
        document.getElementById('tanggalAkhirCetak').value = tanggalAkhir;
        if(form == "Absensi"){
          var idguru = $('#idguru').val();
          document.getElementById('idguruCetak').value = idguru;
        }
        document.getElementById('cetak-form').submit();
    }

    function editData(token, id, act, target) {
        //alert(id);
        $.post(act, {
            _token: token,
            id: id
        },
            function (data) {
                $(target).html(data);
            });
    }

    function deleteData(token, id, act) {
          Swal.fire({
              title: 'Anda Yakin ?',
              text: "",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#4caf50',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, hapus!',
              cancelButtonText: 'Batal',
          }).then((result) => {
              if (result.value) {
                  $.post(act, {
                      _token: token,
                      id: id
                  },
                      function (data) {
                      });
                  Swal.fire(
                      'Sukses!',
                      'Data Berhasil dihapus!',
                      'success'
                  )
                  $("#item-" + id).hide();
              }
          });
    }

    function simpanAbsensi(token) {
        // Ambil nilai dari form
        const idJadwal = document.getElementById("id-jadwal-for-absensi").value;
        const pokokPembahasan = document.getElementById("pokok_pembahasan_absensi").value;

        // Pastikan lokasi pengguna tersedia
        if (!navigator.geolocation) {
            Swal.fire('Gagal', 'Geolocation tidak didukung oleh browser Anda.', 'error');
            return;
        }

        // Ambil lokasi pengguna
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const koordinat = `${latitude},${longitude}`;

                // Kirim data ke server menggunakan AJAX
                $.ajax({
                    url: 'jadwal/simpan-absensi', // Endpoint API di Laravel
                    type: 'POST',
                    data: {
                        _token: token, // Token CSRF
                        id: idJadwal,
                        pokok_pembahasan: pokokPembahasan,
                        koordinat: koordinat,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Sukses!', response.message, 'success');
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }

                    },
                    error: function (xhr) {
                        const error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan pada server.';
                        Swal.fire('Gagal', error, 'error');
                    }
                });
            },
            function (error) {
                Swal.fire('Gagal', 'Tidak dapat mengambil lokasi Anda. Pastikan GPS aktif.', 'error');
            }
        );
        $('#verticalycentered').modal('hide');
    }
  </script>
</body>

</html>
