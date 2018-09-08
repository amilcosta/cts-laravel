<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

// Hacemos uso del modelo User.
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Model::unguard();

		$this->call('RolSeeder');
	   	$this->call('UsuarioSeeder');
        // $this->call(UsersTableSeeder::class);

        // Solo queremos un único usuario en la tabla, así que truncamos primero la tabla
		// Para luego rellenarla con los registros.
		//User::truncate();

		// LLamamos al seeder de Users.
		//$this->call('UserSeeder');
    }
}
