<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

// base64 validation
// https://github.com/crazybooot/base64-validation
// composer require crazybooot/base64-validation

// custom messages
// php artisan vendor:publish --provider="Crazybooot\Base64Validation\Providers\ServiceProvider" --tag=config
// setting up replace_validation_messages option to false on config/base64validation.php
// add localizations for rules in standard Laravel way.

class UploadPhoto extends Component
{
    public $photo;
    private $tmpFileValidate;

    public function render()
    {
        return view('livewire.user.upload-photo');
    }

    protected $listeners = ['fileUpload' => 'handleFileUpload'];
    protected $messages = [
        'photo.base64dimensions' => 'The Max dimension for :attribute is 200x100 (width X height)',
    ];

    function handleFileUpload($id, $file) {
        $this->resetErrorBag();
        $validator = Validator::make(
            [
                'file' => $file
            ],
            [
                'file' => 'base64image|base64max:100|base64dimensions:max_width=300,max_height=300'
            ],
            [
                'file.base64image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp)',
                'file.base64max' => 'The Max size for file is :max kb',
                'file.base64dimensions' => 'The Max dimension for :attribute is :width X :height (width X height)',
            ]
        );

        if (!$validator->fails()) {
            $this->resetErrorBag('file');
            return $this->photo = $file;
        }

        foreach ($validator->getMessageBag()->getMessages()['file'] as $message) {
            $this->addError('photo', $message);
        };
    }

    public function save()
    {
        $image = $this->photo;
        preg_match("/data:image\/(.*?);/", $image, $image_extension); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/', '', $image); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '_' . \Str::random(5) . '.' . $image_extension[1]; //generating unique file name;
        Storage::disk('public')->put('participants/'.$imageName, base64_decode($image));
    }
}
