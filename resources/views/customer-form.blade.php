@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-car"></i> Car Selection Form</h4>
          </div>

          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <form method="POST" action="{{ route('customer.store') }}">
              @csrf

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Full Name *</label>
                <div class="col-md-6">
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name" autofocus>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number *</label>
                <div class="col-md-6">
                  <input id="phone_number" type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                    name="phone_number" value="{{ old('phone_number') }}" required>
                  @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email Address *</label>
                <div class="col-md-6">
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required>
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">Complete Address *</label>
                <div class="col-md-6">
                  <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address"
                    rows="3" required>{{ old('address') }}</textarea>
                  @error('address')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Select Car Type(s) *</label>
                <div class="col-md-6">
                  <div class="card">
                    <div class="card-body">
                      @foreach($carOptions as $option)
                        <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" id="car_option_{{ $option->id }}"
                            name="car_options[]" value="{{ $option->name }}" {{ is_array(old('car_options')) && in_array($option->name, old('car_options')) ? 'checked' : '' }}>
                          <label class="form-check-label" for="car_option_{{ $option->id }}">
                            <i class="fas fa-car me-1"></i> {{ $option->name }}
                          </label>
                        </div>
                      @endforeach
                      @error('car_options')
                        <span class="text-danger" style="font-size: 0.875rem;">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Form
                  </button>
                  <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Back to Home
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection