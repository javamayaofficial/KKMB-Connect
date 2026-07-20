<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::published()->with('author')->latest('published_at')->paginate(10);
        return view('app.feed.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);
        return view('app.feed.show', compact('post'));
    }

    public function create()
    {
        return view('app.feed.create');
    }

    // Posting anggota masuk status "pending" (dimoderasi admin) sebelum tampil.
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('posts', 'public');
        }

        $isPengurus = $request->user()->hasAnyRole(['super_admin', 'pengurus']);

        Post::create([
            'author_user_id' => auth()->id(),
            'tipe' => 'artikel',
            'judul' => $data['judul'],
            'konten' => $data['konten'],
            'gambar_path' => $gambarPath,
            'status' => $isPengurus ? 'published' : 'pending',
            'published_at' => $isPengurus ? now() : null,
        ]);

        $msg = $isPengurus
            ? 'Artikel dipublikasikan.'
            : 'Artikel dikirim & menunggu moderasi pengurus.';

        return redirect()->route('feed.index')->with('status', $msg);
    }
}
