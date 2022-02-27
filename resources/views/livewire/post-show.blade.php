<section>
    <!--Title-->
    <div class="text-center pt-16 md:pt-32">
        <h1 class="break-normal text-3xl md:text-5xl font-semibold">{{ $post->title }}</h1>
        <p class="text-sm md:text-base text-gray-500 mt-2">
           Created at {{ $post->created_at }}
        </p>
    </div>
    <!--image-->
    <img class="container w-100 max-w-6xl mx-auto bg-white bg-cover mt-8 rounded"
        src="{{ asset('storage/photos/' . $post->image) }}" />
    <!--Container-->
    <div class="container w-full max-w-6xl mx-auto mt-8">
        <div class="mx-0 sm:mx-6">
            <div class="bg-white w-full p-8 md:p-24 text-xl md:text-2xl text-gray-800 leading-normal"
                style="font-family:Georgia,serif;">
                {{ $post->body }}
            </div>
        </div>
    </div>
</section>
