<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithFileUploads, WithPagination;

    public $showPostModal = false;
    public $title;
    public $body;
    public $image;
    public $postId = null;
    public $newImage;

    public function showCreatePostModal()
    {
        $this->showPostModal = true;
    }
    public function updatedCreatePostModal()
    {
        $this->reset();
    }

    public function storePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image_name = $this->image->getClientOriginalName();
        $this->image->storeAs('public/photos/', $image_name);
        $post = new Post();
        $post->title = $this->title;
        $post->user_id = auth()->user()->id;
        $post->body = $this->body;
        $post->image = $image_name;
        $post->slug = Str::slug($this->title);
        $post->save();
        $this->reset();
        session()->flash('message', 'Post successfully created');

    }

    public function showEditPostModal($id)
    {
        $this->reset();
        $this->showPostModal = true;
        $this->postId = $id;
        $this->loadEditForm();
    }

    public function loadEditForm()
    {
        $post = Post::findOrFail($this->postId);

        $this->title = $post->title;
        $this->body = $post->body;
        $this->newImage = $post->image;
    }

    public function updatePost()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
        ]);
        if ($this->image) {
            Storage::delete('public/photos/' . $this->newImage);
            $this->newImage = $this->image->getClientOriginalName();
            $this->image->storeAs('public/photos/', $this->newImage);
        }
        $post = Post::findOrFail($this->postId)->update([
            'title' => $this->title,
            'body' => $this->body,
            'image' => $this->newImage,
            'slug' => Str::slug($this->title),
        ]);
        $this->reset();
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        Storage::delete('public/photos/' . $post->image);
        $post->delete();
        session()->flash('message', 'Post deleted successfully');

    }

    public function render()
    {
        return view('livewire.posts', [
            'posts' => Post::latest()->paginate(3)
        ]);
    }
}
