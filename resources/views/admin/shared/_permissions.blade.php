<div id="accordion">
    <div class="card card-success mt-1">
        <div class="card-header" id="heading{{$id}}">
        <h3 class="card-title">
            <a class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$id}}" aria-expanded="true" aria-controls="collapse{{$id}}">
            {{ $title }} {!! isset($user) ? '<span class="text-danger">(' . $user->getDirectPermissions()->count() . ')</span>' : '' !!}
            </a>
        </h3>
        <!-- /.card-tools -->
        <div class="card-tools">
            
        </div>
        </div>
        <!-- /.card-header -->
        <div id="collapse{{$id}}" class="collapse" aria-labelledby="heading{{$id}}" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    @foreach($permissions as $perm)
                        <?php
                            $per_found = null;

                            if( isset($role) ) {
                                $per_found = $role->hasPermissionTo($perm->name);
                            }

                            if( isset($user)) {
                                $per_found = $user->hasDirectPermission($perm->name);
                            }
                        ?>

                        <div class="col-md-3">
                            <div class="checkbox">
                                <label class="{{ Str::contains($perm->name, 'delete') ? 'text-danger' : '' }}">
                                    {!! Form::checkbox("permissions[]", $perm->name, $per_found, isset($options) ? $options : []) !!} {{ $perm->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @can('edit_roles')
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                @endcan
            </div>
        </div>

        <!-- /.card-body -->
    </div>
</div>