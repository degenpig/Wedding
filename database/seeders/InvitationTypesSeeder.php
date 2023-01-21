<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvitationTypes;

class InvitationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $records = $this->records();

        foreach ($records as $record) {

            $invitationType =  InvitationTypes::where('title', '=', $record['title'])->first();

            if(empty($invitationType)) {

                InvitationTypes::insert([
                    'id'          => $record['id'],
                    'title'       => ucfirst($record['title']),
                    'active'      => 1,
                ]);  

            }
            
        }
    }

     protected function records() {
        return [
            ["id" => "1","title" => "SMS"],
            ["id" => "2","title" => "Email"],
            ["id" => "3","title" => "WhatsApp"],
        ];
    }
}
