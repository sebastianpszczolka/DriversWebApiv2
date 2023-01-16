<?php
declare(strict_types=1);

namespace App\Repositories\Database\Installations;

use App\Entities\Installation;
use App\Entities\InstallationUserRole;
use App\Entities\User;
use App\Entities\UserRole;
use Illuminate\Support\Facades\DB;

class InstallationRepository
{
    public function getInstallationsForUser(User $user): array
    {
        $result = DB::table(User::TABLE)
            ->join(InstallationUserRole::TABLE, User::TABLE . '.' . User::COLUMN_ID, '=', InstallationUserRole::TABLE . '.' . InstallationUserRole::COLUMN_USER_ID)
            ->join(Installation::TABLE, InstallationUserRole::TABLE . '.' . InstallationUserRole::COLUMN_INSTALLATION_ID, '=', Installation::TABLE . '.' . Installation::COLUMN_ID)
            ->join(UserRole::TABLE, InstallationUserRole::TABLE . '.' . InstallationUserRole::COLUMN_USER_ROLE_ID, '=', UserRole::TABLE . '.' . UserRole::COLUMN_ID)
            ->where(User::TABLE . '.' . User::COLUMN_ID, '=', $user->getId())
            ->orderBy(Installation::COLUMN_ID, 'asc')
            ->select(Installation::TABLE . '.' . Installation::COLUMN_ID,
                Installation::TABLE . '.' . Installation::COLUMN_NAME,
                Installation::TABLE . '.' . Installation::COLUMN_DESCRIPTION,
                Installation::TABLE . '.' . Installation::COLUMN_INSTALLATION_BARCODE,
                UserRole::TABLE . '.' . UserRole::COLUMN_ID . ' as ur_id',
                UserRole::TABLE . '.' . UserRole::COLUMN_ROLE,
                UserRole::TABLE . '.' . UserRole::COLUMN_DESCRIPTION . ' as ur_description',
            )
            ->get()
            ->all();

        $response = [];
        foreach ($result as $item) {
            $response[] = [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'installationBarcode' => $item->inst_barcode,
                'user_role' => [
                    'id' => $item->ur_id,
                    'role' => $item->role,
                    'description' => $item->ur_description
                ]
            ];
        }
        return $response;
    }

    public function getById(int $installationId): ?Installation
    {
        /** @var Installation | null $installation */
        $installation = Installation::query()->find($installationId);

        return $installation;
    }
}
