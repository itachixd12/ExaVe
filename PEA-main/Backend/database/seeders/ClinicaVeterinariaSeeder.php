<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Servicio;
use App\Models\Veterinario;
use Hash;

class ClinicaVeterinariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        User::create([
            'name' => 'Admin Clínica',
            'email' => 'admin@clinica.com',
            'password' => Hash::make('123456'),
            'rol' => 'admin'
        ]);

        User::create([
            'name' => 'Cliente Test',
            'email' => 'cliente@email.com',
            'password' => Hash::make('123456'),
            'rol' => 'cliente'
        ]);

        // Crear servicios
        Servicio::create([
            'nombre' => 'Consulta General',
            'slug' => 'consulta-general',
            'descripcion' => 'Revisión completa del animal, diagnóstico y recomendaciones',
            'precio' => 30.00,
            'tipo' => 'consulta',
            'duracion' => 30
        ]);

        Servicio::create([
            'nombre' => 'Vacunación',
            'slug' => 'vacunacion',
            'descripcion' => 'Vacunas de refuerzo y vacunaciones necesarias',
            'precio' => 25.00,
            'tipo' => 'vacuna',
            'duracion' => 20
        ]);

        Servicio::create([
            'nombre' => 'Baño Completo',
            'slug' => 'bano-completo',
            'descripcion' => 'Baño, secado y acondicionamiento para tu mascota',
            'precio' => 50.00,
            'tipo' => 'bano',
            'duracion' => 60
        ]);

        Servicio::create([
            'nombre' => 'Corte de Uñas',
            'slug' => 'corte-unas',
            'descripcion' => 'Corte seguro y profesional de las uñas',
            'precio' => 15.00,
            'tipo' => 'bano',
            'duracion' => 15
        ]);

        Servicio::create([
            'nombre' => 'Limpieza Dental',
            'slug' => 'limpieza-dental',
            'descripcion' => 'Limpieza profesional de dientes',
            'precio' => 80.00,
            'tipo' => 'consulta',
            'duracion' => 45
        ]);

        Servicio::create([
            'nombre' => 'Cirugía General',
            'slug' => 'cirugia-general',
            'descripcion' => 'Procedimientos quirúrgicos menores y mayores',
            'precio' => 500.00,
            'tipo' => 'cirugia',
            'duracion' => 120
        ]);

        Servicio::create([
            'nombre' => 'Castración/Esterilización',
            'slug' => 'castracion-esterilizacion',
            'descripcion' => 'Procedimiento quirúrgico de castración o esterilización',
            'precio' => 400.00,
            'tipo' => 'cirugia',
            'duracion' => 90
        ]);

        Servicio::create([
            'nombre' => 'Ultrasonido',
            'slug' => 'ultrasonido',
            'descripcion' => 'Diagnóstico por ultrasonido',
            'precio' => 120.00,
            'tipo' => 'consulta',
            'duracion' => 30
        ]);

        // Crear veterinarios
        Veterinario::create([
            'nombre' => 'Dr. Juan Pérez',
            'email' => 'juan.perez@clinica.com',
            'telefono' => '+34 612 345 678',
            'especialidad' => 'Cirugía y Traumatología',
            'licencia' => 'VET-2024-001'
        ]);

        Veterinario::create([
            'nombre' => 'Dra. María García',
            'email' => 'maria.garcia@clinica.com',
            'telefono' => '+34 612 345 679',
            'especialidad' => 'Medicina Interna',
            'licencia' => 'VET-2024-002'
        ]);

        Veterinario::create([
            'nombre' => 'Dr. Carlos López',
            'email' => 'carlos.lopez@clinica.com',
            'telefono' => '+34 612 345 680',
            'especialidad' => 'Odontología Veterinaria',
            'licencia' => 'VET-2024-003'
        ]);

        Veterinario::create([
            'nombre' => 'Dra. Ana Martínez',
            'email' => 'ana.martinez@clinica.com',
            'telefono' => '+34 612 345 681',
            'especialidad' => 'Dermatología',
            'licencia' => 'VET-2024-004'
        ]);

        $this->command->info('Datos de prueba creados exitosamente');
        $this->command->info('Usuarios de prueba:');
        $this->command->info('  Admin: admin@clinica.com / 123456');
        $this->command->info('  Cliente: cliente@email.com / 123456');
    }
}
