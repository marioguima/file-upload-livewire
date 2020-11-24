<div>
  @if ($photo)
  <img src="{{$photo}}" width="500px" />
  @endif

  <form wire:submit.prevent="save">
    <input type="file" id="file-upload-0" wire:change="$emit('fileChoosen', {{0}})" accept="image/*">

    @error('photo') <span class="error">{{ $message }}</span> @enderror

    <button type="submit">Save Photo</button>
  </form>

</div>
<script>
  Livewire.on('fileChoosen', id => {
    let inputFile = document.getElementById('file-upload-' + id);
    let file = inputFile.files[0];
    let reader = new FileReader();
    reader.onloadend = () => {
      Livewire.emit('fileUpload', id, reader.result);
    }
    reader.readAsDataURL(file);
  })

</script>
