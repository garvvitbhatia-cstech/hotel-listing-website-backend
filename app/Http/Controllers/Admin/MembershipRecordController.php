<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MembershipRecords;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Responses;

use ReallySimpleJWT\Token;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

use App\Models\Languages;

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;



class MembershipRecordController extends Controller {

	

    private static $MembershipRecords;

    private static $TokenHelper;

    public function __construct(){

        self::$MembershipRecords = new MembershipRecords();

        self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/memberships_record/index');

    }

    public function listPaginate(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$MembershipRecords->where('status', '!=', 3);

        if ($request->input('title') && $request->input('title') != ""){
            $title = $request->input('title');
            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/memberships_record/paginate', compact('records'));

    }

	

}