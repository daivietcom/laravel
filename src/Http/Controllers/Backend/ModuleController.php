<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;
use Repositories\Module\ModuleRepositoryInterface as ModuleRepository;

class ModuleController extends \App\Http\Controllers\Controller
{
    protected $modules;

    public function __construct(ModuleRepository $modules) {
        $this->modules = $modules;
    }

    public function index()
    {
        $modules = $this->modules->getAll('module');

        return response()->json($modules);
    }

    public function store(Request $request)
    {
        $module = $this->modules->store($request->input('name'));
        return response()->json($module);
    }

    public function download(Request $request)
    {
        $download = $this->modules->download($request->input('name'));
        return response()->json($download);
    }

    public function unpack(Request $request)
    {
        $unpack = $this->modules->unpack($request->input('name'), $request->input('version'));
        return response()->json($unpack);
    }

    public function update(Request $request, $name)
    {
        $module = $this->modules->update($name, $request->only(['displayName','description','homepage','license','version','authors','enabled', 'toggleStatus']));
        return response()->json($module);
    }

    public function check(Request $request)
    {
        $info = $this->modules->check($request->input('github'));
        if($info['type'] != 'module') {
            $info = [];
        }
        return response()->json($info);
    }

    public function destroy(Request $request, $name)
    {
        $delete = $this->modules->delete($name);
        return response()->json($delete);
    }
}
