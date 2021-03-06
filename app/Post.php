<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;

//默认表=>posts
class Post extends Model
{
    //不可以注入的字段
//     protected $guarded;
    
    //可以注入的字段
    protected $fillable = ['title', 'content', 'user_id'];
    
    
    use Searchable;
    //定义索引里面的type
    public function searchableAs() {
        return 'post';
    }
    //定义有哪些字段需要搜索
    public function toSearchableArray() {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
    
    //关联用户
    public function user() {
        
        //belongsTo3个参数,1:关联模型;2:当前模型数据名;3:关联表数据名      
        //默认就是该表user_id与id关联,请遵循命名规范,即可省略参数
        return $this->belongsTo('App\User');
    }
    
    //关联评论模块
    public function comments() {
        
        //hasMany 是一对多的关系,一遍文章是否有多个评论
        return $this->hasMany('App\Comment')
                    ->orderBy('created_at', 'desc');
    }
    
    //关联赞模块
    public function zan($user_id) {
        //通过user_id来判断是否有赞
        return $this->hasOne(\App\Zan::class)
                    ->where('user_id', $user_id);
    }
    
    //文章所以得赞
    public function zans() {
        return $this->hasMany(\App\Zan::class);
    }
    
    /*
     * scope 使用范围查询 
     */
    
    //属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id) {
        return $query->where('user_id', $user_id);
    }
    
    public function postTopics() {
        return $this->hasMany(PostTopic::class, 'post_id', 'id');
    }
    
    //不属于某个专题的文章
    public function scopeTopicNotBy(Builder $query, $topic_id) {
        return $query->doesntHave('postTopics', 'and', 
            function($q) use($topic_id) {
            $q->where('topic_id', $topic_id);
        });
    }
    
    //全局scope的方式
    protected static function boot() {
        parent::boot();
        
        static::addGlobalScope("avaiable", function(Builder $builder) {
            $builder->whereIn('status', [0,1]);
        });
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}