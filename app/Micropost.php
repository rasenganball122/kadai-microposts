<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable=["content"];
    
    /**
     * この投稿を所有するユーザ（ Userモデルとの関係を定義）。この投稿をしたユーザ（のインスタンス）を指す（もちろん，この投稿をしたユーザは1人だけ）。使う使わないにかかわらず，必ず書かないといけない。
     */
    public function user(){
        return $this->belongsTo(User::class);//このUserは，対応する相手の形式を書いている。このreturnでは，この投稿のインズタンスと，sql文が入ったインスタンスが返される。
    }
}
