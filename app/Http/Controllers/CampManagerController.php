<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EvacuationCenter;
use App\Models\DisasterResponse;
use App\Models\DeliveryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\FamilyMember;
use App\Models\Evacuee;
use App\Models\CampManager;
use App\Models\ReliefRecipient;
use Illuminate\Support\Str;

use App\Http\Controllers\Carbon\Carbon;

class CampManagerController extends Controller
{
    public function evacuees()
    {
        return view('camp-manager.evacuees.evacuees');
    }
    public function admitView()
    {
        $disaster_responses = DisasterResponse::all();
        $family_members = DB::table('family_members')
            ->leftJoin('relief_recipients', 'family_members.family_code', '=', 'relief_recipients.family_code')
            ->whereNotNull('family_members.family_code')->where('family_members.is_family_head', 'Yes')
            ->where(function ($query) {
                $query->where('relief_recipients.recipient_type', 'Non-Evacuee')
                    ->orWhere('relief_recipients.recipient_type', null);
            })
            ->select('family_members.family_code', 'name')
            ->get();
        return view('camp-manager.evacuees.admit', ['family_members' => $family_members, 'disaster_responses' => $disaster_responses]);
    }
    // public function selectFam(Request $request)
    // {
    //    // dd($request->checkedResidents);
    // //    $family_members=array();
    // //    foreach ($request->checkedResidents as $checkedResident) {
    // //     $family_member = FamilyMember::find($checkedResident);
    // //         if($family_member){
    // //             array_push($family_members, $family_member);
    // //         }
    // //     }
    //     //dd($family_members);
    //     // $disaster_responses = DisasterResponse::all();
    //     // return view('camp-manager.evacuees.group-fam', ['disaster_responses' => $disaster_responses, 'family_members' => $family_members]);
    // }
    public function admit(Request $request)
    {
        $user = Auth::user();
        $evacuation_center = DB::table('evacuation_centers')->where('camp_manager_id', $user->id)->first();

        $validated = $request->validate([
            'checkedResidents'              => ['required'],
            'disaster_response_id'              => ['required'],
        ]);
        foreach ($request->checkedResidents as $checkedResident) {
            $find_checkedResident = DB::table('relief_recipients')->where('family_code', $checkedResident)->first();
            if ($find_checkedResident != null) {
                $relief_recipient = ReliefRecipient::find($find_checkedResident->id);
                $relief_recipient->disaster_response_id = $request->disaster_response_id;
                $relief_recipient->recipient_type = 'Evacuee';
                $relief_recipient->save();

                $evacuee = new Evacuee();
                $evacuee->relief_recipient_id = $relief_recipient->id;
                $evacuee->date_admitted = now();
                $evacuee->evacuation_center_id = $evacuation_center->id;
                $evacuee->save();
            } else if ($find_checkedResident == null) {
                $relief_recipient = new ReliefRecipient();
                $relief_recipient->family_code = $checkedResident;
                $relief_recipient->disaster_response_id = $request->disaster_response_id;
                $relief_recipient->recipient_type = 'Evacuee';
                $relief_recipient->save();

                $evacuee = new Evacuee();
                $evacuee->relief_recipient_id = $relief_recipient->id;
                $evacuee->date_admitted = now();
                $evacuee->evacuation_center_id = $evacuation_center->id;
                $evacuee->save();
            }
        }

        $request->session()->flash('message', 'Admit Resident successfully!');
        return view('camp-manager.evacuees.evacuees');
        //  dd(now());
    }
    // public function admit(Request $request)
    // {
    //    // dd($request->selectedResidents, $request->selectedRepresentative);
    // //    $family_members=array();
    // //    foreach ($request->checkedResidents as $checkedResident) {
    // //     $family_member = FamilyMember::find($checkedResident);
    // //         if($family_member){
    // //             array_push($family_members, $family_member);
    // //         }
    // //     }
    // //     //dd($family_members);
    // //      $disaster_responses = DisasterResponse::all();
    // //      return view('camp-manager.evacuees.group-fam', ['disaster_responses' => $disaster_responses, 'family_members' => $family_members]);
    //     $family_code = 'eLIKAS-' . Str::random(6);
    //     if($request->selectedResidents){
    //         foreach ($request->selectedResidents as $selectedResident) {
    //         $family_member = FamilyMember::find($selectedResident);
    //         $family_member->family_code   = $family_code;
    //         $family_member->save();
    //         }
    //     }

    //     $family_member_rep = FamilyMember::find($request->selectedRepresentative);
    //     $family_member_rep->is_representative = 'Yes';
    //     $family_member_rep->save();

    // $relief_recipient = new ReliefRecipient();
    // $relief_recipient->family_code      = $family_code;
    // $relief_recipient->no_of_members    = count($request->selectedResidents);
    // $relief_recipient->address          = 'N/A';
    // $relief_recipient->recipient_type   = 'Evacuee';
    // $relief_recipient->save();

    //     $user = Auth::user();
    //     $evacuation_center = DB::table('evacuation_centers')->where('camp_manager_id', $user->id)->first();
    //   //  $evacuation_center = CampManager::find($camp_manager->user_id)->evacuation_center;

    //   // dd($evacuation_center);

    //     $evacuee = new Evacuee();
    //     $evacuee->relief_recipient_id = $relief_recipient->id;
    //     $evacuee->evacuation_center_id = $evacuation_center->id;
    //     $evacuee->save();

    //     $request->session()->flash('message', 'Admit Resident successfully!');
    //     return view('camp-manager.evacuees.evacuees');
    // }
    public function dischargeView()
    {
        $family_members = DB::table('family_members')
            ->whereNotNull('family_members.family_code')->where('is_family_head', 'Yes')
            ->leftJoin('relief_recipients', 'family_members.family_code', '=', 'relief_recipients.family_code')
            ->where('relief_recipients.recipient_type', 'Evacuee')
            ->select('family_members.family_code', 'name')
            ->get();
        return view('camp-manager.evacuees.discharge', ['family_members' => $family_members]);
    }
    public function discharge(Request $request)
    {
        $validated = $request->validate([
            'checkedEvacuees'              => ['required'],
        ]);
        // dd($request->checkedEvacuees);
        foreach ($request->checkedEvacuees as $checkedEvacuee) {
            $findRelief_recipient = DB::table('relief_recipients')->where('family_code', $checkedEvacuee)->first();
            $relief_recipient = ReliefRecipient::find($findRelief_recipient->id);
            $relief_recipient->recipient_type = 'Non-Evacuee';
            $relief_recipient->save();

            $findEvacuee = DB::table('evacuees')->where('relief_recipient_id', $relief_recipient->id)->where('date_discharged', null)->first();
            $evacuee = Evacuee::find($findEvacuee->id);
            $evacuee->date_discharged = now();
            $evacuee->save();
        }
        $request->session()->flash('message', 'Discharge Evacuee successfully!');
        return view('camp-manager.evacuees.evacuees');
    }
    public function supplyView()
    {
        $id = Auth::id();
        $evacuation_center = EvacuationCenter::where('camp_manager_id', '=', $id)->first();
        if (empty($evacuation_center)) {
            abort(403, "You have not been assigned to an evacuation center yet. Contact your adminstrator for further info.");
        } else {
            $stock_level = $evacuation_center->stock_level()->first();
            return view('camp-manager.supply.supplies', ['capacity' => $evacuation_center->capacity, 'stock_level' => $stock_level]);
        }
    }
    public function dispenseView()
    {
        return view('camp-manager.supply.dispense');
    }
    public function requestSupplyView()
    {
        $disaster_responses = DisasterResponse::all();
        return view('camp-manager.supply.request')->with('disaster_responses', $disaster_responses);
    }
    public function historyView(Request $request)
    {
        $id = Auth::id();
        $delivery_requests = DeliveryRequest::where('camp_manager_id', '=', $id)
            ->orderByRaw("CASE WHEN requests.status = 'pending' THEN '1'
                WHEN requests.status = 'preparing' THEN '2'
                WHEN requests.status = 'in-transit' THEN '3'
                WHEN requests.status = 'delivered' THEN '4'
                WHEN requests.status = 'cancelled' THEN '5'
                WHEN requests.status = 'decline' THEN '6' END ASC, requests.updated_at DESC")
            ->get();
        return view('camp-manager.request.history')->with('delivery_requests', $delivery_requests);
    }
    public function detailsView($id)
    {
        $delivery_request = DeliveryRequest::find($id);
        return view('camp-manager.request.details')->with('delivery_request', $delivery_request);
    }
}