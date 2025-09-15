@extends('backend.includes.backend_layout')
@section('content')
    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="card-title mb-3">Dashboard</h4>
               <div class="row">
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Rooms</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Rooms Occupied</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Rooms Available</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Rooms Reserved</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Check-ins Today</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>      
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Check-outs Today</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Revenue Overview</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Occupancy Rate (%)</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>     
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Pending Housekeeping Tasks</h6>
                                </div>
                                <div class="mt-2" style="color: green ;font-size:18px">
                                    {{ $data['total_student'] }}
                                </div>
                            </div>
                        </div>
                    </div>     
               </div>
            </div>
        </div>
    </div>
@endsection
