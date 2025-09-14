<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blog Manager</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #fafafa; margin: 0; padding: 0; color: #222; }
    .header { display: flex; justify-content: flex-end; padding: 24px 40px 0; }
    nav { display: flex; gap: 24px; }
    nav a { text-decoration: none; color: #222; font-weight: 500; font-size: 1.05em; }
    nav a.active { color: #e91e63; border-bottom: 2px solid #e91e63; }

    .container { max-width: 900px; margin: 32px auto 60px; padding: 0 40px; }
    h1 { color: #e91e63; margin: 20px 0; }

    form { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 40px; }
    .form-group { margin-bottom: 14px; }
    label { font-weight: bold; display: block; margin-bottom: 6px; }
    input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
    textarea { resize: vertical; min-height: 120px; }
    button { background: #e91e63; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; }

    .error { background: #ffe6e6; color: #b00020; padding: 10px; border-radius: 8px; margin-bottom: 16px; }

    .post { background: #fff; padding: 16px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .post h2 { margin: 0 0 6px; }
    .post img { max-width: 100%; border-radius: 8px; margin-top: 10px; }
    .meta { color: #666; font-size: 0.9em; margin-bottom: 10px; }
    .actions a { margin-right: 12px; text-decoration: none; color: #e91e63; font-weight: bold; }
  </style>
</head>
<body>
  @auth
  <div style="position: absolute; top: 20px; left: 20px; background: #fff0f5; padding: 10px 16px; border-radius: 8px; font-weight: bold; color: #e91e63; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    Welcome {{ auth()->user()->email }}
  </div>
  @endauth

  <div class="header">
    <nav>
      <a href="{{ route('posts.index') }}" class="active">View Blog</a>
      <a href="{{ route('about') }}" class="active">About</a>
      <a href="#">Portfolio</a>
      <a href="#">Contact</a>
      @auth
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background:none;border:none;color:#e91e63;font-weight:bold;cursor:pointer;">
          Logout
        </button>
      </form>
      @endauth
    </nav>
  </div>

  <div class="container">
    <h1>Create a New Post</h1>

    @if ($errors && $errors->any())
      <div class="error">
        @foreach ($errors->all() as $e)
          <div>- {{ $e }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required>
      </div>
      <div class="form-group">
        <label>Category</label>
        <input type="text" name="category">
      </div>
      <div class="form-group">
        <label>Content</label>
        <textarea name="content" required></textarea>
      </div>
      <div class="form-group">
        <label>Image (optional)</label>
        <input type="file" name="image" accept="image/*">
      </div>
      <button type="submit">Publish</button>
    </form>

    <h1>All Posts</h1>
    @if ($posts->isEmpty())
      <div>No posts yet. Create your first one above.</div>
    @endif

    @foreach ($posts as $post)
      <div class="post">
        <h2>{{ $post->title }}</h2>
        <div class="meta">
          {{ $post->created_at }}
          @if ($post->category)
            · {{ $post->category }}
          @endif
        </div>
        <p>{{ nl2br(e($post->content)) }}</p>
        @if ($post->image_path)
          <img src="{{ asset($post->image_path) }}" alt="Post image">
        @endif
        <div class="actions">
          <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
          <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Delete this post?')" style="background:none;border:none;color:#e91e63;font-weight:bold;cursor:pointer;">Delete</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</body>
</html>
