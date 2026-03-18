/**
 * App eCommerce Category List
 */

'use strict';


// Datatable (js)
document.addEventListener('DOMContentLoaded', function (e) {
  var dt_category_list_table = document.querySelector('.datatables-category-list');

  //select2 for dropdowns in offcanvas

  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder') //for dynamic placeholder
      });
    });
  }

  // Customers List Datatable

  if (dt_category_list_table) {
    var dt_category = new DataTable(dt_category_list_table, {
      // Use existing tbody content from Blade (DOM source)
      columnDefs: [
        {
          // For checkboxes column
          targets: 0,
          orderable: false,
          searchable: false,
          responsivePriority: 1,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' }
        },
        {
          // Categories
          targets: 1,
          responsivePriority: 2,
          orderable: true
        },
        {
          // Total products
          targets: 2,
          responsivePriority: 3,
          className: 'text-sm-end'
        },
        {
          // Total Earnings
          targets: 3,
          orderable: false,
          className: 'text-sm-end'
        },
        {
          // Actions
          targets: 4,
          orderable: false,
          searchable: false,
          className: 'text-lg-center'
        }
      ],
      select: { style: 'multi', selector: 'td:first-child' },
      order: [1, 'asc'],
      layout: {
        topStart: {
          rowClass: 'row m-3 me-2 ms-0 my-0 justify-content-between',
          features: [{ search: { placeholder: 'Search Category', text: '_INPUT_' } }]
        },
        topEnd: {
          features: {
            pageLength: { menu: [7, 10, 25, 50, 100], text: '_MENU_' },
            buttons: [
              {
                extend: 'collection',
                className: 'btn btn-outline-secondary dropdown-toggle me-4 waves-effect',
                text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-upload-2-line icon-16px me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                buttons: [

                  {
                    extend: 'excel',
                    text: `<span class="d-flex align-items-center"><i class="icon-base ri ri-file-excel-line me-1"></i>Excel</span>`,
                    className: 'dropdown-item',
                    exportOptions: {
                      columns: [1, 2, 3],
                      format: {
                        body: function (inner, coldex, rowdex) {
                          if (inner.length <= 0) return inner;

                          // Parse HTML content
                          const parser = new DOMParser();
                          const doc = parser.parseFromString(inner, 'text/html');

                          let text = '';

                          // Handle category-name elements specifically
                          const userNameElements = doc.querySelectorAll('.category-name');
                          if (userNameElements.length > 0) {
                            userNameElements.forEach(el => {
                              // Get text from nested structure - try different selectors
                              const nameText =
                                el.querySelector('.fw-medium')?.textContent ||
                                el.querySelector('.d-block')?.textContent ||
                                el.textContent;
                              text += nameText.trim() + ' ';
                            });
                          } else {
                            // Handle other elements (status, role, etc)
                            text = doc.body.textContent || doc.body.innerText;
                          }

                          return text.trim();
                        }
                      }
                    }
                  },

                ]
              },
              {
                text: `<i class="icon-base ri ri-add-line icon-18px me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add Category</span>`,
                className: 'add-new btn btn-primary',
                attr: { 'data-bs-toggle': 'offcanvas', 'data-bs-target': '#offcanvasEcommerceCategoryList' }
              }
            ]
          }
        },
        bottomStart: { rowClass: 'row mx-3 justify-content-between', features: ['info'] },
        bottomEnd: 'paging'
      },
      displayLength: 7,
      language: {
        paginate: {
          next: '<i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>',
          previous: '<i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>',
          first: '<i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>',
          last: '<i class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>'
        }
      },
      // For responsive popup
        responsive: {
          details: {
            display: DataTable.Responsive.display.modal({
              header: function (row) {
                const data = row.data();
                return 'Details';
              }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
              const data = columns
                .map(function (col) {
                  const value =
                    typeof col.data === 'string'
                      ? col.data
                      : col.data && col.data.textContent
                        ? col.data.textContent
                        : '';

                  return col.title !== '' // Do not show row in modal popup if title is blank (for check box)
                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        <td>${col.title}:</td>
                        <td>${value}</td>
                      </tr>`
                    : '';
                })
                .join('');

              if (data) {
                const div = document.createElement('div');
                div.classList.add('table-responsive');
                const table = document.createElement('table');
                div.appendChild(table);
                table.classList.add('table');
                const tbody = document.createElement('tbody');
                tbody.innerHTML = data;
                table.appendChild(tbody);
                return div;
              }
              return false;
            }
          }
        }
    });
  }

  // Filter form control to default size
  // ? setTimeout used for category-list table initialization
  setTimeout(() => {
    const elementsToModify = [
      { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
      { selector: '.dt-search', classToAdd: 'mb-0 mb-md-5' },
      { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
      { selector: '.dt-layout-start', classToAdd: 'mt-0' },
      { selector: '.dt-layout-end', classToAdd: 'gap-md-2 gap-0 mt-0' },
      { selector: '.dt-layout-end .dt-buttons.btn-group', classToAdd: 'mb-md-0 mb-5' },
      { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
    ];

    elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
      document.querySelectorAll(selector).forEach(element => {
        if (classToRemove) {
          classToRemove.split(' ').forEach(className => element.classList.remove(className));
        }
        if (classToAdd) {
          classToAdd.split(' ').forEach(className => element.classList.add(className));
        }
      });
    });
  }, 100);
});
