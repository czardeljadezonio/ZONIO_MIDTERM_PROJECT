<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl ">
        <!-- Stats Cards -->
       <!-- Session Messages (Success/Error Notifications) -->
        @if (session('success'))
            <div id="success-message" class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.getElementById('success-message');
                    if (msg) {
                        msg.classList.add('opacity-0');
                        setTimeout(() => msg.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        @if (session('error'))
            <div class="rounded-lg bg-red-100 p-4 text-sm text-red-800 dark:bg-red-900 dark:text-red-300" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-3"    >
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Members</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $stats['total_members'] ?? 'N/A' }}</h3>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Plans</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $stats['total_plans'] ?? 'N/A' }}</h3>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Enrolled Rate</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $stats['membership_rate'] ?? 'N/A' }}</h3>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. Add New Member Form Trigger -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/background.jpg') }}');">
            <div class="flex h-full flex-col p-6">

                <div class="mb-4 flex justify-end">
                    <form method="GET" action="{{ route('members.export') }}" class="inline">
                        
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        
                        <input type="hidden" name="plan_id" value="{{ request('plan_id') }}">
                    
                        <button type="submit"
                                class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export to PDF
                        </button>
                    </form>
                </div>
                <!-- Add New Student Form -->
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Member</h2>
                        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                            @csrf
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Full Name</label>
                                <input type="text" name="name" id="name" required placeholder="Enter student name" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                                <input type="email" name="email" id="email" required placeholder="Enter email address" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="start_date" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Start Date</label>
                                <input type="date" name="start_date" id="start_date" required placeholder="Enter phone number" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="plan_id" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Membership Plan (Optional)</label>
                                <select name="plan_id" id="plan_id" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                    <option value="">Select a plan</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->name }} - ₱{{ $plan->price }}</option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    Member Photo (Optional)
                                </label>
                                <input
                                    type="file"
                                    name="photo"
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                                >
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    JPG, PNG or JPEG. Max 2MB.
                                </p>
                                @error('photo')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                    Add Member
                                </button>
                            </div>
                        </form>
                </div>
            
                <div class="rounded-xl border mb-10 border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        Search & Filter Members
                    </h2>
                
                    <form action="{{ route('dashboard') }}" method="GET" class="grid gap-4 md:grid-cols-3 items-end">
                        
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Search
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by name or email..."
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                        </div>
                    
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Filter by Plan
                            </label>
                            <select
                                name="plan_id"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                                <option value="">All Plans</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="md:col-span-1 flex gap-2">
                            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                                Search
                            </button>
                            
                            @if(request('search') || request('plan_id'))
                                <a href="{{ route('dashboard') }}" class="rounded-lg border border-neutral-300 bg-white px-5 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                    Clear
                                </a>
                            @endif
                        </div>
                    
                    </form>
                </div>


                <!-- Student List Table -->
                <div class="flex-1 overflow-auto dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Member List</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Photo</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Email</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Start Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Plan</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach ($members as $member)
                                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                        <td class="px-4 py-3 text-sm font-bold text-neutral-600 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if($member->photo)
                                                    <img
                                                        src="{{ Storage::url($member->photo) }}"
                                                        alt="{{ $member->name }}"
                                                        class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900"
                                                    >
                                                @else
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                
                                                <div class="ml-3">
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                        </td>                                        
                                        <td class="px-4 py-3 text-sm font-bold text-neutral-900 dark:text-neutral-100">{{ $member->name }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-neutral-900 dark:text-neutral-100">{{ $member->email }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-neutral-600 dark:text-neutral-100">{{ \Carbon\Carbon::parse($member->start_date)->format('F j, Y') }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-neutral-600 dark:text-neutral-100">
                                            {{ $member->plan_name ?? 'N/A' }}
                                        </td>
                                        
                                        <td class="px-4 py-3 text-sm">
                                            <button onclick="openEditMemberModal({{ json_encode($member) }})" class="text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                            <span class="mx-1 text-neutral-400">|</span>
                                            <form method="POST" action="{{ route('members.destroy', $member->id) }}" onsubmit="return confirm('Are you sure you want to trash this member?');" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Trash</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($members->isEmpty())
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-sm text-neutral-600 dark:text-neutral-400">No members found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT MEMBER MODAL -->
    <div id="edit-member-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Edit Member</h2>

            <form id="edit-member-form" enctype="multipart/form-data" method="POST">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="edit_member_name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Full Name</label>
                        <input type="text" id="edit_member_name" name="name" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_member_email" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                        <input type="email" id="edit_member_email" name="email" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_member_start_date" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Start Date</label>
                        <input type="date" id="edit_member_start_date" name="start_date" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_member_plan_id" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Membership Plan (Optional)</label>
                        <select id="edit_member_plan_id" name="plan_id"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            <option value="">Select a plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ₱{{ $plan->price }}</option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            Member Photo
                        </label>
                
                        <div id="currentPhotoPreview" class="mb-3 flex items-center gap-3"></div>
                        <input
                            type="file"
                            id="edit_photo"
                            name="photo"
                            accept="image/jpeg,image/png,image/jpg"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                        >
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                            Leave empty to keep current photo. JPG, PNG or JPEG. Max 2MB.
                        </p>
                    </div>
                    
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditMemberModal()"
                            class="rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        function openEditMemberModal(member) {
            const form = document.getElementById('edit-member-form');
            form.action = `/members/${member.id}/update`;

            document.getElementById('edit_member_name').value = member.name;
            document.getElementById('edit_member_email').value = member.email;
            document.getElementById('edit_member_start_date').value = member.start_date;
            document.getElementById('edit_member_plan_id').value = member.plan_id || '';
            const previewContainer = document.getElementById('currentPhotoPreview');
            previewContainer.innerHTML = '';

            if (member.photo) {
                // If the member has a photo, show it
                previewContainer.innerHTML = `
                    <div class="flex items-center gap-3">
                        <img src="/storage/${member.photo}" alt="Current Photo" class="h-16 w-16 rounded-full object-cover ring-2 ring-gray-200">
                        <span class="text-xs text-gray-500 font-medium">Current Photo</span>
                    </div>
                `;
            } else {
                // If no photo, show text
                previewContainer.innerHTML = '<span class="text-sm text-gray-400 italic">No photo currently uploaded</span>';
            }

            toggleModal('edit-member-modal');
        }

        function closeEditMemberModal() {
            toggleModal('edit-member-modal');
        }
    </script>
</x-layouts.app>
