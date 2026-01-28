<?php
namespace Modules\Library\App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Books;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LibraryController extends Controller
{

	public function login(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			return response()->json([
				'status' => false,
				'message' => 'Invalid credentials'
			], 401);
		}

		$token = $user->createToken('api-token')->plainTextToken;

		return response()->json([
			'status' => true,
			'token' => $token,
			'user' => $user
		]);
	}
	public function logout(Request $request)
	{
		$request->user()->currentAccessToken()->delete();

		return response()->json(['status' => true, 'message' => 'Logged out']);
	}
    /**
     * Display a listing of the resource.
     */
	//list
    public function index(Request $request)
    {
		//if had any filter 
		if($request->has("status"))
		{
			$data = Books::where("status",$status)->orderBy('book_id', 'desc')->paginate();
		}
		else
			$data = Books::orderBy('book_id', 'desc')->paginate();
		
		
        return response()->json(['status'=>true,'data'=>$data],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
		
		$inp = [
			"title"=>"required|string|max:300",
			"author"=>"required|string|max:300",
			"isbn"=>"required|max:20|unique:books,isbn",
			"status"=>"required|integer|in:0,1"
			
		];
		
		$validator = Validator::make($request->all(), $inp);

		if ($validator->fails()) {
			return response()->json(['status'=>false,'pg'=>'Create','request'=>$request->all(),'validator'=>$validator->errors()],200);
		}
		
		//validation success insert data
		$inp1 = [
			"title"=>$request->title,
			"author"=>$request->author,
			"isbn"=>$request->isbn,
			"status"=>$request->status,
			"created_at"=>Date("Y-m-d H:i:s")
		];
		Books::insert($inp1);

		return response()->json(['status'=>true,'msg'=>'Book Created Successfully'],200);
	}

    /**
     * Show the specified resource.
     */
    public function show(Request $request,$id)
    {
		$data = Books::where("book_id",$id)->first(); //pass the value
		if(!$data)
			return response()->json(['status'=>false,'msg'=>'No Record Found'],200);
		
		return response()->json(['status'=>true,'data'=>$data],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id)
    {
		
		$data = Books::where("book_id",$id)->first(); //pass the value
		if(!$data)
			return response()->json(['status'=>false,'msg'=>'No Record Found'],200);
		
        return response()->json(['status'=>true,'data'=>$data],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
		
		$data = Books::where("book_id",$id)->first(); //pass the value
		if(!$data)
			return redirect()->back()->with('fail', 'No Record Found');
		
		$inp = [
			"title"=>"required|string|max:300",
			"author"=>"required|string|max:300",
			"isbn"=>"required|max:20|unique:books,isbn," . $id.",book_id",
			"status"=>"required|integer|in:0,1",
		];
		
		$validator = Validator::make($request->all(), $inp);

		if ($validator->fails()) {
			return response()->json(['status'=>false,'pg'=>'Edit','request'=>$request->all(),'validator'=>$validator->errors()],200);
		}
		
		//validation success insert data
		$inp1 = [
			"title"=>$request->title,
			"author"=>$request->author,
			"isbn"=>$request->isbn,
			"status"=>$request->status,
			"updated_at"=>Date("Y-m-d H:i:s")
		];
		Books::where("book_id",$id)->update($inp1);

		return response()->json(['status'=>true,'msg'=>'Book Updated Successfully'],200);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id) {
		
		//soft delete
		$data = Books::where("book_id",$id)->first(); //pass the value
		if(!$data)
			return response()->json(['status'=>false,'errMsg'=>'Data Not Found'],200);
		
		$status = $data["is_deleted"];
		if($status == 1)
		{
			Books::where("book_id",$id)->update(["is_deleted"=>0]); //activate
			return response()->json(['status'=>true,'msg'=>'Activate Successfully'],200);
		}
		else
		{
			Books::where("book_id",$id)->update(["is_deleted"=>1,"status"=>1]); //delete and stauts change to unavailable
			return response()->json(['status'=>true,'msg'=>'deleted Successfully'],200);
		}
		
	}
}
