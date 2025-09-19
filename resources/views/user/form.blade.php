<div class="mb-2">
    <x-form.label 
        for="name" 
        label="name" 
        :required="true" 
        class="block" />

    <x-form.input 
        id="name" 
        name="name" 
        placeholder="Enter user fullname" 
        :autofocus="true"
        value="{{ $user->name ?? null }}" />
</div>

<div class="mb-2">
    <x-form.label 
        for="username" 
        label="username" 
        :required="true" 
        class="block" />

    <x-form.input 
        id="username" 
        name="username" 
        placeholder="Enter username"
        value="{{ $user->username ?? null }}" />
</div>

@if ($state !== 'edit')
    <div class="mb-2">
        <x-form.label 
            for="password" 
            label="password" 
            :required="true" 
            class="block" />

        <x-form.input 
            type="password"
            id="password" 
            name="password" 
            placeholder="Enter password" />
    </div>
@endif

<div class="mb-4">
    <x-form.label 
        for="role" 
        label="role" 
        :required="true" 
        class="block" />
        
        <div class="roleParent flex items-center space-x-4 mt-2">
            <x-form.radio
                name="role"
                value="admin"
                label="Admin"
                :checked="old('role', $user->role ?? '') === 'admin'" />

            <x-form.radio
                name="role"
                value="employee"
                label="Employee"
                :checked="old('role', $user->role ?? '') === 'employee'" />
        </div>
</div>

<div class="mb-4">
    <x-form.label 
        for="shift" 
        label="shift" 
        :required="true" 
        class="block" />
        
        <div class="shiftParent flex items-center space-x-4 mt-2">
            <x-form.radio
                name="shift"
                value="morning"
                label="Morning"
                :checked="old('shift', $user->shift ?? '') === 'morning'" />

            <x-form.radio
                name="shift"
                value="night"
                label="Night"
                :checked="old('shift', $user->shift ?? '') === 'night'" />
        </div>
</div>
