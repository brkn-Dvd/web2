@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Editar Livro</h1>

        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                    value="{{ old('title', $book->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="publisher_id" class="form-label">Editora</label>
                <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id"
                    name="publisher_id" required>
                    <option value="" disabled>Selecione uma editora</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ $publisher->id == $book->publisher_id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
                @error('publisher_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem de Capa</label>

                @if($book->cover_image)
                    <div class="mb-2">
                        <img src="{{ $book->cover_image_url }}" alt="Capa atual" class="img-thumbnail"
                            style="max-height: 200px;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="remove_cover" name="remove_cover">
                            <label class="form-check-label text-danger" for="remove_cover">
                                Remover imagem atual
                            </label>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-2">Nenhuma imagem de capa definida</p>
                @endif

                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image"
                    name="cover_image" accept="image/*">
                @error('cover_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Preview da nova imagem -->
            <div class="mt-2" id="newImagePreview" style="display: none;">
                <img id="newPreviewImage" src="#" alt="Nova capa" class="img-thumbnail" style="max-height: 200px;">
                <small class="text-muted">Pré-visualização da nova imagem</small>
            </div>
    </div>

    <div class="mb-3">
        <label for="author_id" class="form-label">Autor</label>
        <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id" required>
            <option value="" disabled>Selecione um autor</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ $author->id == $book->author_id ? 'selected' : '' }}>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
        @error('author_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categoria</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
            <option value="" disabled>Selecione uma categoria</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $book->category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview da nova imagem selecionada
        document.getElementById('cover_image').addEventListener('change', function (e) {
            const previewContainer = document.getElementById('newImagePreview');
            const preview = document.getElementById('newPreviewImage');

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    </script>
@endsection