@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Most Searched Cars</h2>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
        <i class="fas fa-plus"></i> Add New Car
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
        @if($cars->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-car fa-3x text-muted mb-3"></i>
            <p class="text-muted">No cars found. Add your first car!</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Model</th>
                  <th>Price</th>
                  <th>Searches</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cars as $car)
                  <tr>
                    <td>
                      <img src="{{ Storage::url($car->image) }}" width="80" class="img-thumbnail">
                    </td>
                    <td>{{ $car->name }}</td>
                    <td>{{ $car->model }}</td>
                    <td>₹{{ number_format($car->price) }}</td>
                    <td>{{ $car->search_count }}</td>
                    <td>
                      <span class="badge bg-{{ $car->active ? 'success' : 'danger' }}">
                        {{ $car->active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                          data-bs-target="#editCarModal{{ $car->id }}">
                          <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.most-searched-cars.destroy', $car->id) }}" method="POST"
                          class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>

                  <!-- Edit Car Modal -->
                  <div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form action="{{ route('admin.most-searched-cars.update', $car->id) }}" method="POST"
                          enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Car</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <label>Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ $car->name }}" required>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label>Model *</label>
                                <input type="text" name="model" class="form-control" value="{{ $car->model }}" required>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6 mb-3">
                                <label>Price *</label>
                                <input type="number" name="price" class="form-control" step="0.01" value="{{ $car->price }}"
                                  required>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label>Search Count</label>
                                <input type="number" name="search_count" class="form-control"
                                  value="{{ $car->search_count }}">
                              </div>
                            </div>
                            <div class="mb-3">
                              <label>Current Image</label><br>
                              <img src="{{ Storage::url($car->image) }}" width="150" class="mb-2">
                              <input type="file" name="image" class="form-control">
                              <small class="text-muted">Leave empty to keep current image</small>
                            </div>
                            <div class="mb-3">
                              <label>Status</label>
                              <select name="active" class="form-control">
                                <option value="1" {{ $car->active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$car->active ? 'selected' : '' }}>Inactive</option>
                              </select>
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

  <!-- Add Car Modal -->
  <div class="modal fade" id="addCarModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('admin.most-searched-cars.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Car</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Model *</label>
                <input type="text" name="model" class="form-control" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Price *</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Search Count</label>
                <input type="number" name="search_count" class="form-control" value="0">
              </div>
            </div>
            <div class="mb-3">
              <label>Image *</label>
              <input type="file" name="image" class="form-control" accept="image/*" required>
              <small class="text-muted">Recommended size: 400x300px</small>
            </div>
            <div class="mb-3">
              <label>Status</label>
              <select name="active" class="form-control">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Car</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection