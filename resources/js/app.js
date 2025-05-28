/** Bootstrap 4 */
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/** Jquery */
import $ from 'jquery';
window.$ = window.jQuery = $;

/** Sweetalert2 */
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
    confirmButton: 'btn btn-success',
    cancelButton: 'btn btn-danger',
  },
  buttonsStyling: false,
});

/** AdminLTE 3 */
import 'admin-lte';

document.addEventListener('livewire:navigated', function () {
  /** Initialize Tooltip */
  $('.tooltip').remove();
  $(document).tooltip({
    selector: '.tooltips',
    container: 'body',
    html: true,
    placement: function (context, source) {
      let position = $(source).position();
      if (position.left > 515) return 'left';
      if (position.left < 515) return 'right';
      if (position.top < 110) return 'bottom';
      return 'top';
    },
  });

  /** Fixing Bug Sidebar */
  function fixingSidebar() {
    if (window.innerWidth < 991) {
      document.body.classList.add('sidebar-collapse');
    }
  }
  fixingSidebar();
  window.addEventListener('resize', fixingSidebar);

  $('input, textarea').on('input', function () {
    $(this).removeClass('is-invalid').parents('.form-group').find('.error').remove();
  });

  $('select').on('change', function () {
    $(this).removeClass('is-invalid').parents('.form-group').find('.error').remove();
  });
});

document.addEventListener('livewire:initialized', function () {
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
