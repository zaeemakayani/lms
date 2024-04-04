<?php

namespace Database\Seeders;

use App\Models\PermissionModule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $namespace = 'App\\Models';
        $modelsPath = app_path('Models');
        $modelFiles = File::files($modelsPath);

        foreach ($modelFiles as $mfkey => $modelFile) {
            $fileName = pathinfo($modelFile, PATHINFO_FILENAME);
            $className = $namespace.'\\'.$fileName;
            $camelCaseString = $fileName;
            $wordsWithSpaces = $this->camelCaseToWords($camelCaseString);
            if (class_exists($className)) {
                if (!PermissionModule::where('name', $wordsWithSpaces)->exists()) {
                    PermissionModule::create([
                        'name' => strtolower($wordsWithSpaces)
                    ]);
                }
            }
        }

        $permissionModules = PermissionModule::get();
        $permission = [
            'view',
            'create',
            'update',
            'delete'
        ];
        foreach ($permissionModules as $pmkey => $permissionModule) {
            for ($i = 0; $i < 4; $i++) {
                if (!Permission::where('name', $permissionModule->name.'_'.$permission[$i])->exists()) {
                    Permission::create([
                        'module_id' => $permissionModule->id,
                        'name' => strtolower($permissionModule->name.'_'.$permission[$i]),
                        'guard_name' => 'web'
                    ]);
                }
            }
        }
        $user = User::find(1);
        $permissions = Permission::get();
        $role = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $user->assignRole($role);
        $role->syncPermissions($permissions);

    }
    function camelCaseToWords($input) {
        $output = preg_replace('/(?<=\\w)(?=[A-Z])/', '_', $input);
        return $output;
    }
}
