<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Schema;

class User extends BaseModel  implements JWTSubject, Authenticatable
{
    use Notifiable, AuthenticableTrait;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function funcGetAllUser($input)
    {

        $users = User::select();

        if (isset($input['filter'])) {
            foreach ($input['filter'] as $variable => $value) {
                $value = json_decode($value);
                if (!Schema::hasColumn('users', $value->id)) {
                    return [
                        'error' => true,
                        'data' => [],
                        'errorCode' => 'fieldSearch_not_found'
                    ];
                }
                $users = $users->where($value->id, 'LIKE', '%' . $value->value . '%');
            }
        }
        
        //sort
        if (isset($input['sort']) && isset($input['sortBy'])) {
            if (!Schema::hasColumn('users', $input['sortBy'])) {
                return [
                    'error' => true,
                    'data' => [],
                    'errorCode' => 'fieldSort_not_found'
                ];
            }
            $users = $users->orderBy($input['sortBy'], $input["sort"]);
        }

        //pagination
        if (isset($input['page'])) {
            $limit = 10;
            if (isset($input['limit'])) {
                $limit = $input['limit'];
            }
            $users = $users->paginate($limit)->withPath('admin/getAllUser');
        }

        return [
            "error" => false,
            "data" => $users,
            "errorCode" => null,
        ];
    }

    public static $rules = array(
        'Rule_Create_User' => [
            'username' => 'required|min:3|max:30|string',
            'email' => 'required|unique:users,email|regex:/^[a-z][a-z0-9_\.]{2,}@[a-z0-9]{2,}(\.[a-z0-9]{2,}){1,2}$/',
            'password' => 'required|string|min:6|max:16',
            'confirmPassword' => 'required|same:password',
        ],
        'Rule_Signin' => [
            'email' => 'required|regex:/^[a-z][a-z0-9_\.]{2,}@[a-z0-9]{2,}(\.[a-z0-9]{2,}){1,2}$/',
            'password' => 'required|string|min:6|max:16',
            'admin' => 'boolean',
        ],
        'Rule_ChangPassword' => [
            'currentPassword' => 'required|string|min:6|max:16',
            'newPassword' => 'required|string|min:6|max:16',
            'confirmNewPassword' => 'required|same:newPassword'
        ],
        'Rule_DeleteUser' => [
            'userId' => 'required|min:1|integer',
        ],
        'Rule_EditUser' => [
            'userId' => 'required|min:1|integer',
            'admin' => 'required|boolean',
            'username' => 'required|min:3|max:30|string',
        ],
        'Rule_Change_UserAvatar' => [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
        'Rule_Get_All_User' => [
            'page' => 'integer',
            'limit' => 'integer',
            'sort' => 'in:asc,desc',
            'admin' => 'boolean'
        ],
    );
}
