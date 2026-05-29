<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Preset Avatar K3LT Selection -->
        <div x-data="{ selectedAvatar: '{{ old('avatar', $user->avatar ?? 'Safety Officer') }}' }" class="mb-6">
            <x-input-label :value="__('Preset Avatar Keselamatan (K3LT)')" class="mb-1" />
            <p class="text-xs text-gray-500 mb-4">Pilih bidang spesialisasi K3LT Anda untuk mempersonalisasi foto avatar profil.</p>
            
            <input type="hidden" name="avatar" :value="selectedAvatar">
            
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-4">
                @foreach (['Safety Officer', 'Engineering', 'Construction', 'Security', 'Logistics', 'Environment Officer'] as $role)
                    <button type="button" 
                            @click="selectedAvatar = '{{ $role }}'"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl border-2 transition-all duration-300 relative group"
                            :class="selectedAvatar === '{{ $role }}' ? 'border-indigo-500 bg-indigo-50/50 scale-105 shadow-sm ring-4 ring-indigo-500/20' : 'border-gray-100 hover:border-gray-200 bg-white hover:scale-102'">
                        
                        <x-user-avatar :avatar="$role" size="w-12 h-12" />
                        
                        <span class="text-[10px] sm:text-xs font-bold text-gray-700 mt-2 text-center leading-tight">
                            {{ $role }}
                        </span>
                        
                        <!-- Selected Checkmark Badge -->
                        <span x-show="selectedAvatar === '{{ $role }}'" class="absolute -top-1.5 -right-1.5 bg-indigo-500 text-white rounded-full p-0.5 text-[8px] w-5 h-5 flex items-center justify-center font-bold border border-white shadow">
                            ✓
                        </span>
                    </button>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
