<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    # Nombre de la tabla en MySQL.
	protected $table='usuario';

	# Clave primaria
	protected $primaryKey = 'id_usuario';

	// Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('rut','nombre','apellido','usuario','password');
	
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at']; 

	// Definimos a continuación la relación de esta tabla con otras.
	// Ejemplos de relaciones:
	// 1 usuario tiene 1 teléfono   ->hasOne() Relación 1:1
	// 1 teléfono pertenece a 1 usuario   ->belongsTo() Relación 1:1 inversa a hasOne()
	// 1 post tiene muchos comentarios  -> hasMany() Relación 1:N 
	// 1 comentario pertenece a 1 post ->belongsTo() Relación 1:N inversa a hasMany()
	// 1 usuario puede tener muchos roles  ->belongsToMany()
	//  etc..


	# Relación de Usuario con Rol:
	public function rol()
	{
		// 1 usuario pertenece a un Rol.
		// $this hace referencia al objeto que tengamos en ese momento de Avión.
		return $this->belongsTo('App\Rol');
	}
}
