<?php

require_once 'modules/admin/models/ServerPlugin.php';

class PluginHesitia extends ServerPlugin
{

    public $features = [
        'packageName' => true,
        'testConnection' => true,
        'showNameservers' => false,
        'directlink' => true
    ];

    public function getVariables()
    {
        $variables = [
            'Name' => [
                'type' => 'hidden',
                'description' => 'Used by CE to show plugin',
                'value' => 'Hestia'
            ],
            'Description' => [
                'type' => 'hidden',
                'description' => 'Description viewable by admin in server settings',
                'value' => 'Hestia Server Plugin'
            ],
            'Text Field' => [
                'type' => 'text',
                'description' => 'Text Field Description',
                'value' => 'Default Value',
            ],
            'Encrypted Text Field' => [
                'type' => 'text',
                'description' => 'Encrypted Text Field Description',
                'value' => '',
                'encryptable' => true
            ],
            'Password Text Field' => [
                'type' => 'password',
                'description' => 'Encrypted Password Field Description',
                'value' => '',
                'encryptable' => true
            ],
            'Text Area' => [
                'type' => 'textarea',
                'description' => 'Text Area Description',
                'value' => 'Default Value',
            ],
            'Yes / No' => [
                'type' => 'yesno',
                'description' => 'Yes / No Description',
                'value' => '1',
            ],
            'Actions' => [
                'type' => 'hidden',
                'description' => 'Current actions that are active for this plugin per server',
                'value'=>'Create,Delete,Suspend,UnSuspend'
            ],
            'Registered Actions For Customer' => [
                'type' => 'hidden',
                'description' => 'Current actions that are active for this plugin per server for customers',
                'value' => 'authenticateClient'
            ],
            'package_addons' => [
                'type' => 'hidden',
                'description' => 'Supported signup addons variables',
                'value' => ['DISKSPACE', 'BANDWIDTH', 'SSL']
            ],
            'package_vars' => [
                'type' => 'hidden',
                'description' => 'Whether package settings are set',
                'value' => '1',
            ],
            'package_vars_values' => [
                'type'  => 'hidden',
                'description' => lang('Package Settings'),
                'value' => [
                    'Text Field' => [
                        'type' => 'text',
                        'label' => 'Text Field Label',
                        'description' => 'Text Field Description',
                        'value' => 'Default Value',
                    ],
                    'Drop Down' => [
                        'type' => 'dropdown',
                        'multiple' => false,
                        'getValues' => 'getDropDownValues',
                        'label' => 'Drop Down Label',
                        'description' => 'Drop Down Description',
                        'vaue' => '',
                    ]
                ]
            ]
        ];

        return $variables;
    }

    public function validateCredentials($args)
    {
    }

    public function doDelete($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $args = $this->buildParams($userPackage);
        $this->delete($args);
        return 'Package has been deleted.';
    }

    public function doCreate($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $args = $this->buildParams($userPackage);
        $this->create($args);
        return 'Package has been created.';
    }

    public function doUpdate($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $args = $this->buildParams($userPackage);
        $this->update($args);
        return 'Package has been updated.';
    }

    public function doSuspend($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $args = $this->buildParams($userPackage);
        $this->suspend($args);
        return 'Package has been suspended.';
    }

    public function doUnSuspend($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $args = $this->buildParams($userPackage);
        $this->unsuspend($args);
        return 'Package has been unsuspended.';
    }

    public function unsuspend($args)
    {
        // Call Unsuspend at the server
    }

    public function suspend($args)
    {
        // Call suspend at the server
    }

    public function delete($args)
    {
        // Call delete at the server
    }

    public function update($args)
    {
        foreach ($args['changes'] as $key => $value) {
            switch ($key) {
                case 'username':
                    // update username on server
                    break;
                case 'password':
                    // update password on server
                    break;
                case 'domain':
                    // update domain on server
                    break;
                case 'ip':
                    // update ip on server
                    break;
                case 'package':
                    // update package on server
                    break;
            }
        }
    }

    public function getAvailableActions($userPackage)
    {
        $args = $this->buildParams($userPackage);

        $actions = [];
        // Get Status at Server

        // If not created yet
        $actions[] = 'Create';

        // If we can delete
        $actions[] = 'Delete';

        // If we can suspend
        $actions[] = 'Suspend';

        // If suspended at Server
        $actions[] = 'UnSuspend';
        return $actions;
    }

    public function create($args)
    {
        $userPackage = new UserPackage($args['package']['id']);

        // call create at the server
        // If we need to store custom data for later
        $userPackage->setCustomField('Server Acct Properties', $externalServerId);
    }

    public function testConnection($args)
    {
        CE_Lib::log(4, 'Testing connection to server');

        // if failed
        throw new CE_Exception("Connection to server failed.");
    }

    public function getDropDownValues()
    {
        $values = [
            '0' => 'Zero',
            '1' => 'One',
            '2' => 'Two'
        ];

        return $values;
    }

    public function getDirectLink($userPackage, $getRealLink = true, $fromAdmin = false, $isReseller = false)
    {
        $linkText = $this->user->lang('Login to Server');
        $args = $this->buildParams($userPackage);

        if ($getRealLink) {
            // call login at server

            return [
                'link'    => '<li><a target="_blank" href="url to login">' .$linkText . '</a></li>',
                'rawlink' =>  'url to login',
                'form'    => ''
            ];
        } else {
            return [
                'link' => '<li><a target="_blank" href="index.php?fuse=clients&controller=products&action=openpackagedirectlink&packageId='.$userPackage->getId().'&sessionHash='.CE_Lib::getSessionHash().'">' .$linkText . '</a></li>',
                'form' => ''
            ];
        }
    }

    public function dopanellogin($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $response = $this->getDirectLink($userPackage);
        return $response['rawlink'];
    }

    public function dopanellogin_reseller($args)
    {
        $userPackage = new UserPackage($args['userPackageId']);
        $response = $this->getDirectLink($userPackage, true, false, true);
        return $response['rawlink'];
    }
}