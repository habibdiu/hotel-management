@extends('backend.includes.backend_layout')

@push('css')
<link rel="stylesheet" href="{{ asset('backend_assets/css/ckeditor.css') }}">

<style>
    textarea.form-control {
        vertical-align: top;
        padding-top: 0.5rem;
    }

    input.form-control,
    textarea.form-control {
        font-weight: bold;
        border-color: #7a7878;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class=" text-center mb-2">Message Edit</h3>

                    @if (session('success'))
                        <div style="width:100%" class="alert alert-primary alert-dismissible fade show" role="alert">
                            <strong> Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Failed!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.message.edit', $data['message']->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="" class="form-label"> Name </label>
                                <input type="text" class="form-control" name="name" value="{{ $data['message']->name }}" placeholder="ex: Mr. Rahman">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label"> Titile *</label>
                                <input type="text" class="form-control" name="title" value="{{ $data['message']->title }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label"> Position </label>
                                <input type="text" class="form-control" name="position" value="{{ $data['message']->position }}">
                            </div>
                            <div class="col-md-3">
                                <label for="" class="form-label"> Company </label>
                                <input type="text" class="form-control" name="company" value="{{ $data['message']->company }}" placeholder="ex: Name of your company">
                            </div>

                            <div class="col-md-3">
                                <label for="" class="form-label"> Priority *</label>
                                <input type="number" class="form-control" name="priority" min="1" value="{{ $data['message']->priority }}" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Your Photo</label>
                                    <input name="photo" class="form-control" type="file" id="imgPreview"
                                        onchange="readpicture(this, '#imgPreviewId');">
                                </div>
                                <div class="text-center">
                                    @php
                                        $photoPath = $data['message']->photo;
                                        $photoUrl = $photoPath && file_exists(public_path($photoPath)) ? asset($photoPath) : asset('backend_assets/images/uploads_preview.png');
                                    @endphp

                                    <img id="imgPreviewId"
                                        src="{{ $photoUrl }}"
                                        style="max-width: 200px; max-height: 200px; border:1px solid #ccc; padding:4px; border-radius:6px;"
                                        alt="Message Photo Preview"
                                        onclick="image_upload()" />
                                </div>
                            </div>
                        </div>

                        <label for="" class="form-label"> Description </label>
                        <textarea name="description" id="editor" style="width:100%" cols="20" rows="5">{{ $data['message']->description }}</textarea>

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
<script src="{{ asset('backend_assets/js/ckeditor.js') }}"></script>
<script src="{{ asset('backend_assets/js/ckeditor_custom.js') }}"></script>

<script>
    function image_upload() {
        $('#imgPreview').trigger('click');
    }

    function readpicture(input, preview_id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(preview_id).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
