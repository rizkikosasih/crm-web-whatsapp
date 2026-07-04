@props (['id'])

<script type="module">
  document.addEventListener('livewire:init', () => {
    Livewire.on('bootstrap:show', () => {
      const modalEl = document.getElementById(@js ($id));
      if (modalEl) {
        modalEl.dispatchEvent(new CustomEvent('open-modal', { bubbles: true }));
      }
    });

    Livewire.on('bootstrap:hide', () => {
      const modalEl = document.getElementById(@js ($id));
      if (modalEl) {
        modalEl.dispatchEvent(new CustomEvent('close-modal', { bubbles: true }));
      }
    });
  });
</script>
