<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 7/22/16
 * Time: 7:41 PM
 */

namespace App\Models\Auth;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];

}