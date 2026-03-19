@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product Add - Apps')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/app-menu-product-add.js'])
@endsection

@section('content')
  <div class="app-ecommerce">
    @if ($errors->any())
      <div class="alert alert-danger" role="alert">
        <ul class="mb-0 ps-4">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="product-form" method="POST" enctype="multipart/form-data"
      action="{{ route('fnb.menu.product.store', ['fnbSlug' => $fnbSlug], false) }}">
      @csrf

      <!-- Add Product -->
      <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
          <h4 class="mb-1">Add a new Product</h4>
          <p class="mb-0">Orders placed across your store</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
          <a href="{{ route('fnb.menu.product.index', ['fnbSlug' => $fnbSlug], false) }}"
            class="btn btn-outline-secondary">Discard</a>
          <button type="submit" class="btn btn-primary">Publish product</button>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-lg-8">
          <div class="card mb-6">
            <div class="card-header">
              <h5 class="card-tile mb-0">Product Information</h5>
            </div>
            <div class="card-body">
              <div class="form-floating form-floating-outline mb-5">
                <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Product title"
                  name="name" aria-label="Product title" value="{{ old('name') }}" />
                <label for="ecommerce-product-name">Name</label>
              </div>

              <div>
                <p class="mb-1">Description (Optional)</p>
                <input type="hidden" name="description" id="product-description-input" value="{{ old('description') }}">
                <div class="form-control p-0 pt-1">
                  <div class="comment-toolbar border-0 border-bottom">
                    <div class="d-flex justify-content-start">
                      <span class="ql-formats me-0">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                        <button class="ql-link"></button>
                      </span>
                    </div>
                  </div>
                  <div class="comment-editor border-0 pb-1" id="ecommerce-category-description">{{ old('description') }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-6">
            <div class="card-header">
              <h5 class="card-title mb-0">Variants</h5>
            </div>
            <div class="card-body">
              <div class="form-repeater">
                <div data-repeater-list="variants">
                  @foreach (old('variants', [['name' => '', 'price' => '']]) as $variant)
                    <div data-repeater-item>
                      <div class="row gx-5">
                        <div class="mb-6 col-sm-6">
                          <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" placeholder="Variant name" name="name"
                              value="{{ $variant['name'] ?? '' }}" />
                            <label>Variant Name</label>
                          </div>
                        </div>
                        <div class="mb-6 col-sm-4">
                          <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" placeholder="0" name="price" step="0.01"
                              min="0" value="{{ $variant['price'] ?? '' }}" />
                            <label>Price</label>
                          </div>
                        </div>
                        <div class="mb-6 col-sm-2 d-flex align-items-center">
                          <button class="btn btn-outline-danger" type="button" data-repeater-delete>
                            <i class="icon-base ri ri-delete-bin-line"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div>
                  <button class="btn btn-primary" type="button" data-repeater-create>
                    <i class="icon-base ri ri-add-line icon-16px me-2"></i>
                    Add another option
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card mb-6">
            <div class="card-header">
              <h5 class="card-title mb-0">Product Image</h5>
            </div>
            <div class="card-body">
              <div class="text-center mb-4">
                <img id="product-image-preview"
                  src="{{ old('image_preview', 'https://ui-avatars.com/api/?name=Product&color=7F9CF5&background=EBF4FF') }}"
                  alt="Product Preview" class="rounded border" style="width: 100%; max-height: 220px; object-fit: cover;">
              </div>
              <div class="mb-2">
                <input type="file" class="form-control" id="product-image-input" name="image"
                  accept="image/png,image/jpeg,image/jpg,image/webp">
              </div>
              <small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</small>
            </div>
          </div>

          <div class="card mb-6">
            <div class="card-header">
              <h5 class="card-title mb-0">Organize</h5>
            </div>
            <div class="card-body">
              <div class="mb-5 col ecommerce-select2-dropdown">
                <div class="form-floating form-floating-outline">
                  <select id="category-org" name="fnb_product_category_id" class="form-select form-select-sm select2"
                    data-placeholder="Select Category">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" @selected((string) old('fnb_product_category_id') === (string) $category->id)>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                  <label for="category-org">Category</label>
                </div>
              </div>

              <div class="mb-5 col ecommerce-select2-dropdown">
                <div class="form-floating form-floating-outline">
                  <select id="status-org" name="status" class="form-select form-select-sm"
                    data-placeholder="Select Status">
                    <option value="">Select Status</option>
                    <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                  </select>
                  <label for="status-org">Status</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

@endsection
