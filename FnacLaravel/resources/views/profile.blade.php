@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    {!! Form::open(['route'=>'profilePost']) !!}
                        {!! Form::token() !!}

                        <div class="form-group{{ $errors->has('cli_mel') ? ' has-error' : '' }}">
                            {!! Form::label('cli_mel','E-mail',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::email('cli_mel',$user->cli_mel?:'',['class' => 'form-control','required'=>'required','disabled'=>'disabled']) !!}

                                @if ($errors->has('cli_mel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_mel') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_pseudo') ? ' has-error' : '' }}">
                            {!! Form::label('cli_pseudo','Pseudo',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text('cli_pseudo',$user->cli_pseudo?:'',['class' => 'form-control','required'=>'required','autofocus'=>'autofocus']) !!}

                                @if ($errors->has('cli_pseudo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_pseudo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_civilite') ? ' has-error' : '' }}">
                            {!! Form::label('cli_civilite','Civilité',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::select('cli_civilite',['M.'=>'Monsieur','Mlle'=>'Mademoiselle','Mme'=>'Madame'],$user->cli_civilite?:'',['class' => 'form-control','required'=>'required','placeholder'=>'Choisissez une option']) !!}

                                @if ($errors->has('cli_civilite'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_civilite') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_nom') ? ' has-error' : '' }}">
                            {!! Form::label('cli_nom','Nom',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text('cli_nom',$user->cli_nom?:'',['class' => 'form-control','required'=>'required']) !!}

                                @if ($errors->has('cli_nom'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_nom') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_prenom') ? ' has-error' : '' }}">
                            {!! Form::label('cli_prenom','Prénom',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text('cli_prenom',$user->cli_prenom?:'',['class' => 'form-control','required'=>'required']) !!}

                                @if ($errors->has('cli_prenom'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_prenom') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_telfixe') ? ' has-error' : '' }}">
                            {!! Form::label('cli_telfixe','Tél fixe',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text('cli_telfixe',$user->cli_telfixe?:'',['class' => 'form-control']) !!}

                                @if ($errors->has('cli_telfixe'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_telfixe') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_telportable') ? ' has-error' : '' }}">
                            {!! Form::label('cli_telportable','Tél portable',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text('cli_telportable',$user->cli_telportable?:'',['class' => 'form-control']) !!}

                                @if ($errors->has('cli_telportable'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_telportable') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_motpasse') ? ' has-error' : '' }}">
                            {!! Form::label('cli_motpasse','Mot de passe',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::password('cli_motpasse',['class' => 'form-control']) !!}

                                @if ($errors->has('cli_motpasse'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_motpasse') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cli_motpasse_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('cli_motpasse_confirmation','Confirmer mot de passe',['class' => 'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::password('cli_motpasse_confirmation',['class' => 'form-control']) !!}

                                @if ($errors->has('cli_motpasse_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cli_motpasse_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
