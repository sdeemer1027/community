<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="mt-3">
        @csrf
        @method('patch')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" name="name" type="text"
                       class="form-control"
                       value="{{ old('name', $user->name) }}"
                       required autocomplete="name">
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" name="email" type="email"
                       class="form-control"
                       value="{{ old('email', $user->email) }}"
                       required autocomplete="username">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="small mt-2">
                        Your email address is unverified.
                        <button form="send-verification"
                                class="btn btn-link btn-sm p-0">
                            Resend verification email
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small">
                            A new verification link has been sent.
                        </p>
                    @endif
                @endif
            </div>

            <div class="col-12">
                <label for="address" class="form-label">Street Address</label>
                <input id="address" name="address" type="text"
                       class="form-control"
                       value="{{ old('address', $user->address) }}"
                       autocomplete="street-address">
                @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="city" class="form-label">City</label>
                <input id="city" name="city" type="text"
                       class="form-control"
                       value="{{ old('city', $user->city) }}"
                       autocomplete="address-level2">
                @error('city')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="state" class="form-label">State / Province</label>
                <input id="state" name="state" type="text"
                       class="form-control"
                       value="{{ old('state', $user->state) }}"
                       autocomplete="address-level1">
                @error('state')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="zip_code" class="form-label">ZIP / Postal Code</label>
                <input id="zip_code" name="zip_code" type="text"
                       class="form-control"
                       value="{{ old('zip_code', $user->zip_code) }}"
                       autocomplete="postal-code">
                @error('zip_code')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number</label>
                <input id="phone" name="phone" type="tel"
                       class="form-control"
                       value="{{ old('phone', $user->phone) }}"
                       autocomplete="tel">
                @error('phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select id="gender" name="gender" class="form-select">
                    <option value="">Prefer not to say</option>
                    @foreach (['Male', 'Female', 'Non-binary', 'Other'] as $option)
                        <option value="{{ $option }}"
                            @selected(old('gender', $user->gender) === $option)>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('gender')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input id="birthdate" name="birthdate" type="date"
                       class="form-control"
                       value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}">
                @error('birthdate')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="bio" class="form-label">About Me</label>
                <textarea id="bio" name="bio" rows="4"
                          maxlength="2000"
                          class="form-control"
                          placeholder="Tell your community a little about yourself">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label d-block">Profile Picture</label>

                @if ($user->profile_photo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) }}"
                         alt="Current profile picture"
                         class="rounded-circle mb-3"
                         style="width: 90px; height: 90px; object-fit: cover;">
                @endif

                <input id="profile_photo" name="profile_photo"
                       type="file" class="form-control"
                       accept="image/*">
                <div class="form-text">
                    Choose an image up to 2 MB. Leave this empty to keep your current picture.
                </div>
                @error('profile_photo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">Save Profile</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">Saved successfully.</span>
            @endif
        </div>
    </form>
</section>
