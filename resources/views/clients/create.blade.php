@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">
                        <span class="icon">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        <span>Nuevo Cliente</span>
                    </h2>

                    @if($errors->any())
                        <div class="notification is-danger">
                            <button class="delete"></button>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        @include('clients.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Cerrar notificaciones
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;
            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
</script>
@endpush
@endsection
