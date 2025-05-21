<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear directorio de documentos si no existe
        $documentPath = storage_path('app/public/documents');
        if (!File::exists($documentPath)) {
            File::makeDirectory($documentPath, 0755, true);
        }

        // Crear proveedores de prueba
        $providers = [
            [
                'company' => 'Distribuidora Comercial S.A.',
                'contact_name' => 'Juan Pérez',
                'phone' => '12345678',
                'email' => 'ventas@distribuidoracomercial.com',
                'address' => 'Calle Principal #123, Zona 1',
                'order_website' => 'https://distribuidoracomercial.com/ordenes',
                'billing_email' => 'facturacion@distribuidoracomercial.com',
                'order_phone' => '87654321',
            ],
            [
                'company' => 'Suministros Industriales del Norte',
                'contact_name' => 'María González',
                'phone' => '23456789',
                'email' => 'contacto@suministrosdelnorte.com',
                'address' => 'Avenida Las Américas 5-67, Zona 13',
                'order_website' => 'https://suministrosdelnorte.com/pedidos',
                'billing_email' => 'contabilidad@suministrosdelnorte.com',
                'order_phone' => '98765432',
            ],
            [
                'company' => 'Tecnología Avanzada S.A.',
                'contact_name' => 'Carlos López',
                'phone' => '34567890',
                'email' => 'ventas@tecnologiaavanzada.com',
                'address' => 'Boulevard Los Próceres 8-90, Zona 10',
                'order_website' => 'https://tecnologiaavanzada.com/compras',
                'billing_email' => 'facturacion@tecnologiaavanzada.com',
                'order_phone' => '09876543',
            ],
        ];

        foreach ($providers as $providerData) {
            $provider = Provider::create($providerData);
            
            // Crear algunos documentos de ejemplo (sin archivos reales)
            $documents = [
                ['name' => 'RUT ' . $provider->company, 'file_path' => 'documents/rut_' . Str::slug($provider->company) . '.pdf', 'file_type' => 'application/pdf', 'file_size' => 1024 * 50],
                ['name' => 'Certificado de Cámara de Comercio', 'file_path' => 'documents/camara_' . Str::slug($provider->company) . '.pdf', 'file_type' => 'application/pdf', 'file_size' => 1024 * 75],
            ];
            
            foreach ($documents as $document) {
                $provider->documents()->create($document);
            }
        }
    }
}
