@php
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();
  $fnbSlug = request()->route('fnbSlug');
  $fnbBusiness = App\Models\FnbBusiness::where('slug', $fnbSlug)->first();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  @foreach ($configData['menuAttributes'] as $attribute => $value)
  {{ $attribute }}="{{ $value }}" @endforeach>

  <!-- ! Hide app brand if navbar-full -->
  @if (!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{ url('/') }}" class="app-brand-link gap-xl-0 gap-2">
        <span class="app-brand-logo demo">@include('_partials.macros')</span>
        <span class="app-brand-text demo menu-text fw-semibold ms-2">{{ config('variables.templateName') }}</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z"
            fill-opacity="0.9" />
          <path
            d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z"
            fill-opacity="0.4" />
        </svg>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">


    <li class="menu-item {{ request()->routeIs('fnb.dashboard.*') ? 'active' : null }}">
      <a href="{{ route('fnb.dashboard.index', $fnbSlug) }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-dashboard-line"></i>
        <div>{{ __('Dashboard') }}</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('fnb.menu.*') ? 'active' : null }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-menu-add-fill"></i>
        <div>Menu Management</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('fnb.menu.category.*') ? 'active' : null }}">
          <a href="{{ url('/fnb/' . $fnbSlug . '/product-category') }}" class="menu-link">
            <div>{{ __('Category') }}</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('fnb.menu.product.*') ? 'active' : null }}">
          <a href="{{ url('/fnb/' . $fnbSlug . '/product') }}" class="menu-link">
            <div>{{ __('Product') }}</div>
          </a>
        </li>
      </ul>
    </li>

    <li class="menu-item {{ request()->routeIs('fnb.coupon.*') ? 'active' : null }}">
      <a href="{{ url('/fnb/' . $fnbSlug . '/coupon') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-percent-line"></i>
        <div>{{ __('Coupon') }}</div>
      </a>
    </li>

    <li class="menu-item {{ request()->routeIs('fnb.outlet.show') && request()->route('id') == $outlet->id ? 'active' : null }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-bank-card-line"></i>
        <div>Payment</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item ">
          <a href="{" class="menu-link">
            <div>Payment method</div>
          </a>
        </li>
        <li class="menu-item ">
          <a href="" class="menu-link">
            <div>Tax</div>
          </a>
        </li>

      </ul>
    </li>

    <li class="menu-header small mt-5">
      <span class="menu-header-text">{{ __('Outlet') }}</span>
    </li>

    @php
      $outlets = App\Models\FnbOutlet::where('fnb_business_id', $fnbBusiness->id)->get();
    @endphp

    <li class="menu-item {{ request()->routeIs('fnb.outlet.*') ? 'active' : null }}">
      <a href="{{ route('fnb.outlet.create', $fnbSlug) }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-add-circle-line"></i>
        <div>Create Outlet</div>
      </a>
    </li>
    @foreach ($outlets as $outlet)
    <li class="menu-item {{ request()->routeIs('fnb.outlet.show') && request()->route('id') == $outlet->id ? 'active' : null }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon icon-base ri ri-store-3-line"></i>
        <div>{{ $outlet->name }}</div>
      </a>

      <ul class="menu-sub">
        <li class="menu-item ">
          <a href="{" class="menu-link">
            <div>Table</div>
          </a>
        </li>
        <li class="menu-item ">
          <a href="" class="menu-link">
            <div>Transaction</div>
          </a>
        </li>
        <li class="menu-item ">
          <a href="" class="menu-link">
            <div>Staff</div>
          </a>
        </li>
        <li class="menu-item ">
          <a href="" class="menu-link">
            <div>Setting</div>
          </a>
        </li>
      </ul>
    </li>


    @endforeach

    <li class="menu-header small mt-5">
      <span class="menu-header-text">{{ __('Laporan') }}</span>
    </li>
  </ul>

</aside>
