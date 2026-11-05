@php
    $clientes = App\Models\Cliente::where('active', true)->orderBy('orden')->get();
@endphp

@extends('layouts.web')

@section('title', 'Clientes')

@section('content')

<x-banner-seccion seccion="clientes" :migas="['Clientes' => null]" />

<section class="contenedor pt-[80px] pb-[126px]">
    @if ($clientes->isEmpty())
        <p class="text-[16px]">Próximamente.</p>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-[24px]">
            @foreach ($clientes as $cliente)
                <div class="aspect-square max-w-[184px] w-full border border-linea rounded-[6px] overflow-hidden">
                    @if ($cliente->foto)
                        <img src="{{ $cliente->foto }}" alt="{{ $cliente->t('nombre') }}" class="w-full h-full object-cover">
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</section>

@endsection
