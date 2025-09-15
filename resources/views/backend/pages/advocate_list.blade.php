@extends('backend.includes.backend_layout')
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class=" mb-2" style="text-align:center">
                            <h3>Advocate List</h3>
                        </div>
                        <div class="mt-3">
                            @if (session('error'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Failed!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="btn-close"></button>
                                </div>
                            @endif
                            <div id="success"></div>
                            <div id="failed"></div>
                        </div>
                        <div class="table-responsive" id="print_data">
                            <table id="dataTableExample" class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="">SL</th>
                                        <th style="">Photo</th>
                                        <th style="">Personal Info</th>
                                        <th style="">Validity</th>
                                        <th style="">Limits</th>
                                        <th style="">Status</th>
                                        <th style="width:15%">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['advocate_list'] as $key => $single_advocate)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($single_advocate->photo ? $single_advocate->photo : 'backend_assets/images/user-dummy.png') }}"
                                                    alt="" loading="lazy" style="width:1000px" >
                                            </td>
                                            <td>
                                                {{ 'Name: ' . $single_advocate->name }} <br>
                                                {{ 'Email: ' . $single_advocate->email }} <br>
                                                {{ 'Sonod No: ' . $single_advocate->barcouncil_sonod_no }}
                                            </td>
                                            <td>
                                                {{ 'Start Date: ' . date('d M Y', strtotime($single_advocate->start_date)) }}
                                                <br>
                                                {{ 'End Date: ' . date('d M Y', strtotime($single_advocate->end_date)) }}
                                                <br>
                                            </td>
                                            <td>
                                                {{ 'Case Limit: ' . $single_advocate->case_limit }} <br>
                                                {{ 'Storage Limit: ' . $single_advocate->storage_limit }} <br>
                                            </td>
                                            <td>
                                                @if ($single_advocate->active_status == 1)
                                                  <span class="badge bg-success">Active</span>
                                                  @else
                                                  <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.advocate.edit', $single_advocate->id) }}"
                                                    class="btn btn-success btn-icon" href=""><i
                                                        class="fa-solid fa-edit"></i></a>

                                                        
                                                <a class="btn btn-danger btn-icon" data-delete="{{ $single_advocate->id }}"
                                                    id="delete"><i class="fa-solid fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('click', '#delete', function() {
            if (confirm('Are You Sure ?')) {
                let id = $(this).attr('data-delete');
                let row = $(this).closest('tr');
                $.ajax({
                    url: '/admin/advocate/delete/' + id,
                    success: function(data) {
                        var data_object = JSON.parse(data);
                        if (data_object.status == 'SUCCESS') {
                            row.remove();
                            $('#Table tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                            $('#success').css('display', 'block');
                            $('#success').html(
                                '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success! </strong>' +
                                data_object.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button></div>'
                            );
                        } else {
                            $('#failed').html('display', 'block');
                            $('#failed').html(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Failed! </strong>' +
                                data_object.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button></div>'
                            );
                        }

                    }
                });
            }
        });
    </script>
@endpush
