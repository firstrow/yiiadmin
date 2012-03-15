<?php

class UserIdentity extends CUserIdentity
{
    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $password=Yii::app()->getModule('yiiadmin')->password;
        if($password===null)
        {
            throw new CException('Please configure the "password" property of the "yiiadmin" module.');     } elseif ($password===false || $password===$this->password) {
            $this->errorCode=self::ERROR_NONE;
        } else {
            $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
        }
        return !$this->errorCode;
    }
}
// vim: set filetype=php expandtab tabstop=4 shiftwidth=4 autoindent smartindent:
