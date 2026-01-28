<!DOCTYPE html>
<html>
<head>
    <title>{{ $pg}} Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <h3>Add Book</h3>

	
    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

	{{-- Fail Message --}}
    @if (session('fail'))
        <div style="color:red" class="alert alert-fail">
            {{ session('fail') }}
        </div>
    @endif
	
    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="<?php echo ($pg == 'Edit'?route('library_book_update',$data["book_id"]):route('library_book_store')); ?>">
        @csrf
		<div class="mb-12 d-flex justify-content-end">
            <button type="button" onclick="return back_to_list()" class="btn btn-primary">Back To List</button>
        </div>
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control"
                   value="<?php echo ($pg=='Edit'?$data['title']:old('title')); ?>">
        </div>

        <div class="mb-3">
            <label>Author</label>
            <input type="text" name="author" class="form-control"
                   value="<?php echo (($pg=='Edit'?$data['author']:old('author'))); ?>">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" name="isbn" class="form-control"
                   value="<?php echo (($pg=='Edit'?$data['isbn']:old('isbn'))); ?>">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">-- Select --</option>
                <option value="0" <?php echo ((($pg == "Edit" && $data['status'] === 0)?'selected':(old('status')===0?'selected':''))); ?> >Available</option>
                <option value="1" <?php echo ((($pg == "Edit" && $data['status'] === 1)?'selected':(old('status')===1?'selected':''))) ?> >Unavailable</option>
            </select>
        </div>
		
		
		@if(auth()->check() && auth()->user()->role === 'admin')
        <button type="submit" class="btn btn-primary">{{ ($pg == "Edit"?"Edit":"Save") }}</button>
		@endif
    </form>

</div>

<script>
function back_to_list()
{
	window.location.href="<?php echo url('books');?>";
	return false;
}
</script>
</body>
</html>
