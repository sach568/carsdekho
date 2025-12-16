@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Header Configuration</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.header.update') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Logo</label>
                @if($header && $header->logo)
                  <div class="mb-2">
                    <img src="{{ Storage::url($header->logo) }}" width="150" class="img-thumbnail">
                  </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
                <small class="text-muted">Upload logo image (Recommended: 200x60px)</small>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $header->phone ?? '' }}">
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $header->email ?? '' }}">
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Menu Items</label>
            <div id="menu-items-container">
              @if($header && $header->menu_items && is_array($header->menu_items))
                @foreach($header->menu_items as $index => $item)
                  <div class="row mb-2 menu-item">
                    <div class="col-md-5">
                      <input type="text" name="menu_items[{{ $index }}][name]" class="form-control" placeholder="Menu Text"
                        value="{{ $item['name'] ?? '' }}">
                    </div>
                    <div class="col-md-5">
                      <input type="text" name="menu_items[{{ $index }}][url]" class="form-control" placeholder="URL"
                        value="{{ $item['url'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-danger btn-sm remove-menu-item">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
            <button type="button" id="add-menu-item" class="btn btn-secondary btn-sm">
              <i class="fas fa-plus"></i> Add Menu Item
            </button>
          </div>

          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      // Safely get menu item count
      let menuItemIndex = 0;
      @if($header && $header->menu_items)
        @if(is_array($header->menu_items))
          menuItemIndex = {{ count($header->menu_items) }};
        @else
          menuItemIndex = 0;
        @endif
      @else
        menuItemIndex = 0;
      @endif

      document.getElementById('add-menu-item').addEventListener('click', function () {
        const container = document.getElementById('menu-items-container');
        const div = document.createElement('div');
        div.className = 'row mb-2 menu-item';
        div.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="menu_items[${menuItemIndex}][name]" 
                           class="form-control" placeholder="Menu Text">
                </div>
                <div class="col-md-5">
                    <input type="text" name="menu_items[${menuItemIndex}][url]" 
                           class="form-control" placeholder="URL">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-menu-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        container.appendChild(div);
        menuItemIndex++;
      });

      document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-menu-item')) {
          e.target.closest('.menu-item').remove();
        }
      });
    </script>
  @endpush
@endsection