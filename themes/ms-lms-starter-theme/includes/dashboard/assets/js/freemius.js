window.addEventListener('load', () => {
  const $ = jQuery

  function checkMarchSaleCoupon() {
    let currentDate = new Date();
    let startDate   = new Date('2024-12-19');
    let endDate     = new Date('2025-01-11');

    return currentDate >= startDate && currentDate <= endDate
  }

  const couponActive = checkMarchSaleCoupon();
  const handler = FS.Checkout.configure({
    plugin_id: '16465',
    plan_id: '27492',
    public_key: 'pk_1a5d1ac7060675e58a0ad41379efc',
    image: '',
  })

  function handlerPurchase(event, $this, freemiusFromUrl = '', coupon = '' ) {
    let name = 'Stylemix'
    let licenses = $this.data('license')
    let selectedPlan = '';
    if ($('.masterstudy-starter-wizard__price-button.annual').hasClass('selected-price')) {
      selectedPlan = 'annual';
    } else if ($('.masterstudy-starter-wizard__price-button.lifetime').hasClass('selected-price')) {
      selectedPlan = 'lifetime';
    }

    let billing_cycle = selectedPlan === 'lifetime' ? 'lifetime' : 'annual'

    handler.open({
      name,
      licenses,
      billing_cycle,
      coupon,
      purchaseCompleted: function (response) {
        if (typeof fbq !== 'undefined') {
          fbq('track', 'Purchase', {
            currency: response.purchase.currency.toUpperCase(),
            value: response.purchase.initial_amount,
          });
        }

        window.location.href = 'admin.php?page=masterstudy-starter-freemius'
      },
    })
  }

  $(document).on('click', '.masterstudy-starter-wizard__price-button', function(event) {
    event.preventDefault();
    $('.masterstudy-starter-wizard__price-button').removeClass('selected-price');
    $(this).addClass('selected-price');
    let selectedPlan = $(this).hasClass('annual') ? 'annual' : 'lifetime';
    $(this).closest('.masterstudy-starter-wizard__price-box').find('.masterstudy-starter-wizard__button-freemius').attr('data-plan', selectedPlan);
  });

  $(document).on('click', '.masterstudy-starter-wizard__button-freemius', function(e) {
    if (couponActive) {
      handlerPurchase(e, $(this), '', 'RECAP24')
    } else {
      handlerPurchase(e, $(this));
    }

    return false;
  });
});
