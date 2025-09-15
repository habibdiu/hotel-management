<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Advocate;
use App\Models\CaseDate;
use App\Models\Cases;
use App\Models\Library;
use App\Models\Rack;
use App\Models\RackFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDOException;
use Intervention\Image\Facades\Image;

class ApiController extends Controller
{
    public function __construct()
    {
        header('Content-Type:application/json; charset=UTF-8');
        header('Access-control-Allow-origin: *');
        date_default_timezone_set("Asia/Dhaka");
    }

    // login 
    public function login(Request $request)
    {
        $server_response =
            [
                "status" => false,
                "message" => "Wrong Attempt..",
                "advocate_id" => 0,
                "name" => "",
                "barcouncil_sonod_no" => "",
                "photo" => "",
                "api_secret_key" => "",
                "start_date" => "",
                "end_date" => "",
                'sms_username' => '',
                'sms_password' => '',
                'active_status' => 2
            ];
        if ($request->isMethod('post')) {
            $login_status = Auth::guard('advocate')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);
            if ($login_status != null) {
                if (Auth::guard('advocate')->user()->id > 0) {
                    $token_generate =  sha1(rand(100000, 900000));
                    $server_response = [
                        "status" => true,
                        "message" => "Successfully Logged In",
                        "advocate_id" => Auth::guard('advocate')->id(),
                        "name" => Auth::guard('advocate')->user()->name,
                        "barcouncil_sonod_no" => Auth::guard('advocate')->user()->barcouncil_sonod_no,
                        "photo" => Auth::guard('advocate')->user()->photo,
                        "api_secret_key" => $token_generate,
                        'sms_username' => Auth::guard('advocate')->user()->sms_username,
                        'sms_password' => Auth::guard('advocate')->user()->sms_password,
                        "start_date" => Auth::guard('advocate')->user()->start_date,
                        "end_date" => Auth::guard('advocate')->user()->end_date,
                        'active_status' => Auth::guard('advocate')->user()->active_status
                    ];
                    Advocate::where('id', Auth::guard('advocate')->id())->update([
                        'api_secret_key' => $token_generate,
                    ]);
                } else {
                    $server_response['message'] =  'Wrong Attempt.';
                }
            } else {
                $server_response['message'] = 'Wrong Attempt.';
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // authentication Check
    public function login_auth_check($advocate_id, $api_secret_key)
    {
        $advocate = Advocate::where('id', $advocate_id)->where('api_secret_key', $api_secret_key)->count();
        if ($advocate) {
            return true;
        } else {
            return false;
        }
    }


    // case add 
    public function case_add(Request $request)
    {
        $server_response = array(
            "status" => false,
            "message" => "Failed"
        );
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $validation = $request->validate([
                       'case_number' => 'required'
                ]);
                $server_response = array(
                    "status" => false,
                    "message" => $validation
                );
                try {
                    if ($request->case_start_date) {
                        $case_start_date = date('Y-m-d', strtotime($request->case_start_date));
                    } else {
                        $case_start_date = null;
                    }
                    if ($request->case_close_date) {
                        $case_close_date = date('Y-m-d', strtotime($request->case_close_date));
                    } else {
                        $case_close_date = null;
                    }
                    $unique_id = $request->unique_id;
                    $unique_check = Cases::where('unique_id', $unique_id)->count();
                    if ($unique_check < 1) {
                        Cases::create([
                            'advocate_id' => $advocate_id,
                            'case_number' => $request->case_number,
                            'unique_id' => $request->unique_id,
                            'serial_number' => $request->serial_number,
                            'case_type' => $request->case_type,
                            'court_type' => $request->court_type,
                            'court_name' => $request->court_name,
                            'my_side' => $request->my_side,
                            'badi_name' => $request->badi_name,
                            'badi_phone' => $request->badi_phone,
                            'bibadi_name' => $request->bibadi_name,
                            'bibadi_phone' => $request->bibadi_phone,
                            'case_start_date' => $case_start_date,
                            'case_details' => $request->case_details,
                            'case_status' => $request->case_status,
                            'case_close_date' => $case_close_date,
                            'mohuri_name' => $request->mohuri_name,
                            'changed_case_no_1' => $request->changed_case_no_1,
                            'changed_case_no_2' => $request->changed_case_no_2,
                        ]);
                        $server_response = array(
                            "status" => true,
                            "message" => "Added Successfully"
                        );
                    } else {
                        $server_response = array(
                            "status" => false,
                            "message" => "Duplicate unique Id"
                        );
                    }
                } catch (PDOException $e) {
                    $server_response = array(
                        "status" => false,
                        "message" => "Failed"
                    );
                }
                // try catch end 
            } else {
                $server_response = array(
                    "status" => false,
                    "message" => "Authentication Failed"
                );
            }
        }
        //   auth check end 
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // case edit 
    public function case_edit(Request $request)
    {
        $server_response = array(
            "status" => false,
            "message" => "Failed"
        );
        if ($request->isMethod('post')) {
            $case_id = $request->case_id;
            $case = Cases::find($case_id);
            if ($case) {
                $api_secret_key = $request->api_secret_key;
                $advocate_id = $request->advocate_id;
                $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
                if ($check_login_access && $advocate_id == $case->advocate_id) {
                    try {
                        if ($request->case_start_date) {
                            $case_start_date = date('Y-m-d', strtotime($request->case_start_date));
                        } else {
                            $case_start_date = $case->case_start_date;
                        }
                        if ($request->case_close_date) {
                            $case_close_date = date('Y-m-d', strtotime($request->case_close_date));
                        } else {
                            $case_close_date = $case->case_close_date;
                        }
                        $unique_id = $request->unique_id;
                        $unique_check = Cases::where('unique_id', $unique_id)->whereNot('id', $case_id)->count();
                        if ($unique_check < 1) {
                            $case->update([
                                'advocate_id' => $advocate_id,
                                'case_number' => $request->case_number,
                                'serial_number' => $request->serial_number,
                                'case_type' => $request->case_type,
                                'unique_id' => $request->unique_id,
                                'court_type' => $request->court_type,
                                'court_name' => $request->court_name,
                                'my_side' => $request->my_side,
                                'badi_name' => $request->badi_name,
                                'badi_phone' => $request->badi_phone,
                                'bibadi_name' => $request->bibadi_name,
                                'bibadi_phone' => $request->bibadi_phone,
                                'case_start_date' => $case_start_date,
                                'case_details' => $request->case_details,
                                'case_status' => $request->case_status,
                                'case_close_date' => $case_close_date,
                                'mohuri_name' => $request->mohuri_name,
                                'changed_case_no_1' => $request->changed_case_no_1,
                                'changed_case_no_2' => $request->changed_case_no_2,
                            ]);
                            $server_response = array(
                                "status" => true,
                                "message" => "updated Successfully"
                            );
                        } else {
                            $server_response = array(
                                "status" => false,
                                "message" => "Duplicate Unique Id"
                            );
                        }
                    } catch (PDOException $e) {
                        $server_response = array(
                            "status" => false,
                            "message" => "Failed" . $e->getMessage()
                        );
                    }
                } else {
                    $server_response = array(
                        "status" => false,
                        "message" => "Authentication Failed"
                    );
                }
            } else {
                $server_response = array(
                    "status" => false,
                    "message" => "Case Not Found"
                );
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // case List 
    public function case_list(Request $request)
    {
        $server_response = [
            'status' => false,
            'messages' => 'Failed',
            'case_list' => array(),
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $case_list = DB::table('cases')
                    ->leftJoin('advocates', 'advocates.id', '=', 'cases.advocate_id')
                    ->select(
                        'cases.id',
                        'cases.case_number',
                        'cases.serial_number',
                        'cases.unique_id',
                        'cases.case_type',
                        'cases.court_type',
                        'cases.court_name',
                        'cases.my_side',
                        'cases.badi_name',
                        'cases.badi_phone',
                        'cases.bibadi_name',
                        'cases.bibadi_phone',
                        'cases.case_start_date',
                        'cases.case_details',
                        'cases.case_status',
                        'cases.case_close_date',
                        'cases.mohuri_name',
                        'cases.changed_case_no_1',
                        'cases.changed_case_no_2',
                        'advocates.name',
                    )->where('cases.advocate_id', $advocate_id)->get();
                $server_response = [
                    'status' => true,
                    'messages' => 'Case updated Successfully',
                ];
                if (count($case_list) > 0) {
                    $server_response["case_list"] = $case_list;
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication failed'
                ];
            }
        }
        // end post method 
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // case delete 
    public function case_delete(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Not Found'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $case_id = $request->case_id;
            $case = Cases::find($case_id);
            if ($case) {
                $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
                if ($check_login_access && $advocate_id == $case->advocate_id) {
                    $case->delete();
                    $server_response = [
                        'status' => true,
                        'message' => 'Deleted Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Unauthorized'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Case Not Found'
                ];
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    //case date add
    public function case_date_add(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                if ($request->running_date) {
                    $running_date = date('Y-m-d', strtotime($request->running_date));
                } else {
                    $running_date = null;
                }
                if ($request->next_date) {
                    $next_date = date('Y-m-d', strtotime($request->next_date));
                } else {
                    $next_date = null;
                }
                $unique_id = $request->unique_id;
                $unique_check = CaseDate::where('unique_id', $unique_id)->count();
                if ($unique_check < 1) {
                    CaseDate::create([
                        'advocate_id' => $advocate_id,
                        'unique_id' => $request->unique_id,
                        'running_date' => $running_date,
                        'next_date' => $next_date,
                        'reason' => $request->reason,
                    ]);
                    $server_response = [
                        'status' => true,
                        'Message' => 'Data Added Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'Message' => 'Duplicate Unique Id'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'Message' => 'Authentication Failed'
                ];
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // case date edit 
    public function case_date_edit(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $case_date_id = $request->case_date_id;
                $case_date_row = CaseDate::find($case_date_id);
                if ($case_date_row) {
                    if ($case_date_row->advocate_id == $advocate_id) {
                        if ($request->running_date) {
                            $running_date = date('Y-m-d', strtotime($request->running_date));
                        } else {
                            $running_date = null;
                        }
                        if ($request->next_date) {
                            $next_date = date('Y-m-d', strtotime($request->next_date));
                        } else {
                            $next_date = null;
                        }
                        $unique_id = $request->unique_id;
                        // $unique_check = CaseDate::where('unique_id', $unique_id)->whereNot('id', $case_date_id)->count(); 
                        // if ($unique_check < 1) { 
                        $case_date_row->update([
                            'unique_id' => $request->unique_id,
                            'advocate_id' => $advocate_id,
                            'running_date' => $running_date,
                            'next_date' => $next_date,
                            'reason' => $request->reason,
                        ]);
                        $server_response = [
                            'status' => true,
                            'Message' => 'Data Updated Successfully'
                        ];
                        // } else {
                        //     $server_response = [
                        //         'status' => false,
                        //         'Message' => 'Duplicate Unique Id'
                        //     ];
                        // }
                    } else {
                        $server_response = [
                            'status' => false,
                            'message' => 'Case is not Yours',
                        ];
                    }
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Case Date Not Found',
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'Message' => 'Authentication Failed'
                ];
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }


    // case date list 
    public function case_date_list(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed',
            'case_date_list' => array()
        ];
        $api_secret_key = $request->api_secret_key;
        $advocate_id = $request->advocate_id;
        $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
        if ($request->isMethod('post')) {
            if ($check_login_access) {
                $case_date_list = DB::table('case_dates')
                    ->leftJoin('cases', 'cases.unique_id', '=', 'case_dates.unique_id')
                    ->select(
                        'case_dates.id',
                        'cases.unique_id',
                        'case_dates.next_date',
                        'case_dates.running_date',
                        'cases.case_number'
                    )
                    ->where('case_dates.advocate_id', $advocate_id)->get();
                $server_response = [
                    'status' => true,
                    'message' => 'Auth Successful',
                    'case_date_list' => $case_date_list
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed',
                    'case_date_list' => array()
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed',
                'case_date_list' => array()
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // case date delete 
    public function case_date_delete(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Not Found'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $case_date_id = $request->case_date_id;
                $case_date_row = CaseDate::find($case_date_id);
                if ($case_date_row) {
                    if ($case_date_row->advocate_id == $advocate_id) {
                        $case_date_row->delete();
                        $server_response = [
                            'status' => true,
                            'message' => 'Deleted Successfully'
                        ];
                    } else {
                        $server_response = [
                            'status' => false,
                            'message' => 'Case is not Yours'
                        ];
                    }
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Not Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    //   library add 
    public function library_add(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                if ($request->issue_date) {
                    $issue_date = date('Y-m-d', strtotime($request->issue_date));
                } else {
                    $issue_date = null;
                }
                $file = $request->file('file');
                if ($file) {
                    $file_extension = $file->getClientOriginalExtension();
                    $file_name = 'backend_assets/library_file/' . uniqid() . '.' . $file_extension;
                    $file_size =  $file->getSize();
                    $file->move('backend_assets/library_file', $file_name);
                } else {
                    $file_name = null;
                    $file_size = null;
                }
                Library::create([
                    'advocate_id' => $advocate_id,
                    'issue_date' => $issue_date,
                    'title' => $request->title,
                    'file_path' => $file_name,
                    'file_size' => $file_size,
                ]);
                $server_response = [
                    'status' => true,
                    'message' => 'Added Successfully'
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    public function library_edit(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $library_id = $request->library_id;
                $library = Library::find($library_id);
                if ($library) {
                    if ($library->advocate_id == $advocate_id) {
                        if ($request->issue_date) {
                            $issue_date =  date('Y-m-d', strtotime($request->issue_date));
                        } else {
                            $issue_date = $library->issue_date;
                        }
                        $file = $request->file('file');
                        if ($file) {
                            $file_extension = $file->getClientOriginalExtension();
                            $file_name = 'backend_assets/library_file/' . uniqid() . '.' . $file_extension;
                            $file_size =  $file->getSize();
                            $file->move('backend_assets/library_file', $file_name);
                            if (File::exists($library->file_path)) {
                                File::delete($library->file_path);
                            }
                        } else {
                            $file_name = $library->file_path;
                            $file_size = $library->file_size;
                        }
                        $library->update([
                            'advocate_id' => $advocate_id,
                            'issue_date' => $issue_date,
                            'title' => $request->title,
                            'file_path' => $file_name,
                            'file_size' => $file_size,
                        ]);
                        $server_response = [
                            'status' => true,
                            'message' => 'Updated Successfully'
                        ];
                    } else {
                        $server_response = [
                            'status' => false,
                            'message' => 'You Are Not Allowed'
                        ];
                    }
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Library Nottt Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // library list 
    public function library_list(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed',
            'library_list' => array()
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $library_list = DB::table('libraries')
                    ->leftJoin('advocates', 'advocates.id', '=', 'libraries.advocate_id')
                    ->select('libraries.id', 'libraries.issue_date', 'libraries.title', 'libraries.file_path', 'libraries.file_size')
                    ->where('libraries.advocate_id', $advocate_id)->get();
                $server_response = [
                    'status' => true,
                    'message' => 'Successful',
                    'library_list' => $library_list
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed',
                    'library_list' => array()
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed',
                'library_list' => array()
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }

    public function library_delete(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Not Found'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $library_id = $request->library_id;
                $library = Library::find($library_id);
                if ($library->advocate_id == $advocate_id) {
                    $library->delete();
                    $server_response = [
                        'status' => true,
                        'message' => 'Deleted Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'The Case is not Yours'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    // racks
    public function rack_add(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $unique_id = $request->unique_id;
                $check_unique_id = Rack::where('unique_id', $unique_id)->count();
                if ($check_unique_id < 1) {
                    Rack::create([
                        'rack_name' => $request->rack_name,
                        'unique_id' => $unique_id,
                        'priority' => $request->priority,
                        'insert_by' => $advocate_id,
                    ]);
                    $server_response = [
                        'status' => true,
                        'message' => 'Added Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Duplicate Unique Id'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response);
    }

    public function rack_edit(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $unique_id = $request->unique_id;
                $rack = Rack::where('unique_id', $unique_id)->first();
                if ($rack) {
                    $check_unique_id = Rack::where('unique_id', $unique_id)->whereNot('id', $rack->id)->count();
                    if ($check_unique_id < 1) {
                        if ($request->rack_name) {
                            $rack_name = $request->rack_name;
                        } else {
                            $rack_name = $rack->rack_name;
                        }
                        if ($request->priority) {
                            $priority = $request->priority;
                        } else {
                            $priority = $rack->priority;
                        }
                        $rack->update([
                            'rack_name' => $rack_name,
                            'unique_id' => $unique_id,
                            'priority' => $priority,
                            'insert_by' => $advocate_id,
                        ]);
                        $server_response = [
                            'status' => true,
                            'message' => 'Updated Successfully'
                        ];
                    } else {
                        $server_response = [
                            'status' => false,
                            'message' => 'Duplicate Unique Id'
                        ];
                    }
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Rack Not Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response);
    }
    public function rack_list(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed',
            'rack_list' => []
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $rack_list = DB::table('racks')->where('insert_by', $advocate_id)->select('id', 'rack_name', 'unique_id', 'priority')->get();
                $server_response = [
                    'status' => true,
                    'message' => 'Rack List Found',
                    'rack_list' => $rack_list
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed',
                    'rack_list' => []
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed',
                'rack_list' => []
            ];
        }
        echo json_encode($server_response);
    }

    public function rack_delete(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $unique_id = $request->unique_id;
                $rack = Rack::where('unique_id', $unique_id)->first();
                if ($rack) {
                    $rack->delete();
                    $server_response = [
                        'status' => true,
                        'message' => 'Deleted Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Rack Not Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response);
    }
    /// rack files 
    public function rack_files_add(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $rf_unique_id = $request->rf_unique_id;
                // check unique check 
                $check_unique_id = RackFile::where('rf_unique_id', $rf_unique_id)->count();
                if ($check_unique_id < 1) {
                    if ($request->issue_date) {
                        $issue_date = date('Y-m-d', strtotime($request->issue_date));
                    } else {
                        $issue_date = null;
                    }
                    RackFile::create([
                        'issue_date' => $issue_date,
                        'rf_unique_id' => $rf_unique_id,
                        'racks_unique_id' => $request->racks_unique_id,
                        'file_title' => $request->file_title,
                        'insert_by' => $advocate_id,
                    ]);
                    $server_response = [
                        'status' => true,
                        'message' => 'Rack File added Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Duplicate Unique Id'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not allowed'
            ];
        }
        echo json_encode($server_response);
    }
    public function rack_files_edit(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $rf_unique_id = $request->rf_unique_id;
                //check row 
                $rack_file = RackFile::where('rf_unique_id', $rf_unique_id)->first();
                if ($rack_file) {
                    // check unique check 
                    $check_unique_id = RackFile::where('rf_unique_id', $rf_unique_id)->whereNot('id', $rack_file->id)->count();
                    if ($check_unique_id < 1) {
                        if ($request->issue_date) {
                            $issue_date = date('Y-m-d', strtotime($request->issue_date));
                        } else {
                            $issue_date = $rack_file->issue_date;
                        }
                        if ($request->racks_unique_id) {
                            $racks_unique_id = $request->racks_unique_id;
                        } else {
                            $racks_unique_id = $rack_file->racks_unique_id;
                        }
                        if ($request->file_title) {
                            $file_title = $request->file_title;
                        } else {
                            $file_title = $rack_file->file_title;
                        }
                        $rack_file->update([
                            'issue_date' => $issue_date,
                            'rf_unique_id' => $rf_unique_id,
                            'racks_unique_id' => $racks_unique_id,
                            'file_title' => $file_title,
                            'insert_by' => $advocate_id,
                        ]);
                        $server_response = [
                            'status' => true,
                            'message' => 'updated Successfully'
                        ];
                    } else {
                        $server_response = [
                            'status' => false,
                            'message' => 'Duplicate Unique Id'
                        ];
                    }
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'Not Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response);
    }

    public function rack_files_list(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed',
            'rack_file_list' => []
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $rack_file_list  = DB::table('rack_files')
                    ->leftJoin('racks', 'racks.unique_id', '=', 'rack_files.racks_unique_id')
                    ->select('rack_files.id', 'rack_files.rf_unique_id', 'rack_files.racks_unique_id', 'rack_files.issue_date', 'rack_files.file_title', 'racks.rack_name')
                    ->where('rack_files.insert_by', $advocate_id)->get();
                $server_response = [
                    'status' => true,
                    'message' => 'Rack Files Found',
                    'rack_file_list' => $rack_file_list
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed',
                    'rack_file_list' => []
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed',
                'rack_file_list' => []
            ];
        }
        echo json_encode($server_response);
    }
    public function rack_files_delete(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Failed'
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $rf_unique_id = $request->rf_unique_id;
                $rack_file = RackFile::where('rf_unique_id', $rf_unique_id)->first();
                if ($rack_file) {
                    $rack_file->delete();
                    $server_response = [
                        'status' => true,
                        'message' => 'Deleted Successfully'
                    ];
                } else {
                    $server_response = [
                        'status' => false,
                        'message' => 'File Not Found'
                    ];
                }
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Authentication Failed'
                ];
            }
        } else {
            $server_response = [
                'status' => false,
                'message' => 'Method Not Allowed'
            ];
        }
        echo json_encode($server_response);
    }
    // profile 
    public function profile(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Not Found',
            'id' => '',
            'name' => '',
            'barcouncil_sonod_no' => '',
            'email' => '',
            'password' => '',
            'photo' => '',
            'start_date' => '',
            'end_date' => '',
            'case_limit' => '',
            'storage_limit' => '',
            'api_secret_key' => '',
            'sms_username' => '',
            'sms_password' => '',
            'active_status' => '',
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $advocate = Advocate::find($advocate_id);
                $server_response = [
                    'status' => true,
                    'message' => 'Successful',
                    'id' => $advocate->id,
                    'name' => $advocate->name,
                    'barcouncil_sonod_no' => $advocate->barcouncil_sonod_no,
                    'email' => $advocate->email,
                    'password' => $advocate->password,
                    'photo' => $advocate->password,
                    'start_date' => $advocate->start_date,
                    'end_date' => $advocate->end_date,
                    'case_limit' => $advocate->case_limit,
                    'storage_limit' => $advocate->storage_limit,
                    'sms_username' => $advocate->sms_username,
                    'sms_password' => $advocate->sms_password,
                    'active_status' => $advocate->active_status,
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Not Found'
                ];
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
    public function profile_update(Request $request)
    {
        $server_response = [
            'status' => false,
            'message' => 'Not Found',
        ];
        if ($request->isMethod('post')) {
            $api_secret_key = $request->api_secret_key;
            $advocate_id = $request->advocate_id;
            $check_login_access = $this->login_auth_check($advocate_id, $api_secret_key);
            if ($check_login_access) {
                $advocate = Advocate::find($advocate_id);
                if ($request->password) {
                    $password = bcrypt($advocate->password);
                } else {
                    $password = $advocate->password;
                }
                if ($request->sms_password) {
                    $sms_password = bcrypt($advocate->sms_password);
                } else {
                    $sms_password = $advocate->sms_password;
                }
                $photo  = $request->file('photo');
                if ($photo) {
                    $photo_extension = $photo->getClientOriginalExtension();
                    $photo_name = 'backend_assets/images/advocates/' . uniqid() . '.' . $photo_extension;
                    $image = Image::make($photo);
                    $image->resize(300, 300);
                    $image->save($photo_name);
                } else {
                    $photo_name = $advocate->photo;
                }
                $advocate->update([
                    'name' => $request->name,
                    'barcouncil_sonod_no' => $request->barcouncil_sonod_no,
                    'email' => $request->email,
                    'password' => $password,
                    'photo' => $photo_name,
                    'sms_username' => $request->sms_username,
                    'sms_password' => $sms_password,
                ]);
                $server_response = [
                    'status' => true,
                    'message' => 'Updated Successfully'
                ];
            } else {
                $server_response = [
                    'status' => false,
                    'message' => 'Not Found'
                ];
            }
        }
        echo json_encode($server_response, JSON_UNESCAPED_UNICODE);
    }
}
