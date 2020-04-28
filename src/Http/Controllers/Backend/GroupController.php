<?php namespace Http\Controllers\Backend;

use Http\Requests\Group\StoreRequest;
use Http\Requests\Group\UpdateRequest;
use Repositories\Group\GroupRepositoryInterface as GroupRepository;

class GroupController extends \App\Http\Controllers\Controller
{
    protected $groups;

    public function __construct(GroupRepository $groups)
    {
        $this->groups = $groups;
    }

    public function index()
    {
        $groups = $this->groups->getAllWithUsers();
        return response()->json($groups);
    }

    public function show($code)
    {
        $group = $this->groups->find($code);
        return response()->json($group);
    }

    public function store(StoreRequest $request)
    {
        $group = $this->groups->create($request->all());
        return response()->json($group);
    }

    public function update($code, UpdateRequest $request)
    {
        $group = $this->groups->update($code, $request->only(['name', 'description', 'permissions', 'status', 'toggleStatus']));
        return response()->json($group);
    }

    public function destroy($code)
    {
        $group = $this->groups->delete($code);
        return response()->json($group);
    }

    public function getQuery($query = '')
    {
        $permissions = config('site.permissions');
        $json = [];
        foreach ($permissions as $key => $val) {
            if(empty($query)) {
                $json[] = [
                    'code' => $key,
                    'name' => $val
                ];
            } else {
                $tran = __($val);
                if (str_contains(strtolower($tran), strtolower($query))
                    || str_contains(strtolower($val), strtolower($query))
                    || str_contains(strtolower($key), strtolower($query))
                ) {
                    $json[] = [
                        'code' => $key,
                        'name' => $tran
                    ];
                }
            }
        }
        return response()->json($json);
    }

    public function getAll()
    {
        $groups = array_reduce($this->groups->getAll()->toArray(), function($all, $item) {
            array_push($all, [
                'id' => $item['code'],
                'name' => __($item['name'])
            ]);
            return $all;
        }, []);
        return response()->json($groups);
    }
}
