@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Manage Banners</h2>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
        <i class="fas fa-plus"></i> Add New Banner
      </button>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        @if($banners->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-image fa-3x text-muted mb-3"></i>
            <p class="text-muted">No banners found. Add your first banner!</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($banners as $banner)
                  <tr>
                    <td>
                      <img src="{{ Storage::url($banner->image) }}" width="100" class="img-thumbnail">
                    </td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->order }}</td>
                    <td>
                      <span class="badge bg-{{ $banner->active ? 'success' : 'danger' }}">
                        {{ $banner->active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                          data-bs-target="#editBannerModal{{ $banner->id }}">
                          <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this banner?')">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <!-- Edit Banner Modal -->
                  <div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST"
                          enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Banner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <label>Title</label>
                              <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                            </div>
                            <div class="mb-3">
                              <label>Description</label>
                              <textarea name="description" class="form-control" rows="3">{{ $banner->description }}</textarea>
                            </div>
                            <div class="mb-3">
                              <label>Current Image</label><br>
                              <img src="{{ Storage::url($banner->image) }}" width="150" class="mb-2">
                              <input type="file" name="image" class="form-control">
                              <small class="text-muted">Leave empty to keep current image</small>
                            </div>
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <label>Order</label>
                                <input type="number" name="order" class="form-control" value="{{ $banner->order }}">
                              </div>
                              <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="active" class="form-control">
                                  <option value="1" {{ $banner->active ? 'selected' : '' }}>Active</option>
                                  <option value="0" {{ !$banner->active ? 'selected' : '' }}>Inactive</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Add Banner Modal -->
  <div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Banner</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label>Title *</label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label>Image *</label>
              <input type="file" name="image" class="form-control" accept="image/*" required>
              <small class="text-muted">Recommended size: 1200x400px</small>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Order</label>
                <input type="number" name="order" class="form-control" value="0">
              </div>
              <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="active" class="form-control">
                  <option value="1" selected>Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Banner</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection