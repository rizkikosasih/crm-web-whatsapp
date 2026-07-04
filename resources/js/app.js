import './bootstrap';

import Chart from 'chart.js/auto';
window.Chart = Chart;

import Swal from 'sweetalert2';
window.Swal = Swal;

window.Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
});

window.swalWithBsBtn = Swal.mixin({
  customClass: {
    confirmButton:
      'bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded shadow mx-1 transition duration-150 cursor-pointer',
    cancelButton:
      'bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow mx-1 transition duration-150 cursor-pointer',
  },
  buttonsStyling: false,
});

// Event listener for SweetAlert from Livewire components
document.addEventListener('livewire:init', () => {
  Livewire.on('swal:confirm', ({ method, params = {}, options = {} }) => {
    const {
      title = 'Konfirmasi',
      text = '',
      icon = 'warning',
      html = '',
      showCancelButton = true,
      confirmButtonText = 'Ya',
      cancelButtonText = 'Batal',
    } = options;

    swalWithBsBtn
      .fire({
        title,
        text,
        html,
        icon,
        showCancelButton,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
        focusConfirm: true,
      })
      .then((result) => {
        if (result.isConfirmed && method) {
          Livewire.dispatch(method, params);
        }
      });
  });
});
