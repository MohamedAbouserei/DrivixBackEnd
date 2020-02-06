<?php

namespace App\Http\Controllers;

use App\User;
use App\Comment;
use App\Role;
use App\Commentlikes;

Use Exception;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use File;


class CommentController extends Controller
{
    //
    public function addComment(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'role_id' => 'required|integer|exists:role,id',
                'comment'=>'required|min:5|max:400|string',
                'comment_id'=>'nullable|integer|exists:comment,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $carservice = Role::find($request->role_id)->Carservice()->first();
            if($carservice){
                $newComment = new Comment;
                $newComment->comment = $request->comment;
                $newComment->date = Carbon::now();
                $newComment->carservice_id = $carservice->id;
                $newComment->comment_id = $request->comment_id;
                $newComment->User_id = $user->id;
                $newComment->save();
                return response()->json(['msg'=>$newComment],200);                
            }
            return response()->json(['msg'=>'Please try again'],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 500);
        }
    }
    
    public function editComment(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'comment'=> 'required|min:5|max:50|string',
                'targetCommentID' => 'required|integer|exists:comment,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }

            $user = User::where('token', $request->token)->first();

            $editedComment = Comment::where('id',$request->targetCommentID)->where('User_id',$user->id)->first();

            if($editedComment){
                $editedComment->comment = $request->comment;
                $editedComment->date = Carbon::now();
                $editedComment->save();
                return response()->json(['msg'=>$editedComment],200);                
            }
            return response()->json(['msg'=>'Please try again'],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }
    
    public function deleteComment(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'targetCommentID' => 'required|integer|exists:comment,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }

            $user = User::where('token', $request->token)->first();

            $editedComment = Comment::where('id',$request->targetCommentID)->where('User_id',$user->id)->first();

            if($editedComment){
                $editedComment->delete();
                return response()->json(['msg'=>'Comment delete Successfully'],200);                
            }
            return response()->json(['msg'=>'Please try again'],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }  
    
    public function getComments(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'role_id' => 'required|integer|exists:role,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $user = User::where('token', $request->token)->first();
            $carservice = Role::find($request->role_id)->Carservice()->first();
            
            if($carservice){
                $comments = $carservice->Comment;
                
                foreach($comments as $comment){
                    $comment->Commentlikes;
                    $username = $comment->User->name;
                    $userToken = $comment->User->token;
                    unset($comment->User);
                    $comment->username  = $username;
                    $comment->userToken =  $userToken;
                    
                    foreach($comment->Commentlikes as $commentlikes){
                        $commentlikes->User->name;
                        $username = $commentlikes->User->name;
                        $userToken = $commentlikes->User->token;
                        unset($commentlikes->User);
                        $commentlikes->username  = $username;
                        $commentlikes->userToken  = $userToken;
                    }
                    $comment->likesCount = $comment->Commentlikes->where('like',1)->count();
                    $comment->dislikesCount = $comment->Commentlikes->where('like',0)->count();
                    $comment->replayCount = $comment->replay->count();
                    foreach($comment->replay as $reply){
                        $reply->Commentlikes;
                        $username = $reply->User->name;
                        $userToken = $reply->User->token;
                        unset($reply->User);
                        $reply->username  = $username;
                        $reply->userToken  = $userToken;
                        foreach($reply->Commentlikes as $rplikes){
                            $username = $rplikes->User->name;
                            $userToken = $rplikes->User->token;
                            unset($rplikes->User);
                            $rplikes->username  = $username;
                            $rplikes->token  = $userToken;
                        }
                        $reply->replayLikesCount = $reply->Commentlikes->where('like',1)->count();
                        $reply->replayDislikesCount = $reply->Commentlikes->where('like',0)->count();
                    }
                }
                return response()->json([$comments],200);                
            }
            return response()->json([[]],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }

    public function addEstimateComment(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'role_id' => 'required|integer|exists:role,id',
                'estimate'=>'required|integer|in:0,1',
                'comment_id'=>'required|integer|exists:comment,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $comment = Role::find($request->role_id)->Carservice->Comment->find($request->comment_id);
            
            if($comment){
                $newlike = Commentlikes::where('User_id',$user->id)->where('comment_id',$request->comment_id)->first();
                if(!$newlike){
                    $newlike = new Commentlikes;
                }
                $newlike->like = $request->estimate;
                $newlike->date = Carbon::now();
                $newlike->comment_id = $request->comment_id;
                $newlike->User_id = $user->id;
                $newlike->save();
                
                $return = Commentlikes::where('User_id',$user->id)->where('comment_id',$request->comment_id)->first();
                return response()->json(['msg'=>$return],200);                
            }
            
            return response()->json(['msg'=>'Please try again'],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again' + $ex], 500);
        }
    }


    public function deleteEstimateComment(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'comment_id'=>'required|integer|exists:comment,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $Like = Commentlikes::where('User_id',$user->id)->where('comment_id',$request->comment_id)->first();
            
            if($Like){
                $Like->delete();
                
                return response()->json(['msg'=>[]],200);                
            }
            
            return response()->json(['msg'=>'Please try again'],300);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again' + $ex], 500);
        }
    }

}