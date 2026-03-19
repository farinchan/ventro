@extends('layouts/layoutMaster')

@section('title', 'eCommerce Category List - Apps')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss'])
@endsection

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-ecommerce.scss')
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js'])
@endsection

@section('page-script')
  @vite('resources/assets/js/app-menu-category-list.js')
@endsection

@section('content')
  <div class="app-ecommerce-category">
    @if (session('success'))
      <div class="alert alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger" role="alert">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Category List Table -->
    <div class="card">
      <div class="card-datatable table-responsive">
        <table class="datatables-category-list table">
          <thead>
            <tr>
              <th></th>
              <th>Categories</th>
              <th class="text-nowrap text-sm-end">Total Products &nbsp;</th>
              <th class="text-nowrap text-sm-end">Total Earning</th>
              <th class="text-lg-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($categories as $category)
              <tr>
                <td><input type="checkbox" class="dt-checkboxes form-check-input"></td>
                <td>
                  <div class="d-flex align-items-center category-name">
                    <div class="d-flex flex-column justify-content-center">
                      <span class="text-heading text-wrap fw-medium">{{ $category->name }}</span>
                      <span
                        class="text-truncate mb-0 d-none d-sm-block"><small>{{ \Illuminate\Support\Str::limit($category->description, 50) }}</small></span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="text-sm-end">{{ $category->products_count }}</div>
                </td>
                <td>
                  <div class="text-sm-end">-</div>
                </td>
                <td>
                  <div class="d-flex align-items-sm-center justify-content-sm-center">
                    <button class="btn btn-icon btn-text-secondary rounded-pill waves-effect" data-bs-toggle="offcanvas"
                      data-bs-target="#offcanvasCategoryEdit{{ $category->id }}"
                      aria-controls="offcanvasCategoryEdit{{ $category->id }}">
                      <i class="icon-base ri ri-edit-box-line icon-22px"></i>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCategoryEdit{{ $category->id }}"
                      aria-labelledby="offcanvasCategoryEditLabel{{ $category->id }}">
                      <!-- Offcanvas Header -->
                      <div class="offcanvas-header">
                        <h5 id="offcanvasCategoryEditLabel{{ $category->id }}" class="offcanvas-title">Edit Category</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                          aria-label="Close"></button>
                      </div>
                      <!-- Offcanvas Body -->
                      <div class="offcanvas-body border-top">
                        <form class="pt-0" method="POST"
                          action="{{ route('fnb.menu.category.update', ['fnbSlug' => $fnbSlug]) }}">
                          @method('PUT')
                          @csrf
                          <input type="hidden" name="id" value="{{ $category->id }}" />

                          <div class="form-floating form-floating-outline mb-5 form-control-validation">
                            <input type="text" class="form-control" id="category-name-{{ $category->id }}"
                              placeholder="Enter category title" name="name" aria-label="category title"
                              value="{{ $category->name }}" required />
                            <label for="category-name-{{ $category->id }}">Title</label>
                          </div>

                          <!-- Description -->
                          <div class="form-floating form-floating-outline mb-5">
                            <textarea class="form-control" id="category-description-{{ $category->id }}" placeholder="Description"
                              name="description" style="height: 120px">{{ $category->description }}</textarea>
                            <label for="category-description-{{ $category->id }}">Description</label>
                          </div>

                          <!-- Submit and reset -->
                          <div>
                            <button type="submit" class="btn btn-primary me-3 data-submit">Save Changes</button>
                            <button type="reset" class="btn btn-outline-danger"
                              data-bs-dismiss="offcanvas">Discard</button>
                          </div>
                        </form>
                      </div>
                    </div>

                    <button class="btn btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow"
                      data-bs-toggle="dropdown">
                      <i class="icon-base ri ri-more-2-fill icon-22px"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end m-0">

                      <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->id }}">
                        Delete
                      </button>

                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                      aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <form action="{{ route('fnb.menu.category.destroy', $fnbSlug) }}" method="post">
                          @csrf
                          @method('DELETE')
                          <input type="hidden" name="id" value="{{ $category->id }}">x
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                              </button>
                            </div>
                            <div class="modal-body ">
                              <p>Are you sure want to delete category <strong>{{ $category->name }}</strong>?</p>
                              <p class="text-danger"><small><strong>WARNING!</strong> This action cannot be undone.</small>
                              </p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>

                  </div>
                </td>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>


    <!-- Offcanvas to add new customer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryList"
      aria-labelledby="offcanvasEcommerceCategoryListLabel">
      <!-- Offcanvas Header -->
      <div class="offcanvas-header">
        <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Add Category</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <!-- Offcanvas Body -->
      <div class="offcanvas-body border-top">
        <form class="pt-0" method="POST" action="{{ route('fnb.menu.category.store', ['fnbSlug' => $fnbSlug]) }}">
          @csrf
          <!-- Title -->

          <div class="form-floating form-floating-outline mb-5 form-control-validation">
            <input type="text" class="form-control" id="new-category-title" placeholder="Enter category title"
              name="name" aria-label="category title" required />
            <label for="ecommerce-category-title">Title</label>
          </div>

          <!-- Description -->
          <div class="form-floating form-floating-outline mb-5">
            <textarea class="form-control" id="new-category-description" placeholder="Description" name="description"
              style="height: 120px"></textarea>
            <label for="new-category-description">Description</label>
          </div>
          <!-- Submit and reset -->
          <div>
            <button type="submit" class="btn btn-primary me-3 data-submit">Add</button>
            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="offcanvas">Discard</button>
          </div>
        </form>
      </div>
    </div>




  </div>
@endsection
