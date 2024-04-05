<?php

namespace App\Repositories\SQL;

use App\Constants\FileConstants;
use App\Models\Article;
use App\Repositories\Contracts\ArticleContract;
use App\Repositories\Contracts\FileContract;

class ArticleRepository extends BaseRepository implements ArticleContract
{
    /**
     * ArticleRepository constructor.
     * @param Article $model
     */
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    public function syncRelations($model, $attributes)
    {
        self::syncMainImage($model, $attributes);
        self::syncImages($model, $attributes);
        return $model;
    }

    public static function syncMainImage($model, $attributes)
    {
        if (isset($attributes['main_image'])) {
            if ($model->mainImage && $model->mainImage->id != $attributes['main_image'])
                resolve(FileContract::class)->remove($model->mainImage);
            if (is_file($attributes['main_image'])){
                $file = resolve(FileContract::class)->create(['file' => $attributes['main_image'],
                    'type' => FileConstants::FILE_TYPE_ARTICLE_MAIN_IMAGE->value]);
            }else{
                $file = resolve(FileContract::class)->find($attributes['main_image']);
            }
            $model->mainImage()->save($file);
        }
        return $model;
    }

    public static function syncImages($model, $attributes)
    {
        if (isset($attributes['images'])) {
            if(is_file($attributes['images'][0])){
                $images = collect($attributes['images'])->map(function ($image) {
                    return ['file' => $image, 'type' => FileConstants::FILE_TYPE_ARTICLE_IMAGES->value];
                })->toArray();
                $files = resolve(FileContract::class)->createMany($images);
            }else{
                $files = resolve(FileContract::class)->findIds($attributes['images']);
            }
            foreach ($files as $file)
                $model->images()->save($file);
        }
        return $model;
    }

}
