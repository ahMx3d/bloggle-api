<?php

namespace App\Models;

use Mindscms\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $guarded = [];

    /**
     * Get the permission parent record associated with this permission.
     *
     * @return object
     */
    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent');
    }

    /**
     * Get the permission children records associated with this permission.
     *
     * @return object
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    /**
     * Get the visible permission children records associated with this permission.
     *
     * @return object
     */
    public function visibleChildren()
    {
        return $this->hasMany(self::class, 'parent', 'id')->whereAppear(1);
        // return $this->hasMany(self::class, 'parent', 'id')->where('appear',1);
    }

    /**
     * Get the sidebar visible parent permission tree of children records.
     *
     * @return object
     */
    public static function tree($level = 1)
    {
        return static::with(implode('.', array_fill(0, $level, 'children')))
            ->whereParent(0)
            ->whereAppear(1)
            ->whereSidebarLink(1)
            ->orderBy('ordering', 'asc')
            ->get();
    }

    /**
     * Get the sidebar visible parent permission tree of children records.
     *
     * @return object
     */
    public static function VisibleTree($level = 1)
    {
        return static::with(implode('.', array_fill(0, $level, 'visibleChildren')))
            ->whereParent(0)
            ->whereAppear(1)
            ->whereSidebarLink(1)
            ->orderBy('ordering', 'ASC')
            ->get();
    }

    /**
     * Assign many children records associated with this parent permission.
     *
     * @return object
     */
    public function assign_children()
    {
        return $this->hasMany(self::class, 'parent_original', 'id');
    }

    /**
     * Assign the sidebar visible children permissions tree to the parent records.
     *
     * @return object
     */
    public static function assign_permissions($level = 1)
    {
        return static::with(implode('.', array_fill(0, $level, 'assign_children')))
        ->whereParentOriginal(0)
        ->whereAppear(1)
        ->orderBy('ordering', 'ASC')
        ->get();
    }
}
