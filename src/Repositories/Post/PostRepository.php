<?php namespace Repositories\Post;

use Repositories\EloquentRepository;

abstract class PostRepository extends EloquentRepository implements PostRepositoryInterface
{
    public function getModel()
    {
        return \Models\Post::class;
    }

    public function filter(array $attributes)
    {
        $count = empty($attributes['count'])?10:$attributes['count'];
        $page = empty($attributes['page'])?1:$attributes['page'];
        $offset = $count * ($page - 1);
        $total = -1;

        $query = $this->_model->leftJoin('users', 'posts.created_by', '=', 'users.id')
            ->where(function ($q) use ($attributes) {
                if (isset($attributes['filter'])) {
                    foreach ($attributes['filter'] as $key => $filter) {
                        if ($key == 'status') {
                            $q->where('posts.status', filter_var($filter, FILTER_VALIDATE_BOOLEAN));
                        } elseif($key == 'author') {
                            $q->where('users.display_name', 'like', '%' . urldecode($filter) . '%');
                        } else {
                            $q->where('posts.'.$key, 'like', "%$filter%");
                        }
                    }
                }
            })
        ;
        if (isset($attributes['sorting'])) {
            foreach ($attributes['sorting'] as $key => $val) {
                $query->orderBy($key, $val);
            }
        }

        if($count != -1) {
            $query->limit($count)
                ->offset($offset)
            ;
            $total = $this->_model->leftJoin('users', 'posts.created_by', '=', 'users.id')
                ->where(function ($q) use ($attributes) {
                    if (isset($attributes['filter'])) {
                        foreach ($attributes['filter'] as $key => $filter) {
                            if ($key == 'status') {
                                $q->where('posts.status', filter_var($filter, FILTER_VALIDATE_BOOLEAN));
                            } elseif($key == 'auth') {
                                $q->where('users.display_name', 'like', '%' . urldecode($filter) . '%');
                            } else {
                                $q->where('posts.'.$key, 'like', "%$filter%");
                            }
                        }
                    }
                })
                ->count()
            ;
        }
        $query->select('posts.*', 'users.display_name as author');

        return [$query->get(), $total];
    }

    public function pagination($category = null) {
        return $this->_model->orderBy('id', 'desc')
            ->where(function ($q) use ($category) {
                if ($category !== null) {
                    $q->where('posts.parent', $category);
                }
            })
            ->leftJoin('users', 'posts.created_by', '=', 'users.id')
            ->select('posts.id', 'posts.name', 'posts.title', 'posts.excerpt', 'posts.image', 'posts.slug', 'posts.publish_at', 'posts.created_at', 'posts.created_by', 'users.display_name as auth')
            ->paginate(config('site.per_page'))
        ;
    }
}