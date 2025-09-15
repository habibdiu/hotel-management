@extends('backend.includes.backend_layout')
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class=" mb-2" style="text-align:center">
                            <h3>All Messages</h3>
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
                                        <th style="">Serial</th>
                                        <th style="">Photo</th>
                                        <th style="">Name</th>
                                        <th style="">Title</th>
                                        <th style="">Position</th>
                                        <th style="">Company</th>
                                        <th style="">Description</th>
                                        <th style="">Priority</th>
                                        <th style="width:15%">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($data['message_list'] as $key => $single_message)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($single_message->photo ? $single_message->photo : 'backend_assets/images/user-dummy.png') }}"
                                                    alt="" loading="lazy" style="width:1000px" >
                                            </td>
                                            <td>
                                                {{$single_message->name }}
                                            </td>
                                            <td>
                                                {{$single_message->title }}
                                            </td>
                                            <td>
                                                {{$single_message->position }}
                                            </td>
                                            <td>
                                                {{$single_message->company }}
                                            </td>
                                            <td>
                                                {{$single_message->description }}
                                            </td>
                                            <td>
                                                {{$single_message->priority }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.message.edit', $single_message->id) }}"
                                                    class="btn btn-success btn-icon" href=""><i
                                                        class="fa-solid fa-edit"></i></a>

                                                        
                                                <a class="btn btn-danger btn-icon" data-delete="{{ $single_message->id }}"
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
                    url: '/admin/message/delete/' + id,
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