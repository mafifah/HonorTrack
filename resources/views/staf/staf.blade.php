@extends ('master')
@section('navigation')
@endsection
@section('content')
<form id="hapus" method="GET" action= "" style="display:none;">
  @csrf 
</form>
<div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Staf</h5>
          <a type="button" class="btn btn-primary" style="margin-bottom: 10px;" href="{{ route('staf-tambah') }}"><i class="ri-add-circle-fill"></i> Tambah Data</a>

          <!-- Table with stripped rows -->
          <table id="myTable" class="table table-striped">
            <thead>
              <tr>
                <th>
                  Nama
                </th>
                <th>Jabatan</th>
                <th>Rate Honor</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $value)
              <tr id="item-{{$value->id}}">
                <td>{{ $value->nama }}</td>
                <td>{{ $value->jabatan->nama }}</td>
                <td>{{ $value->rate_gaji }}</td>
                <td>
                  <a type="button" class="btn btn-secondary" href="#" onclick= "event.preventDefault();if(confirm('Apakah Anda Yakin?')) { $('form#hapus').attr('action', '{{ route('staf-reset', $value->id) }}').submit();}"><i class="ri-lock-password-line"></i></a>
                	<a type="button" class="btn btn-warning" href="{{ route('staf-edit', $value->id) }}"><i class="ri-pencil-ruler-2-line"></i></a>
                	<a type="button" class="btn btn-danger" href="#" onclick="deleteData('{{csrf_token()}}', '{{ $value->id }}', 'staf-delete')"><i class="ri-delete-bin-2-line"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
</div>
@endsection
