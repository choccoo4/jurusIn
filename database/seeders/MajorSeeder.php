<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $csv = array_map('str_getcsv', file(base_path('api_service/data/Dataset.csv')));
        $header = array_shift($csv);

        foreach ($csv as $row) {
            if (count($row) < 5) continue;

            DB::table('majors')->insert([
                'major_name'    => $row[0], // Jurusan
                'field'         => $row[1], // Bidang
                'description'   => $row[2], // Deskripsi
                'interests'     => $row[3], // Minat
                'keywords'      => $row[4], // Keywords
                'combined_text' => $row[5] ?? '', // combined_text
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('Majors imported: ' . count($csv));
    }
}
