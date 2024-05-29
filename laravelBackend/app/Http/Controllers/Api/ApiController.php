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


class ApiController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "username" => "required",
            "name"=> "required",
            "email"=> "required|email|unique:users",
            "password"=>"required|confirmed",
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
                'name'=> $user->name // Return the user's name
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
        $user = Auth::user(); // Assuming the user is authenticated
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
     
         // Increment the post count
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
    // paņem kādu id tu gribi atrast
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
            'comments' => $comments, // Ensure the key is 'comments'
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

    // Decrement the post count
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

    // Count the total number of forum posts
    $postCount = ForumPost::count();

    return response()->json([
        'status' => true,
        'message' => 'Total number of forum posts retrieved successfully',
        'data' => $postCount,
    ]);
}
}
