@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <h2 class="mb-4">Customer Management</h2>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">

        {{-- If no customers --}}
        @if($customers->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">No customers found.</p>
          </div>
        @else

          {{-- Customer Table --}}
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Car Options</th>
                  <th>Submitted At</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody>
                @foreach($customers as $customer)
                  @php
                    // Decode car options safely
                    $carOptions = $customer->car_options;
                    if (is_string($carOptions)) {
                      $carOptions = json_decode($carOptions, true) ?? [];
                    }
                  @endphp

                  <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone_number }}</td>

                    {{-- Car Options --}}
                    <td>
                      @if(!empty($carOptions))
                        @foreach($carOptions as $option)
                          <span class="badge bg-primary mb-1">{{ $option }}</span><br>
                        @endforeach
                      @else
                        <span class="text-muted">No options selected</span>
                      @endif
                    </td>

                    <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>

                    {{-- Actions --}}
                    <td>
                      <button type="button" class="btn btn-sm btn-info view-customer-btn"
                        data-customer-id="{{ $customer->id }}" data-customer-name="{{ htmlspecialchars($customer->name) }}"
                        data-customer-email="{{ $customer->email }}" data-customer-phone="{{ $customer->phone_number }}"
                        data-customer-address="{{ htmlspecialchars($customer->address) }}"
                        data-car-options="{{ json_encode($carOptions) }}"
                        data-submitted-at="{{ $customer->created_at->format('d M Y, h:i A') }}">
                        <i class="fas fa-eye"></i> View
                      </button>

                      <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this customer?')">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- Pagination --}}
          <div class="mt-3">
            {{ $customers->links() }}
          </div>

        @endif
      </div>
    </div>
  </div>

  {{-- Customer Details Modal --}}
  <div class="modal fade" id="customerDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Customer Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" id="customerDetailsContent">
          {{-- Dynamic content --}}
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-primary" id="emailCustomerBtn">
            <i class="fas fa-envelope"></i> Email Customer
          </button>
        </div>

      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {

        const modalEl = document.getElementById('customerDetailsModal');
        const modal = new bootstrap.Modal(modalEl);
        const contentBox = document.getElementById('customerDetailsContent');
        const emailBtn = document.getElementById('emailCustomerBtn');

        let customerEmail = '';

        document.querySelectorAll('.view-customer-btn').forEach(btn => {
          btn.addEventListener('click', function () {

            const carOptions = JSON.parse(this.dataset.carOptions || '[]');
            customerEmail = this.dataset.customerEmail;

            let optionsHtml = '<span class="text-muted">No car options selected</span>';
            if (carOptions.length) {
              optionsHtml = carOptions.map(opt =>
                `<span class="badge bg-primary me-2 mb-2 p-2">${opt}</span>`
              ).join('');
            }

            contentBox.innerHTML = `
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>ID:</strong> ${this.dataset.customerId}</div>
                        <div class="col-md-6"><strong>Submitted:</strong> ${this.dataset.submittedAt}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Name:</strong> ${this.dataset.customerName}</div>
                        <div class="col-md-6"><strong>Email:</strong> ${this.dataset.customerEmail}</div>
                    </div>

                    <div class="mb-2"><strong>Phone:</strong> ${this.dataset.customerPhone}</div>
                    <div class="mb-2"><strong>Address:</strong><div class="border p-2 rounded">${this.dataset.customerAddress}</div></div>

                    <div>
                        <strong>Car Options:</strong>
                        <div class="border p-3 rounded mt-1">${optionsHtml}</div>
                    </div>
                `;

            modal.show();
          });
        });

        emailBtn.addEventListener('click', function () {
          if (customerEmail) {
            window.location.href = `mailto:${customerEmail}`;
          }
        });
      });
    </script>
  @endpush
@endsection