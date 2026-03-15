/**
 *  Modal Example Wizard
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  // Modal id
  const appModal = document.getElementById('createApp');
  const createBusinessForm = document.getElementById('createBusinessForm');
  const createBusinessSubmitBtn = document.getElementById('createBusinessSubmitBtn');
  const businessLogoInput = document.getElementById('modalBusinessLogo');
  const businessLogoPreview = document.getElementById('modalBusinessLogoPreview');
  const businessLogoReset = document.getElementById('modalBusinessLogoReset');
  const defaultBusinessLogoPreview = businessLogoPreview ? businessLogoPreview.getAttribute('src') : '';
  const licenseSelect = document.getElementById('modalLicenseSelect');
  const billingCycleWrapper = document.getElementById('licenseBillingCycleWrapper');
  const billingCycleSelect = document.getElementById('modalBillingCycleSelect');
  const summaryLicenseName = document.getElementById('summaryLicenseName');
  const summaryLicensePrice = document.getElementById('summaryLicensePrice');
  const summaryBillingCycle = document.getElementById('summaryBillingCycle');
  const summaryTotalPrice = document.getElementById('summaryTotalPrice');
  let createAppStepper = null;

  // Credit Card
  const creditCardMask1 = document.querySelector('.app-credit-card-mask'),
    expiryDateMask1 = document.querySelector('.app-expiry-date-mask'),
    cvvMask1 = document.querySelector('.app-cvv-code-mask');
  let cleave;

  // Cleave JS card Mask
  setTimeout(() => {
    if (creditCardMask1) {
      creditCardMask1.addEventListener('input', event => {
        let cleanValue = event.target.value.replace(/\D/g, '');
        let cardType = getCreditCardType(cleanValue);
        creditCardMask1.value = formatCreditCard(cleanValue);
        if (cardType && cardType !== 'unknown' && cardType !== 'general') {
          document.querySelector('.app-card-type').innerHTML =
            `<img src="${assetsPath}img/icons/payments/${cardType}-cc.png" height="28"/>`;
        } else {
          document.querySelector('.app-card-type').innerHTML = '';
        }
      });

      registerCursorTracker({
        input: creditCardMask1,
        delimiter: ' '
      });
    }
  }, 200);

  // Expiry Date Mask
  if (expiryDateMask1) {
    expiryDateMask1.addEventListener('input', event => {
      expiryDateMask1.value = formatDate(event.target.value, {
        delimiter: '/',
        datePattern: ['m', 'y']
      });
    });
    registerCursorTracker({
      input: expiryDateMask1,
      delimiter: '/'
    });
  }

  // CVV
  if (cvvMask1) {
    cvvMask1.addEventListener('input', event => {
      const cleanValue = event.target.value.replace(/\D/g, '');
      cvvMask1.value = formatNumeral(cleanValue, {
        numeral: true,
        numeralPositiveOnly: true
      });
    });
  }

  if (businessLogoInput && businessLogoPreview) {
    businessLogoInput.addEventListener('change', event => {
      const selectedFile = event.target.files && event.target.files[0] ? event.target.files[0] : null;

      if (!selectedFile) {
        businessLogoPreview.src = defaultBusinessLogoPreview;
        return;
      }

      const reader = new FileReader();
      reader.onload = loadEvent => {
        businessLogoPreview.src = loadEvent.target.result;
      };
      reader.readAsDataURL(selectedFile);
    });
  }

  if (businessLogoReset && businessLogoInput && businessLogoPreview) {
    businessLogoReset.addEventListener('click', () => {
      businessLogoInput.value = '';
      businessLogoPreview.src = defaultBusinessLogoPreview;
    });
  }

  const setSubmitLoadingState = isLoading => {
    if (!createBusinessSubmitBtn) {
      return;
    }

    const submitContent = createBusinessSubmitBtn.querySelector('.submit-btn-content');
    const submitLoading = createBusinessSubmitBtn.querySelector('.submit-btn-loading');

    createBusinessSubmitBtn.disabled = isLoading;

    if (submitContent) {
      submitContent.classList.toggle('d-none', isLoading);
    }

    if (submitLoading) {
      submitLoading.classList.toggle('d-none', !isLoading);
    }
  };

  const formatCurrency = amount =>
    new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount);

  const updatePaymentSummary = () => {
    if (!licenseSelect || !summaryLicenseName || !summaryLicensePrice || !summaryBillingCycle || !summaryTotalPrice) {
      return;
    }

    const selectedOption = licenseSelect.options[licenseSelect.selectedIndex];
    const selectedLicenseName = selectedOption ? selectedOption.text.trim() : '-';
    const selectedLicensePrice = selectedOption ? Number.parseFloat(selectedOption.dataset.price || '0') : 0;
    const normalizedPrice = Number.isFinite(selectedLicensePrice) ? selectedLicensePrice : 0;
    const isFreeLicense = normalizedPrice <= 0;

    if (billingCycleWrapper && billingCycleSelect) {
      billingCycleWrapper.classList.toggle('d-none', isFreeLicense);
      billingCycleSelect.disabled = isFreeLicense;

      if (isFreeLicense) {
        billingCycleSelect.value = 'monthly';
      }
    }

    const activeBillingCycle = billingCycleSelect ? billingCycleSelect.value : 'monthly';
    const billingCycleText = isFreeLicense ? 'N/A' : activeBillingCycle === 'yearly' ? 'Yearly' : 'Monthly';
    const totalPrice = isFreeLicense ? 0 : activeBillingCycle === 'yearly' ? normalizedPrice * 12 : normalizedPrice;

    summaryLicenseName.textContent = selectedLicenseName;
    summaryLicensePrice.textContent = isFreeLicense ? 'Free' : formatCurrency(normalizedPrice);
    summaryBillingCycle.textContent = billingCycleText;
    summaryTotalPrice.textContent = isFreeLicense ? 'Free' : formatCurrency(totalPrice);
  };

  if (licenseSelect) {
    licenseSelect.addEventListener('change', updatePaymentSummary);
  }

  if (billingCycleSelect) {
    billingCycleSelect.addEventListener('change', updatePaymentSummary);
  }

  updatePaymentSummary();

  if (!appModal) {
    return;
  }

  appModal.addEventListener('show.bs.modal', function (event) {
    const wizardCreateApp = document.querySelector('#wizard-create-app');
    if (wizardCreateApp !== null && typeof Stepper !== 'undefined') {
      // Wizard next prev button
      const wizardCreateAppNextList = [].slice.call(wizardCreateApp.querySelectorAll('.btn-next'));
      const wizardCreateAppPrevList = [].slice.call(wizardCreateApp.querySelectorAll('.btn-prev'));
      const wizardCreateAppBtnSubmit = wizardCreateApp.querySelector('.btn-submit');

      if (createAppStepper === null) {
        createAppStepper = new Stepper(wizardCreateApp, {
          linear: false
        });

        if (wizardCreateAppNextList) {
          wizardCreateAppNextList.forEach(wizardCreateAppNext => {
            wizardCreateAppNext.addEventListener('click', event => {
              event.preventDefault();
              createAppStepper.next();
            });
          });
        }
        if (wizardCreateAppPrevList) {
          wizardCreateAppPrevList.forEach(wizardCreateAppPrev => {
            wizardCreateAppPrev.addEventListener('click', event => {
              event.preventDefault();
              createAppStepper.previous();
            });
          });
        }

        if (wizardCreateAppBtnSubmit && createBusinessForm) {
          wizardCreateAppBtnSubmit.addEventListener('click', event => {
            event.preventDefault();

            if (typeof createBusinessForm.reportValidity === 'function' && !createBusinessForm.reportValidity()) {
              return;
            }

            setSubmitLoadingState(true);
            createBusinessForm.submit();
          });
        }
      }

      createAppStepper.to(1);
      setSubmitLoadingState(false);

      updatePaymentSummary();
    }
  });
});
