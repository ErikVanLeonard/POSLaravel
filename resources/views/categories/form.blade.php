<div class="field">
    <label class="label">Nombre de la Categoría <span class="has-text-danger">*</span></label>
    <div class="control has-icons-left">
        <input type="text" 
               name="name" 
               class="input @error('name') is-danger @enderror" 
               value="{{ old('name', $category->name ?? '') }}" 
               placeholder="Ej. Electrónicos, Ropa, Hogar..."
               required
               autofocus>
        <span class="icon is-small is-left">
            <i class="fas fa-tag"></i>
        </span>
    </div>
    @error('name')
        <p class="help is-danger">
            <span class="icon is-small">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>

<div class="field is-grouped is-grouped-right mt-5">
    <div class="control">
        <a href="{{ route('categories.index') }}" class="button is-light">
            <span class="icon">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span>Cancelar</span>
        </a>
    </div>
    <div class="control">
        <button type="submit" class="button is-primary">
            <span class="icon">
                <i class="fas {{ isset($category) ? 'fa-save' : 'fa-plus' }}"></i>
            </span>
            <span>{{ isset($category) ? 'Actualizar Categoría' : 'Crear Categoría' }}</span>
        </button>
    </div>
</div>
