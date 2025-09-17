@extends('backend.includes.backend_layout')
@push('css')
@endpush

@section('content')
<div class="page-content">

    {{-- Add Room Type Card --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h6 class="mb-0">Add Room Type</h6>
                </div>
                <div class="card-body">
                    {{-- Alerts --}}
                    <x-alert type="success" :message="session('success')" :timeout="2000" />
                    <x-alert type="warning" :message="session('error')" :timeout="2000" />

                    
                    <form action="{{ route('admin.room.type') }}" method="POST">
                        @csrf
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="room_type_name" class="form-label">Room Type*</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="room_type_name" name="room_type_name"
                                    placeholder="ex: Deluxe | Superior | Executive etc" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                        @error('room_type_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </form>

                </div>
            </div>
        </div>
    </div>

    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h6 class="mb-0">All Room Types</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Room Type</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['room_types'] as $index => $room_type)
                                <tr class="text-center">
                                    <td>{{ $data['room_types']->firstItem() + $index }}</td>
                                    <td>{{ $room_type->room_type_name }}</td>
                                    <td>{{ $room_type->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                    
                                        <a href="{{ route('admin.room.type.update', $room_type->id) }}" class="btn btn-success btn-icon" data-bs-toggle="modal" data-bs-target="#roomTypeEditModal{{ $room_type->id }}">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.room.type.delete', $room_type->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Room Type Modal -->
                                <div class="modal fade" id="roomTypeEditModal{{ $room_type->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">

                                    <div class="modal-content">

                                    <div class="d-flex justify-content-end p-2 border-0">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="{{ route('admin.room.type.update', $room_type->id) }}" method="POST">
                                            @csrf                                               
                                            <div class="mb-3">
                                                <label for="room_type_name{{ $room_type->id }}" class="form-label">Room Type*</label>
                                                <input type="text" 
                                                    class="form-control @error('room_type_name') is-invalid @enderror"
                                                    id="room_type_name{{ $room_type->id }}"
                                                    name="room_type_name"
                                                    value="{{ old('room_type_name', $room_type->room_type_name) }}"
                                                    placeholder="ex: Deluxe | Superior | Executive etc" 
                                                    required>
                                                @error('room_type_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>

                                        </form>
                                    </div>

                                    </div>
                                </div>
                                <!-- End Edit Room Type Modal -->
                                </div>
                                @endforeach
                                </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $data['room_types']->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
