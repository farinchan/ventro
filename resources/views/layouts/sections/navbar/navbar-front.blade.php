@php
  use Illuminate\Support\Facades\Route;
  $currentRouteName = Route::currentRouteName();
  $activeRoutes = ['front-pages-pricing', 'front-pages-payment', 'front-pages-checkout', 'front-pages-help-center'];
  $activeClass = in_array($currentRouteName, $activeRoutes) ? 'active' : '';

  use Illuminate\Support\Facades\Auth;

@endphp
<!-- Navbar: Start -->
<nav class="layout-navbar shadow-none py-0">
  <div class="container">
    <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
      <!-- Menu logo wrapper: Start -->
      <div class="navbar-brand app-brand demo d-flex py-0 me-4 me-xl-8">
        <!-- Mobile menu toggle: Start-->
        <button class="navbar-toggler border-0 px-0 me-4" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <i class="icon-base ri ri-menu-fill icon-lg align-middle text-heading fw-medium"></i>
        </button>
        <!-- Mobile menu toggle: End-->
        <a href="javascript:;" class="app-brand-link">
          <span class="app-brand-logo demo">@include('_partials.macros')</span>
          <span
            class="app-brand-text demo menu-text fw-semibold ms-2 ps-1">{{ config('variables.templateName') }}</span>
        </a>
      </div>
      <!-- Menu logo wrapper: End -->
      <!-- Menu wrapper: Start -->
      <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
        <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 p-2" type="button"
          data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
          aria-expanded="false" aria-label="Toggle navigation">
          <i class="icon-base ri ri-close-fill"></i>
        </button>
      </div>
      <div class="landing-menu-overlay d-lg-none"></div>
      <!-- Menu wrapper: End -->
      <!-- Toolbar: Start -->
      <ul class="navbar-nav flex-row align-items-center ms-auto">
        @if ($configData['hasCustomizer'] == true)
          <!-- Style Switcher -->
          <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow me-sm-2" id="nav-theme" href="javascript:void(0);"
              data-bs-toggle="dropdown">
              <i class="icon-base ri ri-sun-line icon-24px theme-icon-active"></i>
              <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
              <li>
                <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light"
                  aria-pressed="false">
                  <span><i class="icon-base ri ri-sun-line icon-24px me-3" data-icon="sun-line"></i>Light</span>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark"
                  aria-pressed="true">
                  <span><i class="icon-base ri ri-moon-clear-line icon-24px me-3"
                      data-icon="moon-clear-line"></i>Dark</span>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
                  aria-pressed="false">
                  <span><i class="icon-base ri ri-computer-line icon-24px me-3"
                      data-icon="computer-line"></i>System</span>
                </button>
              </li>
            </ul>
          </li>
          <!-- / Style Switcher-->
        @endif

        @guest
          <!-- navbar button: Start -->
          <li>
            <a href="{{ route('login') }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4"><span
                class="icon-base ri ri-user-line me-md-1"></span><span class="d-none d-md-block">Login/Register</span></a>
          </li>
          <!-- navbar button: End -->

        @endguest


        @auth
          <!-- navbar button: Start -->
          <li>
            <a href="{{ route('business.index') }}" class="btn btn-primary px-2 px-sm-4 px-lg-2 px-xl-4"><span
                class="icon-base ri ri-dashboard-line me-md-1"></span><span class="d-none d-md-block">Manage My
                business</span></a>
          </li>
          <!-- navbar button: End -->
          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ Auth::user()->photo }}" alt="avatar" class="rounded-circle" />
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
              <li>
                <a class="dropdown-item"
                  href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      <div class="avatar avatar-online">
                        <img src="{{ Auth::user()->photo }}" alt="alt" class="w-px-40 h-auto rounded-circle" />
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0 small">
                        @if (Auth::check())
                          {{ Auth::user()->name }}
                        @else
                          John Doe
                        @endif
                      </h6>
                      <small class="text-body-secondary">{{ Auth::user()->role ?? 'User' }}</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item"
                  href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                  <i class="icon-base ri ri-user-3-line icon-22px me-2"></i> <span class="align-middle">My
                    Profile</span> </a>
              </li>
              @if (Auth::check())
                <li>
                  <a class="dropdown-item" href=""> <i
                      class="icon-base ri ri-settings-4-line icon-22px me-3"></i><span
                      class="align-middle">Settings</span>
                  </a>
                </li>
              @endif
              <li>
                <a class="dropdown-item" href="">
                  <span class="d-flex align-items-center align-middle">
                    <i class="flex-shrink-0 icon-base ri ri-file-text-line icon-22px me-3"></i>
                    <span class="flex-grow-1 align-middle">Billing Plan</span>
                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger">4</span>
                  </span>
                </a>
              </li>
              @if (Auth::User())
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <li>
                  <h6 class="dropdown-header">Manage Business</h6>
                </li>
                <li>
                  <div class="dropdown-divider my-1"></div>
                </li>
                <li>
                  <a class="dropdown-item" href="">
                    <i class="icon-base ri ri-briefcase-3-line icon-md me-3"></i><span>My Business</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="">
                    <i class="icon-base ri ri-function-add-fill icon-md me-3"></i><span>Create New Business</span>
                  </a>
                </li>
                <li>
                  <div class="dropdown-divider my-1"></div>
                </li>
              @endif

              @if (Auth::check())
                <li>
                  <div class="d-grid px-4 pt-2 pb-1">
                    <a class="btn btn-danger d-flex" href="{{ route('logout') }}"
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      <small class=" align-middle">Logout</small>
                      <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
                    </a>
                  </div>
                </li>
                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                  @csrf
                </form>
              @else
                <li>
                  <div class="d-grid px-4 pt-2 pb-1">
                    <a class="btn btn-danger d-flex" href="{{ route('login') }}">
                      <small class="align-middle">Login</small>
                      <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
                    </a>
                  </div>
                </li>
              @endif
            </ul>
          </li>
          <!--/ User -->
        @endauth
      </ul>
      <!-- Toolbar: End -->
    </div>
  </div>
</nav>
<!-- Navbar: End -->
