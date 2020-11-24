<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class UploadPhoto extends Component
{
    use WithFileUploads;

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

    // function handleFileUpload($id, $fileUploaded) {
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

        // $this->photo = $fileUploaded;
        // $this->validate(
        //     ['photo' => 'base64image|base64dimensions:max_width=200,max_height=100'],
        //     [
        //         'photo.base64image' => 'Use only file image',
        //         'photo.base64dimensions' => 'Max dimension is 200x100 (width X height)'
        //     ]
        // );

    }

    public function save()
    {
        $this->validate([
            'photo' => 'image|max:1024', // 1MB Max
        ]);
        $this->photo->store('photos');
    }
}
