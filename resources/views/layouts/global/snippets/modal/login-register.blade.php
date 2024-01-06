@if(isset($incentive))
<p>LOGIN / REGISTER TO LIKE OR SAVE</p>
@endif

<div class="" style="{{ $show == 'register' ? 'display:none;' : '' }}">
    @include('layouts.global.snippets.login')
    <p>
        Not registered yet? <strong>Sign Up</strong>
    </p>
</div>
<div class="" style="{{ $show == 'login' ? 'display:none;' : '' }}">
    @include('layouts.global.snippets.register')
    <p>
        Already have an account? <strong>Log In</strong>
    </p>
</div>