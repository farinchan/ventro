/**
 * App eCommerce Add Product Script
 */
'use strict';

// Javascript to handle the e-commerce product add page

(function () {
  // Comment editor
  const commentEditor = document.querySelector('.comment-editor');
  const descriptionInput = document.querySelector('#product-description-input');
  const productForm = document.querySelector('#product-form');
  const productImageInput = document.querySelector('#product-image-input');
  const productImagePreview = document.querySelector('#product-image-preview');

  if (commentEditor) {
    const quill = new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Product Description',
      theme: 'snow'
    });

    if (descriptionInput && descriptionInput.value) {
      quill.root.innerHTML = descriptionInput.value;
    }

    if (productForm && descriptionInput) {
      productForm.addEventListener('submit', function () {
        descriptionInput.value = quill.root.innerHTML;
      });
    }
  }

  if (productImageInput && productImagePreview) {
    productImageInput.addEventListener('change', function (event) {
      const [file] = event.target.files || [];

      if (!file) {
        return;
      }

      const previewUrl = URL.createObjectURL(file);
      productImagePreview.src = previewUrl;

      productImagePreview.onload = function () {
        URL.revokeObjectURL(previewUrl);
      };
    });
  }
})();

// jQuery to handle the e-commerce product add page

$(function () {
  // Select2
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') // for dynamic placeholder
      });
    });
  }

  var formRepeater = $('.form-repeater');

  // Form Repeater
  // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
  // -----------------------------------------------------------------------------------------------------------------

  if (formRepeater.length) {
    formRepeater.repeater({
      show: function () {
        $(this).slideDown();
      }
    });
  }
});
