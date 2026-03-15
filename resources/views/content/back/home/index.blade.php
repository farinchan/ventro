@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/bs-stepper/bs-stepper.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/cleave-zen/cleave-zen.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/bs-stepper/bs-stepper.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/modal-create-app.js'])

@endsection

@section('content')
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1">My Business</h4>
            <p class="mb-0">Manage your businesses across multiple industries, including Food & Beverage, Laundry, and
                Retail, all in one platform.</p>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createApp">Create Business</button>
        </div>
    </div>
    <div class="row g-6">
      @foreach ($fnb_businesses as $business)

      <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card h-100">
              <div class="card-header pb-4">
                  <div class="d-flex align-items-center">
                      <div class="d-flex align-items-center">
                          <div class="avatar me-4">
                              <img src="{{ $business->logo }}" alt="Avatar"
                                  class="rounded-circle" />
                          </div>
                          <div class="me-2">
                              <h5 class="mb-0"><a href="javascript:;" class="stretched-link text-heading">{{ $business->name }}</a></h5>
                              <div class="client-info text-body"><span class="fw-medium">Domain: </span><span>
                                      {{ $business->domain }}
                                  </span>
                              </div>
                          </div>
                      </div>

                  </div>
              </div>
              <div class="card-body">
                  <div class="d-flex flex-wrap">
                      <div class="bg-lightest px-3 py-2 rounded-3 me-2 mb-4">
                          <p class="mb-1"><span class="fw-medium text-heading">{{ $business->outlets->count() }}</span> <span>/{{ $business->license->max_outlets ?? 'Unlimited' }}</span></p>
                          <span class="text-body">Outlet</span>
                      </div>
                      <div class="bg-lightest px-3 py-2 rounded-3 me-2 mb-4">
                          <p class="mb-1"><span class="fw-medium text-heading">0</span> <span>/{{ $business->license->max_transactions_per_day ?? 'Unlimited' }}</span></p>
                          <span class="text-body">Transaksi hari ini</span>
                      </div>
                      <div class="bg-lightest px-3 py-2 rounded-3 me-2 mb-4">
                          <p class="mb-1"><span class="fw-medium text-heading">0</span> <span>/ $18.2k</span></p>
                          <span class="text-body">Pendapatan Bulan Ini</span>
                      </div>
                  </div>
                  <p class="mb-0">{{ $business->description }}</p>
              </div>
              <div class="card-body border-top">
                  <div class="d-flex align-items-center mb-4">
                      <p class="mb-1"><span class="text-heading fw-medium">License: </span> <span>{{ $business->license?->name ?? 'No License' }}</span></p>
                      <span class="badge bg-label-danger ms-auto rounded-pill">Food & Beverage</span>
                  </div>
                  <div class="d-flex align-items-center">
                      <div class="d-flex align-items-center">
                          <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                              <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                  title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                                  <img class="rounded-circle" src="{{ asset('assets/img/avatars/5.png') }}"
                                      alt="Avatar" />
                              </li>

                              <li><small class="text-body-secondary">{{ $business->businessUsers->count() }} Manage user</small></li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endforeach
    </div>

    @include('_partials/_modals/modal-create-business')
@endsection
