<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];  
    
    public function user()      
    {
        return $this->belongsTo(User::class);
    }
    
    /** favorites機能用に多対多の設定をする
    belongsToMany() では、第一引数:Model クラス (User::class)、第二引数:中間テーブル (user_follow)、第三引数:中間テーブルの自分の id を示すカラム名 (user_id) 、第四引数:中間テーブルの関係先の id を示すカラム名 (follow_id) 
    */
    
    public function favorite_users()   /**このmicropostが好きだと言っているUser達*/
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }
}
