<?php namespace VnSource\Services;

use Intervention\Image\ImageManagerStatic as Image;

class Avatar {
	
	protected $app;
	protected $mediaPath;

	public function __construct($app) {
		$this->app = $app;
        $this->mediaPath = $this->app['config']->get('filesystems.disks.media.root');
	}

	public function sync($id, $avatar, $size = 128) {
		$avatar_path = $this->mediaPath.'/avatar/'.$id.'.jpg';
		if(!file_exists($avatar_path)) {
		    Image::make(str_replace('?sz=50','',$avatar))->widen($size)->save($avatar_path);
		}
	}

    public function create($id, $avatar, $size = 128) {
        $avatar_path = $this->mediaPath.'/avatar/'.$id.'.jpg';
        Image::make($avatar)->widen($size)->save($avatar_path);
    }

	public function response($id, $size = 128, $type = 'jpg') {
        $avatar_path = $this->mediaPath.'/avatar/'.$id.'.jpg';
        if(!file_exists($avatar_path)) {
            $avatar_path = $this->mediaPath.'/avatar/default.jpg';
        }
        return Image::make($avatar_path)->widen($size)->response($type);
	}
}
