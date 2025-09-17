@extends('backend.includes.backend_layout')
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class=" mb-5" style="text-align:center">
                            <h3>Room List</h3>
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
                                        <th>ID</th>
                                        <th>View</th>
                                        <th>Room Name</th>
                                        <th>Description</th>
                                        <th>Room Type</th>
                                        <th>Amenities</th>
                                        <th>Floor Number</th>
                                        <th>Price</th>
                                        <th style="width:15%">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['room_list'] as $key => $single_room)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($single_room->photo ? $single_room->photo : 'backend_assets/images/room_dummy.jpg') }}"
                                                    alt="" loading="lazy" style="width:1000px" >
                                            </td>
                                            <td>
                                                {{$single_room->room_name }}
                                            </td>

                                            <td>
                                                <x-readmore-modal 
                                                    :id="'roomDescription' . $single_room->id"
                                                    :content="$single_room->description"
                                                    previewWords="5"
                                                />
                                            </td>

                                            <td>
                                                {{$single_room->roomType->room_type_name ?? 'n/a'}}
                                            </td>

                                            <td>
                                                <x-readmore-modal 
                                                    :id="'roomAmenities' . $single_room->id"
                                                    :content="$single_room->amenities"
                                                    previewWords="5"
                                                />
                                            </td>
                                            <td>
                                                {{$single_room->floor_number}}
                                            </td>
                                            <td>
                                                {{$single_room->price_per_night }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.room.edit', $single_room->id) }}"
                                                    class="btn btn-success btn-icon"  data-bs-toggle="modal" data-bs-target="#roomEditModal{{ $single_room->id }}"><i
                                                        class="fa-solid fa-edit"></i></a>

                                                        
                                                <a class="btn btn-danger btn-icon" data-delete="{{ $single_room->id }}"
                                                    id="delete"><i class="fa-solid fa-trash"></i> </a>
                                            </td>
                                        </tr>

                                        <!-- Edit Room Modal -->
                                        <div class="modal fade" id="roomEditModal{{ $single_room->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen-sm-down">

                                                <div class="modal-content">

                                                    <div class="d-flex justify-content-end p-2 border-0">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.room.edit', $single_room->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="room_name{{ $single_room->id }}" class="form-label">Room Name*</label>
                                                                        <input type="text" 
                                                                        class="form-control @error('room_name') is-invalid @enderror"
                                                                        id="room_name{{ $single_room->id }}"
                                                                        name="room_name"
                                                                        value="{{ old('room_name', $single_room->room_name) }}"
                                                                        required>
                                                                        @error('room_name')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="price_per_night{{ $single_room->id }}" class="form-label">Price Per Night*</label>
                                                                        <input type="number" 
                                                                        class="form-control @error('price_per_night') is-invalid @enderror"
                                                                        id="price_per_night{{ $single_room->id }}"
                                                                        name="price_per_night"
                                                                        value="{{ old('price_per_night', $single_room->price_per_night) }}"
                                                                        min="0"
                                                                        required>
                                                                        @error('price_per_night')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="floor_number{{ $single_room->id }}" class="form-label">Floor Number</label>
                                                                        <input type="text" 
                                                                        class="form-control"
                                                                        id="floor_number{{ $single_room->id }}"
                                                                        name="floor_number"
                                                                        value="{{ old('floor_number', $single_room->floor_number) }}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="photo{{ $single_room->id }}" class="form-label">Photo</label>
                                                                        <input type="file" 
                                                                        class="form-control"
                                                                        id="photo{{ $single_room->id }}"
                                                                        name="photo">
                                                                        <div class="text-center mt-2">
                                                                            @php
                                                                            $photoPath = $single_room->photo;
                                                                            $photoUrl = $photoPath && file_exists(public_path($photoPath)) ? asset($photoPath) : asset('backend_assets/images/uploads_preview.png');
                                                                            @endphp
                                                                            <img src="{{ $photoUrl }}" 
                                                                            style="max-width: 80px; max-height: 80px; border:1px solid #ccc; padding:4px; border-radius:6px;"
                                                                            alt="Room Photo Preview" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="amenities{{ $single_room->id }}" class="form-label">Amenities</label>
                                                                        <input type="text" 
                                                                        class="form-control"
                                                                        id="amenities{{ $single_room->id }}"
                                                                        name="amenities"
                                                                        value="{{ old('amenities', $single_room->amenities) }}">                                                                    
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <label for="description{{ $single_room->id }}" class="form-label">Description</label>
                                                                        <textarea class="form-control" 
                                                                                id="description{{ $single_room->id }}" 
                                                                                name="description"
                                                                                rows="6">{{ old('description', $single_room->description) }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer border-0">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Room Modal -->

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->
@endsection

@push('js')
    <script>
        $(document).on('click', '#delete', function() {
            if (confirm('Are You Sure ?')) {
                let id = $(this).attr('data-delete');
                let row = $(this).closest('tr');
                $.ajax({
                    url: '/admin/room/delete/' + id,
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
                                data_object.room +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button></div>'
                            );
                        } else {
                            $('#failed').html('display', 'block');
                            $('#failed').html(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Failed! </strong>' +
                                data_object.room +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button></div>'
                            );
                        }

                    }
                });
            }
        });
    </script>
@endpush