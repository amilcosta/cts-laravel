<?php

use Illuminate\Database\Seeder;

# Hace uso del modelo de Rol.
use App\Rol;

# Hace uso del modelo de Usuario.
use App\Usuario;

// Le indicamos que utilice también Faker.
// Información sobre Faker: https://github.com/fzaninotto/Faker
use Faker\Factory as Faker;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creamos una instancia de Faker
		$faker = Faker::create();

		# Para cubrir los usuarios tenemos que tener en cuanta qué Roles tenemos.
		# Para que la clave foránea no nos de problemas.
		# Averiguamos cuantos roles hay en la tabla.
		$cuantos= Rol::all()->count();

		# Creamos un bucle para cubrir 2 usuarios:
		for ($i=0; $i<2; $i++)
		{
			// Cuando llamamos al método create del Modelo Usuario 
			// se está creando una nueva fila en la tabla.
			Usuario::create(
				[
				 'rut'		=>$faker->randomNumber(7),
				 'nombre'	=>$faker->word(),//$faker->randomFloat(2,10,150),
				 'apellido'	=>$faker->word(),//$faker->randomNumber(3),	// de 3 dígitos como máximo.
				 'usuario'	=>$faker->word(),//$faker->randomNumber(4),	// de 4 dígitos como máximo.
				 'password'	=>$faker->word(),//$faker->randomNumber(),
				 'rol_id'	=>$faker->numberBetween(1,$cuantos)
				]
			);
		}
    }
}
