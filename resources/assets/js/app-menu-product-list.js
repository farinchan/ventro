/**
 * app-ecommerce-product-list
 */

'use strict';

// Datatable (js)
document.addEventListener('DOMContentLoaded', function (e) {
  // Variable declaration for table
  const dt_product_table = document.querySelector('.datatables-products');
  const productAdd = dt_product_table?.dataset.createUrl || `${window.location.pathname.replace(/\/$/, '')}/create`;

  // E-commerce Products datatable

  if (dt_product_table) {
    var dt_products = new DataTable(dt_product_table, {
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' }
        },
        {
          // Total products
          targets: 2,
          responsivePriority: 3
        },
        {
          targets: 3,
          responsivePriority: 5
        },
        {
          // Variants
          targets: 4,
          orderable: false
        },
        {
          // Status
          targets: 5,
          orderable: false
        },
        {
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false
        }
      ],
      select: { style: 'multi', selector: 'td:nth-child(2)' },
      order: [2, 'asc'],
      layout: {
        topStart: {
          rowClass: 'card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start',
          features: [{ search: { className: 'me-5 ms-n4 pe-5 mb-n6 mb-md-0', placeholder: 'Search', text: '_INPUT_' } }]
        },
        topEnd: {
          rowClass: 'row m-3 mx-2 my-0 justify-content-between',
          features: [
            {
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
                        columns: [2, 3, 4, 5],
                        format: {
                          body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;

                            // Parse HTML content
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(inner, 'text/html');

                            let text = '';

                            // Handle product-name elements specifically
                            const userNameElements = doc.querySelectorAll('.product-name');
                            if (userNameElements.length > 0) {
                              userNameElements.forEach(el => {
                                const nameText =
                                  el.querySelector('h6')?.textContent ||
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
                  text: '<i class="icon-base ri ri-add-line me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Add Product</span>',
                  className: 'add-new btn btn-primary',
                  action: function () {
                    window.location.href = productAdd;
                  }
                }
              ]
            }
          ]
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
              return 'Product Details';
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
      },
      initComplete: function () {
        const api = this.api();
        const escapeRegex = value => value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

        // Adding status filter once table is initialized
        api.columns(5).every(function () {
          const column = this;
          const select = document.createElement('select');
          select.id = 'ProductStatus';
          select.className = 'form-select text-capitalize';
          select.innerHTML = '<option value="">Select Status</option>';

          document.querySelector('.product_status').appendChild(select);

          select.addEventListener('change', function () {
            const val = select.value ? `^${escapeRegex(select.value)}$` : '';
            column.search(val, true, false).draw();
          });

          column
            .data()
            .unique()
            .sort()
            .each(function (d) {
              const parser = new DOMParser();
              const doc = parser.parseFromString(d, 'text/html');
              const statusValue = (doc.body.textContent || '').trim();

              if (!statusValue) {
                return;
              }

              const option = document.createElement('option');
              option.value = statusValue;
              option.textContent = statusValue;
              select.appendChild(option);
            });
        });

        // Adding category filter once table is initialized
        api.columns(3).every(function () {
          const column = this;
          const select = document.createElement('select');
          select.id = 'ProductCategory';
          select.className = 'form-select text-capitalize';
          select.innerHTML = '<option value="">Category</option>';

          document.querySelector('.product_category').appendChild(select);

          select.addEventListener('change', function () {
            const val = select.value ? `^${escapeRegex(select.value)}$` : '';
            column.search(val, true, false).draw();
          });

          column
            .data()
            .unique()
            .sort()
            .each(function (d) {
              const categoryValue = String(d).trim();

              if (!categoryValue) {
                return;
              }

              const option = document.createElement('option');
              option.value = categoryValue;
              option.textContent = categoryValue;
              select.appendChild(option);
            });
        });

      }
    });
  }

  // Filter form control to default size
  // ? setTimeout used for product-list table initialization
  setTimeout(() => {
    const elementsToModify = [
      { selector: '.dt-buttons .btn', classToRemove: 'btn-secondary' },
      { selector: '.dt-search .form-control', classToAdd: 'ms-0' },
      { selector: '.dt-search', classToAdd: 'mb-0 mb-md-5' },
      { selector: '.dt-layout-table', classToRemove: 'row mt-2' },
      { selector: '.dt-layout-end', classToAdd: 'gap-md-2 gap-0 mt-0' },
      { selector: '.dt-layout-start', classToAdd: 'mt-0' },
      { selector: '.dt-layout-end .dt-buttons.btn-group', classToAdd: 'mb-md-0 mb-5' },
      { selector: '.dt-layout-full', classToRemove: 'col-md col-12', classToAdd: 'table-responsive' }
    ];

  }, 100);
});
