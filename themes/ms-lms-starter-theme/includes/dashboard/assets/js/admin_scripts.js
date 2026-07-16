(function ($) {
    let adminAjaxUrl = null;

    $(document).ready(function () {
        /** Set ajax url value **/
        if( typeof stm_lms_starter_theme_data.stm_lms_admin_ajax_url !== 'undefined'
            &&  stm_lms_starter_theme_data.hasOwnProperty('stm_lms_admin_ajax_url') ) {
            adminAjaxUrl = stm_lms_starter_theme_data.stm_lms_admin_ajax_url;
        }
        /** show step 2 **/
        $('#loader').on('click', function (e) {
            e.preventDefault();
            $('#loader .installing').css('display','inline-block');
            $('#loader span').html('Updating ');
            $('#loader').addClass("updating");
            $.ajax({
                url: adminAjaxUrl,
                dataType: 'json',
                context: this,
                method: 'POST',
                data: {
                    action: 'stm_update_starter_theme',
                    slug: 'ms-lms-starter-theme',
                    type: 'theme',
                    nonce: starter_theme_nonces['stm_update_starter_theme'],
                },
                complete: function (data) {
                    $('#loader .installing').css('display','none');
                    $('#loader .downloaded').css('display','inline-block');
                    $('#loader span').html('Successfully Updated');
                    $('#loader').css('pointer-events','none');
                    $('#loader').css('cursor','default');
                }
            });
        });
    });

    //Wizard scroll to contacts
    $('.ms-lms-starter-info-box-tabs a').click(function(e) {
      const href = $(this).attr('href')

      if (href.startsWith('#') && href.length > 1) {
        const target = $(href);
        if (target.length) {
          e.preventDefault();
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 500);
        }
      }
    });

    //Wizard tabs
    $('.masterstudy-starter-system-status, .masterstudy-starter-change-log').hide();

    $('.ms-lms-starter-info-box-tabs .ms-lms-starter-templates-tab').click(function(e) {
      const href = $(this).attr('href');

      if (href === '#') {
        e.preventDefault();
      }

      $('.ms-lms-starter-info-box-tabs a').removeClass('active');
      $('.masterstudy-starter-templates, .masterstudy-starter-system-status, .masterstudy-starter-change-log').hide();
      $(this).addClass('active');

      if ($(this).hasClass('ms-lms-starter-templates')) {
        $('.masterstudy-starter-templates').show();
      } else if ($(this).hasClass('ms-lms-starter-system-status')) {
        $('.masterstudy-starter-system-status').show();
      } else if ($(this).hasClass('ms-lms-starter-changelog')) {
        $('.masterstudy-starter-change-log').show();
      }
    });

    $('.masterstudy-starter-accordion-content.active').show()

    $('.masterstudy-starter-accordion-header').click(function () {
      if ($(this).hasClass('active')) {
        return;
      }

      $('.masterstudy-starter-accordion-header.active').removeClass('active');
      $('.masterstudy-starter-accordion-content.active').removeClass('active').slideUp();

      $(this).addClass('active');
      $(this).next('.masterstudy-starter-accordion-content').addClass('active').slideDown();
    });
})(jQuery);