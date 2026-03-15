@php
    $configData = Helper::appClasses();
@endphp
<!-- Create App Modal -->
<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-simple modal-upgrade-plan">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center">
                    <h4 class="mb-2">Create Business</h4>
                    <p class="mb-6">Provide data with this form to create your business.</p>
                </div>
                <!-- Property Listing Wizard -->
                <div id="wizard-create-app" class="bs-stepper vertical wizard-vertical-icons mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#database">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-3">
                                        <i class="icon-base ri ri-pie-chart-2-line icon-24px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-4">
                                    <span class="bs-stepper-title text-uppercase">Type</span>
                                    <small class="bs-stepper-subtitle">Select Business Type</small>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#details">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-3">
                                        <i class="icon-base ri ri-file-text-line icon-24px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-4">
                                    <span class="bs-stepper-title text-uppercase">Details</span>
                                    <small class="bs-stepper-subtitle">Enter Details</small>
                                </span>
                            </button>
                        </div>


                        <div class="step" data-target="#billing">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-3">
                                        <i class="icon-base ri ri-bank-card-line icon-24px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-4">
                                    <span class="bs-stepper-title text-uppercase">License</span>
                                    <small class="bs-stepper-subtitle">Select License Type</small>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#submit">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-3">
                                        <i class="icon-base ri ri-check-double-line icon-24px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-4">
                                    <span class="bs-stepper-title text-uppercase">Submit</span>
                                    <small class="bs-stepper-subtitle">Submit</small>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <form id="createBusinessForm" action="{{ route('business.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Details -->
                            <div id="database" class="content pt-4 pt-lg-0">

                                <h5>Type of bussiness</h5>
                                <ul class="p-0 m-0">
                                    <li class="d-flex align-items-center mb-4">
                                        <div
                                            class="avatar avatar-md bg-label-info d-flex align-items-center justify-content-center flex-shrink-0 me-4 rounded-3">
                                            <i class="icon-base ri ri-drinks-line icon-30px"></i>
                                        </div>
                                        <div class="d-flex justify-content-between w-100">
                                            <div class="me-2">
                                                <h6 class="mb-0">Food & Beverage</h6>
                                                <small>Restaurants, Cafes, Food Trucks</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-check-inline me-0">
                                                    <input name="type" class="form-check-input" type="radio"
                                                        value="fnb" checked="checked" />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-center mb-4">
                                        <div
                                            class="avatar avatar-md bg-label-success d-flex align-items-center justify-content-center flex-shrink-0 me-4 rounded-3">
                                            <i class="icon-base ri ri-t-shirt-air-line icon-30px"></i>
                                        </div>
                                        <div class="d-flex justify-content-between w-100">
                                            <div class="me-2">
                                                <h6 class="mb-0">Laundry Service <small class="text-warning">(Coming
                                                        Soon)</small></h6>
                                                <small>Professional laundry services</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-check-inline me-0">
                                                    <input name="type" class="form-check-input" type="radio"
                                                        value="laundry" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div
                                            class="avatar avatar-md bg-label-danger d-flex align-items-center justify-content-center flex-shrink-0 me-4 rounded-3">
                                            <i class="icon-base ri ri-shopping-cart-line icon-30px"></i>
                                        </div>
                                        <div class="d-flex justify-content-between w-100">
                                            <div class="me-2">
                                                <h6 class="mb-0">Retail Store <small class="text-warning">(Coming
                                                        Soon)</small></h6>
                                                <small>Manage your retail business</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="form-check form-check-inline me-0">
                                                    <input name="type" class="form-check-input" type="radio"
                                                        value="retail" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button type="button" class="btn btn-outline-secondary btn-prev" disabled>
                                        <i class="icon-base ri ri-arrow-left-line icon-16px"></i>
                                        <span class="align-middle d-sm-block d-none ms-2">Previous</span>
                                    </button>
                                        <button type="button" class="btn btn-primary btn-next"><span
                                            class="align-middle d-sm-block d-none me-2">Next</span> <i
                                            class="icon-base ri ri-arrow-right-line icon-16px"></i></button>
                                </div>
                            </div>

                            <!-- Database -->
                            <div id="details" class="content pt-4 pt-lg-0">
                                <div class="text-center mb-6">
                                    <img id="modalBusinessLogoPreview" src="{{ asset('assets/img/uploadlogo.jpg') }}"
                                        alt="Business Logo Preview" class="rounded-circle border"
                                        style="width: 120px; height: 120px; object-fit: cover;" />
                                    <div class="mt-3 d-flex justify-content-center gap-2">
                                        <label for="modalBusinessLogo" class="btn btn-outline-primary btn-sm">
                                            Upload Logo
                                        </label>
                                        <button type="button" id="modalBusinessLogoReset"
                                            class="btn btn-outline-secondary btn-sm">
                                            Reset
                                        </button>
                                    </div>
                                    <input type="file" id="modalBusinessLogo" name="logo" class="d-none"
                                        accept="image/jpeg,image/png,image/webp" />
                                    <small class="d-block text-muted mt-2">JPG, PNG, WEBP (maks. 5MB)</small>
                                </div>
                                <div class="form-floating form-floating-outline mb-6">
                                    <input type="text" class="form-control form-control-lg" id="modalAppName"
                                        name="name"
                                        placeholder="Business Name" />
                                    <label for="modalAppName">Business Name</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-6">
                                    <textarea class="form-control form-control-lg" id="modalAppDescription" name="description" placeholder="Business Description"
                                        style="height: 100px"></textarea>
                                    <label for="modalAppName">Business Description</label>
                                </div>


                                <div class="col-12 d-flex justify-content-between mt-6">
                                        <button type="button" class="btn btn-outline-secondary btn-prev"><i
                                            class="icon-base ri ri-arrow-left-line icon-16px"></i> <span
                                            class="align-middle d-sm-block d-none ms-2">Previous</span></button>
                                        <button type="button" class="btn btn-primary btn-next"><span
                                            class="align-middle d-sm-block d-none me-2">Next</span> <i
                                            class="icon-base ri ri-arrow-right-line icon-16px"></i></button>
                                </div>
                            </div>

                            <!-- billing -->
                            <div id="billing" class="content">
                                <div id="AppNewCCForm" class="row g-5 pt-4 pt-lg-6 mb-6" onsubmit="return false">
                                    <div class="col-12">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="modalLicenseSelect" name="license_id"
                                                aria-label="Select license type">
                                                @foreach ($licenses as $license)
                                                    <option value="{{ $license->id }}"
                                                        data-price="{{ (float) $license->price }}">
                                                        {{ $license->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="modalLicenseSelect">Select License</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="licenseBillingCycleWrapper">
                                        <div class="form-floating form-floating-outline">
                                            <select class="form-select" id="modalBillingCycleSelect" name="billing_cycle"
                                                aria-label="Select billing cycle">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="yearly">Yearly</option>
                                            </select>
                                            <label for="modalBillingCycleSelect">Select Billing Cycle</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <h6 class="mb-3">Payment Summary</h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-body">License</span>
                                                    <span id="summaryLicenseName" class="fw-medium">-</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-body">Price</span>
                                                    <span id="summaryLicensePrice" class="fw-medium">Rp 0</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-body">Billing Cycle</span>
                                                    <span id="summaryBillingCycle" class="fw-medium">-</span>
                                                </div>
                                                <hr class="my-3" />
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-heading fw-medium">Total</span>
                                                    <span id="summaryTotalPrice" class="text-heading fw-semibold">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button type="button" class="btn btn-outline-secondary btn-prev"><i
                                            class="icon-base ri ri-arrow-left-line icon-16px"></i> <span
                                            class="align-middle d-sm-block d-none ms-2">Previous</span></button>
                                    <button type="button" class="btn btn-primary btn-next"><span
                                            class="align-middle d-sm-block d-none me-2">Next</span> <i
                                            class="icon-base ri ri-arrow-right-line icon-16px"></i></button>
                                </div>
                            </div>

                            <!-- submit -->
                            <div id="submit" class="content text-center pt-4 pt-lg-0">
                                <h5 class="mb-1 mt-4">Submit</h5>
                                <p class="small">Submit to kick start your project.</p>
                                <!-- image -->
                                <img src="{{ asset('assets/img/illustrations/create-app-modal-illustration-' . $configData['theme'] . '.png') }}"
                                    alt="Create App img" width="265" class="img-fluid"
                                    data-app-light-img="illustrations/create-app-modal-illustration-light.png"
                                    data-app-dark-img="illustrations/create-app-modal-illustration-dark.png" />
                                <div class="col-12 d-flex justify-content-between mt-4 pt-2">
                                    <button type="button" class="btn btn-outline-secondary btn-prev"><i
                                            class="icon-base ri ri-arrow-left-line icon-16px"></i> <span
                                            class="align-middle d-none d-sm-block ms-2">Previous</span></button>
                                    <button type="button" class="btn btn-success btn-submit" id="createBusinessSubmitBtn">
                                        <span class="submit-btn-content d-inline-flex align-items-center">
                                            <span class="align-middle d-none d-sm-block me-2">Submit</span>
                                            <i class="icon-base ri ri-check-line icon-18px"></i>
                                        </span>
                                        <span class="submit-btn-loading d-none d-inline-flex align-items-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"
                                                aria-hidden="true"></span>
                                            <span>Submitting...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Property Listing Wizard -->
        </div>
    </div>
</div>
<!--/ Create App Modal -->
