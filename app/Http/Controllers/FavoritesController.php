<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Micropostをお気に入り登録するアクション。
     *
     * @param  $id  お気に入り登録するMicropostのid
     * @return \Illuminate\Http\Response
     */
    public function store($id){
        // 認証済みユーザ（閲覧者）が、 idのMicropostをお気に入り登録する
        \Auth::user()->favor($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    
     /**
     * Micropostをお気に入りから外すアクション。
     *
     * @param  $id  お気に入りから外すMicropostのid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        // 認証済みユーザ（閲覧者）が、 idのMicropostをお気に入りから外す
        \Auth::user()->unfavor($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
