<form method="post" action="{{ route('login.perform') }}">
    
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="current_page" value="{{ url()->previous() }}">
    
    @include('layouts.global.snippets.messages')

    <div class="form-group form-floating mb-3">
        <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
        <label for="floatingName">Email or Username</label>
        @if ($errors->has('username'))
            <span class="text-danger text-left">{{ $errors->first('username') }}</span>
        @endif
    </div>
    
    <div class="form-group form-floating mb-3">
        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
        <label for="floatingPassword">Password</label>
        @if ($errors->has('password'))
            <span class="text-danger text-left">{{ $errors->first('password') }}</span>
        @endif
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
    
</form>