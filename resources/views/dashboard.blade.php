@extends ('master')
@section('navigation')
@endsection
@section('content')
  @if(Auth::user() != null && Auth::user()->role == 'Guru')
  <section class="section">
      <div class="row align-items-top">
        @foreach($data as $hari => $jadwals)
        <h5>{{ $hari }}</h5>
          @foreach($jadwals as $value)
          <div class="col-lg-3" id="item-{{$value->id}}">
            <!-- Card with header and footer -->
            <div class="card">
              <div class="card-header">{{$value->hari}}, {{ date('H:i', strtotime($value->jam_mulai)) }} - {{ date('H:i', strtotime($value->jam_selesai)) }}</div>
              <div class="card-body">
                <h5 class="card-title">{{ $value->matapelajaran->nama }}</h5>
                <h6 class="text-muted">Kelas: {{$value->kelas->nama}}</h6>
                <h6 class="text-muted">Pengajar: {{$value->staf->nama}}</h6>
              </div>
              @if ($hariIni == $value->hari)
              <div class="card-footer">
                
                  <a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verticalycentered" 
                     onclick="editData('{{ csrf_token() }}', '{{ $value->id }}', 'akademik/jadwal/rekam-absensi', '#modal-absensi-content')">
                     <i class="ri-check-fill"></i>
                  </a>
              </div>
              @endif
            </div><!-- End Card with header and footer -->
          </div>
          @endforeach
        @endforeach
      </div>
      <div class="modal fade" id="verticalycentered" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Form Rekam Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="modal-absensi-content">
                
              </div>
            </div>
          </div>
        </div><!-- End Vertically centered Modal-->
    </section>
  @else
  <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Kelas</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi-mortarboard"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $totalKelas }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->

            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Mata Pelajaran</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi-lightbulb"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $totalMataPelajaran }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-3 col-md-6">
              <div class="card info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Guru</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi-person-check"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $totalGuru }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->

            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Absensi</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi-calendar-check"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ $totalAbsenTercatat }}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->

            
            <!-- Top Staf Hadir -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">
                <div class="card-body pb-0">
                  <h5 class="card-title">Top 5 Kehadiran Staf</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Staf</th>
                        <th scope="col">Total Hadir</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($topStafHadir as $index => $staf)
                      <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $staf->nama }}</td>
                        <td class="fw-bold text-success">{{ $staf->total_hadir }}x</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="3" class="text-center">Belum ada data kehadiran bulan ini.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>

                </div>
              </div>
            </div><!-- End Top Staf -->


          </div>
        </div>
      </div>
    </section>
  @endif
@endsection
