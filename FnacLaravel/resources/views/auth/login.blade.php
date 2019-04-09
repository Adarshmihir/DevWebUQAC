@extends('layouts.default')

@section('content')
    <h1>Connexion</h1>

    <div class="alert alert-primary text-center" role="alert">
      Compte admin:<br>
      Email: paul.durand@gmail.com<br>
      Mdp: 123
    </div>

    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('cli_mel') ? ' has-error' : '' }}">
            <label for="cli_mel" class="col-md-4 text-right control-label">Adresse mail</label>

            <div class="col-md-6">
                <input id="cli_mel" type="text" class="form-control" name="cli_mel" value="{{ old('cli_mel') }}" required autofocus>
                @if ($errors->has('cli_mel'))
                    <span class="help-block"><strong>{{ $errors->first('cli_mel') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 text-right control-label">Mot de passe</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                    <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Se connecter
                </button>

                <a href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <a href="{{ route('register') }}">
                    Créer mon compte
                </a>
            </div>
        </div>

    </form>
@endsection
