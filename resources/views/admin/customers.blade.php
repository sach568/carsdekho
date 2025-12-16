@extends('layouts.admin')

@section('content')
  <div class="container-fluid">
    <h2 class="mb-4">Customer Management</h2>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card">
      <div class="card-body">
        @if($customers->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <p class="text-muted">No customers found.</p>
          </div>
        @else
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
                          <?php
                  // Safely decode car_options
                  $carOptions = $customer->car_options;
                  if (is_string($carOptions)) {
                    $carOptions = json_decode($carOptions, true) ?? [];
                  }
                                                                                                  ?>
                          <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone_number }}</td>
                            <td>
                              @if(!empty($carOptions) && is_array($carOptions))
                                @foreach($carOptions as $option)
                                  <span class="badge bg-primary mb-1">{{ $option }}</span><br>
                                @endforeach
                              @else
                                <span class="text-muted">No options selected</span>
                              @endif
                            </td>
                            <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                              <!-- FIXED: Added view-customer-btn class and data attributes -->
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

          <div class="mt-3">
            {{ $customers->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Customer Details Modal -->
  <div class="modal fade" id="customerDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Customer Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="customerDetailsContent">
          <!-- Dynamic content will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="emailCustomerBtn">
            <i class="fas fa-envelope"></i> Email Customer
          </button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        console.log('Customer page loaded');

        // Check if modal element exists
        const modalElement = document.getElementById('customerDetailsModal');
        if (!modalElement) {
          console.error('Modal element not found!');
          return;
        }

        // Initialize Bootstrap modal
        const customerDetailsModal = new bootstrap.Modal(modalElement);
        const customerDetailsContent = document.getElementById('customerDetailsContent');
        const emailCustomerBtn = document.getElementById('emailCustomerBtn');

        let currentCustomerEmail = '';

        // Get all view buttons
        const viewButtons = document.querySelectorAll('.view-customer-btn');
        console.log('Found view buttons:', viewButtons.length);

        viewButtons.forEach(button => {
          button.addEventListener('click', function () {
            console.log('View button clicked');

            try {
              // Get data attributes
              const customerId = this.getAttribute('data-customer-id');
              const customerName = this.getAttribute('data-customer-name');
              const customerEmail = this.getAttribute('data-customer-email');
              const customerPhone = this.getAttribute('data-customer-phone');
              const customerAddress = this.getAttribute('data-customer-address');
              const carOptionsJSON = this.getAttribute('data-car-options');
              const submittedAt = this.getAttribute('data-submitted-at');

              console.log('Customer ID:', customerId);

              let carOptions = [];
              try {
                carOptions = JSON.parse(carOptionsJSON || '[]');
              } catch (e) {
                console.error('Error parsing car options:', e);
              }

              currentCustomerEmail = customerEmail;

              // modal content
              let carOptionsHtml = '';
              if (Array.isArray(carOptions) && carOptions.length > 0) {
                carOptions.forEach(option => {
                  carOptionsHtml += `<span class="badge bg-primary me-2 mb-2 p-2">${option}</span>`;
                });
              } else {
                carOptionsHtml = '<span class="text-muted">No car options selected</span>';
              }

              const content = `
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Customer ID:</label>
                                                    <p class="form-control-plaintext">${customerId}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Submitted:</label>
                                                    <p class="form-control-plaintext">${submittedAt}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Name:</label>
                                                    <p class="form-control-plaintext">${customerName}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Email:</label>
                                                    <p class="form-control-plaintext">${customerEmail}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Phone:</label>
                                                    <p class="form-control-plaintext">${customerPhone}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Address:</label>
                                            <p class="form-control-plaintext border p-2 rounded">${customerAddress}</p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Car Options Selected:</label>
                                            <div class="border p-3 rounded">
                                                ${carOptionsHtml}
                                            </div>
                                        </div>
                                    `;

              customerDetailsContent.innerHTML = content;
              customerDetailsModal.show();

            } catch (error) {
              console.error('Error showing customer details:', error);
              alert('Error loading customer details');
            }
          });
        });

        // Email button functionality
        if (emailCustomerBtn) {
          emailCustomerBtn.addEventListener('click', function () {
            if (currentCustomerEmail) {
              window.location.href = `mailto:${currentCustomerEmail}`;
            } else {
              alert('No customer email available');
            }
          });
        }

        // modal trigger
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
          if (!button.classList.contains('view-customer-btn')) {
            button.addEventListener('click', function () {
              const modalId = this.getAttribute('data-bs-target');
              console.log('Opening old modal:', modalId);
            });
          }
        });
      });
    </script>
  @endpush
@endsection