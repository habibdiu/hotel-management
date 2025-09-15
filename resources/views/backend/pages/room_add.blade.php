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
                                            <label for="" class="form-label">Room Type*</label>
                                            <Select class="form-select" name="room_type" id="" required>
                                                <option value="">---Select---</option>
                                                <option value="single">Single</option>
                                                <option value="double">Double</option>
                                                <option value="suite">Suite</option>
                                                <option value="deluxe">Deluxe</option>
                                            </Select>
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
        function image_upload() {

            $('#imgPreview').trigger('click');
        }

        function readpicture(input, preview_id) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(preview_id)
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }

        }
    </script>
@endpush