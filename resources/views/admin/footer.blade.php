@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Footer Configuration</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.footer.update') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Logo</label>
                @if($footer && $footer->logo)
                  <div class="mb-2">
                    <img src="{{ Storage::url($footer->logo) }}" width="150" class="img-thumbnail">
                  </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Upload footer logo</small>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $footer->description ?? '' }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="2">{{ $footer->address ?? '' }}</textarea>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ $footer->phone ?? '' }}">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ $footer->email ?? '' }}">
            </div>
          </div>

          <h5 class="mt-4 mb-3">Social Links</h5>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Facebook URL</label>
              <input type="url" name="facebook_url" class="form-control"
                value="{{ $footer && $footer->social_links && isset($footer->social_links[0]) ? $footer->social_links[0]['url'] ?? '' : '' }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Twitter URL</label>
              <input type="url" name="twitter_url" class="form-control"
                value="{{ $footer && $footer->social_links && isset($footer->social_links[1]) ? $footer->social_links[1]['url'] ?? '' : '' }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Instagram URL</label>
              <input type="url" name="instagram_url" class="form-control"
                value="{{ $footer && $footer->social_links && isset($footer->social_links[2]) ? $footer->social_links[2]['url'] ?? '' : '' }}">
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
@endsection