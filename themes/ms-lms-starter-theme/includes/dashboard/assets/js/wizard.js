(function ($) {
  $(document).ready(function () {
    $(document).on('click', '.masterstudy-starter-wizard__builder-elementor, .masterstudy-starter-wizard__builder-gutenberg', function () {
      const $this = $(this);

      const $buildersNames = $('.masterstudy-starter-wizard__builder-elementor, .masterstudy-starter-wizard__builder-gutenberg');
      const $templateElementor = $('.masterstudy-starter-wizard__template-elementor');
      const $templateGutenberg = $('.masterstudy-starter-wizard__template-gutenberg');

      $buildersNames.removeClass('active');
      $this.addClass('active');

      if ($this.hasClass('masterstudy-starter-wizard__builder-elementor')) {
        $templateElementor.show();
        $templateGutenberg.hide();
      } else if ($this.hasClass('masterstudy-starter-wizard__builder-gutenberg')) {
        $templateElementor.hide();
        $templateGutenberg.show();
      }
    });

    $(document).on('click', '.masterstudy-starter-wizard__wrapper-content li', function() {
      $('.masterstudy-starter-wizard__wrapper-content li').removeClass('masterstudy-starter-wizard__show-buttons');
      $(this).addClass('masterstudy-starter-wizard__show-buttons');
    });

    $(document).on('click', '.masterstudy-starter-wizard__button-continue', function(event) {
      const $button = $(this);
      $.ajax({
        url: masterstudy_starter_wizard.ajaxurl,
        type: 'POST',
        data: {
          action: 'masterstudy_starter_demo_options',
          nonce: masterstudy_starter_wizard.wpnonce,
          demo: $button.data('demo'),
          builder: $button.data('builder'),
        },
        success: function (response) {
          if ($button.hasClass('demo-activated')) {
            $('.masterstudy-starter-wizard__template-popup').addClass('reinstallation');
          } else {
            masterstudy_demo_steps($button, event);
          }
        },
        error: function () {
          console.error('404 error');
        },
      });

      $(window).on('beforeunload', function (e) {
        const confirmationMessage =
          'If you reload the page, the installation process may be interrupted and something may go wrong. Are you sure you want to reload?'

        e.returnValue = confirmationMessage
        return confirmationMessage
      });
    });

    $(document).on('click', '.masterstudy-starter-wizard__button-close', function(event) {
      $('.masterstudy-starter-wizard__template-popup').removeClass('reinstallation');
    });

    $(document).on('click', '.masterstudy-starter-wizard__button-reset', function(event) {
      if ($(this).hasClass('disabled')) {
        event.preventDefault();
        return;
      }

      masterstudy_demo_reset($(this), event);
    });

    $(document).on('click', '.masterstudy-starter-wizard__button', function(event) {
      if (!$(this).hasClass('masterstudy-starter-wizard__button-continue') && 
          !$(this).hasClass('masterstudy-starter-wizard__button-reset')) {
        masterstudy_demo_steps($(this), event);
      }
      if ($(this).data('template') === 'finish' || 
        $(this).hasClass('masterstudy-starter-wizard__button-install-child')) {
          $(window).off('beforeunload');
      }
      if ($(this).data('template') === 'activation' || $(this).data('template') === 'plugins') {
        $('html, body').animate({ scrollTop: 0 }, 200);
      }
    });

    $(document).on('click', '.masterstudy-starter-wizard__button-box a', function(event) {
      if (typeof $(this).attr('href') !== 'undefined') {
        $(window).off('beforeunload');
      }
    });
  });

  function masterstudy_demo_steps(button, event) {
    if (typeof button.attr('href') === 'undefined') {
      event.preventDefault();
    }

    let template = button.data('template');
    const steps = ['templates', 'plugins', 'demo-content', 'child-theme']
    const currentStepIndex = steps.indexOf(template);

    if (template) {
      if (currentStepIndex !== -1) {
        steps.forEach((step, index) => {
          const $stepElement = $(`.progress-step-${step}`);
          if (index < currentStepIndex) {
            $stepElement.removeClass('active').addClass('progress-step-done');
          } else if (index === currentStepIndex) {
            $stepElement.addClass('active').removeClass('progress-step-done');
          } else {
            $stepElement.removeClass('active progress-step-done');
          }
        });
      } else {
        $('.masterstudy-starter-wizard__navigation').hide();
      }

      $('.masterstudy-starter-wizard__wrapper').empty();

      $.ajax({
        url: masterstudy_starter_wizard.ajaxurl,
        type: 'POST',
        data: {
          action: 'masterstudy_starter_template',
          nonce: masterstudy_starter_wizard.wpnonce,
          template: button.data('template'),
        },
        success: function (response) {
          $('.masterstudy-starter-wizard__wrapper').html(response);
          if (template !== 'templates') {
            $('.masterstudy-starter-wizard__navigation-back-button').hide();
          } else {
            $('.masterstudy-starter-wizard__navigation-back-button').show();
          }

          if (template === 'plugins') {
            let allActivated = true;

            $('.masterstudy-starter-wizard__plugin').each(function () {
              if ($(this).data('status') !== 'Activated') {
                allActivated = false;
                return false;
              }
            });

            if (allActivated) {
              $('.masterstudy-starter-wizard__button-box').addClass('hide-install');
            }
          }
        },
        error: function () {
          console.error('404 error');
        },
      });
    }
  }

  function masterstudy_demo_reset(button, event) {
    if (typeof button.attr('href') === 'undefined') {
      event.preventDefault();

      $('.masterstudy-starter-wizard__demo-checkbox').addClass('disable-check');
      $('.masterstudy-starter-wizard__progress-wrap').show();
      $('.masterstudy-starter-wizard__progress-bar-fill').css('width', '0%');
      $('.masterstudy-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 10);
      $('.masterstudy-starter-wizard__button-box').hide();

      $.ajax({
        url: masterstudy_starter_wizard.ajaxurl,
        type: 'POST',
        data: {
          action: 'masterstudy_starter_template_reset',
          nonce: masterstudy_starter_wizard.wpnonce,
        },
        success: function (response) {
          $('.masterstudy-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 10, function () {
            $.ajax({
              url: masterstudy_starter_wizard.ajaxurl,
              type: 'POST',
              data: {
                action: 'masterstudy_starter_template',
                nonce: masterstudy_starter_wizard.wpnonce,
                template: 'plugins',
              },
              success: function (response) {
                $('.masterstudy-starter-wizard__wrapper').html(response);
                let allActivated = true;

                $('.masterstudy-starter-wizard__plugin').each(function () {
                  if ($(this).data('status') !== 'Activated') {
                    allActivated = false;
                    return false;
                  }
                });

                if (allActivated) {
                  $('.masterstudy-starter-wizard__button-box').addClass('hide-install');
                }
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
    }
  }
})(jQuery);
