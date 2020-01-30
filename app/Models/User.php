<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @package App\Models
 * @version January 28, 2020, 1:44 pm CST
 *
 * @property \Illuminate\Database\Eloquent\Collection options
 * @property string username
 * @property string name
 * @property string email
 * @property string|\Carbon\Carbon email_verified_at
 * @property string password
 * @property string thumb
 * @property string img
 * @property string provider
 * @property string provider_uid
 * @property string remember_token
 */
class User extends Authenticatable implements  MustVerifyEmail,HasMedia
{
    use Notifiable,HasMediaTrait,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','name', 'email', 'password','provider','provider_uid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImgAttribute()
    {
        $media = $this->getMedia('avatars')->first();
        return $media ? $media->getUrl() : asset('dist/img/avatar5.png');
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function options()
    {
        return $this->belongsToMany(\App\Models\Option::class, 'option_user');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    public function getThumbAttribute()
    {
        $media = $this->getMedia('avatars')->first();
        return $media ? $media->getUrl('thumb') : asset('dist/img/avatar5.png');
    }

}
