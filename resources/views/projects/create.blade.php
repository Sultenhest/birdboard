<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h1>Create a Project</h1>
    <form action="/projects" method="POST">
        @csrf
        <div class="field">
            <label for="title" class="label">Title</label>

            <div class="control">
                <input type="text" class="input" name="title" placeholder="">
            </div>
        </div>

        <div class="field">
            <label for="description" class="label">Description</label>

            <div class="control">
                <textarea name="description" id="" class="textarea"></textarea>
            </div>
        </div>

        <div class="field">
            <button type="submit" class="button is-link">Create Project</button>
        </div>
    </form>
</body>
</html>