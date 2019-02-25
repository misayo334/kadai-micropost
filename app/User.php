<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function microposts()        
    {
        return $this->hasMany(Micropost::class);
    }
    
    
    /** 多対多の設定をする（followingsとfollowers両方のfunctionが必要）
    belongsToMany() では、第一引数:Model クラス (User::class)、第二引数:中間テーブル (user_follow)、第三引数:中間テーブルの自分の id を示すカラム名 (user_id) 、第四引数:中間テーブルの関係先の id を示すカラム名 (follow_id) 
    */
    
    public function followings()   /**User が フォローしている User 達*/
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()    /**User を フォローしている User 達*/
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    /**$user->follow($user_id) や、$user->unfollow($user_id) とすれば、フォロー／アンフォローできるように follow() とunfollow() メソッドを User モデルで定義しておく
     */
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function follow($userId)  /**この場合、$userIdはフォローされる人、$this->idはフォローする人*/
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist || $its_me) {   /**既にフォローしている、あるいは、相手が自分自身だったら*/
            // 既にフォローしていれば何もしない
            return false;
        } 
        else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);  /**多対多を定義した中間テーブル（この場合user_follow）へのレコード挿入はattachメソッドを使う*/
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist && !$its_me) {   /**既にフォローしている、かつ、相手が自分自身だったら*/
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);  /**多対多を定義した中間テーブル（この場合user_follow）からののレコード削除はdetachメソッドを使う*/
            return true;
        } 
        else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    /**タイムライン用*/
    public function feed_microposts()
    {
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    
    

}
