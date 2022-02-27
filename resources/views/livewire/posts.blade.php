<div class="container mx-auto mt-2">
    <x-jet-banner />
    <div>
        @if (session()->has('message'))
            <div class="relative py-2 pl-2 pr-10 leading-normal text-white bg-green-500 rounded-lg" role="alert">
                <p>{{ session('message') }}</p>
                <span class="absolute inset-y-0 right-0 flex items-center mr-4">
                    <svg class="w-4 h-4 fill-current" role="button" viewBox="0 0 20 20">
                        <path
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </span>
            </div>
        @endif
    </div>
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">

        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="flex content-center my-3">
                <x-jet-button wire:click="showCreatePostModal">
                    Create new post
                </x-jet-button>
            </div>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-600 dark:text-gray-200">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Id
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Image
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Body
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">
                                Created at
                            </th>
                            <th scope="col" class="relative px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($posts as $post)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img class="w-8 h-8 rounded-full"
                                        src="{{ asset('storage/photos/' . $post->image) }}" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $post->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $post->body }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $post->active === 1 ? 'Active ' : 'Inactive' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $post->created_at }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <x-jet-button wire:click="showEditPostModal({{ $post->id }})" class="bg-blue-600 hover:bg-blue-700">
                                        Edit
                                    </x-jet-button>
                                    <x-jet-button wire:click="deletePost({{ $post->id }})" class="bg-red-500 hover:bg-red-700">
                                        Delete
                                    </x-jet-button>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center p-3">
                                <td colspan="7">No data !</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="m-2 p-2">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="showPostModal">
        <x-slot name="title">
            @if ($postId)
                Update post
            @else
                Create new post
            @endif
        </x-slot>
        <x-slot name="content">
            <div class="space-y-8 divide-y divide-gray-200 w-full my-10">
                <form enctype="multipart/form-data" class="my-4">
                    <div class="sm:col-span-6 mt-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Post Title :</label>
                        <div class="mt-2">
                            <input type="text" id="title" wire:model.lazy="title" name="title"
                                class="block w-full transition duration-150 ease-in-out appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                            @error('title')
                                <span class="error">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    <div class="sm:col-span-6 mt-2">
                        <div class="w-full py-2 my-2">
                            @if ($newImage)
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-3">Image :</label>
                                <img src="{{ asset('storage/photos/' . $post->image) }}" class="w-20">
                            @endif
                        </div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Post Image :</label>
                        <div class="mt-2">
                            <input type="file" id="image" wire:model="image" name="image"
                                class="block w-full transition duration-150 ease-in-out appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5" />
                        </div>
                        @error('image')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <div class="w-full py-2 my-2">
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="w-50">
                            @endif
                        </div>
                    </div>
                    <div class="sm:col-span-6 mt-2">
                        <label for="body" class="block text-sm font-medium text-gray-700">Body :</label>
                        <div class="mt-2">
                            <textarea id="body" rows="3" wire:model.lazy="body"
                                class="shadow-sm focus:ring-indigo-500 appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                            @error('body')
                                <span class="error">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                </form>
        </x-slot>
        <x-slot name="footer">
            @if ($postId)
                <x-jet-button wire:click="updatePost">Update post </x-jet-button>
            @else
                <x-jet-button wire:click="storePost">Create post</x-jet-button>
            @endif
        </x-slot>
    </x-jet-dialog-modal>
</div>
