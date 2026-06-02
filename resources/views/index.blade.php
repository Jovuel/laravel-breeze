<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Halaman Admin - Daftar User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2">Nama</th>
                                <th class="border-b py-2">Email</th>
                                <th class="border-b py-2">No HP</th>
                                <th class="border-b py-2">Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="border-b py-2">{{ $user->name }}</td>
                                <td class="border-b py-2">{{ $user->email }}</td>
                                <td class="border-b py-2">{{ $user->no_hp }}</td>
                                <td class="border-b py-2">{{ ucfirst($user->role) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>