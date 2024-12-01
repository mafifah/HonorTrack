@if(isset($data) && $data->count() > 0)
  <?php $no = 0;?>
  @foreach($data as $value)
  <?php $no++ ?>
  <tr>
    <th scope="row">{{$no}}</th>
    <td>{{ $value->jadwal->staf->nama }}</td>
    <td>{{ date("d F Y", strtotime($value->tanggal)) }}</td>
    <td>{{ $value->status_kehadiran}}</td>
  </tr>
  @endforeach
@else
  <p class="text-center">Tidak ada data untuk periode yang dipilih.</p>
@endif