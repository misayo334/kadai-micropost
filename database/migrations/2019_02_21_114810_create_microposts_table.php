<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMicropostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microposts', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();        //unsigned() は「負の数は許可しない」という設定、index()は、検索速度を高めることができるもの
            $table->string('content');
            $table->timestamps();

            // 外部キー制約(外部キー制約は絶対に必要なものではないが、これを利用することで「間違ったデータが保存されにくくなる」)
            $table->foreign('user_id')->references('id')->on('users'); //$table->foreign(外部キーを設定するカラム名)->references(制約先のID名)->on(外部キー制約先のテーブル名)
        
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('microposts');
    }
}
