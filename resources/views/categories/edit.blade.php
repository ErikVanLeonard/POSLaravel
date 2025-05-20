@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="box">
                    <h2 class="title is-4 has-text-info">Editar Categoría</h2>

                    <form action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('categories.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
