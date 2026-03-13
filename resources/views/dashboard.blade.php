@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <h4 class="mb-4">Tableau de bord</h4>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #1e4d8c;">
                <div class="card-body">
                    <h6 class="card-title">Élèves inscrits</h6>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Classes</h6>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Réinscriptions</h6>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Notifications</h6>
                    <h2 class="mb-0">0</h2>
                </div>
            </div>
        </div>
    </div>
@endsection