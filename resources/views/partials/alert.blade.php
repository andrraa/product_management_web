@session('error')
    <div class="mb-6 flex items-center space-x-4 rounded-lg h-14 px-8 border-2 border-red-600 bg-red-600/20 text-red-600 dark:text-red-300">
        <i class="fa-solid fa-times text-sm"></i>
        <span class="tracking-wide font-semibold">{{ session('error') }}</span>
    </div>
@endsession

@session('success')
    <div class="mb-6 flex items-center space-x-4 rounded-lg h-14 px-8 border-2 border-green-600 bg-green-600/20 text-green-600 dark:text-green-300">
        <i class="fa-solid fa-check text-sm"></i>
        <span class=" tracking-wide font-semibold">{{ session('success') }}</span>
    </div>
@endsession