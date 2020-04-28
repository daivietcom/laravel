<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;
use Repositories\Module\ModuleRepositoryInterface as ModuleRepository;

class ThemeController extends \App\Http\Controllers\Controller
{
    protected $modules;

    public function __construct(ModuleRepository $modules) {
        $this->modules = $modules;
    }

    public function index()
    {
        $themes = $this->modules->getAll('theme');
        return response()->json($themes);
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
        if (is_base64($name)) {
            $name = base64_decode($name);
        }
        $input = $request->all();
        if (isset($input['toggleStatus'])) {
            $customs = require config_path('custom.php');
            $customs['site.theme'] = $name;
            if(\File::put(config_path('custom.php'),"<?php\r\nreturn ". var_export($customs, true) .';') === false) {
                $input = false;
            }
        }
        return response()->json($input);
    }

    public function check(Request $request)
    {
        $info = $this->modules->check($request->input('github'));
        if($info['type'] != 'theme') {
            $info = [];
        }
        return response()->json($info);
    }

    public function destroy(Request $request, $name)
    {
        $theme = $this->modules->delete($name);
        if (!empty($theme) && $theme['name'] == config('site.theme')) {
            $customs = require config_path('custom.php');
            $customs['site.theme'] = '';
            \File::put(config_path('custom.php'),"<?php\r\nreturn ". var_export($customs, true) .';');
        }
        return response()->json($theme);
    }

    public function getGadget() {
        $theme = config('site.theme');
        $result = [
            'theme' => empty($theme)?'default':$theme,
            'themeGadgets' => \Gadget::getThemeGadget(),
            'groups' => \Gadget::getGroup(),
            'gadgets' => \Gadget::getGadget(),
        ];
        return response()->json($result);
    }

    public function postGadget(Request $request) {
        $theme = $request->input('theme');
        $gadgets = $request->input('gadgets');
        $gadgetPath = $theme == 'default'?resource_path('views/frontend/gadget.json'):base_path('vendor/'.$theme.'/gadget.json');
        if (save_json($gadgetPath, $gadgets) !== false) {
            return response()->json(true);
        }
        return response()->json(false);
    }
}
