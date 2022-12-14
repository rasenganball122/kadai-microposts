<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
     /**
     * このユーザが所有する投稿（ Micropostモデルとの関係を定義）。このユーザ（のインスタンス）の投稿の履歴のまとまりを指す。使う使わないにかかわらず，必ず書かないといけない。
     */
    public function microposts(){
        return $this->hasMany(Micropost::class);//このMicropostは，対応する相手の形式を書いている。このreturnでは，このユーザの投稿履歴，インスタンスがまとまった，EloquentCollectionのようなものと，sql文が入ったインスタンスが返される。
    }
    
     /**
     * このユーザがフォロー中のユーザを指す（ Userモデルとの関係を定義）。
     * User_followテーブルのuser_idはフォローしているユーザのid, follow_idはフォローされているユーザのidを指す。
     */
    public function followings() {
        return $this->belongsToMany(User::class, "user_follow", "user_id", "follow_id")->withTimestamps();//このユーザのidをuser_idに入れた時の，user_followのレコードでの相手のfollow_idの集まりを指す。     
    }
    
    /*
    フォロー／アンフォローとは、中間テーブルのレコードを保存／削除することです。そのために、あらかじめ用意されたattach() と detach() というメソッドを使用します
    */
     /**
     * このユーザをフォロー中のユーザを指す（ Userモデルとの関係を定義）。
     *  User_followテーブルのuser_idはフォローしているユーザのid, follow_idはフォローされているユーザのidを指す。
     */
    public function followers() {
        return $this->belongsToMany(User::class, "user_follow", "follow_id", "user_id")->withTimestamps();//このユーザのidをfollow_idに入れた時の，user_followのレコードでの相手のuser_idの集まりを指す。     
    }
    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId){
        // すでにフォローしているか
        $exist=$this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me=$this->id==$userId;
          
        if($exist||$its_me){
            // フォロー済み、または、自分自身の場合は何もしない
            return false;
        }else{
            // 上記以外はフォローする
            $this->followings()->attach($userId); //$this->followings()というsql文を含んだこのユーザのフォローしているユーザの塊に，$userIdのユーザを加える。
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
     
    public function unfollow($userId){
        // すでにフォローしているか
        $exist=$this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me=$this->id==$userId;
        
        if($exist && !$its_me){
            // フォロー済み、かつ、自分自身でない場合はフォローを外す
            $this->followings()->detach($userId); //$this->followings()というsql文を含んだこのユーザのフォローしているユーザの塊から，$userIdのユーザを消去する。
            return true;
        }else{
            // 上記以外の場合は何もしない
            return false;    
        }
    }
     
      /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
     public function is_following($userId){
         // フォロー中ユーザの中に $userIdのものが存在するか
         return $this->followings()->where("follow_id", "$userId")->exists();
     }
     
      /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts(){
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds=$this->followings()->pluck("users.id")->toArray();//$this->followings()->pluck("users.id")はこのユーザがフォローしているユーザのidの集まりとsql文を含んだ塊
        
        // このユーザのidもその配列に追加
        $userIds[]=$this->id;
        
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn("user_id", $userIds);
    }
    
    
    
     /**
     * このユーザがお気に入り登録しているMicropostを指す（ Micropostモデルとの関係を定義）。
     *  favoritesテーブルのuser_idはお気に入り登録しているユーザのid, micropost_idはお気に入り登録されているmicropostのidを指す。
     */
    public function favorites() {
        return $this->belongsToMany(Micropost::class, "favorites", "user_id", "micropost_id")->withTimestamps();//このユーザのidをuser_idに入れた時の，favoritesのレコードでの相手のmicropost_idの集まりを指す。     
    }
    
     /**
     * $micropostIdで指定されたユーザをフォローする。
     *
     * @param  int  $micropostId
     * @return bool
     */
    public function favor($micropostId){
        // すでにお気に入り登録しているか
        $exist=$this->is_favoring($micropostId);
        
        if($exist){
             // お気に入り登録済みの場合は何もしない
            return false;
        }else{
            // 上記以外はお気に入り登録する
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfavor($micropostId){
        // すでにお気に入り登録しているか
        $exist=$this->is_favoring($micropostId);
        if($exist){
            // 上記以外はお気に入り登録を外す
            $this->favorites()->detach($micropostId);
            return true;    
        }else{
            //上記以外の場合は何もしない
            return false;
        }
    }
    
    /**
     * 指定された $micropostIdのMicropostをこのユーザがお気に入り登録中であるか調べる。お気に入り登録中ならtrueを返す。
     *
     * @param  int  $micropostId
     * @return bool
     */
    public function is_favoring($micropostId){
        // お気に入り登録中のMicropostの中に $micpostIdのものが存在するか
        return $this->favorites()->where("micropost_id", $micropostId)->exists();
    }
    
    
    
     /**
     * このユーザに関係するモデル(microposts,followers, followings, favorites)の件数をロードする。
     */
     public function loadRelationshipCounts(){
         $this->loadCount(["microposts", "followers", "followings", "favorites"]);
     }
}
