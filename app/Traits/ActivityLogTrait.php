<?php
namespace App\Traits;

use Carbon\Carbon;

trait ActivityLogTrait{

    public function logActivity($model,$user,$message): void
    {
        activity()
            ->performedOn($model)
            ->causedBy($user)
            ->log($message);
    }

    public function propertyLogActivity($model, $user, $message, array $property=['key' => 'value']): void
    {
        if (!defined(get_class($model)."::DISABLE_LOG") ||
            (defined(get_class($model)."::DISABLE_LOG") && !get_class($model)::DISABLE_LOG)) {

            activity()
                ->performedOn($model)
                ->causedBy($user)
                ->withProperties($property)
                ->log($message);
        }

    }

    public function customLogOnUpdateFields($filtered, $model, $newModel): array
    {
        $old = $model->getAttributes();
        $old = collect($old)->map(function ($value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_THROW_ON_ERROR);
            }
            return $value;
        })->toArray();
        $filtered = collect($filtered)->map(function ($value) {
            if (is_array($value))
                $value = json_encode($value, JSON_THROW_ON_ERROR);
            return $value;
        })->toArray();
        $changes = array_diff_uassoc($filtered, $old, static function ($a, $b) {
            return $a === $b ? 0 : 1;
        });
        if ((!defined(get_class($model)."::DISABLE_LOG") || !get_class($model)::DISABLE_LOG) && count($changes)) {
            $changes = collect($changes)->map(function ($item, $key) use ($old, $filtered, $model, $newModel) {
                if (array_key_exists($key, $model->getCasts()) && preg_match("/\bConstants\b/i", $model->getCasts()[$key])) {
                    if (!is_null($old[$key])){
                        $oldCase = $model->getCasts()[$key]::tryFrom($old[$key]);
                        $old[$key] = $model->getCasts()[$key]::getLabels($oldCase);
                    }
                    $filteredCase = $model->getCasts()[$key]::tryFrom($filtered[$key]);
                    $filtered[$key] = $model->getCasts()[$key]::getLabels($filteredCase);
                }
                if (isset($model->logKeysValues) && array_key_exists($key, $model->logKeysValues)) {
                    $old[$key] = data_get($model, 'assignee.user.name');
                    $filtered[$key] = data_get($newModel, 'assignee.user.name');
                }
                return [
                    'field' => $key,
                    'old' => $old[$key],
                    'new' => $filtered[$key]
                ];
            })->values()->toArray();
            $this->propertyLogActivity(
                $newModel,
                auth()->user(),
                "$this->modelName #id: $newModel->id Updated",
                [
                    'action' => 'Update',
                    'changes' => $changes,
                    'data' => [
                        'user' => auth()->user()?->name,
                        'url' => '',
                        'created_at' => Carbon::now()->format('Y-m-d H:i')
                    ]
                ]
            );
        }
        return $changes;
    }

    public function customLogOnCreate($model, $user, $message, array $property=['key' => 'value']): void
    {
        activity()
            ->performedOn($model)
            ->causedBy($user)
            ->withProperties($property)
            ->log($message);

    }

}

?>
