@extends('layouts.app')

@section('titulo')
    {{ $post->titulo }}
@endsection

@section('contenido')
    <div class="container mx-auto md:flex">
        <div class=" md:w-1/2">
            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del post {{ $post->titulo }}">

            <div class="p-3 flex items-center gap-3">

                @auth

                    <livewire:like-post :post="$post" />

                @endauth

                
            </div>
            <div>
                <a href="{{ route('posts.index', $post->user) }}" class="font-bold">{{ $post->user->username }}</a>
                <p class="text-sm text-gray-500">
                    {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="mt-5"> {{ $post->descripcion }} </p>
            </div>

            @auth
                @if (auth()->user()->id === $post->user_id)
                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="Eliminar Publicación"
                            class="bg-red-500 hover:bg-red-600 p-2 rounded text-white font-bold mt-4 cursor-pointer">
                    </form>
                @endif
            @endauth
        </div>
        <div class=" md:w-1/2 p-5">

            <div class="shadow bg-white p-5 mb-5">

                @auth
                    <form action="{{ route('comentarios.store', ['user' => $user, 'post' => $post]) }}" method="POST">
                        @csrf

                        @if (session('mensaje'))
                            <div class="bg-green-600 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold">
                                {{ session('mensaje') }}
                            </div>
                        @endif

                        <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold">
                            Agrega un comentario
                        </label>
                        <textarea id="comentario" name="comentario" placeholder="Agrega un comentario"
                            class="border p-3 w-full rounded-lg @error('comentario') border-red-500 @enderror">{{ old('comentario') }}</textarea>
                        @error('comentario')
                            <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> {{ $message }} </p>
                        @enderror
                        <input type="submit" value="Comentar"
                            class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase mt-3 font-bold w-full p-3 text-white rounded-lg">
                    </form>
                @endauth

                @guest

                    <p class="text-gray-400 font-bold text-center">Debes iniciar sesión para publicar un comentario</p>

                @endguest

                <div class="bg-white shadow mb-5 max-h-96 overflow-y-scroll mt-10">
                    @if ($post->comentarios->count())
                        @foreach ($post->comentarios as $comentario)
                            <div class="p-3 border-gray-100 border-b">
                                <a href="{{ route('posts.index', $comentario->user) }}"
                                    class="font-bold">{{ $comentario->user->username }}</a>
                                <p>{{ $comentario->comentario }}</p>
                                <p class="text-sm text-gray-500">{{ $comentario->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="p-10 text-center">No hay comentarios aun </p>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection
