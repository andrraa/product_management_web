@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="grid grid-cols-2 items-center gap-2 mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">User</span>

        <a href="{{ route('user.create')}}" type="button" class="px-4 py-2 rounded-md bg-green-500 text-white w-fit ml-auto text-sm hover:bg-green-600 transition-colors duration-300">
            Create User
        </a>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md overflow-x-auto max-w-full">
        <table id="table-user" class="min-w-full border-collapse">
            <thead id="table-user-head" class="text-left">
                <tr>
                    <x-table.table-head label="#" />

                    <x-table.table-head label="Name" />

                    <x-table.table-head label="Username" />

                    <x-table.table-head label="Role" />

                    <x-table.table-head label="Shift" />

                    <x-table.table-head label="Actions" />
                </tr>
            </thead>
            <tbody id="table-user-body" class="text-left divide-y divide-gray-200 dark:divide-gray-200/20">
                @forelse ($users as $index => $user)
                    <tr>
                        <x-table.table-data :value="$index + 1" />

                        <x-table.table-data :value="$user->name" />

                        <x-table.table-data :value="$user->username" />

                        <x-table.table-data>
                            @php
                                $roleClass = '';

                                if ($user->role === \App\Models\User::ROLE_ADMIN) {
                                    $roleClass = 'text-blue-600 dark:text-blue-300';
                                } else if ($user->role === \App\Models\User::ROLE_EMPLOYEE) {
                                    $roleClass = 'text-red-600 dark:text-red-300';
                                }
                            @endphp

                            <span 
                                class="inline-block px-2 py-1 text-xs font-semibold tracking-wider uppercase rounded-lg {{ $roleClass }}">
                                {{ $user->role }}
                            </span>
                        </x-table.table-data>

                        <x-table.table-data>
                            @php
                                $shiftClass = '';

                                if ($user->shift === \App\Models\User::SHIFT_MORNING) {
                                    $shiftClass = 'text-green-600  dark:text-green-300';
                                } else if ($user->shift === \App\Models\User::SHIFT_NIGHT) {
                                    $shiftClass = 'text-purple-600 dark:text-purple-300';
                                }
                            @endphp

                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold tracking-wider uppercase rounded-lg {{ $shiftClass }}">
                                {{ $user->shift ?? '' }}
                            </span>
                        </x-table.table-data>
                        
                        <x-table.table-data>
                            <div class="flex items-center space-x-2 shrink-0">
                                <a href="{{ route('user.edit', $user->user_id) }}" type="button">
                                    <i class="fa-solid fa-edit text-xs text-green-600 dark:text-green-400"></i>
                                </a>

                                <a href="{{ route('user.edit.password', $user->user_id) }}" type="button">
                                    <i class="fa-solid fa-key text-xs text-blue-600 dark:text-blue-400"></i>
                                </a>

                                <button 
                                    type="button" class="cursor-pointer button-delete"
                                    data-url="{{ route('user.destroy', $user->user_id) }}">
                                    <i class="fa-solid fa-trash text-xs text-red-600 dark:text-red-400"></i>
                                </button>
                            </div>
                        </x-table.table-data>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 dark:text-gray-200 text-sm whitespace-nowrap">
                            User data is empty.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="module">
        $(document).on('click', '.button-delete', function(e) {
            e.preventDefault();

            let button = $(this);
            let url = button.data('url');
            let row = button.closest('tr');

            Swal.fire({
                title: "Are you sure?",
                text: "This user will permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        success: function (response) {
                            Swal.fire({
                                title: "Deleted",
                                text: "Data user deleted successfully.",
                                icon: "success",
                                timer: 1000,
                                showConfirmButton: false
                            });

                            row.fadeOut(300, function () {
                                $(this).remove();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire("Failed", "Failed to delete user.", "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush