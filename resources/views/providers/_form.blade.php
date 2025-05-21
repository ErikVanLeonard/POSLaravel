<div class="field">
    <label class="label">Compañía <span class="has-text-danger">*</span></label>
    <div class="control">
        <input class="input @error('company') is-danger @enderror" type="text" name="company" value="{{ old('company', $provider->company ?? '') }}" required>
    </div>
    @error('company')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Representante <span class="has-text-danger">*</span></label>
    <div class="control">
        <input class="input @error('contact_name') is-danger @enderror" type="text" name="contact_name" value="{{ old('contact_name', $provider->contact_name ?? '') }}" required>
    </div>
    @error('contact_name')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Teléfono <span class="has-text-danger">*</span></label>
    <div class="control">
        <input class="input @error('phone') is-danger @enderror" type="text" name="phone" value="{{ old('phone', $provider->phone ?? '') }}" required>
    </div>
    @error('phone')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Correo Electrónico <span class="has-text-danger">*</span></label>
    <div class="control">
        <input class="input @error('email') is-danger @enderror" type="email" name="email" value="{{ old('email', $provider->email ?? '') }}" required>
    </div>
    @error('email')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Dirección</label>
    <div class="control">
        <textarea class="textarea @error('address') is-danger @enderror" name="address" rows="2">{{ old('address', $provider->address ?? '') }}</textarea>
    </div>
    @error('address')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Sitio Web para Pedidos</label>
    <div class="control">
        <input class="input @error('order_website') is-danger @enderror" type="url" name="order_website" value="{{ old('order_website', $provider->order_website ?? '') }}" placeholder="https://ejemplo.com">
    </div>
    @error('order_website')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Correo para Facturación</label>
    <div class="control">
        <input class="input @error('billing_email') is-danger @enderror" type="email" name="billing_email" value="{{ old('billing_email', $provider->billing_email ?? '') }}">
    </div>
    @error('billing_email')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Teléfono para Pedidos</label>
    <div class="control">
        <input class="input @error('order_phone') is-danger @enderror" type="text" name="order_phone" value="{{ old('order_phone', $provider->order_phone ?? '') }}">
    </div>
    @error('order_phone')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

@if(isset($provider) && $provider->documents->count() > 0)
<div class="field">
    <label class="label">Documentos actuales</label>
    <div class="content">
        <ul>
            @foreach($provider->documents as $document)
                <li>
                    <a href="{{ Storage::url($document->file_path) }}" target="_blank">
                        <span class="icon-text">
                            <span class="icon">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            <span>{{ basename($document->file_path) }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="field">
    <label class="label">Documentos</label>
    <div class="file has-name is-boxed">
        <label class="file-label">
            <input class="file-input" type="file" name="documents[]" multiple>
            <span class="file-cta">
                <span class="file-icon">
                    <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                    Seleccionar archivos...
                </span>
            </span>
            <span class="file-name" id="file-names">
                Ningún archivo seleccionado
            </span>
        </label>
    </div>
    <p class="help">Formatos: PDF, DOC, XLS, etc. (Máx. 10MB por archivo)</p>
    @error('documents.*')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field is-grouped mt-5">
    <div class="control">
        <button type="submit" class="button is-primary">
            <span class="icon">
                <i class="fas fa-save"></i>
            </span>
            <span>Guardar Proveedor</span>
        </button>
    </div>
    <div class="control">
        <a href="{{ route('providers.index') }}" class="button is-link is-light">
            <span class="icon">
                <i class="fas fa-times"></i>
            </span>
            <span>Cancelar</span>
        </a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mostrar nombres de archivos seleccionados
        const fileInput = document.querySelector('.file-input');
        const fileNames = document.getElementById('file-names');

        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                if (files.length === 0) {
                    fileNames.textContent = 'Ningún archivo seleccionado';
                } else if (files.length === 1) {
                    fileNames.textContent = files[0].name;
                } else {
                    fileNames.textContent = `${files.length} archivos seleccionados`;
                }
            });
        }
    });
</script>
@endpush
