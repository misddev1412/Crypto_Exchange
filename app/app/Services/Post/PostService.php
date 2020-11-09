<?php


namespace App\Services\Post;


use App\Services\Core\FileUploadService;
use Illuminate\Support\Str;

class PostService
{
    public function _uploadThumbnail($request)
    {
        if ($request->hasFile('featured_image')) {
            return app(FileUploadService::class)->upload($request->featured_image, config('commonconfig.path_post_feature_image'), 'featured_image', 'post', Str::uuid()->toString(), 'public', 1280, 768, false, 'jpg');
        }
        return false;
    }
}
