@extends('layouts/layoutMaster')

@section('title', 'eCommerce Product List - Apps')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/app-menu-product-list.js'])
@endsection

@section('content')
  @if (session('success'))
    <div class="alert alert-success" role="alert">
      {{ session('success') }}
    </div>
  @endif

  <!-- Product List Widget -->
  {{-- <div class="card mb-6">
    <div class="card-widget-separator-wrapper">
      <div class="card-body card-widget-separator">
        <div class="row gy-4 gy-sm-1">
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
              <div>
                <p class="mb-1">In-store Sales</p>
                <h4 class="mb-1">$5,345.43</h4>
                <p class="mb-0"><span class="me-2">5k orders</span><span
                    class="badge rounded-pill bg-label-success">+5.7%</span></p>
              </div>
              <div class="avatar me-sm-6">
                <span class="avatar-initial rounded-3 text-heading">
                  <i class="icon-base ri ri-home-6-line icon-28px"></i>
                </span>
              </div>
            </div>
            <hr class="d-none d-sm-block d-lg-none me-6" />
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
              <div>
                <p class="mb-1">Website Sales</p>
                <h4 class="mb-1">$674,347.12</h4>
                <p class="mb-0"><span class="me-2">21k orders</span><span
                    class="badge rounded-pill bg-label-success">+12.4%</span></p>
              </div>
              <div class="avatar me-lg-6">
                <span class="avatar-initial rounded-3 text-heading">
                  <i class="icon-base ri ri-computer-line icon-28px"></i>
                </span>
              </div>
            </div>
            <hr class="d-none d-sm-block d-lg-none" />
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
              <div>
                <p class="mb-1">Discount</p>
                <h4 class="mb-1">$14,235.12</h4>
                <p class="mb-0">6k orders</p>
              </div>
              <div class="avatar me-sm-6">
                <span class="avatar-initial rounded-3 text-heading">
                  <i class="icon-base ri ri-gift-line icon-28px"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="mb-1">Affiliate</p>
                <h4 class="mb-1">$8,345.23</h4>
                <p class="mb-0"><span class="me-2">150 orders</span><span
                    class="badge rounded-pill bg-label-danger">-3.5%</span></p>
              </div>
              <div class="avatar">
                <span class="avatar-initial rounded-3 text-heading">
                  <i class="icon-base ri ri-money-dollar-circle-line icon-28px"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}

  <!-- Product List Table -->
  <div class="card">
    <div class="card-header border-bottom">
      <h5 class="card-title mb-4">Filter</h5>
      <div class="d-flex justify-content-between align-items-center row gap-5 gx-6 gap-md-0">
        <div class="col-md-6 product_status"></div>
        <div class="col-md-6 product_category"></div>
      </div>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-products table"
        data-create-url="{{ route('fnb.menu.product.create', ['fnbSlug' => $fnbSlug], false) }}">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th>product</th>
            <th>category</th>
            <th>Variant</th>
            <th>status</th>
            <th>actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $product)
            <tr>
              <td></td>
              <td></td>
              <td>
                <div class="d-flex justify-content-start align-items-center product-name">
                  <div class="avatar-wrapper">
                    <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">
                      <img src="{{ $product->image }}" alt="Product-{{ $product->id }}" class="rounded">
                    </div>
                  </div>
                  <div class="d-flex flex-column">
                    <h6 class="text-nowrap mb-0">{{ $product->name }}</h6>
                    <small
                      class="text-truncate d-none d-sm-block">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 50) }}</small>
                  </div>
                </div>
              </td>
              <td>{{ $product->category->name ?? 'N/A' }}</td>
              <td>
                <ul>
                  @foreach ($product->variants as $variant)
                    <li class="mb-1">
                      <div class="d-flex align-items-center">
                        <span class="badge rounded-pill bg-label-primary me-2">{{ $variant->code }}</span>
                        {{ $variant->name }} :
                        <small class="text-muted ms-2">Rp {{ number_format($variant->price, 0, ',', '.') }}</small>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </td>
              <td>
                @if ($product->status === 'active')
                  <span class="badge rounded-pill bg-label-success">Active</span>
                @else
                  <span class="badge rounded-pill bg-label-danger">Inactive</span>
                @endif
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <a href="{{ route('fnb.menu.product.edit', ['fnbSlug' => $fnbSlug, 'id' => $product->id], false) }}"
                    class="btn btn-sm btn-outline-primary">Edit</a>
                  <form method="POST" action="{{ route('fnb.menu.product.destroy', ['fnbSlug' => $fnbSlug], false) }}"
                    onsubmit="return confirm('Delete this product?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection
