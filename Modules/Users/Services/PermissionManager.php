<?php

namespace Modules\Users\Services;

class PermissionManager
{
    /**
     * @var Module
     */
    private $module;

    /**
     */
    public function __construct()
    {
        $this->module = app('modules');
    }

    /**
     * Get the permissions from all the enabled modules
     * @return array
     */
    public function all()
    {
        $permissions = [];
        foreach ($this->module->allEnabled() as $enabledModule) {
            $role_permission = is_company_role() ? 'company_permissions' : 'permissions';
            $configuration = config(strtolower($enabledModule->getName()) . '.' .$role_permission);
            if (!empty($configuration)) {
                $permissions[$enabledModule->getName()] = $configuration;
            }
        }

        return $permissions;
    }

    /**
     * Return a correctly type casted permissions array
     * @param $permissions
     * @return array
     */
    public function clean($permissions)
    {
        if (!$permissions) {
            return [];
        }
        $cleanedPermissions = [];
        foreach ($permissions as $permissionName => $checkedPermission) {
            $cleanedPermissions[$permissionName] = $this->getState($checkedPermission);
        }

        return $cleanedPermissions;
    }

    /**
     * @param $checkedPermission
     * @return bool
     */
    protected function getState($checkedPermission)
    {
        if ($checkedPermission == 'true') {
            return true;
        }

        if ($checkedPermission == 'false') {
            return false;
        }

        return (bool)$checkedPermission;
    }

    /**
     * Are all of the permissions passed of false value?
     * @param array $permissions    Permissions array
     * @return bool
     */
    public function permissionsAreAllFalse(array $permissions)
    {
        $uniquePermissions = array_unique($permissions);

        if (count($uniquePermissions) > 1) {
            return false;
        }

        $uniquePermission = reset($uniquePermissions);

        $cleanedPermission = $this->getState($uniquePermission);

        return $cleanedPermission === false;
    }
}
