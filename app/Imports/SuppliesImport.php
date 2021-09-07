<?php

namespace App\Imports;

use App\Models\Supply;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SuppliesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $validator = Validator::make($collection->toArray(), [
            '*.supply_type' => ['required', 'string', 'max:255', 'alpha_spaces'],
            '*.quantity' => ['required', 'numeric', "min:1"],
            '*.source' => ['required', 'string', 'max:255'],
        ])->validate();

        $user = Auth::user();

        $user_inventory_id = User::find($user->id)->user_inventory->id;

        foreach ($collection as $row) {
            if ($row->filter()->isNotEmpty()) {
                $user_inventory_prev_stock = $user->user_inventory()->first();
                if($row['supply_type'] == 'Food Packs'){
                    $user->user_inventory()->update([
                        'total_no_of_food_packs'                    => ($user_inventory_prev_stock->total_no_of_food_packs + $row['quantity']),
                    ]);
                }else if($row['supply_type'] == 'Water'){
                    $user->user_inventory()->update([
                        'total_no_of_water'                         => ($user_inventory_prev_stock->total_no_of_water + $row['quantity']),
                    ]);
                }else if($row['supply_type'] == 'Hygiene Kit'){
                    $user->user_inventory()->update([
                        'total_no_of_hygiene_kit'                   => ($user_inventory_prev_stock->total_no_of_hygiene_kit + $row['quantity']),
                    ]);
                }else if($row['supply_type'] == 'Medicine'){
                    $user->user_inventory()->update([
                        'total_no_of_medicine'                      => ($user_inventory_prev_stock->total_no_of_medicine + $row['quantity']),
                    ]);
                }else if($row['supply_type'] == 'Clothes'){
                    $user->user_inventory()->update([
                        'total_no_of_clothes'                       => ($user_inventory_prev_stock->total_no_of_clothes + $row['quantity']),
                    ]);
                }else if($row['supply_type'] == 'ESA'){
                    $user->user_inventory()->update([
                        'total_no_of_emergency_shelter_assistance'  => ($user_inventory_prev_stock->total_no_of_emergency_shelter_assistance + $row['quantity']),
                    ]);
                } 
                $supply = new Supply();
                $supply->inventory_id     = $user_inventory_id;
                $supply->supply_type   = $row['supply_type'];
                $supply->quantity = $row['quantity'];
                $supply->source = $row['source'];
                $supply->save();
            }
        }
    }
}