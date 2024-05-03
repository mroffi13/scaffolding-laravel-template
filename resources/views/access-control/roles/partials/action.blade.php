@if ($user->isAbleTo(['acl-update', 'acl-delete', 'acl-read']))
    <div class="dropdown d-inline mr-2 btn-action">
        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu" style="">
            @if ($user->isAbleTo('acl-read'))
                <a class="dropdown-item" href="{{ route('roles.show', $model->id) }}"><i class="fas fa-eye"></i> View</a>
            @endif
            @if ($user->isAbleTo('acl-update'))
                <a class="dropdown-item" href="{{ route('roles.edit', $model->id) }}"><i class="fas fa-edit"></i> Edit</a>
            @endif
            @if ($user->isAbleTo('acl-update'))
                <a class="dropdown-item" href="#" data-toggle="tooltip" title="Delete"
                data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                data-confirm-yes="deleteData('{{ route('roles.destroy', $model->id) }}')"><i class="fas fa-trash"></i> Delete</a>
            @endif
        </div>
    </div>
@endif
