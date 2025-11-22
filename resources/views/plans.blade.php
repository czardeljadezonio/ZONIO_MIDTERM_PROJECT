<x-layouts.app :title="__('Plans Management')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Stats Cards -->
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

        <!-- Student Management Section -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/background.jpg') }}');">
            <div class="flex h-full flex-col p-6">
                <!-- Add New Student Form -->
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Plan</h2>
                    <form method="POST" action="{{ route('plans.store') }}" class="grid gap-4 md:grid-cols-2">
                        @csrf
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Plan Name</label>
                            <input type="text" name="name" id="name"  class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Price (PHP)</label>
                            <input type="number" step="0.01" name="price"  class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="duration_days" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Duration (Days)</label>
                            <input type="number" name="duration_days" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('duration_days')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                Add Plan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Student List Table -->
                <div class="flex-1 overflow-auto dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Plan List</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Plan Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Price (PHP)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Duration (Days)</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Members Count</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">{{ $plan->name }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">₱{{ number_format($plan->price, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">{{ $plan->duration_days }} days</td>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">{{ $plan->members_count }}</td>
                                        <td class="flex px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">
                                            <button onclick="openEditPlanModal({{ json_encode($plan) }})" class="text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                Edit
                                            </button>
                                            <span class="mx-1 text-neutral-400">|</span>
                                            <form method="POST" action="{{ route('plans.destroy', $plan) }}" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- EDIT PLAN MODAL -->
    <div id="edit-plan-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Edit Plan</h2>

            <form id="edit-plan-form" method="POST">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="edit_plan_name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Plan Name</label>
                        <input type="text" id="edit_plan_name" name="name" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_plan_price" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Price (PHP)</label>
                        <input type="number" step="0.01" id="edit_plan_price" name="price" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edit_plan_duration_days" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Duration (Days)</label>
                        <input type="number" id="edit_plan_duration_days" name="duration_days" required
                               class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('duration_days')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditPlanModal()"
                            class="rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>

                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>

        function openEditPlanModal(plan) {
            const form = document.getElementById('edit-plan-form');
            form.action = `/plans/${plan.id}/update`;

            document.getElementById('edit_plan_name').value = plan.name;
            document.getElementById('edit_plan_price').value = plan.price;
            document.getElementById('edit_plan_duration_days').value = plan.duration_days;

            toggleModal('edit-plan-modal');
        }

        function closeEditPlanModal() {
            toggleModal('edit-plan-modal');
        }
    </script>
</x-layouts.app>
