<?php

namespace App\Http\Controllers\AppAPI;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WeddingUsers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;

use Response;

class WeddingUserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //code
    }

    // fetch all records
    public function fetchAllRecord()
    {  
        try {
                $data       = WeddingUsers::with('owner')->orderBy('id', 'desc')->get(); 
                
                return ['status' => true, 'message' => trans('messages.success'), 'data' => $data];

            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 400);
            }
    }

    // show particular record
    public function showRecord($id)
    {
        try {
                $data       = WeddingUsers::find($id); 

                if ($data) {
                    return ['status' => true, 'message' => trans('messages.success'), 'data' => $data];
                } else {
                    return response()->json(['status' => false, 'message' => trans('auth.not_exist')], 400);
                }

            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 400);
            }


    }

    // store a newly created resource in storage.
    public function addRecord(Request $request)
    { 
        $id = (int)$request->get("id", 0);

        $validator = WeddingUsers::validatorAdd($request->all(), $id); 

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }
 
        try { 
            $insertUpdateData = WeddingUsers::updateOrCreate([
                'id' => $id,
            ], [  
                'role'            => $request->get('role'),
                'name'            => $request->get('name'),
                'email'           => $request->get('email'), 
                'password'        => Hash::make($request->get('password')),
            ]);
            
            return ['status' => true, 'message' => trans('messages.success'), 'data' => $insertUpdateData];
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 400);
        }
         
    }

    // update resource in storage.
    public function updateRecord(Request $request)
    { 
        $id = (int)$request->get("id", 0);

        $validator = WeddingUsers::validatorUpdate($request->all(), $id); 

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }

        try { 
            $insertUpdateData = WeddingUsers::updateOrCreate([
                'id' => $id,
            ], [  
                'role'            => $request->get('role'),
                'name'            => $request->get('name'),
                'email'           => $request->get('email'), 
                'password'        => Hash::make($request->get('password')),
            ]);
            
            return ['status' => true, 'message' => trans('messages.success'), 'data' => $insertUpdateData];
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 400);
        }
         
    }

    // Delete records
    public function deleteRecord(Request $request)
    {
        $id = (int)$request->get("id", 0);

        $validator = WeddingUsers::validatorDelete($request->all(), $id); 

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }

        try { 
                $exist = WeddingUsers::where("id", $id)->get();

                if(count($exist) > 0){
                    WeddingUsers::where("id", $id)->delete(); 
                    return ['status' => true, 'message' => trans('messages.success')];
                }else{
                    return response()->json(['status' => false, 'message' => trans('auth.not_exist')], 400);
                } 
        } 
        catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 400);
        }
    } 
    
}
