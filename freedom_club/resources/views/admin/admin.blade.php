<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Freedom Club</title>
</head>
<body>
    <form action="{{ url('/admin/' .auth()->user()->id) }}" method="POST">
        @csrf
        @method("PUT")
        <h2>Please enter your username and password:</h2>
        <label for="username" class="form-label text-black">Username: </label>
        <input type="text" name="username" class="form-control">
        <label for="password" class="form-label text-black">Password: </label>
        <input type="password" name="password" class="form-control">
        <input type="submit" value="Submit">
    </form>
</body>
</html>