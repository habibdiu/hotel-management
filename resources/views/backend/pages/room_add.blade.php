@extends('backend.includes.backend_layout')
@push('css')
@endpush
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class=" text-center mb-5">Room Add</h3>
                        @if (session('success'))
                            <div style="width:100%" class="alert alert-primary alert-dismissible fade show" role="alert">
                                <strong> Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="btn-close"></button>
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Failed!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="btn-close"></button>
                            </div>
                        @endif
                        <form action="{{ route('admin.room.add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="" class="form-label"> Room Name *</label>
                                            <input type="text" class="form-control" name="room_name"
                                            placeholder="Enter room Name" required>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Room Type / Category / Subcategory*</label>
                                            <select name="subcategory_id" id="room_hierarchy" class="form-control">
                                                <option value="">Select</option>
                                                @foreach ($data['roomTypes'] as $room_type)
                                                    @if(!$room_type->categories || $room_type->categories->isEmpty())
                                                        <option value="roomtype_{{ $room_type->id }}" data-full="{{ $room_type->room_type_name }}">
                                                            {{ $room_type->room_type_name }}
                                                        </option>
                                                    @else
                                                        <optgroup label="{{ $room_type->room_type_name }}">
                                                            @foreach($room_type->categories as $category)
                                                                @if(!$category->subcategories || $category->subcategories->isEmpty())
                                                                    <option value="category_{{ $category->id }}" data-full="{{ $room_type->room_type_name }} / {{ $category->category_name }}">
                                                                        &nbsp;&nbsp;{{ $category->category_name }}
                                                                    </option>
                                                                @else
                                                                    <optgroup label="{!! '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;— '.$category->category_name !!}">
                                                                        @foreach($category->subcategories as $subcategory)
                                                                            <option value="{{ $subcategory->id }}" data-full="{{ $room_type->room_type_name }} / {{ $category->category_name }} / {{ $subcategory->name }}">
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="form-label" for="">Price Per Night*</label>
                                            <input type="Number" name="price_per_night" class="form-control"
                                                placeholder="Enter price_per_night" min="0" required>   
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label" for="">Amenities</label>
                                            <input type="text" name="amenities" class="form-control"
                                                placeholder="Enter amenities">   
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label" for="">Floor Number</label>
                                            <input type="text" id="" name="floor_number" class="form-control"
                                                placeholder="Enter floor_number">   
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label">Your Photo</label>
                                                <input name="photo" class="form-control" type="file" id="imgPreview"
                                                    onchange="readpicture(this, '#imgPreviewId');">
                                            </div>
                                            <div class="text-center">
                                                <img id="imgPreviewId" onclick="image_upload()"
                                                    src="{{ asset('backend_assets/images/uploads_preview.png') }}">
                                            </div>                                    
                                        </div>


                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="ckEditorOne">Description</label>
                                    <textarea id="ckEditorOne" name="description" class="form-control" placeholder="Enter description" style="height:500px" ></textarea>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <button class="btn btn-xs btn-primary" type="submit">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
<script>
    $(document).ready(function(){

        // Image upload preview
        window.image_upload = function() {
            $('#imgPreview').trigger('click');
        }

        window.readpicture = function(input, preview_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(preview_id).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

    });
</script>
<script>
    const select = document.getElementById('room_hierarchy');

    select.addEventListener('change', function() {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption.value !== "") {
            // Update the visible text in the select to the full path
            selectedOption.text = selectedOption.getAttribute('data-full');
        }
    });
</script>
@endpush
