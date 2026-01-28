<!DOCTYPE html>
<html>
<head>
    <title>View Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <h3>View Book</h3>

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

    <form method="POST" >
        @csrf
		<div class="mb-12 d-flex justify-content-end">
            <button type="button" onclick="return back_to_list()" class="btn btn-primary">Back To List</button>
        </div>
		
        <div class="mb-3">
            <label>Title</label>
            <input type="text" disabled="true" name="title" class="form-control"
                   value="<?php echo $data['title']; ?>">
        </div>

        <div class="mb-3">
            <label>Author</label>
            <input type="text" disabled="true" name="author" class="form-control"
                   value="<?php echo $data['author']; ?>">
        </div>

        <div class="mb-3">
            <label>ISBN</label>
            <input type="text" disabled="true" name="isbn" class="form-control"
                   value="<?php echo $data['author']; ?>">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select disabled="true" name="status" class="form-control">
                <option value="">-- Select --</option>
                <option value="0" <?php echo ($data['status'] === 0?'selected':''); ?> >Available</option>
                <option value="1" <?php echo ($data['status'] === 1?'selected':''); ?> >Unavailable</option>
            </select>
        </div>
		
		<div class="mb-3">
            <label>Deleted</label>
            <select disabled="true" name="status" class="form-control">
                <option value="">-- Select --</option>
                <option value="0" <?php echo ($data['is_deleted'] === 0?'selected':''); ?> >Not Deleted</option>
                <option value="1" <?php echo ($data['is_deleted'] === 1?'selected':''); ?> >Deleted</option>
            </select>
        </div>

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
