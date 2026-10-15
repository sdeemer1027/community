
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Community') }} | A Community Built Together</title>
    <meta name="description" content="Join a community built around neighbors, friends, and family. Follow development, share ideas, and help shape what comes next.">

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    {{-- Navigation --}}
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ url('/') }}" class="text-2xl font-extrabold tracking-tight text-indigo-700">
                {{ config('app.name', 'Community') }}
            </a>

            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Dashboard
                    </a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="px-3 py-2 text-sm font-semibold text-slate-700 hover:text-indigo-700">
                            Log in
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Join us
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>

        {{-- Hero --}}
        <section class="overflow-hidden bg-gradient-to-br from-indigo-950 via-indigo-900 to-slate-900 text-white">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 py-20 md:py-28 lg:grid-cols-2 lg:items-center lg:px-8">

                <div>
                    <span class="inline-flex rounded-full border border-indigo-300/30 bg-white/10 px-4 py-2 text-sm font-medium text-indigo-100">
                        A new community is taking shape
                    </span>

                    <h1 class="mt-7 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                        A community built by its members.
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-8 text-indigo-100">
                        More meaningful connections. More useful local information.
                        More opportunity to help shape the community we build together.
                    </p>

                    <p class="mt-4 max-w-xl leading-7 text-indigo-100/90">
                        We are building a place where neighbors, friends, and family
                        can connect, share, discover, and help one another. You can
                        join us now and be part of the journey from the beginning.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">
                        @guest
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="rounded-xl bg-white px-6 py-3.5 font-bold text-indigo-900 shadow-lg hover:bg-indigo-50">
                                    Create Your Free Account
                                </a>
                            @endif
                        @else
                            <a href="{{ url('/dashboard') }}"
                               class="rounded-xl bg-white px-6 py-3.5 font-bold text-indigo-900 shadow-lg hover:bg-indigo-50">
                                Visit Your Dashboard
                            </a>
                        @endguest

                        <a href="#our-vision"
                           class="rounded-xl border border-white/40 px-6 py-3.5 font-semibold text-white hover:bg-white/10">
                            Discover the Vision
                        </a>
                    </div>

                    <p class="mt-5 text-sm text-indigo-200">
                        Early access. Ongoing development. Your voice matters.
                    </p>
                </div>

                {{-- Community illustration made with CSS --}}
                <div class="mx-auto w-full max-w-lg">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-5 shadow-2xl backdrop-blur">
                        <div class="rounded-2xl bg-white p-6 text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-2xl">
                                    <span aria-hidden="true">&#9829;</span>
                                </div>
                                <div>
                                    <p class="font-bold">Stronger Together</p>
                                    <p class="text-sm text-slate-500">A place for people to connect</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div class="rounded-xl bg-indigo-50 p-4">
                                    <p class="font-semibold text-indigo-950">Your neighborhood</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Meet people, exchange ideas, and share useful information.
                                    </p>
                                </div>

                                <div class="rounded-xl bg-emerald-50 p-4">
                                    <p class="font-semibold text-emerald-950">Your people</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Stay connected with friends and family.
                                    </p>
                                </div>

                                <div class="rounded-xl bg-amber-50 p-4">
                                    <p class="font-semibold text-amber-950">Your voice</p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Help guide the features and future of the platform.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p class="px-3 pt-4 text-center text-sm text-indigo-100">
                            Built step by step, with the community.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        {{-- Vision --}}
        <section id="our-vision" class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-bold uppercase tracking-widest text-indigo-600">
                    Our vision
                </p>

                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Technology should bring people closer.
                </h2>

                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Social networks can be more than endless scrolling.
                    Our goal is to create a useful, welcoming place where
                    real relationships, local connections, shared knowledge,
                    and community participation matter.
                </p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="text-3xl" aria-hidden="true">&#128101;</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Connect</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        Build connections with neighbors, friends, relatives,
                        and people who share your interests.
                    </p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="text-3xl" aria-hidden="true">&#128172;</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Share</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        Share updates, photos, ideas, and experiences
                        with the people you choose.
                    </p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="text-3xl" aria-hidden="true">&#127758;</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Discover</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        Work toward useful local information and tools
                        that help communities stay informed.
                    </p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="text-3xl" aria-hidden="true">&#128161;</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Contribute</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        Suggest improvements, test new features,
                        and help shape what we build next.
                    </p>
                </article>
            </div>
        </section>

        {{-- Soft launch --}}
        <section class="border-y border-indigo-100 bg-indigo-50">
            <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 lg:grid-cols-2 lg:items-center lg:px-8">
                <div>
                    <p class="font-bold uppercase tracking-widest text-indigo-600">
                        Join us early
                    </p>

                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                        Help build it. Help improve it. Help make it yours.
                    </h2>

                    <p class="mt-5 leading-8 text-slate-600">
                        We are developing this platform in stages. The first
                        launch will be a starting point, not the finish line.
                        Early members will have an opportunity to follow
                        progress, try available features, offer feedback,
                        and help us prioritize what comes next.
                    </p>

                    <p class="mt-4 leading-8 text-slate-600">
                        Our initial goal is to secure a dedicated domain,
                        establish reliable hosting, and open the doors to a
                        growing group of early members while development continues.
                    </p>
                </div>

                <div class="rounded-2xl border border-indigo-100 bg-white p-7 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900">What we're working toward</h3>

                    <ul class="mt-5 space-y-4 text-slate-600">
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-600">&#10003;</span>
                            <span>A dedicated website and reliable hosting</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-600">&#10003;</span>
                            <span>A welcoming space for early community members</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-600">&#10003;</span>
                            <span>Regular development updates and feedback</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-bold text-indigo-600">&#10003;</span>
                            <span>New features guided by member needs and available resources</span>
                        </li>
                    </ul>

                    <p class="mt-6 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                        Early access means features may be incomplete or change
                        as the platform develops. We will communicate progress
                        and priorities as clearly as possible.
                    </p>
                </div>
            </div>
        </section>

        {{-- Membership --}}
        <section id="membership" class="mx-auto max-w-7xl px-5 py-20 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="font-bold uppercase tracking-widest text-indigo-600">
                    Support the journey
                </p>

                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Help make the community possible.
                </h2>

                <p class="mt-5 text-lg leading-8 text-slate-600">
                    The first priorities are the domain, hosting, and continued
                    development. We plan to introduce optional paid membership
                    choices as the platform and payment system become ready.
                </p>
            </div>

            <div class="mt-12 grid gap-6 lg:grid-cols-3">

                <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <p class="text-sm font-bold uppercase tracking-widest text-indigo-600">
                        Monthly supporter
                    </p>

                    <h3 class="mt-3 text-3xl font-extrabold text-slate-900">$10</h3>
                    <p class="mt-1 text-slate-500">per month after the introductory period</p>

                    <p class="mt-5 leading-7 text-slate-600">
                        The proposed introductory offer includes three months
                        free. The first $10 payment would be due four months
                        after membership begins, followed by $10 monthly.
                    </p>

                    <p class="mt-auto pt-6 text-sm leading-6 text-slate-500">
                        Planned offer. Start date, eligibility, and payment
                        terms will be confirmed before enrollment opens.
                    </p>
                </article>

                <article class="flex flex-col rounded-2xl border-2 border-indigo-600 bg-white p-7 shadow-md">
                    <span class="self-start rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-800">
                        Founding offer
                    </span>

                    <p class="mt-4 text-sm font-bold uppercase tracking-widest text-indigo-600">
                        Annual supporter
                    </p>

                    <h3 class="mt-3 text-3xl font-extrabold text-slate-900">$25</h3>
                    <p class="mt-1 text-slate-500">for the first year</p>

                    <p class="mt-5 leading-7 text-slate-600">
                        The proposed founding offer covers the first year.
                        After that year, the planned membership rate is
                        $10 per month.
                    </p>

                    <p class="mt-auto pt-6 text-sm leading-6 text-slate-500">
                        Planned offer. Renewal terms and the final start date
                        will be disclosed before enrollment opens.
                    </p>
                </article>

                <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <p class="text-sm font-bold uppercase tracking-widest text-emerald-700">
                        Future participation
                    </p>

                    <h3 class="mt-3 text-2xl font-extrabold text-slate-900">
                        Proposed share opportunity
                    </h3>

                    <p class="mt-3 text-3xl font-extrabold text-slate-900">$100</p>
                    <p class="mt-1 text-slate-500">proposed price for a half share</p>

                    <p class="mt-5 leading-7 text-slate-600">
                        We are exploring a possible company ownership
                        participation plan for eligible participants.
                        The proposed structure, availability, and terms
                        have not been finalized.
                    </p>

                    <p class="mt-auto pt-6 text-sm leading-6 text-slate-500">
                        This is not an open investment offer. No shares
                        are currently being offered through this page.
                        Any future offering would require appropriate
                        legal review and disclosures.
                    </p>
                </article>

            </div>

            <p class="mx-auto mt-8 max-w-3xl text-center text-sm leading-6 text-slate-500">
                Membership plans are proposed and may change before launch.
                No payment is being collected through this page.
                Final prices, billing dates, renewal terms, and benefits
                will be displayed before any paid enrollment begins.
            </p>
        </section>

        {{-- Transparency --}}
        <section class="bg-slate-900 text-white">
            <div class="mx-auto max-w-4xl px-5 py-16 text-center lg:px-8">
                <h2 class="text-3xl font-extrabold tracking-tight">
                    Built in the open, one step at a time.
                </h2>

                <p class="mt-5 leading-8 text-slate-300">
                    A meaningful community takes time, commitment, and
                    participation. We will share progress as development
                    moves forward and invite members to help us learn what
                    works, what needs improvement, and what should come next.
                </p>

                <p class="mt-4 leading-8 text-slate-300">
                    Our goal is to build something useful and sustainable
                    with the people who choose to be part of it.
                </p>

                @guest
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="mt-8 inline-flex rounded-xl bg-white px-7 py-3.5 font-bold text-indigo-900 hover:bg-indigo-50">
                            Join the Community
                        </a>
                    @endif
                @else
                    <a href="{{ url('/dashboard') }}"
                       class="mt-8 inline-flex rounded-xl bg-white px-7 py-3.5 font-bold text-indigo-900 hover:bg-indigo-50">
                        Continue to Your Dashboard
                    </a>
                @endguest
            </div>
        </section>

    </main>

    <footer class="bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-8 text-sm text-slate-500 md:flex-row md:items-center md:justify-between lg:px-8">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name', 'Community') }}.
                A community built together.
            </p>

            <p>
                Connecting neighbors, friends, and family.
            </p>
        </div>
    </footer>

</body>
</html>
