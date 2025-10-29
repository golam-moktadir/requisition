@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('company.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Show
</li>
@endsection

@section('content-body')
<style>
/* Smooth zoom hover effect */
.zoomable {
    transition: transform 0.3s ease;
}
.zoomable:hover {
    transform: scale(1.05);
}
</style>


<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" >
        <div class="row g-0 align-items-center p-3">
            <!-- Logo Section -->
            <div class="col-md-4 text-center mb-3 mb-md-0">
                @if($single->image)
                    <img src="{{ asset('storage/companies/' . $single->image) }}" 
                         alt="{{ $single->company_name }}"
                         class="img-fluid rounded shadow-sm border zoomable"
                         style="max-height: 200px; object-fit: contain; cursor: zoom-in;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm border"
                         style="height: 200px;">
                        <span class="text-muted">No Logo</span>
                    </div>
                @endif
            </div>

            <!-- Info Section -->
            <div class="col-md-8">
                <div class="card-body">
                    <h4 class="card-title mb-3 fw-semibold">{{ $single->company_name }}</h4>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Phone:</strong> {{ $single->phone }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $single->email }}</p>
                            <p class="mb-1"><strong>Website:</strong> 
                                @if($single->website)
                                    <a href="{{ $single->website }}" target="_blank" class="text-decoration-none">
                                        {{ $single->website }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Address:</strong> {{ $single->address ?? 'N/A' }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong> 
                                <span class="badge {{ $single->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $single->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('company.index') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Return Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Zoom Modal -->
<div class="modal fade" id="zoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2" data-bs-dismiss="modal"></button>
      <img id="zoomedImage" src="" alt="Zoomed Image" class="img-fluid rounded shadow-lg">
    </div>
  </div>
</div>






@endsection

@section('footerjs')



<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('zoomModal'));
    const zoomedImage = document.getElementById('zoomedImage');
    document.querySelectorAll('.zoomable').forEach(img => {
        img.addEventListener('click', function() {
            zoomedImage.src = this.src;
            modal.show();
        });
    });
});
</script>

@endsection
