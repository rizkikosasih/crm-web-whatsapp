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

  $('input, select, textarea').on('input', function () {
    $('input, select, textarea').removeClass('is-invalid');
    $('.error').remove();
  });
});
