<div class="mb-2">
    <x-form.label 
        for="name" 
        label="name" 
        :required="true" 
        class="block" />

    <x-form.input 
        id="name" 
        name="name" 
        placeholder="Enter product name" 
        :autofocus="true"
        value="{{ $product->name ?? null }}" />
</div>

<div class="mb-2">
    <x-form.label 
        for="price" 
        label="price" 
        :required="true" 
        class="block" />

    <x-form.input 
        id="price" 
        name="price" 
        placeholder="Enter product price" 
        :autofocus="true"
        class="currency"
        value="{{ $product->price ?? null }}" />
</div>

<div class="mb-2">
    <x-form.label 
        for="stock" 
        label="stock"
        class="block" />

    <x-form.input 
        id="stock" 
        name="stock" 
        placeholder="Enter product stock" 
        :autofocus="true"
        class="only-number"
        value="{{ $product->stock ?? null }}" />
</div>

<div class="mb-4">
    <x-form.label 
        for="type" 
        label="type" 
        :required="true" 
        class="block" />
        
        <div class="roleParent flex items-center space-x-4 mt-2">
            <x-form.radio
                name="type"
                value="food"
                label="Food"
                :checked="old('type', $product->type ?? '') === 'food'" />

            <x-form.radio
                name="type"
                value="billing"
                label="Billing"
                :checked="old('type', $product->type ?? '') === 'billing'" />
        </div>
</div>