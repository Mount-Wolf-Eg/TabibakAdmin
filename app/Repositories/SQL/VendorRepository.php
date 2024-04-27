<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\Vendor;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\Contracts\VendorContract;

class VendorRepository extends BaseRepository implements VendorContract
{
    /**
     * VendorRepository constructor.
     * @param Vendor $model
     */
    public function __construct(Vendor $model)
    {
        parent::__construct($model);
    }

    public function beforeCreate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function beforeUpdate($attributes)
    {
        return resolve(UserContract::class)->prepareUserForRoleUsers($attributes);
    }

    public function syncRelations($model, $attributes)
    {
        if (isset($attributes['services'])){
            $model->vendorServices()->sync($attributes['services']);
        }
        if (isset($attributes['icon'])) {
            if (is_file($attributes['icon'])){
                $file = resolve(FileContract::class)->create(['file' => $attributes['icon'],
                    'type' => FileConstants::FILE_TYPE_VENDOR_ICON->value]);
            }else{
                $file = resolve(FileContract::class)->find($attributes['icon']);
            }
            $model->icon()->save($file);
        }
        return $model;
    }
}
