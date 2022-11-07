<?php

namespace Nue\SSOSamarinda;

use Novay\Nue\Extension;
use Novay\Nue\Nue;

class SSOSamarinda extends Extension
{
    public $name = 'sso-samarinda';

    public $views = __DIR__.'/../resources/views';

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        Nue::extend('sso-samarinda', __CLASS__);
    }

    /**
     * Enable this function if you want to automatically inject menu & permission
     * for your package into nue.
     * 
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('SSO Users', 'ext/sso-users', 'fingerprint');

        parent::createPermission('SSO Samarinda', 'ext.sso-samarinda', 'sso-samarinda*');
    }
}