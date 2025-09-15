@extends('backend.includes.backend_layout')
@push('css')
@endpush
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class=" text-center mb-5">Room Edit</h3>
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
                            <form action="{{ route('admin.room.edit',$data['room']->id) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="" class="form-label"> Room Name *</label>
                                                <input type="text" class="form-control" name="room_name"
                                                    value="{{$data['room']->room_name}}" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="">Price Per Night*</label>
                                                <input type="Number" name="price_per_night" class="form-control"
                                                    placeholder="Enter price_per_night" value="{{$data['room']->price_per_night}}" min="0" required>   
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="">Amenities</label>
                                                <input type="text" name="amenities" class="form-control"
                                                    placeholder="Enter amenities" value="{{$data['room']->amenities}}">   
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="">Floor Number</label>
                                                <input type="text" name="floor_number" class="form-control"
                                                    placeholder="Enter floor_number" value="{{$data['room']->floor_number}}">   
                                            </div>

                                            <div class="col-md-6">
                                                <label for="room_type" class="form-label">Room Type*</label>
                                                <Select class="form-select" name="room_type" id="" required>
                                                    <option value="">---Select---</option>
                                                    <option value="single"{{$data['room']->room_type=='single'?'selected':''}}>Single</option>
                                                    <option value="double"{{$data['room']->room_type=='double'?'selected':''}}>Double</option>
                                                    <option value="suite"{{$data['room']->room_type=='suite'?'selected':''}}>Suite</option>
                                                    <option value="deluxe"{{$data['room']->room_type=='deluxe'?'selected':''}}>Deluxe</option>
                                                </Select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Photo</label>
                                                    <input name="photo" class="form-control" type="file" id="imgPreview"
                                                        onchange="readpicture(this, '#imgPreviewId');">
                                                </div>
                                                <div class="text-center">
                                                    @php
                                                        $photoPath = $data['room']->photo;
                                                        $photoUrl = $photoPath && file_exists(public_path($photoPath)) ? asset($photoPath) : asset('backend_assets/images/uploads_preview.png');
                                                    @endphp

                                                    <img id="imgPreviewId"
                                                        src="{{ $photoUrl }}"
                                                        style="max-width: 200px; max-height: 200px; border:1px solid #ccc; padding:4px; border-radius:6px;"
                                                        alt="Room Photo Preview"
                                                        onclick="image_upload()" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="description" class="form-label">Description</label>
                                    <textarea id="ckEditorOne" name="description" class="form-control">{{ $data['room']->description }}</textarea>
                                    </div>

                                </div>

                                <div class="text-center mt-2">
                                    <button class="btn btn-xs btn-primary" type="submit">Update</button>
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