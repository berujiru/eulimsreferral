<?php
namespace common\modules\admin\models\form;

use Yii;
use common\modules\admin\models\User;
use common\models\system\Profile;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $password;
    public $verifypassword;
    
    public $lastname;
    public $firstname;
    public $designation;
    public $middleinitial;
    public $lab_id;
    public $rstl_id;
	public $pstc_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required','message'=>'Username is required!'],
            ['username', 'unique', 'targetClass' => 'common\modules\admin\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required','message'=>'Email is required!'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This email address has already been taken.'],

            [['lastname','firstname', 'designation', 'middleinitial'],'string','min'=>2,'max'=>50],
            ['lastname', 'required','message'=>'Lastname is required!'],
            ['firstname', 'required','message'=>'Firstname is required!'],
            ['designation', 'required','message'=>'Designation is required!'],
            ['rstl_id', 'required','message'=>'RSTL is required!'],
            ['lab_id', 'required','message'=>'Laboratory is required!'],
            [['rstl_id','lab_id','pstc_id'], 'integer'],
            
            [['password','verifypassword'], 'required'],
            [['password', 'verifypassword'], 'string', 'min' => 6],
            ['verifypassword', 'compare','message'=>'Password is not verified!', 'compareAttribute'=>'password'],  
        ];
    }
    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
           // 'verifyCode' => 'Verification Code',
            'verifypassword'=>'Verify Password',
            'middleinitial'=>'Middle Name',
            'rstl_id'=>'RSTL',
            'lab_id'=>'Laboratory',
			'pstc_id'=>'PSTC'
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                // Ceate default Profile
                $profile=Profile::find()->where(['user_id'=>$user->user_id])->one();
                if(!$profile){// No profile Record yet
                    $profile=new Profile();
                    $profile->user_id=$user->user_id;
                    $profile->lastname=$this->lastname;
                    $profile->firstname=$this->firstname;
                    $profile->designation=$this->designation;
                    $profile->lab_id=$this->lab_id;
                    $profile->rstl_id=$this->rstl_id;
					$profile->pstc_id=$this->pstc_id;
                    if(trim($this->middleinitial)!=''){
                        $initial=strtoupper(substr($this->middleinitial, 0, 1)).". ";
                    }else{
                        $initial="";
                    }
                    $profile->middleinitial= $this->middleinitial;
                    $profile->fullname= $this->firstname .' '.$initial.$this->lastname;
                    $profile->save();
                }
                return $user;
            }
        }

        return null;
    }
}
