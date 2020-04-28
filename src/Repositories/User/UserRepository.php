<?php namespace Repositories\User;

use Repositories\EloquentRepository;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{

    public function getModel()
    {
        return \Models\User::class;
    }

    public function findEmail($email)
    {
        $result = $this->_model->where('email', $email)->first();
        return $result;
    }

    public function search($search, $select = '*', $limit = 10)
    {
        $result = $this->_model->select($select)
            ->where('display_name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->limit($limit)
            ->get()
        ;

        return $result;
    }

    public function filter(array $attributes)
    {
        $count = empty($attributes['count'])?10:$attributes['count'];
        $page = empty($attributes['page'])?1:$attributes['page'];
        $offset = $count * ($page - 1);

        $total = $this->_model->where(function ($q) use ($attributes) {
            if (isset($attributes['filter']['id'])) {
                $q->where('id', $attributes['filter']['id']);
            }
            if (isset($attributes['filter']['status'])) {
                $q->where('status', filter_var($attributes['filter']['status'], FILTER_VALIDATE_BOOLEAN));
            }
            if (isset($attributes['filter']['display_name'])) {
                $q->where('display_name', 'like', '%' . $attributes['filter']['display_name'] . '%');
            }
            if (isset($filter['email'])) {
                $q->where(function ($q2) use ($attributes) {
                    $q2->where('email', 'like', '%' . $attributes['filter']['email'] . '%');
                    $q2->orWhere('phone', 'like', '%' . $attributes['filter']['email'] . '%');
                });
            }
        })->count();

        $query = $this->_model->where(function ($q) use ($attributes) {
            if (isset($attributes['filter']['id'])) {
                $q->where('id', $attributes['filter']['id']);
            }
            if (isset($attributes['filter']['status'])) {
                $q->where('users.status', filter_var($attributes['filter']['status'], FILTER_VALIDATE_BOOLEAN));
            }
            if (isset($attributes['filter']['display_name'])) {
                $q->where('display_name', 'like', '%' . $attributes['filter']['display_name'] . '%');
            }
            if (isset($attributes['filter']['email'])) {
                $q->where(function ($q2) use ($attributes) {
                    $q2->where('email', 'like', '%' . $attributes['filter']['email'] . '%');
                    $q2->orWhere('phone', 'like', '%' . $attributes['filter']['email'] . '%');
                });
            }
        });
        if (!empty($attributes['sorting'])) {
            foreach ($attributes['sorting'] as $key => $val) {
                $query->orderBy($key, $val);
            }
        }
        $query->limit($count)
            ->leftJoin('groups', 'users.group_code', '=', 'groups.code')
            ->offset($offset)
            ->select('users.id', 'users.email', 'users.phone', 'users.display_name', 'users.status', 'users.created_at', 'users.group_code', 'groups.name as group_name');

        return [$query->get(), $total];
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->_model->create([
            'email' => empty($attributes['email'])?NULL:$attributes['email'],
            'phone' => empty($attributes['phone'])?NULL:$attributes['phone'],
            'display_name' => $attributes['display_name'],
            'group_code' => empty($attributes['group_code'])?config('site.user_default_group'):$attributes['group_code'],
            'password' => bcrypt($attributes['password']),
            'status' => isset($attributes['status'])?$attributes['status']:config('site.user_default_active')
        ]);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if($result) {
            if (isset($attributes['toggleStatus'])) {
                $attributes = [
                    'status' => $attributes['status']
                ];
            } else {
                $attributes = [
                    'email' => $attributes['email'],
                    'phone' => $attributes['phone'],
                    'display_name' => $attributes['display_name'],
                    'group_code' => $attributes['group_code'],
                    'status' => $attributes['status']
                ];

                if (isset($attributes['password'])) {
                    $attributes['password'] = bcrypt($attributes['password']);
                    $attributes['remember_token'] = str_random(60);
                    $attributes['api_token'] = str_random(60);
                }
            }

            $result->update($attributes);
            return $result;
        }
        return false;
    }

}