@extends('backend.includes.backend_layout')
@push('css')
@endpush

@section('content')
<div class="page-content">

    {{-- Add Room Sub subCategory Card --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h6 class="mb-0">Add Room Subsubcategory</h6>
                </div>
                <div class="card-body">
                    {{-- Alerts --}}
                    <x-alert type="success" :message="session('success')" :timeout="2000" />
                    <x-alert type="warning" :message="session('error')" :timeout="2000" />

                    
                    <form action="{{ route('admin.room.subcategory') }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="room_type_id" class="form-label">Select Room Type*</label>
                                <select class="form-select" name="room_type_id" id="room_type_id" required>
                                    <option value=""> </option>
                                    @foreach($data['room_types'] as $room_type)
                                        <option value="{{ $room_type->id }}">{{ $room_type->room_type_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label for="category_id" class="form-label">Select Category*</label>
                                <select class="form-select" name="category_id" id="category_id" required>
                                    <option value=""> </option>
                                    @foreach($data['categories'] as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label for="sub_category_name" class="form-label">Sub Category*</label>
                                <input type="text" class="form-control" id="sub_category_name" name="sub_category_name"
                                    placeholder="ex: Sea View | Twin | Hill View" required>
                            </div>


                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Add</button>
                            </div>
                        </div>
                        

                        @error('subcategory_name')
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
                    <h6 class="mb-0">All Room Sub subCategory</h6>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Room Type</th>
                                    <th>Room Category</th>
                                    <th>Room Subcategory</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['subcategories'] as $index => $subcategory)
                                <tr class="text-center">
                                    <td>{{ $data['subcategories']->firstItem() + $index }}</td>
                                    <td>{{ $subcategory->category && $subcategory->category->room_type ?$subcategory->category->room_type->room_type_name : '-' }}</td>
                                    <td>{{ $subcategory->category ? $subcategory->category->category_name : '-' }}</td>
                                    <td>{{ $subcategory->name }}</td>
                                    <td>{{ $subcategory->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.room.subcategory.update', $subcategory->id) }}" class="btn btn-success btn-icon" data-bs-toggle="modal" data-bs-target="#roomsubCategoryEditModal{{ $subcategory->id }}">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.room.subcategory.delete', $subcategory->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>


                                <div class="modal fade" id="roomsubCategoryEditModal{{ $subcategory->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                        <div class="d-flex justify-content-end p-2 border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('admin.room.subcategory.update', $subcategory->id) }}" method="POST">
                                                @csrf
                                                <div class="col-md-5">
                                                    <label for="room_type_id{{ $subcategory->id }}" class="form-label">Select Room Type*</label>
                                                    <select class="form-select" name="room_type_id" id="room_type_id{{ $subcategory->id }}" required>
                                                        <option value=""> </option>
                                                        @foreach($data['room_types'] as $room_type)
                                                            <option value="{{ $room_type->id }}" 
                                                                {{ $subcategory->category && $subcategory->category->room_type_id == $room_type->id ? 'selected' : '' }}>
                                                                {{ $room_type->room_type_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="category_id{{ $subcategory->id }}" class="form-label">Select Category*</label>
                                                    <select class="form-select" name="category_id" id="category_id{{ $subcategory->id }}" required>
                                                        <option value=""> </option>
                                                        @foreach($data['categories'] as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="sub_category_name{{ $subcategory->id }}" class="form-label">Sub Category*</label>
                                                    <input type="text" class="form-control" 
                                                        id="sub_category_name{{ $subcategory->id }}" 
                                                        name="sub_category_name"
                                                        value="{{ old('sub_category_name', $subcategory->name) }}"
                                                        placeholder="ex: Sea View | Twin | Hill View" 
                                                        required>
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
                        {{ $data['subcategories']->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection