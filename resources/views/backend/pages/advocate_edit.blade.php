@extends('backend.includes.backend_layout')
@push('css')
@endpush
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class=" text-center mb-2">Advocate Edit</h3>
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
                        <form action="{{ route('admin.advocate.edit',$data['advocate']->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="" class="form-label"> Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data['advocate']->name }}"
                                        placeholder="Enter Advocate Name" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="">Barcouncil Sonod No*</label>
                                    <input type="text" name="barcouncil_sonod_no" value="{{ $data['advocate']->barcouncil_sonod_no }}" class="form-control"
                                        placeholder="Enter Barcouncil Sonod" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="">Email</label>
                                    <input type="email" name="email" placeholder="Enter Email" value="{{ $data['advocate']->email }}" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="">Password</label>
                                    <input type="text" class="form-control" placeholder="Enter Password" name="password">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">Start Date</label>
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text" name="start_date" value="{{ $data['advocate']->start_date }}" id="news_date"
                                            class="form-control" placeholder="Select date" data-input>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">End Date</label>
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text" name="end_date" value="{{ $data['advocate']->end_date }}" id="news_date"
                                            class="form-control" placeholder="Select date" data-input>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">Case Limit</label>
                                    <input type="number" name="case_limit" value="{{ $data['advocate']->case_limit }}" class="form-control" placeholder="Enter Case Limit">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">Storage Limit</label>
                                    <input type="text" name="storage_limit" class="form-control" value="{{ $data['advocate']->storage_limit }}" placeholder="Enter Storage Limit ">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">Sms Username</label>
                                    <input type="text" name="sms_username" class="form-control" value="{{ $data['advocate']->sms_username }}" placeholder="Enter Sms Username">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date" class="form-label">Sms Password</label>
                                    <input type="text" name="sms_password" class="form-control" placeholder="Enter Sms Password">
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="issue_date"  class="form-label">Active Status</label>
                                    <select class="form-select" name="active_status" id="">
                                        <option value="1" @if ($data['advocate']->active_status == 1) selected  @endif>Active</option>
                                        <option value="2" @if ($data['advocate']->active_status == 2) selected  @endif>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <div class="mb-3">
                                        <label class="form-label">Your Photo</label>
                                        <input name="photo" class="form-control" type="file" id="imgPreview"
                                            onchange="readpicture(this, '#imgPreviewId');">
                                    </div>
                                    <div class="text-center">
                                        <img id="imgPreviewId" onclick="image_upload()"
                                            src="{{ asset($data['advocate']->photo ? $data['advocate']->photo : 'backend_assets/images/uploads_preview.png') }}">
                                    </div>
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
