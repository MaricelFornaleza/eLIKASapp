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

class CampManagerController extends Controller
{
    public function evacuees()
    {
        return view('camp-manager.evacuees.evacuees');
    }
    public function admitView()
    {
        return view('camp-manager.evacuees.admit');
    }
    public function groupFam()
    {
        return view('camp-manager.evacuees.group-fam');
    }
    public function dischargeView()
    {
        return view('camp-manager.evacuees.discharge');
    }
    public function supplyView()
    {
        $id = Auth::id();
        $evacuation_center = EvacuationCenter::where('camp_manager_id', '=', $id)->first();
        if(empty($evacuation_center)) {
           abort(403, "You have not been assigned to an evacuation center yet. Contact your adminstrator for further info.");
        } 
        else {
            $stock_level = $evacuation_center->stock_level()->first();
            return view('camp-manager.supply.supplies')->with('stock_level', $stock_level);
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
        $delivery_requests = DeliveryRequest::orderByRaw("CASE WHEN requests.status = 'pending' THEN '1'
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