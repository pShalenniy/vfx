@extends('layouts.client')

@section('content')
    <section class="section py-0 position-relative">
        <img class="section__bg" src="/images/client/hero/bg.png" alt="">
        <div class="row no-gutters">
            <div class="col-md-4">
                <home-top-block1></home-top-block1>
            </div>
            <div class="col-md-4">
                <home-top-block2></home-top-block2>
            </div>
            <div class="col-md-4">
                <home-top-block3></home-top-block3>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <home-intro-block></home-intro-block>
        </div>
    </section>
@stop
