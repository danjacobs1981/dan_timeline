@extends('layouts.portal.master')

@section('content')

        <h1>verif</h1>
        
        @if(session('resent'))
            <div role="alert">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        Before proceeding, please check your email for a verification link. If you did not receive the email,
        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit">
                click here to request another
            </button>
        </form>

@endsection

