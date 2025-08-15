<template>
    <aside
        :class="[
        isCollapsed ? 'w-16' : 'w-64',
        'h-screen sticky top-0 bg-gradient-to-b from-blue-900 via-blue-700 to-slate-800 text-white shadow-lg flex flex-col transition-all duration-300 ease-in-out overflow-y-auto'
        ]"
    >
        <div class="p-4 flex items-center justify-between">
            <button @click="$emit('toggle')" class="text-white focus:outline-none">
                <i :class="[isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left']"></i>
            </button>
        </div>

        <div v-if="!isCollapsed" class="px-6 text-lg font-bold">HEXCO TIMETABLES</div>

        <nav v-if="role === 'admin'" class="flex-1 px-2 py-4 space-y-2 text-sm">
            <div v-if="!isCollapsed" class="px-2 pt-1 pb-2 text-[10px] tracking-wider uppercase text-white/60">Administration</div>
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

            <!-- Users -->
            <Link
                href="/admin/users"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/users') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-users" :class="isActive('/admin/users') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Users</span>
            </Link>

            <!-- Departments -->
            <Link
                href="/admin/departments"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/departments') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-building" :class="isActive('/admin/departments') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Departments</span>
            </Link>

            <!-- Venues -->
            <Link
                href="/admin/venues"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/venues') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-map-marker-alt" :class="isActive('/admin/venues') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Venues</span>
            </Link>

            <!-- Modules -->
            <Link
                href="/admin/modules"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/modules') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-book" :class="isActive('/admin/modules') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Modules</span>
            </Link>

            <!-- Examinations -->
            <Link
                href="/admin/examinations"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/examinations') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-clipboard-list" :class="isActive('/admin/examinations') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Examinations</span>
            </Link>

            <!-- Lecturers -->
            <Link
                href="/admin/lecturers"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/lecturers') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-chalkboard-teacher" :class="isActive('/admin/lecturers') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Lecturers</span>
            </Link>

            <!-- Students -->
            <Link
                href="/admin/students"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/students') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-user-graduate" :class="isActive('/admin/students') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Students</span>
            </Link>

            <!-- Classes -->
            <Link
                href="/admin/classes"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/classes') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-layer-group" :class="isActive('/admin/classes') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Classes</span>
            </Link>

            <!-- Invigilations -->
            <Link
                href="/admin/invigilations"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/admin/invigilations') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-user-shield" :class="isActive('/admin/invigilations') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Invigilations</span>
            </Link>

        </nav>

        <!-- Lecturer Navigation -->
        <nav v-if="role === 'lecturer'" class="flex-1 px-2 py-4 space-y-2 text-sm">
            <div v-if="!isCollapsed" class="px-2 pt-1 pb-2 text-[10px] tracking-wider uppercase text-white/60">Lecturer</div>
            <Link
                href="/lecturer/timetables"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/lecturer/timetables') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-calendar-alt" :class="isActive('/lecturer/timetables') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">My Timetable</span>
            </Link>
        </nav>

        <!-- Student Navigation -->
        <nav v-if="role === 'student'" class="flex-1 px-2 py-4 space-y-2 text-sm">
            <div v-if="!isCollapsed" class="px-2 pt-1 pb-2 text-[10px] tracking-wider uppercase text-white/60">Student</div>
            <Link
                href="/student/timetable"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/student/timetable') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-calendar-alt" :class="isActive('/student/timetable') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">My Timetable</span>
            </Link>

            <Link
                href="/student/modules"
                :class="[
                'flex items-center gap-3 px-4 py-2 rounded-lg transition',
                isActive('/student/modules') ? 'bg-white text-blue-800 font-semibold shadow-inner' : 'text-white hover:bg-white hover:bg-opacity-10 hover:text-blue-300'
                ]"
            >
                <i class="fas fa-book" :class="isActive('/student/modules') ? 'text-blue-800' : ''"></i>
                <span v-if="!isCollapsed" class="truncate">Modules</span>
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
