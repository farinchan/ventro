@extends('layouts/layoutMaster')

@section('title', 'Create Outlet')

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/leaflet/leaflet.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/leaflet/leaflet.js'])
@endsection

@section('page-script')
  @vite(['resources/assets/js/fnb-outlet-create.js'])
@endsection

@section('content')
  <form method="POST" action="{{ route('fnb.outlet.store', ['fnbSlug' => $fnbSlug]) }}">
    @csrf
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">
        <div class="card mb-6">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create Outlet</h5>
          </div>

          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('success') }}
              </div>
            @endif



            <div class="row g-5">
              <div class="col-12">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="outlet-name"
                    placeholder="Outlet name" name="name" value="{{ old('name') }}" required />
                  <label for="outlet-name">Outlet Name</label>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="outlet-phone"
                    placeholder="0812xxxx" name="phone" value="{{ old('phone') }}" />
                  <label for="outlet-phone">Phone Number</label>
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="outlet-email"
                    placeholder="outlet@example.com" name="email" value="{{ old('email') }}" />
                  <label for="outlet-email">Store Contact Email</label>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>



            </div>


          </div>
        </div>
        <div class="card mb-6">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Address Detail</h5>
          </div>

          <div class="card-body">




            <div class="row g-5">


              <div class="col-12">
                <div class="form-floating form-floating-outline">
                  <textarea class="form-control @error('address') is-invalid @enderror" id="outlet-address" style="height: 100px"
                    placeholder="Address" name="address">{{ old('address') }}</textarea>
                  <label for="outlet-address">Address</label>
                  @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12">
                <label class="form-label" for="outletMap">Pilih Lokasi Outlet</label>
                <div id="outletMap" class="rounded border" style="height: 320px;"></div>
                <small class="text-muted">Klik pada peta atau geser marker untuk menentukan koordinat.</small>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude"
                    placeholder="-6.200000" name="latitude" value="{{ old('latitude') }}" />
                  <label for="latitude">Latitude</label>
                  @error('latitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-floating form-floating-outline">
                  <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                    placeholder="106.816666" name="longitude" value="{{ old('longitude') }}" />
                  <label for="longitude">Longitude</label>
                  @error('longitude')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-6">
              <button type="reset" class="btn btn-outline-secondary">Reset</button>
              <button type="submit" class="btn btn-primary">Save Outlet</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
