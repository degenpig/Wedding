<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Statuses;

class InvitationStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $records = $this->records();

        foreach ($records as $record) {

            $invitationStatus =  Statuses::where('title', '=', $record['title'])->first();

            if(empty($invitationStatus)) {

                Statuses::insert([
                    'id'       => $record['id'],
                    'title'    => ucfirst($record['title']),
                ]);  

            }
            
        }
    }

     protected function records() {
        return [
            ["id" => "1","title" => "Not Yet Invited"],
            ["id" => "2","title" => "Pending"],
            ["id" => "3","title" => "Accepted"],
            ["id" => "4","title" => "Declined"],
            ["id" => "5","title" => "Not Yet Decided"],
        ];
    }
}
