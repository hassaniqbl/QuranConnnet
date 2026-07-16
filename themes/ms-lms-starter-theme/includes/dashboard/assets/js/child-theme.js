(function ($) {
  $(document).ready(function () {
    $(document).on('click', '.masterstudy-starter-wizard__button-install-child', function () {
      $('.masterstudy-starter-wizard__progress-wrap').show();
      $('.masterstudy-starter-wizard__progress-bar-fill').css('width', '0%');
      $('.masterstudy-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 500);
      $('.masterstudy-starter-wizard__button-box').hide();

      $.ajax({
        url: masterstudy_starter_child.ajaxurl,
        type: 'POST',
        data: {
          action: 'masterstudy_starter_child_theme_install',
          nonce: masterstudy_starter_child.wpnonce,
        },
        success: function (response) {
          $('.masterstudy-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 500, function () {
            $.ajax({
              url: masterstudy_starter_wizard.ajaxurl,
              type: 'POST',
              data: {
                action: 'masterstudy_starter_template',
                nonce: masterstudy_starter_wizard.wpnonce,
                template: 'finish',
              },
              success: function (response) {
                $('.masterstudy-starter-wizard__wrapper').html(response);
              },
              error: function () {
                console.error('404 error');
              },
            });
          });
        },
        error: function () {
          $('.masterstudy-starter-wizard__progress-bar-fill').css('width', '100%');
          $('.masterstudy-starter-wizard__progress-wrap').fadeOut();
          console.error('404 error');
        },
      });
    });
  });
}
)(jQuery);
