(function ($) {
  $(document).ready(function () {
    let isInstalling = true;

    $(document).on('click', '.masterstudy-starter-wizard__demo-checkbox label', function (e) {
      if ($(this).closest('.masterstudy-starter-wizard__demo, .masterstudy-starter-wizard__demo-checkbox').hasClass('disable-check')) {
          return;
      }

      e.preventDefault();

      const $checkbox = $(this).find('.demo-checkbox');
      const isChecked = $checkbox.data('checked');

      $checkbox.data('checked', !isChecked);
      $checkbox.attr('data-checked', !isChecked);

      const $resetButton = $('.masterstudy-starter-wizard__button-reset');
      if (isChecked) {
        $resetButton.addClass('disabled');
      } else {
        $resetButton.removeClass('disabled');
      }
    });

    $(document).on('click', '.masterstudy-starter-wizard__button-install-demo', function () {
      isInstalling = true;

      $(window).on('beforeunload', function () {
        if (isInstalling) {
          return 'Demo content is still being installed. Are you sure you want to leave?';
        }
      });

      $('.masterstudy-starter-wizard__demo').addClass('disable-check');
      $(document).off('click', '.masterstudy-starter-wizard__demo-checkbox label');
      $('.masterstudy-starter-wizard__button-box').addClass('masterstudy-starter-wizard__button-box__hide');

      const steps = [
        {
          type: 'demo_content',
          checked: $('.masterstudy-starter-wizard__demo[data-demo="demo-content"] .demo-checkbox').data('checked'),
          animationClass: '.masterstudy-starter-wizard__demo[data-demo="demo-content"]',
        },
        {
          type: 'theme_settings',
          checked: $('.masterstudy-starter-wizard__demo[data-demo="theme-settings"] .demo-checkbox').data('checked'),
          animationClass: '.masterstudy-starter-wizard__demo[data-demo="theme-settings"]',
        },
        {
          type: 'lms_options',
          checked: $('.masterstudy-starter-wizard__demo[data-demo="lms-options"] .demo-checkbox').data('checked'),
          animationClass: '.masterstudy-starter-wizard__demo[data-demo="lms-options"]',
        },
      ];

      let currentStep = 0;

      function processNextStep() {
        let hasError = false;

        function processNext() {
          if (currentStep >= steps.length) {
            isInstalling = false;

            $('.masterstudy-starter-wizard__button-box').removeClass('masterstudy-starter-wizard__button-box__hide');

            if (hasError) {
              $('.masterstudy-starter-wizard__button-box').addClass('has-demo-error');
            }

            return;
          }

          const step = steps[currentStep];
          if (!step.checked) {
            currentStep++;
            processNext();
            return;
          }

          $(step.animationClass).addClass('masterstudy-starter-wizard__demo-load');

          $.ajax({
            url: masterstudy_starter_demo.ajaxurl,
            type: 'POST',
            data: {
              action: 'masterstudy_starter_demo_install',
              nonce: masterstudy_starter_demo.wpnonce,
              type: step.type,
            },
            success: function (response) {
              if (response.success) {
                $(step.animationClass)
                  .removeClass('masterstudy-starter-wizard__demo-load')
                  .addClass('masterstudy-starter-wizard__demo-loaded')
              } else if (response.data === false) {
                $(step.animationClass)
                  .removeClass(
                    'masterstudy-starter-wizard__demo-load masterstudy-starter-wizard__demo-loaded'
                  )
                  .addClass('masterstudy-starter-wizard__demo-error')
                hasError = true
              }
              currentStep++
              processNext()
            },
            error: function () {
              console.error('404 error');
              $(step.animationClass)
                .removeClass('masterstudy-starter-wizard__demo-load')
                .addClass('masterstudy-starter-wizard__demo-error')
              hasError = true
              currentStep++
              processNext();
            },
          });
        }

        processNext();
      }

      processNextStep();
    });
  });
})(jQuery);
