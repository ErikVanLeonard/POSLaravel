@extends('layouts.app')

@section('title', 'Nuevo Proveedor')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">
                        <span class="icon">
                            <i class="fas fa-truck"></i>
                        </span>
                        <span>Nuevo Proveedor</span>
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

                    <form action="{{ route('providers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('providers._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
