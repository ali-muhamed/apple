<?php
namespace backend\models;

use common\models\LoginForm;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginFormAdmin extends LoginForm
{
    /**
     * Logs in a admin(only) using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->admin) {
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        }
        
        return false;
    }
}
