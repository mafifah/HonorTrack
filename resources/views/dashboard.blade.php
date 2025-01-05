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
  @endif
@endsection
