<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Admin extends Model
{
	/**
     * 上传文件
     */
    public $file;
	
	/**
     * 文本标题
     */
	public $title;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['file'], 'file'],
        ];
    }

    
	/**
     *  保存上传文件
     */
	public function save()
	{

		$upload = $this->file->saveAs(Yii::$app->basePath.'/uploads/'.$this->file->baseName.'.'.$this->file->extension);
		//上传文件
		if($upload){

			$this->file = 'uploads/'.$this->file->baseName . '.' . $this->file->extension;
			Yii::$app->db->createCommand()->insert('r_upload_file', [
				'title' => $this->title,
				'url' => $this->file,
				'createtime'=>time(),
			])->execute();

			$id = Yii::$app->db->getLastInsertID();
			
			if(!empty($id)){
				return true;
			}else{
				return false;
			}

			return true;

		}else{

			return false;
		}
	}


	/**
     * 列表
     */
	public function getList()
	{
		$posts = Yii::$app->db->createCommand('SELECT * FROM `r_upload_file` order by id desc')->queryAll();
		return $posts;

	}


}
