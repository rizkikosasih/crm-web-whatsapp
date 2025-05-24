@props(['id'])

<script>
  document.addEventListener('livewire:navigated', () => {
    Livewire.on('bootstrap:show', () => {
      const modalEl = document.getElementById(@js($id));
      if (modalEl && window.bootstrap) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      } else {
        console.warn('Modal atau Bootstrap tidak tersedia');
      }
    });
  });
</script>
