<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use App\Models\Member;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MemberRequest;

class MemberController extends Controller
{
    public function store(MemberRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = Member::create($request->only(['name', 'email', 'phone']));

            $hobbies = [];
            foreach ($request->hobbies as $value) {
                $hobbies[] = [
                    "user_id" => $user->id,
                    "hoby" => $value
                ];
            }

            Hobby::insert($hobbies);

            $member = Member::with("hobbies")->where("id", $user->id)->first();
            DB::commit();

            return response()->json(ResponseFormatter::format(201, "OK", $member), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(ResponseFormatter::format($e->getCode(), $e->getMessage(), null), $e->getCode());
        }
    }

    public function update(MemberRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $member = Member::where("id", $id)->first();
            if(!$member){
                return response()->json(ResponseFormatter::format(404, "NOT FOUND", null), 404);
            }
    
            $emailCheck = Member::where("email", $request->email)->where('id', '!=', $id)->exists();
            if ($emailCheck) {
                return response()->json(ResponseFormatter::format(400, "EMAIL ALREADY BEEN TAKEN", null), 400);
            }
    
            $updateMember = [
                "name" => $request->input("name", $member->name),
                "email" => $request->input("email", $member->email),
                "phone" => $request->input("phone", $member->phone)
            ];
    
            Member::where("id", $id)->update($updateMember);
    
            if ($request->has("hobbies")) {
                $hobbies = collect($request->input("hobbies"))->map(function ($value) use($id) {
                    return ["user_id" => $id ,"hoby" => $value];
                })->toArray();
    
                Hobby::where("user_id", $id)->delete();
                Hobby::insert($hobbies);
            }
    
            $memberUpdate = Member::with("hobbies")->findOrFail($member->id);
            DB::commit();
            
            return response()->json(ResponseFormatter::format(200, "OK", $memberUpdate), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(ResponseFormatter::format($e->getCode(), $e->getMessage(), null), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $member = Member::with("hobbies")->where("id", $id)->first();
            if(!$member){
                return response()->json(ResponseFormatter::format(404, "NOT FOUND", null), 404);
            }

            return response()->json(ResponseFormatter::format(200, "OK", $member), 200);
        } catch (\Exception $e) {
            return response()->json(ResponseFormatter::format($e->getCode(), $e->getMessage(), null), $e->getCode());
        }
    }
    
}
