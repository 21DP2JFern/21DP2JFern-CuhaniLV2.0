<?php
namespace App\Http\Controllers\Api;
use App\Models\Comment;
use App\Models\ForumPost;
use App\Models\PostCount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Friendship;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "username" => "required",
            "name"=> "required",
            "email"=> "required|email|unique:users",
            "password"=>"required|confirmed|min:8",
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status'=> true,
            'message'=>'User created successfully',
        ]);
    }
    public function login(Request $request){
        $request->validate([
            "email"=> "required|email",
            "password"=>"required",
        ]);

        Log::info('User login attempt', ['user_id' => $request->email]); 

        if(Auth::attempt([
            "email"=> $request->email,
            "password"=>$request->password
        ])){

            $user = Auth::user();

            $token = $user->createToken("myToken")->accessToken;

            return response()->json([
                'status'=> true,
                'message'=>'User logged in successfully',
                'token'=> $token,
                'name'=> $user->name 
            ]);

        }else{
            return response()->json([
                'status'=> false,
                'message'=>'Invalid login details',
            ]);
        }
    }
    public function profile(){
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'User profile',
            'data' => $user
        ]);
    }
    public function logout(){

        auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User logged out'
        ]);
    }

    public function getUserProfile(){
        $userData = User::where('id', Auth::user()->id)->first();
        return response()->json([
            'status' => true,
            'userData' => $userData
        ]);
    }
    public function updateProfile(Request $request){
        $user = Auth::user(); 
        $user->update(['bio' => $request->input('bio')]);
        $user->update(['username' => $request->input('username')]);

        return response()->json([
           'status' => true,
           'message' => 'Profile updated successfully',
        ]);
     }

     public function deleteProfile(Request $request){
        $user = Auth::user();
        $user->delete();
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Profile deleted',
        ]);
     }

     public function createForumPost(Request $request)
     {
         $user = Auth::user();
     
         $request->validate([
             'nosaukums' => 'required|string|max:50',
             'saturs' => 'required|string|max:5000',
             'autors' => 'required|integer',
         ]);
     
         $date = Carbon::now();
     
         $forumPost = ForumPost::create([
             'nosaukums' => $request->nosaukums,
             'saturs' => $request->saturs,
             'autors' => $request->autors,
             'date' => $date,
         ]);
     
         $postCount = PostCount::first();
         if ($postCount) {
             $postCount->count += 1;
         } else {
             $postCount = new PostCount();
             $postCount->count = 1;
         }
         $postCount->save();
     
         return response()->json([
             'status' => true,
             'message' => 'Forum post created successfully',
             'data' => $forumPost,
         ]);
     }
    public function getForumPost(Request $id){

        $user = Auth::user();
        $id = $id->id;

        $post = ForumPost::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Forum post not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Forum post found',
            'data' => $post,
        ]);
    }

    public function getAllForumPosts(){
        $user = Auth::user();
        $forumPosts = ForumPost::all();

        return response()->json([
            'status' => true,
            'message' => 'All forum posts retrieved successfully',
            'data' => $forumPosts,
        ]);
    }

    public function getAllUsersForumPosts(Request $request)
    {
        $user = Auth::user();
        $sortBy = $request->query('sortBy', 'most-recent'); 

        $query = ForumPost::where('autors', $user->id);

        if ($sortBy === 'most-recent') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            return response()->json(['error' => 'Invalid sortBy parameter'], 400);
        }

        $forumPosts = $query->get();

        return response()->json([
            'status' => true,
            'message' => 'All forum posts retrieved successfully',
            'data' => $forumPosts,
        ]);
    }

    public function getAllFriendsForumPosts()
    {
        $user = Auth::user();
        
        $friendIds = Friendship::where('user_id', $user->id)->pluck('friend_id');

        $forumPosts = ForumPost::whereIn('autors', $friendIds)->get();

        return response()->json([
            'status' => true,
            'message' => 'Forum posts from friends retrieved successfully',
            'data' => $forumPosts,
        ]);
    }

    public function createComment(Request $request){
        $request->validate([
            'post_id' => 'required|exists:forum_posts,id',
            'author_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'author_id' => $request->author_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment created successfully',
            'data' => $comment,
        ]);
    }

    public function getComments(Request $request){
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $request->validate([
            'post_id' => 'required|exists:forum_posts,id',
        ]);

        $comments = Comment::where('post_id', $request->post_id)->with('author')->get();

        return response()->json([
            'status' => true,
            'message' => 'Comments retrieved successfully',
            'comments' => $comments, 
        ]);
    }

    public function deletePost($id)
{
    $user = Auth::user();
    $post = ForumPost::find($id);

    if (!$post) {
        return response()->json([
            'status' => false,
            'message' => 'Forum post not found',
        ], 404);
    }

    if ($user->id != $post->autors && $user->role_id != 1) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized to delete this post',
        ], 403);
    }

    $post->delete();

    $postCount = PostCount::first();
    if ($postCount && $postCount->count > 0) {
        $postCount->count -= 1;
        $postCount->save();
    }

    return response()->json([
        'status' => true,
        'message' => 'Forum post deleted successfully',
    ]);
}


public function editForumPost(Request $request, $id)
{
    $user = Auth::user();
    $post = ForumPost::find($id);

    if (!$post) {
        return response()->json([
            'status' => false,
            'message' => 'Forum post not found',
        ], 404);
    }

    if ($user->id != $post->autors && $user->role_id != 1) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized to edit this post',
        ], 403);
    }

    $request->validate([
        'nosaukums' => 'required|string|max:50',
        'saturs' => 'required|string|max:5000',
    ]);

    $post->nosaukums = $request->nosaukums;
    $post->saturs = $request->saturs;
    $post->save();

    return response()->json([
        'status' => true,
        'message' => 'Forum post edited successfully',
        'data' => $post,
    ]);
}


public function countForumPosts()
{
    $user = Auth::user();

    $postCount = ForumPost::count();

    return response()->json([
        'status' => true,
        'message' => 'Total number of forum posts retrieved successfully',
        'data' => $postCount,
    ]);

    
}

public function searchUsers(Request $request){
    $query = $request->input('query');
    $users = User::where('username', 'LIKE', "%{$query}%")->get(['id', 'username']);

    return response()->json([
        'status' => true,
        'message' => 'Users retrieved successfully',
        'data' => $users,
    ]);
}

public function addFriend(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'friend_id' => 'required|exists:users,id',
    ]);

    if (Friendship::where('user_id', $user->id)->where('friend_id', $request->friend_id)->exists()) {
        return response()->json([
            'status' => false,
            'message' => 'Friendship already exists',
        ], 400);
    }

    $friend = User::find($request->friend_id);
    if (!$friend) {
        return response()->json([
            'status' => false,
            'message' => 'Friend not found',
        ], 404);
    }

    $friendship = new Friendship();
    $friendship->user_id = $user->id;
    $friendship->friend_id = $request->friend_id;
    $friendship->Fusername = $friend->username; 
    $friendship->save();

    return response()->json([
        'status' => true,
        'message' => 'Friend added successfully',
        'friendship' => $friendship,
    ]);
}

public function getUserFriends()
{
    $user = Auth::user();

    $friends = Friendship::where('user_id', $user->id)->with('friend')->get();

    $friendUsernames = $friends->map(function ($friendship) {
        return [
            'id' => $friendship->friend->id,
            'Fusername' => $friendship->friend->username,
        ];
    });


    return response()->json([
        'status' => true,
        'message' => 'User friends retrieved successfully',
        'friends' => $friends,
        'usernames' =>$friendUsernames,
    ]);
}
}
