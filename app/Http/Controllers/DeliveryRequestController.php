<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delivery_requests = DB::table('requests')
            ->leftJoin('users', 'requests.camp_manager_id', '=', 'users.id')
            ->leftJoin('evacuation_centers', 'evacuation_centers.camp_manager_id', '=', 'requests.camp_manager_id')
            ->select(
                'users.name as camp_manager_name',
                'evacuation_centers.name as evacuation_center_name',
                'evacuation_centers.id as evacuation_center_id',
                'requests.*')
            ->orderByRaw('updated_at ASC')
            ->paginate(20);

        // SELECT users.name as camp_manager_name, evacuation_centers.name as evacuation_center_name, requests.*
        // FROM public.requests
        // LEFT JOIN users
        // ON requests.camp_manager_id = users.id
        // LEFT JOIN evacuation_centers
        // ON evacuation_centers.camp_manager_id = requests.camp_manager_id
        // ORDER BY updated_at ASC

        return view('admin.request_resource.requestList', ['delivery_requests' => $delivery_requests] );
    }

    public function approve(Request $request)
    {
        $id = $request->input('id');
        $delivery_requests = DeliveryRequest::where('id', '=', $id)->first();
        $delivery_requests->status = "preparing";
        $delivery_requests->save();

        return redirect()->back()->with('message', 'You have approved Request ID ' . $id);
    }

    public function admin_cancel(Request $request)
    {
        $id = $request->input('id');
        DeliveryRequest::where('id', $id)->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('message', 'You have cancelled Request ID ' . $id);

    }
    
    public function admin_decline(Request $request)
    {
        $id = $request->input('id');
        DeliveryRequest::where('id', $id)->update([
            'status' => 'declined'
        ]);

        return redirect()->back()->with('message', 'You have declined Request ID ' . $id);

    }

    public function assign_courier(Request $request)
    {
        $id = $request->input('id');
        DeliveryRequest::where('id', $id)->update([
            'status' => 'in transit',
            'courier_id' => $request->input('courier_id')
        ]);

        $delivery_requests = DeliveryRequest::where('requests.id', '=', $id)
            ->leftJoin('users', 'requests.courier_id', '=', 'users.id')
            ->leftJoin('evacuation_centers', 'evacuation_centers.camp_manager_id', '=', 'requests.camp_manager_id')
            ->select('users.name as courier_name','evacuation_centers.name as evacuation_center_name')
            ->first();
        //dd($delivery_requests->courier_name);
        return redirect()->back()->with('message', 'You have assigned ' 
            . $delivery_requests->courier_name 
            . ' to ' 
            . $delivery_requests->evacuation_center_name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
