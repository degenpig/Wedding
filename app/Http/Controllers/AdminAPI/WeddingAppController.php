<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Events;
use App\Models\HeadingTitles;
use App\Models\WeddingUsers;
use App\Models\Groups;
use App\Models\Guests;
use App\Models\Companians;
use App\Models\InvitationTypes;
use App\Models\Statuses;
use App\Models\Invitations;
use App\Models\InvitationSends;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Validator;
use Mail;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;
use Spatie\QueryBuilder\Sorts\Sort;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Response;
use Helper;

class WeddingMemberFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query
            ->where('first_name',$value)
            ->where('second_name',$value)
            ->where('third_name',$value)
            ->where('family_name',$value)
            ->where('father_name',$value)
            ->where('mother_name',$value);
    }
}

class WeddingAppController extends Controller
{


    // create wedding member
    public function createWeddingMember(Request $request)
    {
        $id = 0;
        $data = $request->all();

        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }

        $validator = Validator::make($data, [
            'first_name'   => 'required|string|max:15',
            'family_name'  => 'required|string|max:15',
            'father_name'  => 'required|string|max:15',
            'mother_name'  => 'required|string|max:15',
            'email_id' => [
                'required',
                Rule::unique('wedding_users')->where(function ($query) use($data,$id) {
                    return $query->where('email_id', $data['email_id']);
                }),
                'email:strict',
            ],
            'phone_code'   => 'required|string|max:5',
            'phone_number' => [
                'required',
                Rule::unique('wedding_users')->where(function ($query) use($data,$id) {
                    return $query->where('phone_code', $data['phone_code'])
                    ->where('phone_number', $data['phone_number']);
                }),
                'min:8',
                'max:10'
            ],
            'dob'          => 'required|date',
            'type'         => 'required|int',
            'nationality' => 'required|string',

        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }

        $countNumber = strlen($request->phone_number);

        if (!is_numeric($request->phone_number)) {
            return response()->json(['status' => false, 'message' => trans('auth.not_valid_digit')], 400);
        }

        if($countNumber < 8 && $countNumber > 10){
           return response()->json(['status' => false, 'message' => trans('auth.not_valid_number')], 400);
        }

        $phone_code = (isset($request['phone_code'])) ? ltrim($request['phone_code'], '+') : null;

        $userExist = WeddingUsers::where('owner_id',$wedding_user->id)->where('first_name', $request->first_name)->where('type', $request->type)->where('family_name', $request->family_name)->where('active', 1)->first();

        $idUser = (isset($userExist) && !empty($userExist)) ? $userExist->id : 0;

        try
        {

            $dobDate = Carbon::parse($request['dob']);
            $newDate   = $dobDate->format('Y-m-d');

            $ageCalculate = Carbon::parse($newDate)->diff(Carbon::now())->y;


            // Create wedding member
            $weddingMember = WeddingUsers::updateOrCreate([
                'id' => $idUser,
            ], [
                'owner_id' => $wedding_user->id,
                'first_name' => isset($request['first_name']) ? $request['first_name'] : null,
                'second_name' => isset($request['second_name']) ? $request['second_name'] : null,
                'third_name' => isset($request['third_name']) ? $request['third_name'] : null,
                'family_name' => isset($request['family_name']) ? $request['family_name'] : null,
                'father_name' => isset($request['father_name']) ? $request['father_name'] : null,
                'mother_name' => isset($request['mother_name']) ? $request['mother_name'] : null,
                'email_id' => isset($request['email_id']) ? $request['email_id'] : null,
                'phone_code' => $phone_code,
                'phone_number' => isset($request['phone_number']) ? $request['phone_number'] : null,
                'dob' => $newDate,
                'age' => $ageCalculate,
                'type' => isset($request['type']) ? $request['type'] : 0,
                'civil_id_number' => isset($request['civil_id_number']) ? $request['civil_id_number'] : null,
                'nationality' => isset($request['nationality']) ? $request['nationality'] : null,
                'active' => 1,
            ]);

            // If user uploaded an avatar
            $civilIdImage = $request->civil_id_image;
            if($civilIdImage) {
                # get file from request object
                if (false !== mb_strpos($civilIdImage->getMimeType(), "image")) {
                    $image = $request->file('civil_id_image');
                    # finally upload your file to s3 bucket
                    $s3filePath = $image->store('images/property_asset', 's3');
                    $weddingMember->civil_id_image = $s3filePath;
                    $weddingMember->save();
                }
            }
        }
        catch(\Illuminate\Database\QueryException $exception) {
            return response()->json(['status' => false, 'message' => $exception->errorInfo], 500);
        }

        // If wedding member not created
        if(!$weddingMember){
            return response()->json(['status' => false, 'message' => trans('auth.user_not_created')], 500);
        }

        return ['status' => true, 'message' => trans('auth.user_created'), 'data' => $weddingMember];

    }

    //Get wedding member
    public function getWeddingMembers(Request $request)
    {
         $first_name        = (isset($request->filter) && !empty($request->filter['first_name'])) ? $request->filter['first_name'] : null;
        $second_name        = (isset($request->filter) && !empty($request->filter['second_name'])) ? $request->filter['second_name'] : null;
        $third_name         = (isset($request->filter) && !empty($request->filter['third_name'])) ? $request->filter['third_name'] : null;
        $family_name        = (isset($request->filter) && !empty($request->filter['family_name'])) ? $request->filter['family_name'] : null;

        $father_name        = (isset($request->filter) && !empty($request->filter['father_name'])) ? $request->filter['father_name'] : null;
        $mother_name        = (isset($request->filter) && !empty($request->filter['mother_name'])) ? $request->filter['mother_name'] : null;
        $email_id           = (isset($request->filter) && !empty($request->filter['email_id'])) ? $request->filter['email_id'] : null;
        $dob           = (isset($request->filter) && !empty($request->filter['dob'])) ? $request->filter['dob'] : null;

        $civil_id_number    = (isset($request->filter) && !empty($request->filter['civil_id_number'])) ? $request->filter['civil_id_number'] : null;
        $nationality        = (isset($request->filter) && !empty($request->filter['nationality'])) ? $request->filter['nationality'] : null;

        if(isset($request->filter)){
            $request['filter'] = ['first_name' => $first_name, 'second_name' => $second_name, 'third_name' => $third_name, 'family_name' => $family_name, 'father_name' => $father_name, 'mother_name' => $mother_name, 'email_id' => $email_id, 'dob' => $dob, 'civil_id_number' => $civil_id_number, 'nationality' => $nationality];
        }


        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }


        $allMemebers = QueryBuilder::for(WeddingUsers::class)

        ->allowedFilters([
        AllowedFilter::exact('first_name', 'first_name'),
        AllowedFilter::exact('second_name', 'second_name'),
        AllowedFilter::exact('third_name', 'third_name'),
        AllowedFilter::exact('family_name', 'family_name'),
        AllowedFilter::exact('father_name', 'father_name'),
        AllowedFilter::exact('mother_name', 'mother_name'),
        AllowedFilter::exact('email_id', 'email_id'),
        AllowedFilter::exact('dob', 'dob'),
        AllowedFilter::exact('civil_id_number', 'civil_id_number'),
        AllowedFilter::exact('nationality', 'nationality'),
        ])
        ->with(['owner'])
        ->where('owner_id',$wedding_user->id)
        ->where('active',1)
        ->orderBy('id', 'asc')
        ->paginate($request->per_page);

        $dataArray = [];
        foreach($allMemebers as $key => $weddingMember){
            $dataArray[$key] = $weddingMember;
        }

        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $dataArray]);

    }

    // create wedding member
    public function createWeddingEvent(Request $request)
    {
        $id = 0;
        $data = $request->all();

        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }

        $validator = Validator::make($data, [
            'groom_title'        => 'required|string',
            'groom_first_name'   => 'required|string',
            'groom_family_name'  => 'required|string',
            'groom_father_name'  => 'required|string',
            'groom_mother_name'  => 'required|string',
            'bride_title'        => 'required|string',
            'bride_first_name'   => 'required|string',
            'bride_family_name'  => 'required|string',
            'bride_father_name'  => 'required|string',
            'bride_mother_name'  => 'required|string',
            'event_date'         => 'required|date',
            'event_time'         => 'required',
            'location'           => 'required',
            'latitude'           => 'required',
            'longitude'          => 'required',
            'event_title'        => [
                'required',
                Rule::unique('events')->where(function ($query) use($data,$id, $wedding_user) {
                    return $query->where('event_title', $data['event_title'])
                    ->where('owner_id', $wedding_user->id);
                }),
            ],

        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }

        try
        {
            //groom title
            $groomTitle = HeadingTitles::where('title', $request->groom_title)->first();

            if(empty($groomTitle)){
                // Create title
                $groomTitle = HeadingTitles::create([
                    'title' => isset($request->groom_title) ? $request->groom_title : null,
                ]);
            }

            $titleGroom = (isset($groomTitle) && !empty($groomTitle)) ? $groomTitle->id  : null;

            //groom data
            $groomExist = WeddingUsers::where('owner_id',$wedding_user->id)->where('first_name', $request->groom_first_name)->where('type', 0)->where('family_name', $request->groom_family_name)->where('active', 1)->first();

            $idGroom = (isset($groomExist) && !empty($groomExist)) ? $groomExist->id : 0;

            $dobDate = (!empty($request['groom_dob'])) ? Carbon::parse($request['groom_dob']) : null;
            $newDate = (!empty($request['groom_dob'])) ? $dobDate->format('Y-m-d') : null;
            $ageCalculate = (!empty($request['groom_dob'])) ? Carbon::parse($newDate)->diff(Carbon::now())->y : null;
            $groom_phone_code = (!empty($request['groom_phone_code'])) ? ltrim($request['groom_phone_code'], '+') : null;

            // Create wedding member
            $groomMember = WeddingUsers::updateOrCreate([
                'id' => $idGroom,
            ], [
                'owner_id' => $wedding_user->id,
                'heading_title_id' => $titleGroom,
                'first_name' => isset($request['groom_first_name']) ? $request['groom_first_name'] : null,
                'second_name' => isset($request['groom_second_name']) ? $request['groom_second_name'] : null,
                'third_name' => isset($request['groom_third_name']) ? $request['groom_third_name'] : null,
                'family_name' => isset($request['groom_family_name']) ? $request['groom_family_name'] : null,
                'father_name' => isset($request['groom_father_name']) ? $request['groom_father_name'] : null,
                'mother_name' => isset($request['groom_mother_name']) ? $request['groom_mother_name'] : null,
                'email_id' => isset($request['groom_email_id']) ? $request['groom_email_id'] : null,
                'phone_code' => isset($request['groom_phone_code']) ? $groom_phone_code : null,
                'phone_number' => isset($request['groom_phone_number']) ? $request['groom_phone_number'] : null,
                'dob' => $newDate,
                'age' => $ageCalculate,
                'type' => 0,
                'civil_id_number' => isset($request['groom_civil_id_number']) ? $request['groom_civil_id_number'] : null,
                'nationality' => isset($request['groom_nationality']) ? $request['groom_nationality'] : null,
                'active' => 1,
            ]);

            // If user uploaded an avatar
            $civilIdImageGroom = $request->groom_civil_id_image;
            if($civilIdImageGroom) {
                # get file from request object
                if (false !== mb_strpos($civilIdImageGroom->getMimeType(), "image")) {
                    $image = $request->file('groom_civil_id_image');
                    # finally upload your file to s3 bucket
                    $s3filePath = $image->store('images/property_asset', 's3');
                    $groomMember->groom_civil_id_image = $s3filePath;
                    $groomMember->save();
                }
            }

            //bride title
            $brideExist = HeadingTitles::where('title', $request->bride_title)->first();

            if(empty($brideExist)){
                // Create title
                $brideExist = HeadingTitles::create([
                    'title' => isset($request->bride_title) ? $request->bride_title : null,
                ]);
            }

            $titleBride = (isset($brideExist) && !empty($brideExist)) ? $brideExist->id : null;

            //bride data
            $brideExist = WeddingUsers::where('owner_id',$wedding_user->id)->where('first_name', $request->bride_first_name)->where('type', 0)->where('family_name', $request->bride_family_name)->where('active', 1)->first();

            $idBride = (isset($brideExist) && !empty($brideExist)) ? $brideExist->id : 0;

            $dobDate = (!empty($request['bride_dob'])) ? Carbon::parse($request['bride_dob']) : null;
            $newDate = (!empty($request['bride_dob'])) ? $dobDate->format('Y-m-d') : null;
            $ageCalculate = (!empty($request['bride_dob'])) ? Carbon::parse($newDate)->diff(Carbon::now())->y : null;
            $bride_phone_code = (!empty($request['bride_phone_code'])) ? ltrim($request['bride_phone_code'], '+') : null;

            // Create wedding member
            $brideMember = WeddingUsers::updateOrCreate([
                'id' => $idBride,
            ], [
                'owner_id' => $wedding_user->id,
                'heading_title_id' => $titleBride,
                'first_name' => isset($request['bride_first_name']) ? $request['bride_first_name'] : null,
                'second_name' => isset($request['bride_second_name']) ? $request['bride_second_name'] : null,
                'third_name' => isset($request['bride_third_name']) ? $request['bride_third_name'] : null,
                'family_name' => isset($request['bride_family_name']) ? $request['bride_family_name'] : null,
                'father_name' => isset($request['bride_father_name']) ? $request['bride_father_name'] : null,
                'mother_name' => isset($request['bride_mother_name']) ? $request['bride_mother_name'] : null,
                'email_id' => isset($request['bride_email_id']) ? $request['bride_email_id'] : null,
                'phone_code' => isset($request['bride_phone_code']) ? $bride_phone_code : null,
                'phone_number' => isset($request['bride_phone_number']) ? $request['bride_phone_number'] : null,
                'dob' => $newDate,
                'age' => $ageCalculate,
                'type' => 0,
                'civil_id_number' => isset($request['bride_civil_id_number']) ? $request['bride_civil_id_number'] : null,
                'nationality' => isset($request['bride_nationality']) ? $request['bride_nationality'] : null,
                'active' => 1,
            ]);

            // If user uploaded an avatar
            $civilIdImageBride = $request->bride_civil_id_image;
            if($civilIdImageBride) {
                # get file from request object
                if (false !== mb_strpos($civilIdImageBride->getMimeType(), "image")) {
                    $image = $request->file('bride_civil_id_image');
                    # finally upload your file to s3 bucket
                    $s3filePath = $image->store('images/property_asset', 's3');
                    $brideMember->bride_civil_id_image = $s3filePath;
                    $brideMember->save();
                }
            }


            $eventDate = Carbon::parse($request['event_date']);
            $newDate   = $eventDate->format('Y-m-d');

            $eventTime = Carbon::parse($request['event_time']);
            $newTime   = $eventTime->format('h:i:s');

            // Create wedding member
            $weddingEvent = Events::create([
                'owner_id' => $wedding_user->id,
                'groom_id' => isset($groomMember) ? $groomMember->id : null,
                'bride_id' => isset($brideMember) ? $brideMember->id : null,
                'event_title' => isset($request['event_title']) ? $request['event_title'] : null,
                'welcome_msg' => isset($request['welcome_msg']) ? $request['welcome_msg'] : null,
                'event_date' => $newDate,
                'event_time' => $newTime,
                'location' => isset($request['location']) ? $request['location'] : null,
                'latitude' => isset($request['latitude']) ? $request['latitude'] : null,
                'longitude' => isset($request['longitude']) ? $request['longitude'] : null,
                'google_maps_url' => isset($request['google_maps_url']) ? $request['google_maps_url'] : null,
                'active' => 1,
                'priority' => isset($request['priority']) ? $request['priority'] : 1,
            ]);

        }
        catch(\Illuminate\Database\QueryException $exception) {
            return response()->json(['status' => false, 'message' => $exception->errorInfo], 500);
        }

        // If wedding member not created
        if(!$weddingEvent){
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 500);
        }

        $weddingEventData = Events::with(['owner', 'groom.heading_title', 'bride.heading_title'])->where('id',$weddingEvent->id)->first();

        return ['status' => true, 'message' => trans('messages.success'), 'data' => $weddingEventData];

    }

    //Get wedding Events
    public function getWeddingEvents(Request $request)
    {
        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }

        $event_title        = (isset($request->filter) && !empty($request->filter['event_title'])) ? $request->filter['event_title'] : null;
        $welcome_msg        = (isset($request->filter) && !empty($request->filter['welcome_msg'])) ? $request->filter['welcome_msg'] : null;
        $event_date         = (isset($request->filter) && !empty($request->filter['event_date'])) ? $request->filter['event_date'] : null;
        $event_time        = (isset($request->filter) && !empty($request->filter['event_time'])) ? $request->filter['event_time'] : null;

        $location        = (isset($request->filter) && !empty($request->filter['location'])) ? $request->filter['location'] : null;

        if(isset($request->filter)){
            $request['filter'] = ['event_title' => $event_title, 'welcome_msg' => $welcome_msg, 'event_date' => $event_date, 'event_time' => $event_time, 'location' => $location];
        }

        $allEvents = QueryBuilder::for(Events::class)
        ->allowedFilters(['event_title', 'welcome_msg', 'event_date', 'event_time', 'location'])
        ->with(['owner', 'groom.heading_title', 'bride.heading_title'])
        ->where('owner_id',$wedding_user->id)
        ->where('active',1)
        ->orderBy('id', 'asc')
        ->paginate($request->per_page);

        $dataArray = [];
        foreach($allEvents as $key => $weddingEvent){
            $dataArray[$key] = $weddingEvent;
        }

        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $dataArray]);

    }

    // create Group
    public function createGroup(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'title'   => 'required',
            'event_id'  => 'required',
            'user_id'  => 'required',
        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }


        $wedding_user = User::find($data["user_id"]);
        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }





        $eventExist = Events::where('owner_id',$wedding_user->id)->where('id', $data["event_id"])->where('active', 1)->first();

        if(empty($eventExist)){
            return response()->json(['status' => false, 'message' => trans('auth.event_not_exist')], 500);
        }

        try
        {

            $groupExist = Groups::where('user_id',$wedding_user->id)->where('event_id',$eventExist->id)->where('title', $data["title"])->where('active', 1)->first();

            $idGroup = (isset($groupExist) && !empty($groupExist)) ? $groupExist->id : 0;

            // Create group
            $group = Groups::updateOrCreate([
                'id' => $idGroup,
            ], [
                'user_id' => $wedding_user->id,
                'event_id' => $eventExist->id,
                'title' => isset($data['title']) ? $data['title'] : null,
                'active' => 1,
            ]);

        }
        catch(\Illuminate\Database\QueryException $exception) {
            return response()->json(['status' => false, 'message' => $exception->errorInfo], 500);
        }

        // If group not created
        if(!$group){
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 500);
        }

        $groupData = Groups::with(['user', 'event', 'event.owner', 'event.groom.heading_title', 'event.bride.heading_title'])->where('id',$group->id)->first();

        return ['status' => true, 'message' => trans('messages.success'), 'data' => $groupData];
        //group

    }

    //Get Group
    public function getAllGroup(Request $request)
    {
        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }


        $allGroups = Groups::where('active',1)
        ->where('user_id',$wedding_user->id)
        ->where('event_id',$request->event_id)
        ->orderBy('id', 'asc')
        ->get();


        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $allGroups]);

    }

    // Delete records
    public function deleteGuest(Request $request)
    {
        $data = json_decode($request->getContent(), true);


        $validator = Validator::make($data, [
            'user_id'               => 'required',
            'event_id'               => 'required',
            'guest_id'               => 'required',
        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }

        $wedding_user = User::find($data["user_id"]);
        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }

        $guest = Guests::findOrFail($data["guest_id"]);
        $guest->delete();


        return ['status' => true, 'message' => trans('messages.success'), 'data' => ''];




    }

    // create guest
    public function createGuest(Request $request)
    {
        $id = 0;
        $data = $request->all();

        $data = json_decode($request->getContent(), true);


        $wedding_user = User::find($data["user_id"]);
        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }



        $validator = Validator::make($data, [
            'event'               => 'required',
            'group'               => 'required',
            'title'               => 'required|string',
            'first_name'               => 'required|string',
            'email_id' => [
                'required',
                Rule::unique('guests')->where(function ($query) use($data,$id,$wedding_user) {
                    return $query->where('email_id', $data['email_id'])
                    ->where('user_id', '<>', $wedding_user->id);
                }),
                'email:strict',
            ],
            'phone_code'          => 'required|string|max:5',
            'phone_number' => [
                'required',
                Rule::unique('guests')->where(function ($query) use($data,$id,$wedding_user) {
                    return $query->where('phone_code', $data['phone_code'])
                    ->where('phone_number', $data['phone_number'])
                    ->where('user_id', '<>', $wedding_user->id);
                }),
                'min:8',
                'max:10',
            ],
            'whatsapp_phone_code' => 'required|string|max:5',
            'whatsapp_phone_number' => [
                'required',
                Rule::unique('guests')->where(function ($query) use($data,$id,$wedding_user) {
                    return $query->where('whatsapp_phone_code', $data['whatsapp_phone_code'])
                    ->where('whatsapp_phone_number', $data['whatsapp_phone_number'])
                    ->where('user_id', '<>', $wedding_user->id);
                }),
                'min:8',
                'max:10',
            ],
            'companian_available' => 'required|int',

        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }


        $eventExist = Events::where('owner_id',$wedding_user->id)->where('id', $data["event"])->where('active', 1)->first();


        if(empty($eventExist)){
            return response()->json(['status' => false, 'message' => trans('auth.event_not_exist')], 500);
        }

        $groupExist = Groups::where('user_id',$wedding_user->id)->where('title', $data["group"])->where('active', 1)->first();

        if(empty($groupExist)){
            // Create group
                $groupExist = Groups::create([
                    'user_id' => $wedding_user->id,
                    'event_id' => $eventExist->id,
                    'title' => isset(data['group']) ? data['group'] : null,
                    'active' => 1,
                ]);
        }

        try
        {

            $idEvent = (isset($eventExist) && !empty($eventExist)) ? $eventExist->id : null;
            $idGroup = (isset($groupExist) && !empty($groupExist)) ? $groupExist->id : null;

            //groom title
            $guestTitle = HeadingTitles::where('title', $data['title'])->first();

            if(empty($guestTitle)){
                // Create title
                $guestTitle = HeadingTitles::create([
                    'title' => isset($data['title']) ? $data['title'] : null,
                ]);
            }

            $titleGuest = (isset($guestTitle) && !empty($guestTitle)) ? $guestTitle->id  : null;

            $dobDate = (!empty($data['dob'])) ? Carbon::parse($data['dob']) : null;
            $newDate = (!empty($data['dob'])) ? $dobDate->format('Y-m-d') : null;
            $ageCalculate = (!empty($data['dob'])) ? Carbon::parse($newDate)->diff(Carbon::now())->y : null;

            $phone_code = (!empty($data['phone_code'])) ? ltrim($data['phone_code'], '+') : null;

            $whatsapp_phone_code = (!empty($data['whatsapp_phone_code'])) ? ltrim($data['whatsapp_phone_code'], '+') : null;


            $guestExist = Guests::where('user_id',$wedding_user->id)->where('event_id', $idEvent)->where('group_id', $idGroup)->where('first_name', $data['first_name'])->where('family_name', $data['family_name'])->first();

            $idGuest = (isset($guestExist) && !empty($guestExist)) ? $guestExist->id : null;

            // Create Guest
            $guest = Guests::updateOrCreate([
                'id' => $idGuest,
                ], [
                'user_id' => $wedding_user->id,
                'event_id' => $idEvent,
                'group_id' => $idGroup,
                'heading_title_id' => $titleGuest,

                'first_name' => isset($data['first_name']) ? $data['first_name'] : null,
                'second_name' => isset($data['second_name']) ? $data['second_name'] : null,
                'third_name' => isset($data['third_name']) ? $data['third_name'] : null,
                'family_name' => isset($data['family_name']) ? $data['family_name'] : null,
                'email_id' => isset($data['email_id']) ? $data['email_id'] : null,

                'phone_code' => isset($data['phone_code']) ? $phone_code : null,
                'phone_number' => isset($data['phone_number']) ? $data['phone_number'] : null,

                'whatsapp_phone_code' => isset($data['whatsapp_phone_code']) ? $whatsapp_phone_code : null,
                'whatsapp_phone_number' => isset($data['whatsapp_phone_number']) ? $data['whatsapp_phone_number'] : null,

                'companian_available' => isset($data['companian_available']) ? $data['companian_available'] : 0,
                'dob' => $newDate,
                'age' => $ageCalculate,
            ]);

            $companian = $data["companian"];
            if(!empty($guest) && !empty($companian) && $guest->companian_available == 1){
                Companians::where('guest_id', $guest->id)->delete();
                foreach ($companian as $key => $companianData) {
                    $firstName = $companianData['first_name'];
                    $familyName = $companianData['family_name'];

                    Companians::create([
                        'user_id' => $wedding_user->id,
                        'event_id' => $idEvent,
                        'group_id' => $idGroup,
                        'guest_id' => $guest->id,
                        'first_name' => isset($firstName) ? $firstName : null,
                        'family_name' => isset($familyName) ? $familyName : null,
                    ]);
                }
            }

        }
        catch(\Illuminate\Database\QueryException $exception) {
            return response()->json(['status' => false, 'message' => $exception->errorInfo], 500);
        }

        // If guest not created
        if(!$guest){
            return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 500);
        }

        $guestData = Guests::with(['heading_title', 'group', 'companian'])->where('id',$guest->id)->first();

        return ['status' => true, 'message' => trans('messages.success'), 'data' => $guestData];
    }

    //Get Guest
    public function getAllGuest(Request $request)
    {
        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }

        $search = (isset($request->filter) && !empty($request->filter['guest_name'])) ? $request->filter['guest_name'] : null;

        $groups = Groups::with(['guest' => function ($query) use ($search) {
                    $query->when($search, function ($query) use ($search) {
                        $query->where('guests.first_name', 'like', '%'.$search.'%');
                        $query->orWhere('guests.second_name', 'like', '%'.$search.'%');
                        $query->orWhere('guests.third_name', 'like', '%'.$search.'%');
                        $query->orWhere('guests.family_name', 'like', '%'.$search.'%');
                    });
                }, 'guest.heading_title', 'guest.companian', 'event', 'event.owner', 'event.groom.heading_title', 'event.bride.heading_title', 'event.groom.heading_title', 'event.bride.heading_title', 'guest.invitation', 'guest.invitation.invitation_type', 'guest.invitation.invitation_status'])
        ->where('user_id',$wedding_user->id)
        ->get();

        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $groups]);

    }

    //Get Inviatation Type
    public function getAllInviatationType(Request $request)
    {
        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }


        $allTypes = InvitationTypes::where('active',1)
        ->orderBy('id', 'asc')
        ->get();

        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $allTypes]);

    }

    //Get Inviatation Status
    public function getAllInviatationStatus(Request $request)
    {
        $wedding_user = User::find($request->user_id);

        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }


        $invitationExist = Invitations::where('sender_id', $wedding_user->id)->get();

        $invited = $invitationExist->where('invitation_status_id', 2)->count();

        $accpeted = $invitationExist->where('invitation_status_id', 3)->count();

        $decline = $invitationExist->where('invitation_status_id', 4)->count();

        $notDecided = $invitationExist->where('invitation_status_id', 5)->count();

        $pending = $invited - ($accpeted + $decline + $notDecided);


        $array['invited'] = $invited;
        $array['accpeted'] = $accpeted;
        $array['decline'] = $decline;
        $array['notDecided'] = $notDecided;
        $array['pending'] = ($pending >= 0) ? $pending : 0;

        return response()->json(['status' => true, 'message' => trans('messages.success'), 'data' => $array]);

    }

    public function getStatistics(Request $request)
    {

    }



    // Send invitation
    public function sendInvitation(Request $request)
    {
        $data = $request->all();

        $data = json_decode($request->getContent(), true);


        $wedding_user = User::find($data["user_id"]);
        if (!$wedding_user) {
            return response()->json(['status' => false, 'message' => trans('auth.user_not_exist')], 400);
        }


        $validator = Validator::make($data, [
            'event_id'            => 'required|int',
            'receiver_id'         => 'required|int',
            'invitation_type_id'  => 'required|int',
        ]);

        $message = count($validator->errors()->all()) > 1 ? implode(' ', $validator->errors()->all()) : $validator->errors()->first();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors(), 'message' => $message], 400);
        }


        $eventExist = Events::where('owner_id',$wedding_user->id)->where('id', $data["event_id"])->where('active', 1)->first();


        if(empty($eventExist)){
            return response()->json(['status' => false, 'message' => trans('auth.event_not_exist')], 500);
        }

        $receiverExist = Guests::where('user_id',$wedding_user->id)->with(['heading_title'])->where('event_id', $data["event_id"])->where('id', $data["receiver_id"])->first();


        if(empty($receiverExist)){
            return response()->json(['status' => false, 'message' => trans('auth.receiver_not_exist')], 500);
        }

        $invitationTypeExist = InvitationTypes::where('id',$data["invitation_type_id"])->first();


        if(empty($invitationTypeExist)){
            return response()->json(['status' => false, 'message' => trans('auth.invitation_type_not_exist')], 500);
        }

        try
        {
            $idEvent = $eventExist->id;
            $nameEvent = $eventExist->event_title;
            $idInvitationType = $invitationTypeExist->id;
            $typeInvitation = $invitationTypeExist->title;

            //Receiver information
            $idReceiver = $receiverExist->id;
            $nameReceiver = $receiverExist->heading_title->title.' '.$receiverExist->first_name.' '.$receiverExist->family_name;
            $phone_number = $receiverExist->phone_code . $receiverExist->phone_number;
            $emailId = $receiverExist->email_id;


            $codeInvitation = Str::random(10);

            $codeExist = Invitations::where('invitation_code', $codeInvitation)->first();
            if(!empty($codeExist)){
                $codeInvitation = Str::random(10);
            }

            $invitationExist = Invitations::where('sender_id', $wedding_user->id)
                ->where('event_id', $idEvent)
                ->where('receiver_id', $idReceiver)
                ->where('invitation_status_id', 1)
                ->first();

            $appLink = config('app.url');

            $link = $appLink.'/invitation/'.$codeInvitation;
            $welcomeMessage = 'السيد '.$nameReceiver.', يشرفنا دعوتكم لحضور حفل زفاف الجوهرة صباح الخالد الصباح وجابر ثامر جابر الأحمد، يرجى الضغط على رابط الدعوة الخاص بكم: ' . $link;


            $idInvitation = (isset($invitationExist) && !empty($invitationExist)) ? $invitationExist->id : null;

            if($typeInvitation == 'SMS'){
                $curl = curl_init();
                $url = "https://www.smsbox.com/SMSGateway/Services/Messaging.asmx/Http_SendSMS?";
                $fields = array(
                    'username' => 'boostkwt', 'password' => 'boost@KW20', 'customerId' => '2576', 'senderText' => 'BOOSTKWT', 'messageBody' => $welcomeMessage,
                    'recipientNumbers' => $phone_number, 'defdate' => '', 'isBlink' => 'false', 'isFlash' => 'false'
                ); //parameters to be sent
                $fields_string = http_build_query($fields); //convert param to json string

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url . $fields_string,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Accept: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $xml = simplexml_load_string($response);
                $json = json_encode($xml);
                $sms_response_json = json_decode($json, TRUE);
                curl_close($curl);

                if ($sms_response_json["Result"] == 'true') {

                    $invitation = Invitations::updateOrCreate([
                        'id' => $idInvitation,
                    ], [
                        'invitation_code'      => $codeInvitation,
                        'sender_id'            => $wedding_user->id,
                        'event_id'             => $idEvent,
                        'receiver_id'          => $idReceiver,
                        'invitation_type_id'   => $idInvitationType,
                        'invitation_status_id' => 2,
                        'active' => 1,
                    ]);

                    $invitationSend = InvitationSends::create([
                        'type'                 => $idInvitationType,
                        'title'                => 'Invitation',
                        'welcome_message'      => $welcomeMessage,
                        'event_id'             => $idEvent,
                        'sender_id'            => $wedding_user->id,
                        'receiver_id'          => $idReceiver,
                        'invitation_type_id'   => $idInvitationType,
                        'invitation_id'        => $invitation->id,
                        'active' => 1,
                    ]);

                    return ['status' => true, 'message' => trans('messages.success'), 'data' => $invitationSend];
                }
            }elseif($typeInvitation == 'Email'){

                // Prepare data for email
                $data = [
                    'name'  => $nameReceiver,
                    'email' => $emailId,
                    'event' => $nameEvent,
                    'link'  => $link,
                    'message' => 'Hello, this is a test email.'
                ];

                // Send email
                $sent = Mail::send('mail', $data, function ($message) use ($data) {
                    $message->from('r.multani@techgrowthkw.com','Wedding App');
                    $message->to($data['email'], $data['name']);
                    $message->subject('Invitation');
                });

                // Check if email was sent
                if ($sent) {

                    $invitation = Invitations::updateOrCreate([
                        'id' => $idInvitation,
                    ], [
                        'invitation_code'      => $codeInvitation,
                        'sender_id'            => $wedding_user->id,
                        'event_id'             => $idEvent,
                        'receiver_id'          => $idReceiver,
                        'invitation_type_id'   => $idInvitationType,
                        'invitation_status_id' => 2,
                        'active' => 1,
                    ]);

                    $invitationSend = InvitationSends::create([
                        'type'                 => $idInvitationType,
                        'title'                => 'Invitation',
                        'welcome_message'      => $welcomeMessage,
                        'event_id'             => $idEvent,
                        'sender_id'            => $wedding_user->id,
                        'receiver_id'          => $idReceiver,
                        'invitation_type_id'   => $idInvitationType,
                        'invitation_id'        => $invitation->id,
                        'active' => 1,
                    ]);

                    return ['status' => true, 'message' => trans('messages.success'), 'data' => $invitationSend];
                }

            }elseif($typeInvitation == 'WhatsApp'){

            }

        }
        catch(\Illuminate\Database\QueryException $exception) {
            return response()->json(['status' => false, 'message' => $exception->errorInfo], 500);
        }
        return response()->json(['status' => false, 'message' => trans('auth.went_wrong')], 500);

    }


}
