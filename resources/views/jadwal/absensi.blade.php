<form method="POST">
  <div class="row mb-3">
    <label for="inputText" class="col-sm-3 col-form-label">Pokok Pembahasan</label>
    <div class="col-sm-9">
      <textarea type="text" class="form-control" name="pokok_pembahasan" rows="5" id="pokok_pembahasan_absensi"></textarea>
    </div>
  </div>
  <input type="hidden" value="{{$data->id}}" id="id-jadwal-for-absensi">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-primary" onclick="simpanAbsensi('{{csrf_token()}}')">Submit</button>
  </div>
</form>
