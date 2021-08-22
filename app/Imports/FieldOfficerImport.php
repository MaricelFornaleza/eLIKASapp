<?php

namespace App\Imports;

use App\Models\BarangayCaptain;
use App\Models\CampManager;
use App\Models\Contact;
use App\Models\Courier;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;


class FieldOfficerImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $validator = Validator::make($collection->toArray(), [
            '*.name' => ['required', 'string', 'max:255', 'alpha_spaces'],
            '*.email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            '*.contact_no1' => ['required', 'numeric', 'digits:11', 'unique:contacts', 'regex:/^(09)\d{9}$/'],
            '*.contact_no2' => ['nullable', 'numeric', 'digits:11', 'unique:contacts', 'regex:/^(09)\d{9}$/'],
            '*.officer_type' => 'required',
        ])->validate();



        $temp_pass = Str::random(8);

        foreach ($collection as $row) {
            if ($row->filter()->isNotEmpty()) {

                $user =  User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'officer_type' => $row['officer_type'],
                    'password' => Hash::make($temp_pass),
                ]);

                if ($user->officer_type == "Barangay Captain") {
                    BarangayCaptain::create([
                        'user_id' => $user->id,
                        'barangay' => $row['barangay'],
                    ]);
                    Inventory::create([
                        'user_id' => $user->id,
                        'name' => $row['barangay'] . ' Inventory'
                    ]);
                } else if ($user->officer_type == "Camp Manager") {
                    CampManager::create([
                        'user_id' => $user->id,
                        'designation' => $row['designation'],
                    ]);
                } else if ($user->officer_type == "Courier") {
                    $courier = Courier::create([
                        'user_id' => $user->id,
                        'designation' => $row['designation'],
                    ]);
                    //put here location weak entity
                    //dd($courier->id);
                    Location::create([
                        'courier_id' => $courier->id
                    ]);
                }

                Contact::create([
                    'user_id' => $user->id,
                    'contact_no1' => $row['contact_no1'],
                    'contact_no2' => $row['contact_no2'],
                ]);

                //send an email to the newly registered field officer
                //this will contain the temporary password of the user
                // $to_name = $user->name;
                // $to_email = $user->email;
                // $data = [
                //     'name' => $user->name,
                //     'body' => $temp_pass
                // ];
                // Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) {
                //     $message->to($to_email, $to_name)
                //         ->subject('eLIKAS Account Details');
                //     $message->from('elikasph@gmail.com', 'eLIKAS Philippines');
                // });
            }
        }
    }
}