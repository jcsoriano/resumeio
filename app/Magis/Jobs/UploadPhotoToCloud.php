<?php

namespace App\Magis\Jobs;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadPhotoToCloud implements ShouldQueue
{
    private $item;
    

    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get a fresh instance of the item (to get any changes that were made while this job was queued)
        $item = $this->item->fresh();
        $exception = null;

        foreach ($item::editableFields($item) as $key => $type) {
            $value = $item->$key;

            // get the photos in this item that are not already in googleusercontent, but is in local storage
            if ($type == 'photo'
                && !empty($value)
                && !str_contains($value, 'googleusercontent')
                && strpos($value, 'storage') === 0
            ) {
                $item->$key = $this->uploadToCloud($value);
            } elseif ($type == 'mediumText' && !empty($value) && str_contains($value, '<img')) {
                $dom = new DOMDocument;
                $dom->loadHTML($value);
                $imageTags = $dom->getElementsByTagName('img');

                foreach ($imageTags as $img) {
                    $src = $img->getAttribute('src');
                    $img->setAttribute('src', $this->uploadToCloud($src));

                    // for better compatibility between summernote and magis-img
                    $style = $img->getAttribute('style');
                    if (empty($style)) {
                        $img->setAttribute('style', 'width:100%');
                    }

                    list($width, $height) = getimagesize($img->getAttribute('src'));
                    $img->setAttribute('aspect-ratio', $height / $width);
                }

                $item->$key = str_replace(['<body>', '</body>'], ['', ''], $dom->saveHTML($dom->getElementsByTagName('body')->item(0)));
            }
        }

        // save changes in DB
        $item->save();
    }

    private function uploadToCloud($photoPath)
    {
        // check if the photo is already uploaded in the cloud. If so, don't do anything
        if (str_contains($photoPath, 'googleusercontent')) {
            return $photoPath;
        }

        $newPhotoFullUrl = $photoPath;

        // get the basename of the path
        $basename = basename($photoPath);

        // construct the new path
        $newPhotoPath = 'public/'.$this->item::SLUG.'/'.$this->item->id.'/'.$basename;

        // get the image's dimensions
        if (strpos($photoPath, 'storage') === 0) {
            $fullPhotoPath = storage_path(str_replace('storage', 'app/public', $photoPath));
        } elseif (strpos($photoPath, $this->url('storage')) === 0) {
            $fullPhotoPath = storage_path(str_replace($this->url('storage'), 'app/public', $photoPath));
        }

        // upload the photo to cloud
        if (Storage::cloud()->put($newPhotoPath, file_get_contents($fullPhotoPath), 'public')) {
            // save the Cloud path in-case publishing an optimized image fails.
            // str_replace fixes weird bug in cloud storage package resulting in wrong URI
            // when custom URI .env variable is implemented
            $newPhotoFullUrl = str_replace(
                'cdn.magis.solutions/cdn.magis.solutions',
                'cdn.magis.solutions',
                Storage::cloud()->getDriver()->getAdapter()->getUrl($newPhotoPath)
            );

            // delete the local file (can't use local Storage facade because it's run
            // in queue - possibly different path in queue)
            File::delete($fullPhotoPath);
        }

        // get a public-optimized URL if it is already in cdn.magis.solutions
        if (str_contains($newPhotoFullUrl, 'cdn.magis.solutions')) {
            $client = new Client();

            $response = $client->post(
                'https://cloud.magis.solutions/08bbfe194c502098ee8a9dc075d371d334aea295',
                [
                    'form_params' => [
                        env('MAGIS_UPLOAD_SERVICE_KEY') => env('MAGIS_UPLOAD_SERVICE_VALUE'),
                        'file' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX').'/'.$newPhotoPath,
                    ]
                ]
            );
            $responseBody = (string) $response->getBody();
            $responseBody = json_decode($responseBody);

            // save the optimized image URL
            if ($response->getStatusCode() == 200 && $responseBody->status == 'success') {
                $newPhotoFullUrl = $responseBody->url;
            }
        }

        return $newPhotoFullUrl;
    }

    private function url($path)
    {
        $separator = starts_with($path, '/') ? '' : '/';
        return env('APP_URL').$separator.$path;
    }
}
