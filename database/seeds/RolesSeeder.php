<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [1, 'Accès aux administrateurs', 'y'],
            [2, 'Accès au blog', 'y'],
            [3, 'Accès aux menus du site', 'y'],
            [4, 'Accès aux pages du site', 'y'],
            [5, 'Accès aux catégories de médias', 'y'],
            [6, 'Accès aux médias', 'y'],
            [7, 'Accès aux utilisateurs', 'y'],
            [8, 'Accès aux emails', 'y'],
            [9, 'Accès aux sms', 'y'],
            [10, 'Accès au carousel', 'y'],
            [11, 'Accès à la configuration', 'y'],
            [12, 'Accès aux abonnements newsletters', 'y'],
            [13, 'Accès aux tags', 'y'],
            [14, 'Accès aux demandes de contact', 'y'],
            [15, 'Accès aux redirections', 'y'],
            [16, 'Accès aux codes promos', 'y'],
        ];

        foreach ($roles as $r) {
            $array_role = [
                'id' => $r[0],
                'name' => $r[1],
                'used' => $r[2],
            ];
            $role = Role::find($array_role['id']);
            if (!$role) {
                Role::create($array_role);
            } else {
                $role->update($array_role);
            }
        }
    }
}
