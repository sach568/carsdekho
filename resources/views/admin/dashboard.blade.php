@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card text-white bg-primary">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="card-title">Banners</h6>
                <h2>{{ $stats['banners'] }}</h2>
              </div>
              <i class="fas fa-image fa-3x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card text-white bg-success">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="card-title">Most Searched Cars</h6>
                <h2>{{ $stats['most_searched_cars'] }}</h2>
              </div>
              <i class="fas fa-search fa-3x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card text-white bg-warning">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="card-title">Latest Cars</h6>
                <h2>{{ $stats['latest_cars'] }}</h2>
              </div>
              <i class="fas fa-car fa-3x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card text-white bg-info">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="card-title">Customers</h6>
                <h2>{{ $stats['customers'] }}</h2>
              </div>
              <i class="fas fa-users fa-3x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Customers -->
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Recent Customers</h5>
          </div>
          <div class="card-body">
            @if($stats['recent_customers']->count() > 0)
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Car Options</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($stats['recent_customers'] as $customer)
                      <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone_number }}</td>
                        <td>
                          @if($customer->car_options && is_array($customer->car_options))
                            @foreach($customer->car_options as $option)
                              <span class="badge bg-primary mb-1">{{ $option }}</span>
                            @endforeach
                          @elseif($customer->car_options)
                            <span class="badge bg-secondary">Data format issue</span>
                          @else
                            <span class="text-muted">No options selected</span>
                          @endif
                        </td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">No customers yet</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="list-group">
              <a href="{{ route('admin.banners') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-plus me-2"></i> Add New Banner
              </a>
              <a href="{{ route('admin.most-searched-cars') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-plus me-2"></i> Add Most Searched Car
              </a>
              <a href="{{ route('admin.latest-cars') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-plus me-2"></i> Add Latest Car
              </a>
              <a href="{{ route('admin.header') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-cog me-2"></i> Update Header
              </a>
              <a href="{{ route('admin.footer') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-cog me-2"></i> Update Footer
              </a>
              <a href="{{ route('admin.customers') }}" class="list-group-item list-group-item-action">
                <i class="fas fa-eye me-2"></i> View All Customers
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection