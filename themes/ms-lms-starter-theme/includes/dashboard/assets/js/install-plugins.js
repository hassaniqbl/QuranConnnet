(function ($) {
  $(document).ready(function () {
    let isInstalling = false;

    $(document).on('click', '.masterstudy-starter-wizard__button', function () {
      $('.masterstudy-starter-wizard__button-box').addClass('masterstudy-starter-wizard__button-box__hide');
        const $pluginItems = $('.masterstudy-starter-wizard__plugin');
        let currentIndex = 0;

        isInstalling = true;

        function processNextPlugin() {
          if (currentIndex >= $pluginItems.length) {
            isInstalling = false;
            $('.masterstudy-starter-wizard__button-box').removeClass('masterstudy-starter-wizard__button-box__hide').addClass('hide-install');
            return;
          }

          const $currentPlugin = $pluginItems.eq(currentIndex);
          const pluginSlug = $currentPlugin.data('plugin');

          if (pluginSlug) {
            $currentPlugin.addClass('masterstudy-starter-wizard__plugin-load');

            $.ajax({
                url: masterstudy_starter_plugins.ajaxurl,
                type: 'POST',
                data: {
                  action: 'masterstudy_starter_plugins_install',
                  nonce: masterstudy_starter_plugins.wpnonce,
                  plugin_slug: pluginSlug,
                },
                success: function (response) {
                $currentPlugin
                    .removeClass('masterstudy-starter-wizard__plugin-load')
                    .addClass('masterstudy-starter-wizard__plugin-loaded');
                $currentPlugin.find('.masterstudy-starter-wizard__plugin-info__description').text('Activated');
                currentIndex++;
                processNextPlugin();
                },
                error: function () {
                  console.error('404 error');
                  currentIndex++;
                  processNextPlugin();
                },
            });
          }
        }

        processNextPlugin();
      });

      $(window).on('beforeunload', function () {
        if (isInstalling) {
          return 'Plugins are still being installed. Are you sure you want to leave?';
        }
      });
    });
  }
)(jQuery);
