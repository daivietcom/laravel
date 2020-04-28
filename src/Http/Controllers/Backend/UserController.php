<?php namespace Http\Controllers\Backend;

use Avatar;
use Auth;
use Illuminate\Http\Request;
use Http\Requests\User\StoreRequest;
use Http\Requests\User\UpdateRequest;
use Http\Requests\User\AvatarRequest;
use Repositories\User\UserRepositoryInterface as UserRepository;

class UserController extends \App\Http\Controllers\Controller
{

    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index(Request $request)
    {
        list($users, $total) = $this->users->filter($request->all());
        return response()->json($users)->header('total', $total);
    }

    public function getQuery($query = '')
    {
        $users = $this->users->search($query, ['id', 'display_name', 'email', 'phone']);
        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->users->find($id);
        return response()->json(array_merge($user->toArray(), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'birthday' => $user->birthday,
            'url' => $user->url,
            'about' => $user->about,
            'group_name' => $user->group->name,
        ]));
    }

    public function store(StoreRequest $request)
    {
        $user = $this->users->create($request->all());
        return response()->json($user);

    }

    public function update(UpdateRequest $request, $id)
    {
        $user = $this->users->update($id, $request->all());
        return response()->json($user);
    }

    public function destroy(Request $request, $id)
    {
        if ($id != $request->user()['id']) {
            $user = $this->users->delete($id);
            return response()->json($user);
        }

        return response()->json(false);
    }

    public function postAvatar(AvatarRequest $request) {
        Avatar::create($request->input('user'), $request->file('file')->getRealPath());
        return response()->json(true);
    }
}
