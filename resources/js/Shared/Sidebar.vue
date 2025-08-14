<template>
    <aside
        :class="[
        isCollapsed ? 'w-16' : 'w-64',
        'h-screen sticky top-0 bg-gradient-to-b from-blue-900 via-blue-700 to-slate-800 text-white shadow-lg flex flex-col transition-all duration-300 ease-in-out'
        ]"
    >
        <div class="p-4 flex items-center justify-between">
            <img v-if="!isCollapsed" src="../../assets/timelogo.png" alt="Logo" class="h-15" />
            <button @click="$emit('toggle')" class="text-white focus:outline-none">
                <i :class="[isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left']"></i>
            </button>
        </div>

        <div v-if="!isCollapsed" class="px-6 text-lg font-bold">HEXCO</div>

        <nav v-if="role === 'admin'" class="flex-1 px-2 py-4 space-y-2 text-sm">
            <!-- Dashboard -->
            <Link
                href="/admin/dashboards"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/dashboards') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-home" :class="isActive('/admin/dashboards') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Dashboard</span>
            </Link>

        </nav>

        <div v-if="!isCollapsed" class="p-4 text-xs text-white text-opacity-60 border-t border-white border-opacity-20">
            I ❤️ HEXCO
        </div>
    </aside>
</template>

<script>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default {
    props: {
        isCollapsed: Boolean,
        role: String
    },
    setup() {
        const page = usePage()

        const currentUrl = computed(() => page.url)

        const isActive = (url) => currentUrl.value.startsWith(url)

        return { isActive }
    }
};
</script>
