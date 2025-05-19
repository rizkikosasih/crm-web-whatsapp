@livewireScripts

<script type="module">
  Livewire.on('showError', function (e) {
    Toast.fire({icon: 'error', title: e[0].message});
  });

  Livewire.on('test', function (e) {
    console.info(e);
  })
</script>

@yield('page-script')
