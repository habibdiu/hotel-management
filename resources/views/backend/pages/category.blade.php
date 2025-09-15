@extends('backend.includes.backend_layout')
@push('css')
@endpush

@section('content')
<div class="page-content">

    {{-- Add Room Category Card --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h6 class="mb-0">Add Room Category</h6>
                </div>
                <div class="card-body">
                    {{-- Alerts --}}
                    <x-alert type="success" :message="session('success')" :timeout="2000" />
                    <x-alert type="warning" :message="session('error')" :timeout="2000" />

                    
                    <form action="{{ route('admin.room.category') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="room_type_id" class="form-label">Select Room Type*</label>
                                <select class="form-select" name="room_type_id" id="room_type_id" required>
                                    <option value="">Choose Type</option>
                                    @foreach($data['room_types'] as $room_type)
                                        <option value="{{ $room_type->id }}">{{ $room_type->room_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label for="category_name" class="form-label">Room Category*</label>
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    placeholder="ex: Deluxe | Superior | Executive" required>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Add</button>
                            </div>
                        </div>
                        

                        @error('category_name')
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
                    <h6 class="mb-0">All Room Category</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Room Type</th>
                                    <th>Room Category</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['categories'] as $index => $category)
                                <tr class="text-center">
                                    <td>{{ $data['categories']->firstItem() + $index }}</td>
                                    <td>{{ $category->room_type ? $category->room_type->room_type_name : '-' }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->created_at->format('d-m-Y') }}</td>
                                    <td>
                                    
                                        <a href="{{ route('admin.room.category.update', $category->id) }}" class="btn btn-success btn-icon" data-bs-toggle="modal" data-bs-target="#roomCategoryEditModal{{ $category->id }}">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.room.category.delete', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="roomCategoryEditModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                        <div class="d-flex justify-content-end p-2 border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('admin.room.category.update', $category->id) }}" method="POST">
                                                @csrf                                               

                                                <div class="mb-3">
                                                    <label for="room_type_id{{ $category->id }}" class="form-label">Select Room Type*</label>
                                                    <select class="form-select" name="room_type_id" id="room_type_id{{ $category->id }}" required>
                                                        <option value="">Choose Type</option>
                                                        @foreach($data['room_types'] as $room_type)
                                                            <option value="{{ $room_type->id }}" 
                                                                {{ $category->room_type_id == $room_type->id ? 'selected' : '' }}>
                                                                {{ $room_type->room_type_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="category_name{{ $category->id }}" class="form-label">Room Category*</label>
                                                    <input type="text" 
                                                        class="form-control @error('category_name') is-invalid @enderror"
                                                        id="category_name{{ $category->id }}"
                                                        name="category_name"
                                                        value="{{ old('category_name', $category->category_name) }}"
                                                        placeholder="ex: Single | Double | Family"
                                                        required>
                                                    @error('category_name')
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $data['categories']->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
