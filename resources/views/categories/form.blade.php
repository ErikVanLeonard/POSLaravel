<div class="field">
    <label class="label">Nombre de la Categoría</label>
    <div class="control">
        <input type="text" name="name" class="input @error('name') is-danger @enderror" value="{{ old('name', $category->name ?? '') }}" required>
    </div>
    @error('name')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field is-grouped">
    <div class="control">
        <button type="submit" class="button is-primary">
            {{ isset($category) ? 'Actualizar' : 'Crear' }}
        </button>
    </div>
    <div class="control">
        <a href="{{ route('categories.index') }}" class="button is-light">Cancelar</a>
    </div>
</div>
