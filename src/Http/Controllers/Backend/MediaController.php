<?php namespace Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Models\Media;
use Facebook;

class MediaController extends \App\Http\Controllers\Controller
{

    public function jsonMedia($folder, Request $request)
    {
        $medias = Media::where('medias.folder', $folder)
            ->leftJoin('medias as childen', 'medias.id', '=', 'childen.folder')
            ->groupBy('medias.id')
            ->where(function($q) use ($request) {
                if($request->has('type')) {
                    $type = (array) $request->get('type');
                    array_push($type, 'folder');
                    $q->whereIn('medias.type', $type);
                }
            })
            ->select('medias.*', DB::raw('SUM(if(childen.type = \'folder\', 1, 0)) AS folders'), DB::raw('SUM(if(childen.type <> \'folder\', 1, 0)) AS files'))
            ->get()
        ;
        $folders = [];
        $files = [];

        foreach ($medias as $file) {
            if($file->type == 'folder') {
                $folders[] = [
                    'id' => $file->id,
                    'type' => $file->type,
                    'name' => $file->name,
                    'files' => $file->files,
                    'folders' => $file->folders,
                    //'author_name' => $file->createUser->display_name,
                    'created_at' => $file->created_at->format('H:i d/m/Y')
                ];
            } else {
                $sourceUrl = $file->disk=='facebook'?$file->source:Storage::disk($file->disk)->url($file->source);
                $thumbUrl = empty($file->thumb) ? $sourceUrl : ($file->disk=='facebook'?$file->thumb:Storage::disk($file->disk)->url($file->thumb));
                $files[] = [
                    'id' => $file->id,
                    'type' => $file->type,
                    'name' => $file->name,
                    'source' => str_replace('http://mualansurong.com','',$sourceUrl),
                    'thumb' => $thumbUrl,
                    'size' => $file->size,
                    'width' => $file->width,
                    'height' => $file->height,
                    //'author_name' => $file->createUser->display_name,
                    'created_at' => $file->created_at->format('H:i d/m/Y')
                ];
            }
        }
        $json = [
            'medias' => array_merge($folders, $files),
            'folders' => count($folders),
            'files' => count($files)
        ];

        unset($medias);
        unset($folders);
        unset($files);

        return response()->json($json);
    }

    public function store(Request $request)
    {
        $input = $request->only(['source', 'height', 'width', 'thumb', 'folder', 'name']);
        $rules = [
            'name' => 'required',
            'source' => 'required',
            'thumb' => 'required',
            'folder' => 'required'
        ];

        $validator = Validator::make($input, $rules);
        $json = [
            'success' => false
        ];
        if ($validator->fails()) {
            $json['message'] = 'Some fields not valid';
        } else {
            $media = new Media;
            $media->type = 'image';
            $media->disk = 'facebook';
            $media->name = $input['name'];
            $media->folder = $input['folder'];
            $media->size = 0;
            $media->mime = 'image/jpeg';
            $media->source = $input['source'];
            $media->height = $input['height'];
            $media->width = $input['width'];
            $media->thumb = $input['thumb'];
            if ($media->save()) {
                $json = [
                  'success' => true,
                  'id' => $media->id
                ];
            }
        }
        return response()->json($json);
    }

    public function postUpload(Request $request)
    {

        $input = array(
            'file' => $request->file('file'),
            'folder' => $request->get('folder'),
            'callback' => $request->get('callback')
        );

        $rules = array(
            'file' => 'required|mimes:'.trim(str_replace(' ', '', config('site.media.mimes')), ','),
            'folder' => 'required',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => __('Validator')
            ], 422);
        } else {
            if ($input['file']->isValid()) {
                $media = new Media;
                $guessExtension = $input['file']->guessClientExtension();
                $media->name = preg_replace('/\.[a-z0-9]+$/i', '', $input['file']->getClientOriginalName());
                $media->folder = $input['folder'];
                $media->size = $input['file']->getClientSize();
                $media->mime = $input['file']->getClientMimeType();
                $extension = $input['file']->getClientOriginalExtension();
                switch($guessExtension) {
                    case 'jpeg':
                    case 'bmp':
                    case 'png':
                    case 'ico':
                        $media->type = 'image';
                        $media->disk = config('site.media.image');
                        break;
                    case 'oga':
                    case 'wma':
                    case 'mp3':
                    case 'aac':
                    case 'wav':
                        $media->type = 'audio';
                        $media->disk = config('site.media.other');
                        break;
                    case 'h264':
                    case 'mpeg':
                    case 'mp4':
                    case 'flv':
                    case 'avi':
                        $media->type = 'video';
                        $media->disk = config('site.media.video');
                        break;
                    case 'xls':
                    case 'doc':
                    case 'pdf':
                    case 'chm':
                    case 'txt':
                        $media->type = 'document';
                        $media->disk = config('site.media.other');
                        break;
                    case 'exe':
                        $media->type = 'application';
                        $media->disk = config('site.media.other');
                        break;
                    case 'bin':
                        $media->type = 'binary';
                        $media->disk = config('site.media.other');
                        break;
                    default:
                        $media->type = 'other';
                        $media->disk = config('site.media.other');
                }

                $name = $input['file']->getClientOriginalName();
                $filePath = date('Y/m/', $_SERVER['REQUEST_TIME']);
                $fileName = md5($name . $_SERVER['REQUEST_TIME']) . '.' . $extension;
                $fileContent = File::get($input['file']);
                $media->md5 = md5($fileContent);
                $media->sha1 = sha1($fileContent);
                $media->source = $filePath . $fileName;
                Storage::disk($media->disk)->put(
                    $media->source,
                    $fileContent
                );
                if(in_array($guessExtension, ['jpeg','bmp','png'])) {
                    $thumb = Image::make($fileContent);
                    $media->height = $thumb->height();
                    $media->width = $thumb->width();
                    $media->thumb = $filePath . 'thumbs/' . $fileName;
                    if ($media->height > 150 && $media->width > 200) {
                        if ($media->height / $media->width > 0.75) {
                            $thumb->widen(200);
                        } else {
                            $thumb->heighten(150);
                        }
                        Storage::disk($media->disk)->put(
                            $media->thumb,
                            (string)$thumb->encode($extension)
                        );
                    } else {
                        Storage::disk($media->disk)->put(
                            $media->thumb,
                            $fileContent
                        );
                    }
                }

                if($media->save()){
                    $sourceUrl = $media->disk=='facebook'?$media->source:Storage::disk($media->disk)->url($media->source);

                    if(!empty($input['callback']) && method_exists($input['callback'],'uploadMedia')) {
                        App::make($input['callback'])->callAction('uploadMedia', [$media]);
                    }
                    return response()->json([
                        'success' => true,
                        'id' => $media->id,
                        'type' => $media->type,
                        'name' => $media->name,
                        'source' => $sourceUrl,
                        'thumb' => $media->type == 'image' ? (empty($media->thumb) ? $sourceUrl : ($media->disk=='facebook'?$media->thumb:Storage::disk($media->disk)->url($media->thumb))) : null,
                        'size' => $media->size,
                        'width' => $media->width,
                        'height' => $media->height,
                        'created_at' => $media->created_at->format('H:i d/m/Y')
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => __('not Save')
                    ], 422);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => __('isValid')
                ], 422);
            }
        }
    }

    public function postRename(Request $request)
    {
        $input = $request->only(['id', 'name', 'type']);

        $rules = array(
            'id' => 'required',
            'name' => 'required',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(__('Validator'), 422);
        } else {
            $media = Media::find($input['id']);
            $old = $media;
            $media->name = $input['name'];
            if($media->save()) {
                return 'true';
            } else {
                return response(__('Error'), 422);
            }
        }
    }

    public function postDelete($id, Request $request)
    {
        $media = Media::find($id);
        $callback = $request->get('callback');
        if(!empty($media->module) && $media->type == 'folder') {
            return response(__('Can\'t delete folder module: <?name>', ['name'=>$media->module]), 422);
        } else {
            $deletes = [];
            $disk = $media->disk;
            if(!in_array($disk, ['facebook'])) {
                if(!empty($media->source)) {
                    $deletes[] = $media->source;
                }
                if(!empty($media->thumb)) {
                    $deletes[] = $media->thumb;
                }
            }
            $old = $media;
            if ($media->delete()) {
                if(!empty($deletes)) {
                    Storage::disk($disk)->delete($deletes);
                }
                if(!empty($callback) && method_exists($callback,'deleteMedia')) {
                    App::make($callback)->callAction('deleteMedia', [$old]);
                }
                return 'true';
            } else {
                return response(__('Error'), 422);
            }
        }
    }

    public function postDeletes(Request $request)
    {
        $ids = $request->get('ids');
        $callback = $request->get('callback');
        $media = Media::whereIn('id', $ids)->where('type', '!=', 'folder');
        $files = $media->get();
        $deletes = [];
        foreach($files as $file) {
            $deletes[] = $file->source;
            if(!empty($file->thumb)) {
                $deletes[] = $file->thumb;
            }
        }
        $old = $media;
        if ($media->delete()) {
            if(!empty($deletes)) {
                Storage::delete($deletes);
            }
            if(!empty($callback) && method_exists($callback,'deletesMedia')) {
                App::make($callback)->callAction('deletesMedia', [$old]);
            }
            return 'true';
        } else {
            return response(__('Error'), 422);
        }
    }

    public function postCreateFolder(Request $request)
    {
        $input = $request->only(['name', 'folder', 'callback']);

        $rules = array(
            'name' => 'required',
            'folder' => 'required',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response(__('Validator'), 422);
        } else {
            $media = new Media;
            $media->name = $input['name'];
            $media->type = 'folder';
            $media->folder = $input['folder'];
            if($media->save()) {
                if(!empty($input['callback']) && method_exists($input['callback'],'createFolderMedia')) {
                    App::make($input['callback'])->callAction('createFolderMedia', [$media]);
                }
                return response()->json([
                    'id' => $media->id,
                    'type' => 'folder',
                    'name' => $media->name,
                    'files' => 0,
                    'folders' => 0,
                    'created_at' => $media->created_at->format('H:i d/m/Y')
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => __('Error')
                ], 422);
            }
        }
    }
}
