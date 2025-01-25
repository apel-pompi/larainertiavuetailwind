<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    // public function getAllControllers()
    // {
    //     $files = scandir(__DIR__.'app/../Http/controllers');
    //     $controllers = array();
    //     foreach ($files as $file) {
    //         if ($controller = $this->extractController($file)) {
    //             $controllers[] = $controller;
    //         }
    //     }
    //     return $controllers;
    // }

    // public function getAllActions($controller)
    // {
    //     $functions = get_class_methods($controller);
    //     $actions = array();
    //     foreach ($functions as $name) {
    //         $actions[] = $this->extractAction($name);
    //     }
    //     return array_filter($actions);
    // }   

    // protected function extractAction($name)
    // {
    //     $action = explode('Action', $name);
    //     if ((count($action) > 1)) {
    //         return $action[0];
    //     }
    // }

    // protected function extractController($name)
    // {
    //     $filename = explode('.php', $name);
    //     if (count(explode('Controller.php', $name)) > 1) {
    //         # code...
    //         if (count($filename) > 1) {
    //             return $filename[0];
    //         }
    //     }

    //     return false;
    // }
}
