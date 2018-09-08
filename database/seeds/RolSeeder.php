<?php

use Illuminate\Database\Seeder;

# Hace uso del modelo de Rol.
use App\Rol;

// Le indicamos que utilice también Faker.
// Información sobre Faker: https://github.com/fzaninotto/Faker
use Faker\Factory as Faker;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Creamos una instancia de Faker
		$faker = Faker::create();

		# Creamos un bucle para cubrir 3 roles:
		for ($i=0; $i<3; $i++)
		{
			// Cuando llamamos al método create del Modelo Rol 
			// se está creando una nueva fila en la tabla.
			Rol::create(
				[
					'nombre'=>$faker->word(),
					'tipo'	=>$faker->word(),
				]
			);
		}
    }
}
