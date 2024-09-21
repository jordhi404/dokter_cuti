<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\doctor;

class doctorStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_fetch_doctor_status(): void
    {
        $doctors = Doctor::with('doctorStatus')->get();

        $this->assertNotEmpty($doctors, 'No doctors found in the database.');

        foreach ($doctors as $doctor) {
            foreach($doctor->doctorStatus as $status) {
                $expectedStatus = $status->qmax == 0 ? 'CUTI' : 'ON DUTY';

                $this->assertEquals($expectedStatus, $expectedStatus, 
                    "Status dokter {$doctor->nama} tidak sesuai"
                );
            }
        }
    }
}
