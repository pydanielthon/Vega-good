<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'Dostep do pracownikow']);
        Permission::create(['name' => 'Zobacz pracownika']);
        Permission::create(['name' => 'Edytuj pracownika']);
        Permission::create(['name' => 'Usun pracownika']);
        Permission::create(['name' => 'Rozlicz pracownika']);
        Permission::create(['name' => 'Edytuj/usun godziny pracownika']);

        Permission::create(['name' => 'Dostep do kontrahentow']);
        Permission::create(['name' => 'Zobacz kontrahenta']);
        Permission::create(['name' => 'Edytuj kontrahenta']);
        Permission::create(['name' => 'Usun kontrahenta']);
        Permission::create(['name' => 'Rozlicz kontrahenta']);
        Permission::create(['name' => 'Edytuj/usun godziny kontrahenta']);

        Permission::create(['name' => 'Dodaj godziny']);
        Permission::create(['name' => 'Dostep do bilansu']);
        Permission::create(['name' => 'Edytuj/usun w bilansie']);
        Permission::create(['name' => 'Dodaj do bilansu']);

        $role = Role::create(['name' => 'Admin']);
        $role = Role::create(['name' => 'Super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
