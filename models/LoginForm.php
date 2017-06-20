<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\AdminModel;
use yii\web\Session;
use yii\web\Cookie;
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
	
        if ($this->validate()) {
			
			$params = [':username'=>$this->username];
			//$rc = Yii::$app->db->createCommand('SELECT * FROM `r_admin` WHERE username=:username', $params)->queryOne();
			$rc = AdminModel::find()->where(array("username"=>$this->username))->one();
			if(!empty($rc)){
				$check_password = $rc['password'];
				$this->password = md5($this->password.$rc['salt']);
				
				if($check_password == $this->password){				
					//更新数据
					
					$admin_model = AdminModel::findOne(array('id'=>$rc['id']));
					$admin_model->lastlogin = time();
					$admin_model->lastip    = $_SERVER['REMOTE_ADDR'];
					$admin_model->update();

					//session保存
					$session = new Session;
					$session->open();
					$session['user_id'] = $rc['id'];
					

					//cookie保存

					$cookie = new Cookie;
					$cookie->expire = time() + 3600;
					$cookie->name = 'user_id';
					$cookie->value = $rc['id'];
					Yii::$app->response->getCookies()->add($cookie);
					return true;
					
				}
			}
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
