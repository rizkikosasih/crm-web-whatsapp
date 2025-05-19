// Import Ekko Lightbox
import 'ekko-lightbox/dist/ekko-lightbox.css';
import 'ekko-lightbox/dist/ekko-lightbox.min.js';

$(document).on('click', '[data-toggle="lightbox"]', function (event) {
  event.preventDefault();
  $(this).ekkoLightbox({
    alwaysShowClose: true,
  });
});
