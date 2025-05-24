@livewireScripts

<script type="module">
  Livewire.on('showError', function (e) {
    Toast.fire({icon: 'error', title: e.message});
  });

  Livewire.on('showSuccess', function (e) {
    Toast.fire({icon: 'success', title: e.message});
  });

  Livewire.on('scrollToTop', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
</script>

@yield('page-script')
