@extends('backend.includes.backend_layout')
@push('css')
@endpush
@section('content')
    <div class="page-content">
        <div class="mb-3">
            <div class="row">
                <h4 class="">Welcome {{ Auth::user()->name }} </h4>
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
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title"> Update Your Profile</h2>
                       
                        <form action="{{ route('admin.profile.info.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label ">Your Name </label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label ">Your Email </label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}"
                                        class="form-control">
                                </div>
                            </div>
                           
                        
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label ">Your Phone </label>
                                    <input type="text" name="phone" value="{{ Auth::user()->phone }}"
                                        class="form-control">
                                </div>
                            </div>
                                
                                <div class="col-lg-6">
                                    <div class="">
                                        <div class="mb-3">
                                            <label class="form-label">Your Photo</label>
    
                                            <input name="photo" class="form-control" type="file"
                                                id="imgPreview" onchange="readpicture(this, '#imgPreviewId');">
                                        </div>
                                        @if (Auth::user()->photo)
                                            <div class="text-center">
                                                <img id="imgPreviewId" onclick="image_upload()"
                                                    src="{{ asset(Auth::user()->photo) }}">
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <img id="imgPreviewId" onclick="image_upload()"
                                                    src="{{ asset('backend_assets/images/uploads_preview.png') }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-2">
                                <button class="btn btn-sm btn-primary" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title"> Update Your Password </h6>
                        <form action="{{ route('admin.profile.password.update') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="password" class="form-label "> Enter a new Password<span
                                            style="color: red">*</span></label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label ">Confirm Your Password<span
                                            style="color: red">*</span></label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-2">
                                <button class="btn btn-sm btn-primary" type="submit">Update</button>
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
