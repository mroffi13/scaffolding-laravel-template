@if ($user->isAbleTo(['users-update', 'users-delete', 'users-read']))
    <div class="dropdown d-inline mr-2 btn-action">
        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu" style="">
            @if ($user->isAbleTo('users-read'))
                <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> View</a>
            @endif
            @if ($user->isAbleTo('users-update'))
                <a class="dropdown-item" href="#"><i class="fas fa-edit"></i> Edit</a>
            @endif
            @if ($user->isAbleTo('users-update'))
                <a class="dropdown-item" href="#" data-toggle="tooltip" title="Delete"
                data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?"
                data-confirm-yes="deleteData('{{ route('users.destroy', $model->id) }}')"><i class="fas fa-trash"></i> Delete</a>
            @endif
        </div>
    </div>
@endif
