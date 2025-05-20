<div class="field">
    <label class="label">Nombre *</label>
    <div class="control">
        <input class="input @error('name') is-danger @enderror" type="text" name="name" value="{{ old('name', $client->name ?? '') }}" required>
    </div>
    @error('name')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Empresa</label>
    <div class="control">
        <input class="input @error('company') is-danger @enderror" type="text" name="company" value="{{ old('company', $client->company ?? '') }}">
    </div>
    @error('company')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">RFC</label>
    <div class="control">
        <input class="input @error('rfc') is-danger @enderror" type="text" name="rfc" value="{{ old('rfc', $client->rfc ?? '') }}" placeholder="XAXX010101000">
    </div>
    @error('rfc')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
    <p class="help">Formato: 13 caracteres para personas físicas o 12 para personas morales.</p>
</div>

<div class="field">
    <label class="label">Dirección</label>
    <div class="control">
        <textarea class="textarea @error('address') is-danger @enderror" name="address" rows="3">{{ old('address', $client->address ?? '') }}</textarea>
    </div>
    @error('address')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Teléfono *</label>
    <div class="control">
        <input class="input @error('phone') is-danger @enderror" type="tel" name="phone" value="{{ old('phone', $client->phone ?? '') }}" required>
    </div>
    @error('phone')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field">
    <label class="label">Correo Electrónico</label>
    <div class="control">
        <input class="input @error('email') is-danger @enderror" type="email" name="email" value="{{ old('email', $client->email ?? '') }}">
    </div>
    @error('email')
        <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

<div class="field is-grouped mt-5">
    <div class="control">
        <button type="submit" class="button is-primary">
            <span class="icon">
                <i class="fas fa-save"></i>
            </span>
            <span>Guardar</span>
        </button>
    </div>
    <div class="control">
        <a href="{{ route('clients.index') }}" class="button is-link is-light">
            <span class="icon">
                <i class="fas fa-times"></i>
            </span>
            <span>Cancelar</span>
        </a>
    </div>
</div>
